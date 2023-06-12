<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DMS\Filter\Rules as Filter;
use App\Repository\UserRepository;
use App\Security\Authenticator\MfaAuthenticator;
use App\Entity\Timezone;
use App\Entity\Site;

/**
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLE_USER         = 'ROLE_USER';
    const ROLE_ADMIN        = 'ROLE_ADMIN';
    const ROLE_SITE_MANAGER = 'ROLE_SITE_MANAGER';

    const STATUS_ACTIVE = true;
    const STATUS_NOT_ACTIVE = false;

    const PASSWORD_MIN_LENGTH = 8;
    const PASSWORD_MAX_LENGTH = 100;

    const DEFAULT_TIMEZONE = 'UTC';

    const MFA_ENABLED = true;
    const MFA_DISABLED = false;
    const MFA_SECRET_LENGTH = 16;

    static private array $roleNames = [
        self::ROLE_USER         => 'User',
        self::ROLE_ADMIN        => 'Admin',
        self::ROLE_SITE_MANAGER => 'Site Manager',
    ];

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
     * @ORM\ManyToMany(targetEntity="Site")
     * @ORM\JoinTable(name="user_sites",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     * @ORM\OrderBy({"domainName" = "ASC"})
     */
    protected $sites;

    /**
     * @ORM\Column(type="string", length=64, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-z][-.a-z0-9_]+$/u")
     * @Assert\Length(min = "3")
     * @Assert\Length(max = "64")
     * @Filter\Trim()
     * @Filter\ToLower()
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min = "2")
     * @Assert\Length(max = "64")
     * @Filter\Trim()
     * @Filter\StripTags()
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min = "2")
     * @Assert\Length(max = "64")
     * @Filter\Trim()
     * @Filter\StripTags()
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min = "3")
     * @Assert\Length(max = "128")
     * @Assert\Email();
     * @Filter\Trim()
     * @Filter\StripTags()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="Timezone")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $timezone;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $role = self::ROLE_USER;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mfa = self::MFA_DISABLED;

    /**
     * @ORM\Column(type="string", length=64, unique=true, nullable=false)
     */
    private $mfaSecret;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status = self::STATUS_ACTIVE;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @var Collection
     */
    private $groups = [];

    private $plainPassword;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->mfaSecret = MfaAuthenticator::createSecret(self::MFA_SECRET_LENGTH);
        $this->sites = new ArrayCollection();
        $this->roles = [];
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

    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): void
    {
        if (false === $this->sites->contains($site)) {
            $this->sites[] = $site;
        }
    }

    public function removeSite(Site $site): void
    {
        $this->sites->removeElement($site);
    }

    public function removeSites(): void
    {
        $this->sites = new ArrayCollection();
    }

    public function hasSite(Site $site): bool
    {
        $hasSite = $this->sites->contains($site);
        return $hasSite;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getTimezone(): ?Timezone
    {
        return $this->timezone;
    }

    public function setTimezone(?Timezone $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function hasMfaEnabled(): ?bool
    {
        return $this->mfa;
    }

    public function getMfa(): ?string
    {
        return $this->mfa;
    }

    public function setMfa(bool $mfa): void
    {
        $this->mfa = $mfa;
    }

    public function getMfaSecret(): ?string
    {
        return $this->mfaSecret;
    }

    public function setMfaSecret(string $mfaSecret): void
    {
        $this->mfaSecret = $mfaSecret;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function addRole($role): void
    {
        $role = strtoupper($role);
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    public function getRoles(): array
    {
        $roles = array_merge($this->roles, [$this->role]);
        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }
        return array_unique($roles);
    }

    public function getGroups(): ?array
    {
        return $this->groups;
    }

    public function setPlainPassword(string $password): void
    {
        $this->plainPassword = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUserName();
    }

    public function getRoleName(): ?string
    {
        $role = $this->getRole();
        $roleName = self::$roleNames[$role] ?? '';
        return $roleName;
    }

    public static function getRoleNames(): ?array
    {
        return self::$roleNames;
    }

    public function getSalt(): ?string
    {
        return '';
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
            'fields'    => ['userName'],
            'errorPath' => 'userName',
            'message'   => 'This value already exists.',
        )));

        $metadata->addConstraint(new UniqueEntity(array(
            'fields'    => ['email'],
            'errorPath' => 'email',
            'message'   => 'This value already exists.',
        )));
    }
}
