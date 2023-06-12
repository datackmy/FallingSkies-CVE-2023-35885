<?php

namespace App\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping as ORM;
use DMS\Filter\Rules as Filter;
use App\Database\Connection as DatabaseConnection;
use App\Service\Crypto;

/**
 * @ORM\Table(name="database_server",uniqueConstraints={@UniqueConstraint(name="host_user_name_idx", columns={"host", "user_name"})})
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\DatabaseServerRepository")
 */
class DatabaseServer
{
    const DEFAULT_PORT = 3306;

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
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDefault = false;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     * @Filter\Trim()
     */
    private $engine;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     * @Filter\Trim()
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Filter\Trim()
     */
    private $host;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Filter\Trim()
     */
    private $userName;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $certificate;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\Type("integer")
     */
    private $port = self::DEFAULT_PORT;

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

    public function getEngine(): ?string
    {
        return $this->engine;
    }

    public function setEngine(string $engine): void
    {
        $this->engine = $engine;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getCertificate(): ?string
    {
        return $this->certificate;
    }

    public function setCertificate(string $certificate): void
    {
        $this->certificate = $certificate;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
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

    public function getDecryptedPassword(): ?string
    {
        $password = $this->getPassword();
        $decryptedPassword = Crypto::decrypt($password);
        return $decryptedPassword;
    }

    public function isDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault($flag): void
    {
        $this->isDefault = (bool)$flag;
    }

    public function setIsActive($flag): void
    {
        $this->isActive = (bool)$flag;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function setPort($port): void
    {
        $this->port = (int)$port;
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
            'fields'    => array('host', 'userName'),
            'errorPath' => 'host',
            'message'   => 'This value already exists.',
        )));
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        $databaseServer = $context->getObject();
        if (true === isset($databaseServer)) {
            try {
                $tmpDatabaseServer = clone $this;
                $password = $tmpDatabaseServer->getPassword();
                $encryptedPassword = Crypto::encrypt($password);
                $tmpDatabaseServer->setPassword($encryptedPassword);
                $databaseConnection = new DatabaseConnection($tmpDatabaseServer);
                $databaseConnection->connect();
            } catch (\Exception $e) {
                $violation = $context->buildViolation('Database Server credentials are not valid.');
                $violation->atPath('host');
                $violation->addViolation();
            }
        }
    }
}
