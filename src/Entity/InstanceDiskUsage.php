<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InstanceDiskUsageRepository;

/**
 * @ORM\Table(name="instance_disk_usage")
 * @ORM\Entity(repositoryClass=InstanceDiskUsageRepository::class)
 */
class InstanceDiskUsage
{
    public const DISK_ROOT = '/';
    public const DISK_HOME = '/home';

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
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $disk;

    /**
     * @ORM\Column(type="integer")
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

    public function getDisk(): ?string
    {
        return $this->disk;
    }

    public function setDisk(string $disk): void
    {
        $this->disk = $disk;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
