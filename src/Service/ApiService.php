<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 09.11.18
 * Time: 22:18
 */

namespace App\Service;


use App\Entity\Actuator;
use App\Entity\ActuatorType;
use App\Entity\Controller;
use App\Entity\ElementType;
use App\Entity\EventType;
use App\Entity\ObjectParameter;
use App\Entity\Sensor;
use App\Entity\SensorType;
use App\Entity\EventTrigger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use GuzzleHttp\Client;
use PhpParser\Error;

class ApiService
{
    /**
     * @var string
     */
    private $domain;
    /**
     * @var string
     */
    private $uriPrefix;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $accountToken;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        string $domain,
        string $uriPrefix,
        string $accountToken,
        EntityManagerInterface $em
    ) {
        $this->uriPrefix = $uriPrefix;
        $this->em = $em;
        /** @var ObjectParameter[] $objectToken */
        $objectToken = $this->em->createQueryBuilder()
            ->select('op')
            ->from(ObjectParameter::class, 'op')
            ->where('op.name = :name')
            ->setParameter('name', 'objectToken')
            ->getQuery()
            ->getResult();
        if (\count($objectToken) > 0) {
            $this->token = $objectToken[0]->getValue();
        } else {
            $this->token = '';
        }
        $this->accountToken = $accountToken;

        $this->client = new Client([
            'base_uri' => $domain,
            'timeout' => 2.0
        ]);
    }

    public function makeApiRequest(
        string $path,
        $mainParams,
        $useAccountToken
    ) {
        $response = $this->getApiResponse($path, $mainParams, $useAccountToken);
        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new Error('Server responded with status ' . $statusCode . '. Error message: ' . $response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }

    public function getApiResponse(
        string $path,
        $mainParams,
        $useAccountToken = false
    ) {
        return $this->client->post(
            $this->uriPrefix . $path,
            ['form_params' => $this->initParameters($mainParams, $useAccountToken)]
        );
    }

    public function initParameters($mainParams, bool $useAccountToken = false)
    {
        $params = [
            'json' => json_encode($mainParams),
            'token' => $this->token,
        ];

        if ($useAccountToken) {
            $params['account_token'] = $this->accountToken;
        }

        return $params;
    }

    public function initObject(string $title, string $address): int
    {
        $content = $this->makeApiRequest('/state/initObject', [
            'object_title' => $title,
            'object_adress' => $address
        ], true);
        $content = json_decode($content, true);
        $objectId = $content['object']['object_id'];
        $objectToken = $content['object']['object_token'];
        if ($objectId === null || $objectId === false) {
            throw new Error('Server does not return an object id. Or error occurred.');
        }
        $this->createOrUpdateParameter('objectId', $objectId);
        $this->createOrUpdateParameter('objectToken', $objectToken);
        $this->em->flush();
        $this->em->clear();
        return (int)$objectId;
    }

    public function createOrUpdateParameter(string $name, $value)
    {
        return;
        $parameter = $this->em->createQueryBuilder()
            ->select('op')
            ->from(ObjectParameter::class, 'op')
            ->where('op.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
        if (\count($parameter) === 0) {
            $parameter = new ObjectParameter($name, '' . $value);
            $this->em->persist($parameter);
        } else {
            $this->em->createQueryBuilder()
                ->update(ObjectParameter::class, 'op')
                ->where('op.name = \'' . $name . '\'')
                ->set('op.value', "'$value'")
                ->getQuery()
                ->execute();
        }
    }

    public function createOrUpdateField(string $entity, int $id, array $params)
    {
        $oldEntity = $this->em->find($entity, $id);
        if ($oldEntity === null) {
            $oldEntity = new $entity(...$params);
        } else {
            $oldEntity->load($params);
        }
        $this->em->persist($oldEntity);
        return $oldEntity;
    }

//    public function syncStructure(int $objectId)
//    {
//        $content = $this->makeApiRequest('/objects/syncStructure', [
//            'object_id' => $objectId,
//        ], false);
//        $data = json_decode($content, true)['objectId'];
//        if ($data === null || $data === false) {
//            throw new Error('Server does not return a data. Or error occurred.');
//        }
//
//        foreach ($data['actuator_type'] as $actuatorTypeData) {
//            $actuatorType = new ActuatorType($actuatorTypeData['id'], $actuatorTypeData['title']);
//            $this->em->persist($actuatorType);
//        }
//        $this->em->flush();
//        $this->em->clear();
//        foreach ($data['event_type'] as $eventTypeData) {
//            $eventType = new EventType($eventTypeData['id']);
//            $this->em->persist($eventType);
//        }
//        $this->em->flush();
//        $this->em->clear();
//        foreach ($data['element_type'] as $elementTypeData) {
//            $elementType = new ElementType($elementTypeData['id'], $elementTypeData['title']);
//            $this->em->persist($elementType);
//        }
//        $this->em->flush();
//        $this->em->clear();
//        foreach ($data['sensor_type'] as $sensorTypeData) {
//            $sensorType = new SensorType($sensorTypeData['id'], $sensorTypeData['title']);
//            $this->em->persist($sensorType);
//        }
//        $this->em->flush();
//        $this->em->clear();
//        foreach ($data['trigger'] as $triggerData) {
//            $trigger = new EventTrigger($triggerData['id'], $triggerData['status'], $triggerData['content']);
//            $this->em->persist($trigger);
//        }
//        $this->em->flush();
//        $this->em->clear();
//    }

    public function resetIpAddress(): bool
    {
        $response = $this->getApiResponse('/state/resetIp', [], true);
        return $response->getStatusCode() === 200;
    }

    public function getDevises()
    {
        $actuators = $this->em->createQueryBuilder()
            ->select('a')
            ->from(Actuator::class, 'a')
            ->getQuery()
            ->getResult();
        $controllers = $this->em->createQueryBuilder()
            ->select('c')
            ->from(Controller::class, 'c')
            ->getQuery()
            ->getResult();
        $sensors = $this->em->createQueryBuilder()
            ->select('s')
            ->from(Sensor::class, 's')
            ->getQuery()
            ->getResult();
        $devices = [
            'controller' => array_map(function (Controller $controller) {
                return [
                    'controller_id' => $controller->getId(),
                    'room_id' => $controller->getRoom()->getId(),
                ];
            }, $controllers),
            'sensor' => array_map(function (Sensor $sensor) {
                return [
                    'sensor_id' => $sensor->getId(),
                    'sensor_type_id' => $sensor->getSensorType()->getId(),
                    'controller_id' => $sensor->getController()->getId(),
                ];
            }, $sensors),
            'actuator' => array_map(function (Actuator $actuator) {
                return [
                    'actuator_id' => $actuator->getId(),
                    'actuator_type_id' => $actuator->getActuatorType()->getId(),
                    'controller_id' => $actuator->getController()->getId(),
                ];
            }, $actuators),
        ];
        return $devices;
//        $result = $this->makeApiRequest('/objects/syncDevices', $devices, false);
//        var_dump($result);
    }

    public function syncTypes()
    {
        $content = $this->makeApiRequest('/objects/syncTypes', [], true);
        $content = json_decode($content, true);
        $this->createOrUpdateButch(SensorType::class, array_map(function ($item) {
            $item['id'] = $item['sensor_type_id'];
            unset($item['sensor_type_id']);
            return $item;
        }, $content['tables']['sensor_type']));
        $this->createOrUpdateButch(ActuatorType::class, array_map(function ($item) {
            $item['id'] = $item['actuator_type_id'];
            unset($item['actuator_type_id']);
            return $item;
        }, $content['tables']['actuator_type']));
        $this->createOrUpdateButch(EventType::class, array_map(function ($item) {
            $item['id'] = $item['event_type_id'];
            unset($item['event_type_id']);
            return $item;
        }, $content['tables']['event_type']));
        $this->createOrUpdateButch(EventTrigger::class, array_map(function ($item) {
            $item['id'] = $item['trigger_id'];
            unset($item['trigger_id']);
            return $item;
        }, $content['tables']['trigger']));
        $this->em->flush();
    }

    protected function createOrUpdateButch(string $entity, $list)
    {
        foreach ($list as $item) {
            $this->createOrUpdateField($entity, (int)$item['id'], $item);
        }
    }

}