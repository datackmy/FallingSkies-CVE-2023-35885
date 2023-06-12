<?php

namespace App\Entity\Manager;

class VhostTemplateManager extends BaseManager
{
    public function deleteTemplatesByType(int $type): void
    {
        $this->repository->deleteTemplatesByType($type);
    }

    public function findOneByName(string $name)
    {
        return $this->repository->findOneByName($name);
    }
}
