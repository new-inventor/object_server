<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sensor
 *
 * @ORM\Table(name="sensor", indexes={@ORM\Index(name="FK_sensor_sensor_type", columns={"sensor_type_id"})})
 * @ORM\Entity
 */
class Sensor extends AbstractEntity
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="log_type", type="string", nullable=true)
     */
    private $logType;

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
     * @return SensorType
     */
    public function getSensorType(): SensorType
    {
        return $this->sensorType;
    }

    /**
     * @param SensorType $sensorType
     */
    public function setSensorType(SensorType $sensorType): void
    {
        $this->sensorType = $sensorType;
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

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getLogType(): string
    {
        return $this->logType;
    }

    /**
     * @param string $logType
     */
    public function setLogType(string $logType): void
    {
        $this->logType = $logType;
    }
}
