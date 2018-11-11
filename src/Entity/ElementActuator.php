<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementActuator
 *
 * @ORM\Table(name="element_actuator", indexes={@ORM\Index(name="FK_element_actuator_element", columns={"element_id"}), @ORM\Index(name="FK_element_actuator_actuator", columns={"actuator_id"})})
 * @ORM\Entity
 */
class ElementActuator extends AbstractEntity
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
     * @var Actuator
     *
     * @ORM\ManyToOne(targetEntity="Actuator")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actuator_id", referencedColumnName="id")
     * })
     */
    private $actuator;

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
     * ElementActuator constructor.
     * @param Actuator $actuator
     * @param Element $element
     */
    public function __construct(Actuator $actuator, Element $element)
    {
        $this->actuator = $actuator;
        $this->element = $element;
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
     * @return Actuator
     */
    public function getActuator(): Actuator
    {
        return $this->actuator;
    }

    /**
     * @param Actuator $actuator
     */
    public function setActuator(Actuator $actuator): void
    {
        $this->actuator = $actuator;
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


}
