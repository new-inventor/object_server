<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trigger
 *
 * @ORM\Table(name="trigger")
 * @ORM\Entity
 */
class Trigger
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
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=true)
     */
    private $content;

    /**
     * Trigger constructor.
     * @param int $id
     * @param int|null $status
     * @param null|string $content
     */
    public function __construct(int $id, ?int $status, ?string $content)
    {
        $this->id = $id;
        $this->status = $status;
        $this->content = $content;
    }


}
