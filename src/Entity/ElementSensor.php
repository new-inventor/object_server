<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementSensor
 *
 * @ORM\Table(name="element_sensor", indexes={@ORM\Index(name="FK_element_sensor_element", columns={"element_id"}), @ORM\Index(name="FK_element_sensor_sensor", columns={"sensor_id"})})
 * @ORM\Entity
 */
class ElementSensor extends AbstractEntity
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
     * @var Element
     *
     * @ORM\ManyToOne(targetEntity="Element")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="element_id", referencedColumnName="id")
     * })
     */
    private $element;

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
     * ElementSensor constructor.
     * @param Element $element
     * @param Sensor $sensor
     */
    public function __construct(Element $element, Sensor $sensor)
    {
        $this->element = $element;
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
     * @return Element
     */
    public function getElement(): Element
    {
        return $this->element;
    }

    /**
     * @param Element $element
     */
    public function setElement(Element $element): void
    {
        $this->element = $element;
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
