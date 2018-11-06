<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SensorBitLog
 *
 * @ORM\Table(name="sensor_bit_log", indexes={@ORM\Index(name="FK_sensor_bit_log_sensor", columns={"sensor_id"})})
 * @ORM\Entity
 */
class SensorBitLog
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


}
