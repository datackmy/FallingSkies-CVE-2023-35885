<?php

namespace App\Entity\Manager;

class InstanceDiskUsageManager extends BaseManager
{
    public function getAverageDiskSizeValue($disk, \DateTimeInterface $startTime, \DateTimeInterface $endTime)
    {
        return $this->repository->getAverageDiskSizeValue($disk, $startTime, $endTime);
    }
}
