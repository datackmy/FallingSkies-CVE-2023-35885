<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TimezoneRepository;

/**
 * @ORM\Table(name="timezone")
 * @ORM\Entity(repositoryClass=TimezoneRepository::class)
 */
class Timezone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
