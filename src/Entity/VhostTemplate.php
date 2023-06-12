<?php

namespace App\Entity;

use App\Repository\VhostTemplateRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="vhost_template")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=VhostTemplateRepository::class)
 */
class VhostTemplate
{
    public const TYPE_SYSTEM = 1;
    public const TYPE_CUSTOM = 100;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $template;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rootDirectory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phpVersion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $varnishCacheSettings;

    /**
     * @ORM\Column(type="integer")
     */
    private $type = self::TYPE_SYSTEM;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

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

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function getRootDirectory(): ?string
    {
        return $this->rootDirectory;
    }

    public function setRootDirectory(?string $rootDirectory): void
    {
        $this->rootDirectory = $rootDirectory;
    }

    public function getPhpVersion(): ?string
    {
        return $this->phpVersion;
    }

    public function setPhpVersion(?string $phpVersion): void
    {
        $this->phpVersion = $phpVersion;
    }

    public function setVarnishCacheSettings(string $varnishCacheSettings): void
    {
        $this->varnishCacheSettings = $varnishCacheSettings;
    }

    public function getVarnishCacheSettings(): ?string
    {
        return $this->varnishCacheSettings;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
