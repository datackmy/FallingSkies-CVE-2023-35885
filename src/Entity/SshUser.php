<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SshUserRepository;
use App\Validator\Constraints as AppAssert;
use App\Entity\Site;

/**
 * @ORM\Table(name="ssh_user")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=SshUserRepository::class)
 */
class SshUser
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
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="sshUsers")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     **/
    private $site;

    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-z][-a-z0-9_]+$/iu")
     * @Assert\Length(min = "3")
     * @Assert\Length(max = "32")
     * @AppAssert\UniqueSystemUser()
     */
    private $userName;

    /**
     * @ORM\Column(type="text", nullable=false, nullable=true)
     */
    private $sshKeys;

    private $password;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setSite(Site $site): void
    {
        $this->site = $site;
    }

    public function getSite(): ?Site
    {
        return $this->site;
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

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSshKeys(): ?string
    {
        return $this->sshKeys;
    }

    public function setSshKeys(?string $sshKeys): void
    {
        $this->sshKeys = $sshKeys;
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
