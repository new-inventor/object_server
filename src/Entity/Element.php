<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Element
 *
 * @ORM\Table(name="element", indexes={@ORM\Index(name="FK_element_room", columns={"room_id"}), @ORM\Index(name="FK_element_element_type", columns={"element_type_id"})})
 * @ORM\Entity
 */
class Element
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
     * @ORM\Column(name="parent_element_id", type="integer", nullable=false)
     */
    private $parentElementId = '0';

    /**
     * @var \ElementType
     *
     * @ORM\ManyToOne(targetEntity="ElementType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="element_type_id", referencedColumnName="id")
     * })
     */
    private $elementType;

    /**
     * @var \Room
     *
     * @ORM\ManyToOne(targetEntity="Room")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     * })
     */
    private $room;


}
