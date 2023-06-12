<?php

namespace App\Entity\Manager;

class CertificateManager extends BaseManager
{
    public function findOneByUid(string $uid)
    {
        return $this->repository->findOneByUid($uid);
    }
}
