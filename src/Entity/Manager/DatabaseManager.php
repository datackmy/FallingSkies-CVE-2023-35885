<?php

namespace App\Entity\Manager;

class DatabaseManager extends BaseManager
{
    public function findOneByName($name)
    {
        return $this->repository->findOneByName($name);
    }
}
