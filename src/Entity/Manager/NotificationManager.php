<?php

namespace App\Entity\Manager;

use App\Entity\Notification;

class NotificationManager extends BaseManager
{
    public function updateNotification(Notification $notification, $andFlush = true)
    {
        $hash = $notification->hash();
        $existingNotifications = $this->findAll(['hash' => $hash, 'isRead' => false], [], 5);
        if (0 == count($existingNotifications)) {
            $this->entityManager->persist($notification);
            if (true === $andFlush) {
                $this->entityManager->flush();
            }
        }
    }

    public function getNumberOfUnreadNotifications()
    {
        return $this->repository->getNumberOfUnreadNotifications();
    }
}
