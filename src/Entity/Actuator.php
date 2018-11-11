<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actuator
 *
 * @ORM\Table(name="actuator", indexes={@ORM\Index(name="FK_actuator_actuator_type", columns={"actuator_type_id"}), @ORM\Index(name="FK_actuator_controller", columns={"controller_id"})})
 * @ORM\Entity
 */
class Actuator extends AbstractEntity
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var ActuatorType
     *
     * @ORM\ManyToOne(targetEntity="ActuatorType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actuator_type_id", referencedColumnName="id")
     * })
     */
    private $actuatorType;

    /**
     * @var Controller
     *
     * @ORM\ManyToOne(targetEntity="Controller")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="controller_id", referencedColumnName="id")
     * })
     */
    private $controller;

    /**
     * Actuator constructor.
     * @param ActuatorType $actuatorType
     * @param Controller $controller
     */
    public function __construct(ActuatorType $actuatorType, Controller $controller)
    {
        $this->actuatorType = $actuatorType;
        $this->controller = $controller;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return ActuatorType
     */
    public function getActuatorType(): ActuatorType
    {
        return $this->actuatorType;
    }

    /**
     * @param ActuatorType $actuatorType
     */
    public function setActuatorType(ActuatorType $actuatorType): void
    {
        $this->actuatorType = $actuatorType;
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }
}
