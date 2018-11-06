<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 07.11.18
 * Time: 1:48
 */

namespace App\Service;


use App\Entity\Actuator;
use App\Entity\ActuatorType;
use App\Entity\Controller;
use App\Entity\Element;
use App\Entity\ElementActuator;
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
    public function addActuator(ActuatorType $type, Controller $controller, Element $element) {
        $actuator = new Actuator($type, $controller);
        $this->em->persist($actuator);
        $elementActuator = new ElementActuator($actuator, $element);
        $this->em->persist($elementActuator);
        $this->em->commit();
    }
}