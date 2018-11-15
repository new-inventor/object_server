<?php


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
     * @param int $actuator
     * @param string $type
     * @param \DateTime|null $start
     * @param \DateTime|null $end
     * @return mixed
     * @throws \Exception
     */
    public function getIntLog(Actuator $actuator, \DateTime $start = null, \DateTime $end = null)
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
    public function getBitLog(Actuator $actuator, \DateTime $start = null, \DateTime $end = null)
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

    public function getLastIntLog(Actuator $actuator)
    {
        $res = $this->em->createQueryBuilder()
            ->select('i')
            ->andWhere('i.actuator = :actuator')
            ->setParameter('actuator', $actuator)
            ->orderBy('i.id', 'desc')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
        var_dump($res);
        return $res;
    }
}