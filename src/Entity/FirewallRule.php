<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\FirewallRuleRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Table(name="firewall_rule")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=FirewallRuleRepository::class)
 */
class FirewallRule
{
    const FTP_DATA_PORT = 20;
    const FTP_PORT = 21;
    const PROFTPD_PASSIVE_PORTS_FROM = 49152;
    const PROFTPD_PASSIVE_PORTS_TO = 65534;
    const DEFAULT_TYPE = 'Custom';
    const TYPES = [
        '22'          => 'SSH/SFTP',
        '20-21'       => 'FTP',
        '49152-65534' => 'ProFTPD Passive Ports',
        '9200'        => 'Elasticsearch',
        '3306'        => 'MYSQL',
        '80'          => 'HTTP',
        '443'         => 'HTTPS',
        '8443'        => 'CloudPanel',
        'custom'      => 'Custom',
    ];
    const IP_VERSION_V4 = 'ipv4';
    const IP_VERSION_V6 = 'ipv6';

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
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @AppAssert\PortRange
     */
    private $portRange;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @AppAssert\Ip
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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

    public function getPortRange(): ?string
    {
        return $this->portRange;
    }

    public function setPortRange(string $portRange): void
    {
        $this->portRange = $portRange;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getType(): ?string
    {
        $portRange = $this->getPortRange();
        $type = self::TYPES[$portRange] ?? self::DEFAULT_TYPE;
        return $type;
    }

    public function getIpVersion(): ?string
    {
        $source = $this->getSource();
        if (substr_count($source, ':')) {
            $ipVersion = self::IP_VERSION_V6;
        } else {
            $ipVersion = self::IP_VERSION_V4;
        }
        return $ipVersion;
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

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addConstraint(new UniqueEntity(array(
            'fields'    => ['portRange', 'source'],
            'errorPath' => 'source',
            'message'   => 'This value already exists.',
        )));
    }
}
