<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BlockedIpRepository;
use App\Validator\Constraints as AppAssert;
use App\Entity\Site;

/**
 * @ORM\Table(name="blocked_ip")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=BlockedIpRepository::class)
 */
class BlockedIp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="blockedIps")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
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
     * @ORM\Column(type="string", length=128, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max = "125")
     * @AppAssert\Ip
     */
    private $ip;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): void
    {
        $this->site = $site;
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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
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

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addConstraint(new UniqueEntity(array(
            'fields'    => ['site', 'ip'],
            'errorPath' => 'ip',
            'message'   => 'This value already exists.',
        )));
    }
}
