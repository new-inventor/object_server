<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementSensor
 *
 * @ORM\Table(name="element_sensor", indexes={@ORM\Index(name="FK_element_sensor_element", columns={"element_id"}), @ORM\Index(name="FK_element_sensor_sensor", columns={"sensor_id"})})
 * @ORM\Entity
 */
class ElementSensor
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
     * @var \Element
     *
     * @ORM\ManyToOne(targetEntity="Element")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="element_id", referencedColumnName="id")
     * })
     */
    private $element;

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
