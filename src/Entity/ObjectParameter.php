<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjectSettings
 *
 * @ORM\Table(name="object_parameter", uniqueConstraints={@ORM\UniqueConstraint(name="object_settings_parameter_uindex", columns={"name"})})
 * @ORM\Entity
 */
class ObjectParameter extends AbstractEntity
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    public $value;

    /**
     * ObjectParameter constructor.
     * @param string $name
     * @param null|string $value
     */
    public function __construct(string $name, ?string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param null|string $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
}
