<?php

namespace App\Entity\Manager;

class InstanceCpuManager extends BaseManager
{
    public function getAverageCpuValue(\DateTimeInterface $startTime, \DateTimeInterface $endTime)
    {
        return $this->repository->getAverageCpuValue($startTime, $endTime);
    }
}
