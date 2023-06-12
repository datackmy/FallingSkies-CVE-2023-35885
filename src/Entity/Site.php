<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DMS\Filter\Rules as Filter;
use App\Validator\Constraints as AppAssert;
use App\Repository\SiteRepository;
use App\Entity\BasicAuth;
use App\Entity\BlockedBot;
use App\Entity\BlockedIp;
use App\Entity\Certificate;
use App\Entity\CronJob;
use App\Entity\Database;
use App\Entity\PhpSettings;
use App\Entity\NodejsSettings;
use App\Entity\PythonSettings;
use App\Entity\FtpUser;
use App\Entity\SshUser;

/**
 * @ORM\Table(name="site")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 */
class Site
{
    const TYPE_PHP    = 'php';
    const TYPE_NODEJS = 'nodejs';
    const TYPE_STATIC = 'static';
    const TYPE_PYTHON = 'python';
    const TYPE_REVERSE_PROXY = 'reverse-proxy';

    private const PAGE_SPEED_SETTINGS_TEMPLATE =<<<EOD
pagespeed RewriteLevel CoreFilters;
pagespeed EnableFilters remove_quotes;
pagespeed DisableFilters prioritize_critical_css;
pagespeed EnableFilters recompress_images;
pagespeed EnableFilters responsive_images,resize_images;
pagespeed EnableFilters lazyload_images;
pagespeed EnableFilters sprite_images;
pagespeed EnableFilters insert_dns_prefetch;
pagespeed EnableFilters hint_preload_subresources;
pagespeed EnableFilters collapse_whitespace;
pagespeed EnableFilters dedup_inlined_images;
pagespeed EnableFilters inline_preview_images,resize_mobile_images;
pagespeed HttpCacheCompressionLevel 0;
pagespeed FetchHttps enable;
location ~ "^/pagespeed_static/" { }
location ~ "^/ngx_pagespeed_beacon$" { }
location ~ "\.pagespeed\.([a-z]\.)?[a-z]{2}\.[^.]{10}\.[^.]+" {
  add_header "" "";
}
EOD;

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
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Filter\Trim()
     * @Filter\ToLower()
     * @AppAssert\DomainName()
     * @AppAssert\UniqueDomainName()
     */
    private $domainName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $rootDirectory;

    /**
     * @ORM\Column(type="string", length=64, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-z][-a-z0-9_]+$/iu")
     * @Assert\Length(min = "3")
     * @Assert\Length(max = "32")
     * @Filter\Trim()
     * @AppAssert\UniqueSystemUser()
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = "8")
     * @Assert\Length(max = "200")
     * @Filter\Trim()
     */
    private $userPassword;

    /**
     * @ORM\Column(type="text", nullable=false, nullable=true)
     */
    private $sshKeys;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pageSpeedEnabled = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pageSpeedSettings = self::PAGE_SPEED_SETTINGS_TEMPLATE;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $vhostTemplate;

    /**
     * @ORM\OneToOne(targetEntity="BasicAuth", inversedBy="site", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="basic_auth_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $basicAuth;

    /**
     * @ORM\OneToMany(targetEntity="BlockedIp", mappedBy="site", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"ip" = "ASC"})
     */
    private $blockedIps;

    /**
     * @ORM\OneToMany(targetEntity="BlockedBot", mappedBy="site", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $blockedBots;

    /**
     * @ORM\OneToOne(targetEntity="Certificate", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="certificate_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $certificate;

    /**
     * @ORM\OneToMany(targetEntity="Database", mappedBy="site", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $databases;

    /**
     * @ORM\OneToMany(targetEntity="Certificate", mappedBy="site", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $certificates;

    /**
     * @ORM\OneToMany(targetEntity="CronJob", mappedBy="site", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $cronJobs;

    /**
     * @ORM\OneToMany(targetEntity="FtpUser", mappedBy="site", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"userName" = "ASC"})
     */
    private $ftpUsers;

    /**
     * @ORM\OneToMany(targetEntity="SshUser", mappedBy="site", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"userName" = "ASC"})
     */
    private $sshUsers;

    /**
     * @ORM\OneToOne(targetEntity="NodejsSettings", fetch="EAGER", mappedBy="site", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="nodejs_settings_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $nodejsSettings;

    /**
     * @ORM\OneToOne(targetEntity="PhpSettings", fetch="EAGER", mappedBy="site", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="php_settings_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $phpSettings;

    /**
     * @ORM\OneToOne(targetEntity="PythonSettings", fetch="EAGER", mappedBy="site", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="python_settings_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $pythonSettings;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $application;

    /**
     * @ORM\Column(type="boolean")
     */
    private $allowTrafficFromCloudflareOnly = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $varnishCache = false;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $reverseProxyUrl;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->certificates = new ArrayCollection();
        $this->cronJobs = new ArrayCollection();
        $this->blockedBots = new ArrayCollection();
        $this->blockedIps = new ArrayCollection();
        $this->databases = new ArrayCollection();
        $this->ftpUsers = new ArrayCollection();
        $this->sshUsers = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getUserPassword(): ?string
    {
        return $this->userPassword;
    }

    public function setUserPassword($userPassword): void
    {
        $this->userPassword = $userPassword;
    }

    public function getDomainName(): ?string
    {
        return $this->domainName;
    }

    public function setDomainName(string $domainName): void
    {
        $this->domainName = $domainName;
    }

    public function getRootDirectory(): ?string
    {
        return $this->rootDirectory;
    }

