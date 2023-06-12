<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CronJobRepository;
use App\Entity\Site;

/**
 * @ORM\Table(name="cron_job")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=CronJobRepository::class)
 */
class CronJob
{
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
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="cronJobs")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     **/
    private $site;

    /**
     * @ORM\Column(type="string", length=16, nullable=false)
     * @Assert\NotBlank()
     */
    private $minute = '*';

    /**
     * @ORM\Column(type="string", length=16, nullable=false)
     * @Assert\NotBlank()
     */
    private $hour = '*';

    /**
     * @ORM\Column(type="string", length=16, nullable=false)
     * @Assert\NotBlank()
     */
    private $day = '*';

    /**
     * @ORM\Column(type="string", length=16, nullable=false)
     * @Assert\NotBlank()
     */
    private $month = '*';

    /**
     * @ORM\Column(type="string", length=16, nullable=false)
     * @Assert\NotBlank()
     */
    private $weekday = '*';

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $command;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
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

    public function getMinute(): ?string
    {
        return $this->minute;
    }

    public function setMinute(string $minute): void
    {
        $this->minute = $minute;
    }

    public function getHour(): ?string
    {
        return $this->hour;
    }

    public function setHour(string $hour): void
    {
        $this->hour = $hour;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): void
    {
        $this->day = $day;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): void
    {
        $this->month = $month;
    }

    public function getWeekday(): ?string
    {
        return $this->weekday;
    }

    public function setWeekday(string $weekday): void
    {
        $this->weekday = $weekday;
    }

    public function getCommand(): ?string
    {
        return $this->command;
    }

    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    public function getSchedule(): string
    {
        $minute = $this->getMinute();
        $day = $this->getDay();
        $hour = $this->getHour();
        $month = $this->getMonth();
        $weekday = $this->getWeekday();
        $schedule = sprintf('%s %s %s %s %s', $minute, $hour, $day, $month, $weekday);
        return $schedule;
    }

    public function getCrontabExpression(): string
    {
        $site = $this->getSite();
        $siteUser = $site->getUser();
        $minute = $this->getMinute();
        $day = $this->getDay();
        $hour = $this->getHour();
        $month = $this->getMonth();
        $weekday = $this->getWeekday();
        $command = $this->getCommand();
        $crontabExpression = sprintf('%s %s %s %s %s %s %s', $minute, $hour, $day, $month, $weekday, $siteUser, $command);
        return $crontabExpression;
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
