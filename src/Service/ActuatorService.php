<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 07.11.18
 * Time: 1:48
 */

namespace App\Service;


use App\Entity\Actuator;
use App\Entity\ActuatorIntLog;
use App\Entity\ActuatorType;
use App\Entity\Controller;
use App\Entity\Element;
use App\Entity\ElementActuator;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;

class ActuatorService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param ActuatorType $type
     * @param Controller $controller
     * @param Element $element
     * @throws \Doctrine\ORM\ORMException
     */
    public function addActuator(ActuatorType $type, Controller $controller, Element $element)
    {
        $actuator = new Actuator($type, $controller);
        $this->em->persist($actuator);
        $elementActuator = new ElementActuator($actuator, $element);
        $this->em->persist($elementActuator);
        $this->em->commit();
    }

    /**
     * @param int $id
     * @param string $type ('int' | 'bit')
     * @param \DateTime $start
     * @param \DateTime $end
     * @return Actuator[]
     * @throws \Exception
     */
    public function getLog(int $id, string $type, \DateTime $start, \DateTime $end)
    {
        $actuator = $this->em->find(Actuator::class, $id);
        if ($type === 'int') {
            return $this->getIntLog($actuator, $start, $end);
        }
        if ($type === 'bit') {
            return $this->getBitLog($actuator, $start, $end);
        }
        return [];
    }

    /**
     * @param int $actuator
     * @param string $type
     * @param \DateTime|null $start
     * @param \DateTime|null $end
     * @return mixed
     * @throws \Exception
     */
    private function getIntLog(Actuator $actuator, \DateTime $start = null, \DateTime $end = null)
    {
        if ($start === null) {
            $start = new \DateTime();
        }
        if ($end === null) {
            $end = new \DateTime();
        }
        $res = $this->em->createQueryBuilder()
            ->select('i')
            ->from(ActuatorIntLog::class, 'i')
            ->where('i.created >= :start')
            ->andWhere('i.created < :end' . $end->format('U'))
            ->andWhere('i.actuator = :actuator')
            ->setParameter('start', $start->format('U'))
            ->setParameter('end', $end->format('U'))
            ->setParameter('actuator', $actuator)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
        var_dump($res);
        return $res;
    }

    /**
     * @param Actuator $actuator
     * @param \DateTime|null $start
     * @param \DateTime|null $end
     * @return mixed
     */
    private function getBitLog(Actuator $actuator, \DateTime $start = null, \DateTime $end = null)
    {
        if ($start === null) {
            $start = new \DateTime();
        }
        if ($end === null) {
            $end = new \DateTime();
        }
        $res = $this->em->createQueryBuilder()
            ->select('i')
            ->from(ActuatorIntLog::class, 'i')
            ->where('i.created >= :start')
            ->andWhere('i.created < :end' . $end->format('U'))
            ->andWhere('i.actuator = :actuator')
            ->setParameter('start', $start->format('U'))
            ->setParameter('end', $end->format('U'))
            ->setParameter('actuator', $actuator)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
        var_dump($res);
        return $res;
    }
}