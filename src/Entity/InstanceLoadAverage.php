<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InstanceLoadAverageRepository;

/**
 * @ORM\Table(name="instance_load_average")
 * @ORM\Entity(repositoryClass=InstanceLoadAverageRepository::class)
 */
class InstanceLoadAverage
{
    public const PERIOD_ONE_MINUTE = 1;
    public const PERIOD_FIVE_MINUTES = 5;
    public const PERIOD_FIVETEEN_MINUTES = 15;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $period;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): void
    {
        $this->period = $period;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
