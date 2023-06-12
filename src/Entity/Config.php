<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="App\Repository\ConfigRepository")
 */
class Config
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=false)
     */
    private $key;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function set($key, $value): void
    {
        $this->key = $key;
        $this->value = $value;
    }
}