    public function setRootDirectory(string $rootDirectory): void
    {
        $this->rootDirectory = $rootDirectory;
    }

    public function getSshKeys(): ?string
    {
        return $this->sshKeys;
    }

    public function setSshKeys(string $sshKeys): void
    {
        $this->sshKeys = $sshKeys;
    }

    public function getPageSpeedEnabled(): ?bool
    {
        return $this->pageSpeedEnabled;
    }

    public function setPageSpeedEnabled(bool $flag): void
    {
        $this->pageSpeedEnabled = $flag;
    }

    public function getPageSpeedSettings(): ?string
    {
        return $this->pageSpeedSettings;
    }

    public function setPageSpeedSettings(?string $pageSpeedSettings): void
    {
        $this->pageSpeedSettings = $pageSpeedSettings;
    }

    public function getVhostTemplate(): ?string
    {
        return $this->vhostTemplate;
    }

    public function setVhostTemplate(string $vhostTemplate): void
    {
        $this->vhostTemplate = $vhostTemplate;
    }

    public function setCertificate(Certificate $certificate): void
    {
        $this->certificate = $certificate;
    }

    public function getCertificate(): ?Certificate
    {
        return $this->certificate;
    }

    public function getBasicAuth(): ?BasicAuth
    {
        return $this->basicAuth;
    }

    public function setBasicAuth(?BasicAuth $basicAuth): void
    {
        $this->basicAuth = $basicAuth;
    }

    public function getBlockedBots(): ?Collection
    {
        return $this->blockedBots;
    }

    public function addBlockedBot(BlockedBot $blockedBot): void
    {
        $this->blockedBots[] = $blockedBot;
    }

    public function removeBlockedBot(BlockedBot $blockedBot): void
    {
        $this->blockedBots->removeElement($blockedBot);
    }

    public function getBlockedIps(): ?Collection
    {
        return $this->blockedIps;
    }

    public function addBlockedIp(BlockedIp $blockedIp): void
    {
        $this->blockedIps[] = $blockedIp;
    }

    public function removeBlockedIp(BlockedIp $blockedIp): void
    {
        $this->blockedIps->removeElement($blockedIp);
    }

    public function addDatabase(Database $database): void
    {
        $this->databases[] = $database;
    }

    public function removeDatabase(Database $database): void
    {
        $this->databases->removeElement($database);
    }

    public function getDatabases(): ?Collection
    {
        return $this->databases;
    }

    public function addCertificate(Certificate $certificate): void
    {
        $this->certificates[] = $certificate;
    }

    public function removeCertificate(Certificate $certificate): void
    {
        $this->certificates->removeElement($certificate);
    }

    public function getCertificates(): ?Collection
    {
        return $this->certificates;
    }

    public function getFtpUsers(): Collection
    {
        return $this->ftpUsers;
    }

    public function addFtpUser(FtpUser $ftpUser): void
    {
        $this->ftpUsers[] = $ftpUser;
    }

    public function removeFtpUser(FtpUser $ftpUser): void
    {
        $this->ftpUsers->removeElement($ftpUser);
    }

    public function getSshUsers(): Collection
    {
        return $this->sshUsers;
    }

    public function addSshUser(SshUser $sshUser): void
    {
        $this->sshUsers[] = $sshUser;
    }

    public function removeSshUser(SshUser $sshUser): void
    {
        $this->sshUsers->removeElement($sshUser);
    }

    public function getCronJobs(): Collection
    {
        return $this->cronJobs;
    }

    public function addCronJob(CronJob $cronJob): void
    {
        $this->cronJobs[] = $cronJob;
    }

    public function removeCronJob(CronJob $cronJob): void
    {
        $this->cronJobs->removeElement($cronJob);
    }

    public function getNodejsSettings(): ?NodejsSettings
    {
        return $this->nodejsSettings;
    }

    public function setNodejsSettings(NodejsSettings $nodejsSettings): void
    {
        $this->nodejsSettings = $nodejsSettings;
    }
    
    public function getPhpSettings(): ?PhpSettings
    {
        return $this->phpSettings;
    }

    public function setPhpSettings(PhpSettings $phpSettings): void
    {
        $this->phpSettings = $phpSettings;
    }

    public function getPythonSettings(): ?PythonSettings
    {
        return $this->pythonSettings;
    }

    public function setPythonSettings(PythonSettings $pythonSettings): void
    {
        $this->pythonSettings = $pythonSettings;
    }

    public function getApplication(): ?string
    {
        return $this->application;
    }

    public function setApplication(string $application): void
    {
        $this->application = $application;
    }

    public function allowTrafficFromCloudflareOnly(): bool
    {
        return $this->allowTrafficFromCloudflareOnly;
    }

    public function setAllowTrafficFromCloudflareOnly(bool $flag): void
    {
        $this->allowTrafficFromCloudflareOnly = $flag;
    }

    public function getAllowTrafficFromCloudflareOnly(): ?bool
    {
        return $this->allowTrafficFromCloudflareOnly;
    }

    public function setVarnishCache(bool $flag): void
    {
        $this->varnishCache = $flag;
    }

    public function getVarnishCache(): bool
    {
        return $this->varnishCache;
    }

    public function getReverseProxyUrl(): ?string
    {
        return $this->reverseProxyUrl;
    }

    public function setReverseProxyUrl(string $reverseProxyUrl): void
    {
        $this->reverseProxyUrl = $reverseProxyUrl;
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
        $this->userPassword = null;
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now');
        $this->userPassword = null;
    }
}
