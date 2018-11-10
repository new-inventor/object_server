<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sensor
 *
 * @ORM\Table(name="sensor", indexes={@ORM\Index(name="FK_sensor_sensor_type", columns={"sensor_type_id"})})
 * @ORM\Entity
 */
class Sensor
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
     * @var SensorType
     *
     * @ORM\ManyToOne(targetEntity="SensorType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sensor_type_id", referencedColumnName="id")
     * })
     */
    private $sensorType;

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
     * Sensor constructor.
     * @param SensorType $sensorType
     * @param Controller $controller
     */
    public function __construct(SensorType $sensorType, Controller $controller)
    {
        $this->sensorType = $sensorType;
        $this->controller = $controller;
    }


}
