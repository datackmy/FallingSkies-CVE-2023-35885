<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PhpSettingsRepository;
use App\Validator\Constraints as AppAssert;
use App\Entity\Site;

/**
 * @ORM\Table(name="php_settings")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=PhpSettingsRepository::class)
 */
class PhpSettings
{
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
     * @ORM\OneToOne(targetEntity="Site", inversedBy="phpSettings", cascade={"persist","remove"}))
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @AppAssert\PhpVersion()
     */
    private $phpVersion;

    /**
     * @ORM\Column(type="integer")
     */
    private $poolPort;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $memoryLimit = '512M';

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $maxExecutionTime = '60';

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $maxInputTime = '60';

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $maxInputVars = '10000';

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $postMaxSize = '64M';

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $uploadMaxFileSize = '64M';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $additionalConfiguration=<<<EOD
date.timezone=UTC;
display_errors=off;
EOD;

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

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): void
    {
        $this->site = $site;
    }

    public function getPhpVersion(): ?string
    {
        return $this->phpVersion;
    }

    public function setPhpVersion(string $phpVersion): void
    {
        $this->phpVersion = $phpVersion;
    }

    public function getPoolPort(): ?int
    {
        return $this->poolPort;
    }

    public function setPoolPort(int $poolPort): void
    {
        $this->poolPort = $poolPort;
    }

    public function getMaxExecutionTime(): ?string
    {
        return $this->maxExecutionTime;
    }

    public function setMaxExecutionTime(string $maxExecutionTime): void
    {
        $this->maxExecutionTime = $maxExecutionTime;
    }

    public function getMemoryLimit(): ?string
    {
        return $this->memoryLimit;
    }

    public function setMemoryLimit(string $memoryLimit): void
    {
        $this->memoryLimit = $memoryLimit;
    }

    public function getMaxInputTime(): ?string
    {
        return $this->maxInputTime;
    }

    public function setMaxInputTime(string $maxInputTime): void
    {
        $this->maxInputTime = $maxInputTime;
    }

    public function getMaxInputVars(): ?string
    {
        return $this->maxInputVars;
    }

    public function setMaxInputVars(string $maxInputVars): void
    {
        $this->maxInputVars = $maxInputVars;
    }

    public function getPostMaxSize(): ?string
    {
        return $this->postMaxSize;
    }

    public function setPostMaxSize(string $postMaxSize): void
    {
        $this->postMaxSize = $postMaxSize;
    }

    public function getUploadMaxFileSize(): ?string
    {
        return $this->uploadMaxFileSize;
    }

    public function setUploadMaxFileSize(string $uploadMaxFileSize): void
    {
        $this->uploadMaxFileSize = $uploadMaxFileSize;
    }

    public function getAdditionalConfiguration(): ?string
    {
        return $this->additionalConfiguration;
    }

    public function setAdditionalConfiguration(?string $additionalConfiguration): void
    {
        $this->additionalConfiguration = $additionalConfiguration;
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
