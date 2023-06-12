<?php
 namespace App\Notification; use App\Entity\Notification; class NotificationQueue { private static array $queuedNotifications = []; public static function addNotification(Notification $notification) : void { self::$queuedNotifications[] = $notification; } public static function getQueuedNotifications() : array { return self::$queuedNotifications; } }
