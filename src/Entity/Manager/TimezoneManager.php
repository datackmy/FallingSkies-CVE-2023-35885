<?php

namespace App\Entity\Manager;

class TimezoneManager extends BaseManager
{
    public function findOneByName($name)
    {
        return $this->repository->findOneByName($name);
    }
}
