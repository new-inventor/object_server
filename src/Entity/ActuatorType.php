<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActuatorType
 *
 * @ORM\Table(name="actuator_type")
 * @ORM\Entity
 */
class ActuatorType
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;


}
