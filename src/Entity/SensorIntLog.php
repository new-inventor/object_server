<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SensorIntLog
 *
 * @ORM\Table(name="sensor_int_log", indexes={@ORM\Index(name="FK_sensor_int_log_sensor", columns={"sensor_id"})})
 * @ORM\Entity
 */
class SensorIntLog extends AbstractEntity
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
     * @var int
     *
     * @ORM\Column(name="value", type="integer", nullable=false)
     */
    private $value = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="created", type="integer", nullable=true)
     */
    private $created = null;

    /**
     * @var Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sensor_id", referencedColumnName="id")
     * })
     */
    private $sensor;

    /**
     * SensorIntLog constructor.
     * @param int $value
     * @param Sensor $sensor
     */
    public function __construct(int $value, Sensor $sensor)
    {
        $this->value = $value;
        $this->created = (new \DateTime())->format('U');
        $this->sensor = $sensor;
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
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int|null
     */
    public function getCreated(): ?int
    {
        return $this->created;
    }

    /**
     * @param int|null $created
     */
    public function setCreated(?int $created): void
    {
        $this->created = $created;
    }

    /**
     * @return Sensor
     */
    public function getSensor(): Sensor
    {
        return $this->sensor;
    }

    /**
     * @param Sensor $sensor
     */
    public function setSensor(Sensor $sensor): void
    {
        $this->sensor = $sensor;
    }
}
