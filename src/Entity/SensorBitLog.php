<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SensorBitLog
 *
 * @ORM\Table(name="sensor_bit_log", indexes={@ORM\Index(name="FK_sensor_bit_log_sensor", columns={"sensor_id"})})
 * @ORM\Entity
 */
class SensorBitLog extends AbstractEntity
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
     * @ORM\Column(name="value", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $value = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="created", type="integer", nullable=false)
     */
    private $created = '0';

    /**
     * @var \Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sensor_id", referencedColumnName="id")
     * })
     */
    private $sensor;

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
     * @return int
     */
    public function getCreated(): int
    {
        return $this->created;
    }

    /**
     * @param int $created
     */
    public function setCreated(int $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \Sensor
     */
    public function getSensor(): \Sensor
    {
        return $this->sensor;
    }

    /**
     * @param \Sensor $sensor
     */
    public function setSensor(\Sensor $sensor): void
    {
        $this->sensor = $sensor;
    }
}
