<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BasicAuthRepository;
use App\Validator\Constraints as AppAssert;
use App\Entity\Site;

/**
 * @ORM\Table(name="basic_auth")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=BasicAuthRepository::class)
 * @AppAssert\BasicAuth
 */
class BasicAuth
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Site", mappedBy="basicAuth")
     **/
    private $site;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Assert\Length(max = "125")
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Assert\Length(max = "125")
     */
    private $password;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $whitelistedIps;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(Site $site): void
    {
        $this->site = $site;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): void
    {
        $this->userName = $userName;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getWhitelistedIps(): ?string
    {
        return $this->whitelistedIps;
    }

    public function setWhitelistedIps(?string $whitelistedIps): void
    {
        $this->whitelistedIps = $whitelistedIps;
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
