<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Element
 *
 * @ORM\Table(name="element", indexes={@ORM\Index(name="FK_element_room", columns={"room_id"}), @ORM\Index(name="FK_element_element_type", columns={"element_type_id"})})
 * @ORM\Entity
 */
class Element extends AbstractEntity
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
     * @return int
     */
    public function getParentElementId(): int
    {
        return $this->parentElementId;
    }

    /**
     * @param int $parentElementId
     */
    public function setParentElementId(int $parentElementId): void
    {
        $this->parentElementId = $parentElementId;
    }

    /**
     * @return \ElementType
     */
    public function getElementType(): \ElementType
    {
        return $this->elementType;
    }

    /**
     * @param \ElementType $elementType
     */
    public function setElementType(\ElementType $elementType): void
    {
        $this->elementType = $elementType;
    }

    /**
     * @return \Room
     */
    public function getRoom(): \Room
    {
        return $this->room;
    }

    /**
     * @param \Room $room
     */
    public function setRoom(\Room $room): void
    {
        $this->room = $room;
    }


}
