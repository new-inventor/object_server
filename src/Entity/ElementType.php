<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementType
 *
 * @ORM\Table(name="element_type")
 * @ORM\Entity
 */
class ElementType
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
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=true)
     */
    private $title;


}
