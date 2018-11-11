<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventLog
 *
 * @ORM\Table(name="event_log", indexes={@ORM\Index(name="FK_event_log_element", columns={"element_id"}), @ORM\Index(name="FK_event_log_trigger", columns={"trigger_id"}), @ORM\Index(name="event_log_event_type_fk", columns={"event_type_id"})})
 * @ORM\Entity
 */
class EventLog
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
     * @ORM\Column(name="created", type="integer", nullable=true)
     */
    private $created;

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
     * @var \Trigger
     *
     * @ORM\ManyToOne(targetEntity="EventTrigger")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="trigger_id", referencedColumnName="id")
     * })
     */
    private $trigger;

    /**
     * @var \EventType
     *
     * @ORM\ManyToOne(targetEntity="EventType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_type_id", referencedColumnName="id")
     * })
     */
    private $eventType;

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
     * @return int|null
     */
    public function getCreated(): ?int
    {
        return $this->created;
    }

    /**
     * @param int|null $created
     */
    public function setCreated(?int $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \Element
     */
    public function getElement(): \Element
    {
        return $this->element;
    }

    /**
     * @param \Element $element
     */
    public function setElement(\Element $element): void
    {
        $this->element = $element;
    }

    /**
     * @return \Trigger
     */
    public function getTrigger(): \Trigger
    {
        return $this->trigger;
    }

    /**
     * @param \Trigger $trigger
     */
    public function setTrigger(\Trigger $trigger): void
    {
        $this->trigger = $trigger;
    }

    /**
     * @return \EventType
     */
    public function getEventType(): \EventType
    {
        return $this->eventType;
    }

    /**
     * @param \EventType $eventType
     */
    public function setEventType(\EventType $eventType): void
    {
        $this->eventType = $eventType;
    }


}
