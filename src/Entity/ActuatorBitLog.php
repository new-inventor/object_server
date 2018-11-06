<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActuatorBitLog
 *
 * @ORM\Table(name="actuator_bit_log", indexes={@ORM\Index(name="FK_actuator_bit_log_actuator", columns={"actuator_id"})})
 * @ORM\Entity
 */
class ActuatorBitLog
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
     * @var \Actuator
     *
     * @ORM\ManyToOne(targetEntity="Actuator")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actuator_id", referencedColumnName="id")
     * })
     */
    private $actuator;


}
