<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;

/**
 * @ORM\Table(name="event")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $userRole;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $eventName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $eventData;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sourceIpAddress;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $userAgent;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    public function setUserRole(string $userRole): void
    {
        $this->userRole = $userRole;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): void
    {
        $this->eventName = $eventName;
    }

    public function getEventData(): ?array
    {
        $eventData = json_decode($this->eventData, true);
        return $eventData;
    }

    public function setEventData(array $eventData): void
    {
        $this->eventData = json_encode($eventData);
    }

    public function getSourceIpAddress(): ?string
    {
        return $this->sourceIpAddress;
    }

    public function setSourceIpAddress(?string $sourceIpAddress): void
    {
        $this->sourceIpAddress = $sourceIpAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }
}
