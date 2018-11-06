<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementActuator
 *
 * @ORM\Table(name="element_actuator", indexes={@ORM\Index(name="FK_element_actuator_element", columns={"element_id"}), @ORM\Index(name="FK_element_actuator_actuator", columns={"actuator_id"})})
 * @ORM\Entity
 */
class ElementActuator
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


}
