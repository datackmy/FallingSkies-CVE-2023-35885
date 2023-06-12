<?php

namespace App\Entity\Manager;

class SshUserManager extends BaseManager
{
    public function findOneByUserName(string $userName)
    {
        return $this->repository->findOneByUserName($userName);
    }
}
