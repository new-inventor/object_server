<?php


namespace App\Service;


use App\Entity\Actuator;
use App\Entity\Controller;
use App\Entity\Sensor;
use Doctrine\ORM\EntityManagerInterface;

class DevisesService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DevisesService constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function all()
    {
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
        $actuators = $this->em->createQueryBuilder()
            ->select('a')
            ->from(Actuator::class, 'a')
            ->getQuery()
            ->getResult();
        return ['controllers' => $controllers, 'sensors' => $sensors, 'actuators' => $actuators];
    }
}