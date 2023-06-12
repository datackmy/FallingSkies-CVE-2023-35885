<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DatabaseRepository;
use App\Validator\Constraints as AppAssert;
use App\Entity\DatabaseServer;
use App\Entity\DatabaseUser;
use App\Entity\Site;

/**
 * @ORM\Table(name="database")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=DatabaseRepository::class)
 */
class Database
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="databases")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     **/
    private $site;

    /**
     * @ORM\OneToMany(targetEntity="DatabaseUser", mappedBy="database", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"userName" = "ASC"})
     */
    private $users;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, max = 50)
     * @Assert\Regex("/^[a-z][-a-z0-9]+$/iu")
     * @AppAssert\DatabaseName()
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="DatabaseServer")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $databaseServer;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->users = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDatabaseServer(): ?DatabaseServer
    {
        return $this->databaseServer;
    }

    public function setDatabaseServer(DatabaseServer $databaseServer): void
    {
        $this->databaseServer = $databaseServer;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(DatabaseUser $databaseUser): void
    {
        $this->users[] = $databaseUser;
    }

    public function removeUser(DatabaseUser $databaseUser): void
    {
        $this->users->removeElement($databaseUser);
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
