<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SensorType
 *
 * @ORM\Table(name="sensor_type")
 * @ORM\Entity
 */
class SensorType
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

    /**
     * SensorType constructor.
     * @param int $id
     * @param null|string $title
     */
    public function __construct(int $id, ?string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }


}
