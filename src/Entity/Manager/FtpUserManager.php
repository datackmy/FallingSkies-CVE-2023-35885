<?php

namespace App\Entity\Manager;

class FtpUserManager extends BaseManager
{
    public function findOneByUserName(string $userName)
    {
        return $this->repository->findOneByUserName($userName);
    }
}
