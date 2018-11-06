<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActuatorIntLog
 *
 * @ORM\Table(name="actuator_int_log", indexes={@ORM\Index(name="FK_actuator_int_log_actuator", columns={"actuator_id"})})
 * @ORM\Entity
 */
class ActuatorIntLog
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
     * @var int|null
     *
     * @ORM\Column(name="value", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $value = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="created", type="integer", nullable=true, options={"unsigned"=true})
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
