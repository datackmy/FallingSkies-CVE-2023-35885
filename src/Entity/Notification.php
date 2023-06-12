<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DMS\Filter\Rules as Filter;
use App\Repository\NotificationRepository;

/**
 * @ORM\Table(name="notification")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
{
    const SEVERITY_INFO = 1;
    const SEVERITY_WARNING = 2;
    const SEVERITY_CRITICAL = 3;

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
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $hash;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $severity;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Filter\Trim()
     */
    private $subject;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Filter\Trim()
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Filter\Trim()
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRead = false;

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

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getSeverity(): ?string
    {
        return $this->severity;
    }
    public function setSeverity(string $severity): void
    {
        $this->severity = $severity;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead($flag): void
    {
        $this->isRead = (bool)$flag;
    }

    public function hash(): ?string
    {
        if (true === is_null($this->hash)) {
            $subject = $this->getSubject();
            $hash = implode('|', [$subject]);
            $this->hash = md5($hash);
        }
        return $this->hash;
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PreUpdate
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime('now');
    }
}
