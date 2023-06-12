<?php

namespace App\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DatabaseUserRepository;
use App\Service\Crypto;
use App\Entity\Site;

/**
 * @ORM\Table(name="database_user")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=DatabaseUserRepository::class)
 */
class DatabaseUser
{
    public const PERMISSIONS_READ_WRITE = 'rw';
    public const PERMISSIONS_READ_ONLY = 'ro';

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
     * @ORM\ManyToOne(targetEntity="Database", inversedBy="users")
     * @ORM\JoinColumn(name="database_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     **/
    private $database;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min = 3, max = 32)
     * @Assert\Regex("/^[a-z][-a-z0-9]+$/iu")
     */
    private $userName;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $permissions = self::PERMISSIONS_READ_WRITE;

    private $site;

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

    public function getDatabase(): ?Database
    {
        return $this->database;
    }

    public function setDatabase(?Database $database): void
    {
        $this->database = $database;
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function setSite(Site $site): void
    {
        $this->site = $site;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function getPermissions(): ?string
    {
        return $this->permissions;
    }

    public function setPermissions(string $permissions): void
    {
        $this->permissions = $permissions;
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
            'fields'    => array('userName'),
            'errorPath' => 'userName',
            'message'   => 'This value already exists.',
        )));
    }
}
