<?php


namespace App\Service;


use App\Api\Error\ApiError;
use App\Entity\Element;
use App\Entity\Sensor;
use App\Entity\SensorBitLog;
use App\Entity\SensorIntLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class ElementsService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ElementsService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createOrUpdateElement(int $id, $elementType, $roomId, int $parentId)
    {
        $element = $this->getElementById($id);
        if ($element !== null) {
            $element = new Element($id, $elementType, $roomId, $parentId);
            $this->em->persist($element);
        } else {
            $this->em->createQueryBuilder()
                ->update(Element::class, 'op')
                ->where('op.id = \'' . $id . '\'')
                ->set('op.room_id', "'$roomId'")
                ->set('op.parent_element_id', "'$parentId'")
                ->getQuery()
                ->execute();
        }
    }

    public function getSensorCurrentData(int $sensorId)
    {
        $sensor = $this->getSensorById($sensorId);
        if ($sensor === null) {
            throw new ApiError("Undefined sensor id '$sensorId'.");
        }
        if ($sensor->getLogType() === 'int') {
            return [
                "$sensorId" => [
                'value' => (random_int(0, 100) / 50) + 24,
                'level' => random_int(0, 3),
                'type' => $sensor->getSensorType()->getTitle()
                ]
            ];
//            return $this->getSensorCurrentIntData($sensorId);
        } elseif ($sensor->getLogType() === 'bit') {
            return [
                "$sensorId" => [
                'value' => random_int(0, 1),
                'level' => random_int(0, 3),
                'type' => $sensor->getSensorType()->getTitle()
                ]
            ];
//            return $this->getSensorCurrentBitData($sensorId);
        }
        throw new ApiError('Invalid log type.');
    }

    public function getSensorById(int $sensorId): Sensor
    {
        /**
         * @var Sensor
         */
        $res = $this->em->createQueryBuilder()
            ->select('s')
            ->from(Sensor::class, 's')
            ->where('s.id = :id')
            ->setParameter('id', $sensorId)
            ->getQuery()
            ->getResult();
        if (\count($res) > 0) {
            return $res[0];
        }
        return null;
    }

    public function getSensorCurrentIntData(int $sensorId)
    {
        /**
         * @var SensorIntLog[]
         */
        $res = $this->em->createQueryBuilder()
            ->select('s')
            ->from(SensorIntLog::class, 's')
            ->where('s.sensor_id = :id')
            ->setParameter('id', $sensorId)
            ->addOrderBy('s.created', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        if (\count($res) > 0) {
            return $res[0]->getValue();
        }
        return null;
    }

    public function getSensorCurrentBitData(int $sensorId)
    {
        /**
         * @var SensorBitLog[]
         */
        $res = $this->em->createQueryBuilder()
            ->select('s')
            ->from(SensorBitLog::class, 's')
            ->where('s.sensor_id = :id')
            ->setParameter('id', $sensorId)
            ->addOrderBy('s.created', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        if (\count($res) > 0) {
            return $res[0]->getValue();
        }
        return null;
    }

    public function getElementCurrentData(int $elementId)
    {
        $element = $this->getElementById($elementId);
        if ($element === null) {
            throw new ApiError("No element with id '$elementId'.");
        }
        $elementSensors = $this->getElementSensors($element);
        $intIds = array_column(
            array_filter($elementSensors, function ($value) {
                return $value['log_type'] === 'int';
            }),
            'id'
        );
        $bitIds = array_column(
            array_filter($elementSensors, function ($value) {
                return $value['log_type'] === 'bit';
            }),
            'id'
        );
        $res = ['sensor' => []];
        foreach ($bitIds as $id) {
            $res['sensor'][$id] = ['sensor_id' => $id, 'value' => random_int(0, 1), 'level' => 0];
        }
        foreach ($intIds as $id) {
            $res['sensor'][$id] = [
                'sensor_id' => $id,
                'value' => (random_int(0, 100) / 50) + 24,
                'level' => random_int(0, 3)
            ];
        }

        return $res;


//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult('sensor_id', 'sensor_id');
//        $rsm->addScalarResult('value', 'value');
//        $sensorBitLog = $this->em->createNativeQuery(
//            "SELECT * FROM sensor_bit_log as il WHERE il.sensor_id IN ($bitIds) ORDER BY created ASC LIMIT 1;",
//            $rsm
//        );
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult('sensor_id', 'sensor_id');
//        $rsm->addScalarResult('value', 'value');
//        $sensorIntLog = $this->em->createNativeQuery(
//            "SELECT * FROM sensor_bit_log as il WHERE il.sensor_id IN ($bitIds) ORDER BY created ASC LIMIT 1;",
//            $rsm
//        )
//            ->setParameter('el', $element->getId())
//            ->getResult();
    }

    public function getRoomCurrentData(int $roomId)
    {
        $elements = $this->getElementsByRoom($roomId);
        $res = [];
        foreach ($elements as $element) {
            $maxLevel = 0;
            foreach ($this->getElementCurrentData($element['id'])['sensor'] as $el) {
                if ($el['level'] > $maxLevel) {
                    $maxLevel = $el['level'];
                }
            }

            $res['elements'][$element['id']]['level'] = $maxLevel;
        }
        return $res;
    }

    public function getElementLog(int $id, \DateTime $from, \DateTime $to)
    {
        $element = $this->getElementById($id);
        if ($element === null) {
            throw new ApiError("No element with id '$id'.");
        }
        $elementSensors = $this->getElementSensors($element);
        $intIds = implode(array_column(
            array_filter($elementSensors, function ($value) {
                return $value['log_type'] === 'int';
            }),
            'id'
        ), ', ');
        $bitIds = implode(array_column(
            array_filter($elementSensors, function ($value) {
                return $value['log_type'] === 'bit';
            }),
            'id'
        ), ', ');
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('sensor_id', 'sensor_id');
        $rsm->addScalarResult('value', 'value');
        $sensors = $this->em->createNativeQuery(
            "SELECT * FROM sensor_int_log as il WHERE il.sensor_id IN ($intIds)",
            $rsm
        )
            ->setParameter('el', $element->getId())
            ->getResult();
        return $elementSensors;
    }

    public function getElementsByRoom(int $roomId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('room_id', 'room_id');
        $elements = $this->em->createNativeQuery('
            SELECT * FROM element as e WHERE e.room_id = :room
        ', $rsm)
            ->setParameter('room', $roomId)
            ->getResult();
        return $elements;
    }

    public function getElementSensors(Element $element): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('sensor_id', 'id');
        $rsm->addScalarResult('sensor_type_id', 'sensor_type_id');
        $rsm->addScalarResult('controller_id', 'controller_id');
        $rsm->addScalarResult('description', 'description');
        $rsm->addScalarResult('log_type', 'log_type');
        $sensors = $this->em->createNativeQuery('
            SELECT * FROM element_sensor as es JOIN sensor as s ON es.sensor_id = s.id WHERE es.element_id = :el
        ', $rsm)
            ->setParameter('el', $element->getId())
            ->getResult();
        return $sensors;

    }

    public function getElementActuators(Element $element): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('actuator_id', 'id');
        $rsm->addScalarResult('actuator_type_id', 'sensor_type_id');
        $rsm->addScalarResult('controller_id', 'controller_id');
        $rsm->addScalarResult('description', 'description');
        $rsm->addScalarResult('log_type', 'log_type');
        $sensors = $this->em->createNativeQuery('
            SELECT * FROM element_actuator as es JOIN actuator as s ON es.actuator_id = s.id WHERE es.element_id = :el
        ', $rsm)
            ->setParameter('el', $element->getId())
            ->getResult();
        return $sensors;

    }

    /**
     * @param int $id
     * @return null|Element
     */
    public function getElementById(int $id)
    {
        $res = $this->em->createQueryBuilder()
            ->select('e')
            ->from(Element::class, 'e')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        if (\count($res) > 0) {
            return $res[0];
        }
        return null;
    }
}