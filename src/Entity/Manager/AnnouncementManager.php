<?php

namespace App\Entity\Manager;

use App\Entity\User;

class AnnouncementManager extends BaseManager
{
    public function findAllUnreadAnnouncementsForUser(User $user)
    {
        $criteria = [
            'user'   => $user,
            'isRead' => false
        ];
        return $this->repository->findBy($criteria);
    }

    public function findLatestUnreadAnnouncement(User $user)
    {
        return $this->repository->findLatestUnreadAnnouncement($user);
    }

    public function findOneByHash($hash)
    {
        return $this->repository->findOneByHash($hash);
    }
}
