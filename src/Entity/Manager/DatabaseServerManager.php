<?php

namespace App\Entity\Manager;

class DatabaseServerManager extends BaseManager
{
    public function getActiveDatabaseServer()
    {
        $activeDatabaseServer = $this->findOneBy(['isActive' => true]);
        return $activeDatabaseServer;
    }
}
