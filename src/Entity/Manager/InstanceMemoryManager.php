<?php

namespace App\Entity\Manager;

class InstanceMemoryManager extends BaseManager
{
    public function getAverageMemoryValue(\DateTimeInterface $startTime, \DateTimeInterface $endTime)
    {
        return $this->repository->getAverageMemoryValue($startTime, $endTime);
    }
}
