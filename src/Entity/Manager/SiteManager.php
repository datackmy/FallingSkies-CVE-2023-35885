<?php

namespace App\Entity\Manager;

use App\Entity\Site as SiteEntity;
use App\Entity\User as UserEntity;

class SiteManager extends BaseManager
{
    public function getUserSites(UserEntity $user, array $orderBy): array
    {
        $role = $user->getRole();
        if (UserEntity::ROLE_USER == $role) {
            $sites = $user->getSites()->toArray();
        } else {
            $sites = $this->findAll([], $orderBy);
        }
        return $sites;
    }

    public function findOneByDomainName(string $domainName)
    {
        return $this->repository->findOneByDomainName($domainName);
    }

    public function findOneByUser(string $userName)
    {
        return $this->repository->findOneByUser($userName);
    }
}
