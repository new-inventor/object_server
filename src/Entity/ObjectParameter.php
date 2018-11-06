<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjectSettings
 *
 * @ORM\Table(name="object_parameter", uniqueConstraints={@ORM\UniqueConstraint(name="object_settings_parameter_uindex", columns={"name"})})
 * @ORM\Entity
 */
class ObjectParameter
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


}
