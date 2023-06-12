<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Site\Ssl\Certificate as SslCertificate;
use App\Site\Ssl\CertificateParser;
use App\Repository\CertificateRepository;
use App\Validator\Constraints as AppAssert;
use App\Entity\Site;

/**
 * @ORM\Table(name="certificate")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=CertificateRepository::class)
 * @AppAssert\Certificate
 */
class Certificate
{
    const TYPE_SELF_SIGNED = 1;
    const TYPE_LETS_ENCRYPT = 2;
    const TYPE_IMPORTED = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true, nullable=false)
     */
    private $uid;

    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="certificates")
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
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $defaultCertificate = false;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $type = self::TYPE_SELF_SIGNED;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $csr;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $privateKey;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $certificate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $certificateChain;

    private array $domains = [];

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->expiresAt = new \DateTime('now');
        $this->uid = sha1(uniqid(mt_rand(), true));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): void
    {
        $this->uid = $uid;
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

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getExpiresAt(): ?\DateTime
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTime $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function getDefaultCertificate(): ?bool
    {
        return $this->defaultCertificate;
    }

    public function setDefaultCertificate(bool $flag): void
    {
        $this->defaultCertificate = $flag;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getCsr(): ?string
    {
        return $this->csr;
    }

    public function setCsr(?string $csr): void
    {
        $this->csr = $csr;
    }

    public function getPrivateKey(): ?string
    {
        return $this->privateKey;
    }

    public function setPrivateKey(string $privateKey): void
    {
        $this->privateKey = $privateKey;
    }

    public function getCertificate(): ?string
    {
        return $this->certificate;
    }

    public function setCertificate(string $certificate): void
    {
        $this->certificate = $certificate;
    }

    public function getCertificateChain(): ?string
    {
        return $this->certificateChain;
    }

    public function setCertificateChain(?string $certificateChain): void
    {
        $this->certificateChain = $certificateChain;
    }

    public function getDomains(): array
    {
        if (true === empty($this->domains)) {
            try {
                $certificateParser = new CertificateParser();
                $certificate = new SslCertificate();
                $certificate->setCertificate($this->getCertificate());
                $parsedCertificate = $certificateParser->parse($certificate);
                $this->domains = $parsedCertificate->getSubjectAlternativeNames();
            } catch (\Exception $e) {
            }
        }
        return $this->domains;
    }

    private function parseCertificateAndSetExpiredAt()
    {
        $certificateParser = new CertificateParser();
        $certificate = new SslCertificate();
        $certificate->setCertificate($this->getCertificate());
        $parsedCertificate = $certificateParser->parse($certificate);
        $validTo = $parsedCertificate->getValidTo();
        $validTo->setTimezone(new \DateTimeZone('UTC'));
        $this->expiresAt = $validTo;
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
        $this->parseCertificateAndSetExpiredAt();
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now');
        $this->parseCertificateAndSetExpiredAt();
    }
}
