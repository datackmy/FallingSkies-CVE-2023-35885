<?php

namespace App\Entity\Manager;

class DatabaseUserManager extends BaseManager
{
    public function findOneByUserName($userName)
    {
        return $this->repository->findOneByUserName($userName);
    }
}
