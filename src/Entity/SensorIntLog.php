<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SensorIntLog
 *
 * @ORM\Table(name="sensor_int_log", indexes={@ORM\Index(name="FK_sensor_int_log_sensor", columns={"sensor_id"})})
 * @ORM\Entity
 */
class SensorIntLog
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
