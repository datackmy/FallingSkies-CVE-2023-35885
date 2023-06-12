<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use DMS\Filter\Rules as Filter;
use App\Repository\AnnouncementRepository;
use App\Entity\User;

/**
 * @ORM\Table(name="announcement")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=AnnouncementRepository::class)
 */
class Announcement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     * @Assert\NotBlank()
     **/
    private $user;

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
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Filter\Trim()
     */
    private $subject;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Filter\Trim()
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRead = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
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

    public function setHash($hash): void
    {
        $this->hash = $hash;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
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

    /**
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        if (true === is_null($this->createdAt)) {
            $this->createdAt = new \DateTime('now');
            $this->updatedAt = new \DateTime('now');
        } else {
            $this->updatedAt = $this->createdAt;
        }
        $subject = $this->getSubject();
        $url = $this->getUrl();
        $hash = implode('|', [$subject, $url]);
        $this->hash = md5($hash);
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
