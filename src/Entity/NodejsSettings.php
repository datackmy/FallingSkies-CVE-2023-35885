<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\NodejsSettingsRepository;
use App\Validator\Constraints as AppAssert;
use App\Entity\Site;

/**
 * @ORM\Table(name="nodejs_settings")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=NodejsSettingsRepository::class)
 */
class NodejsSettings
{
    public const DEFAULT_PORT = 3000;

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
     * @ORM\OneToOne(targetEntity="Site", inversedBy="nodejsSettings", cascade={"persist","remove"}))
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\AtLeastOneOf({@Assert\Range(min=12,max=18)})
     */
    private $nodejsVersion;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @Assert\NotBlank()
     * @AppAssert\CheckIfPortIsInUse()
     */
    private $port = self::DEFAULT_PORT;

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

    public function getNodejsVersion(): ?string
    {
        return $this->nodejsVersion;
    }

    public function setNodejsVersion(string $nodejsVersion): void
    {
        $this->nodejsVersion = $nodejsVersion;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port): void
    {
        $this->port = $port;
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
            'fields'    => ['port'],
            'errorPath' => 'port',
            'message'   => 'This value already exists.',
        )));
    }
}
