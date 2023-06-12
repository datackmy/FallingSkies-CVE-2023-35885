<?php

namespace App\Entity\Manager;

class InstanceLoadAverageManager extends BaseManager
{
    public function getLoadAverageValue($period, \DateTimeInterface $startTime, \DateTimeInterface $endTime)
    {
        return $this->repository->getLoadAverageValue($period, $startTime, $endTime);
    }
}
