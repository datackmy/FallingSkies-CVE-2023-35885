<?php

namespace App\Entity\Manager;

class ConfigManager extends BaseManager
{
    public function get(string $key): ?string
    {
        $configEntity = $this->repository->findOneByKey($key);
        if (false === is_null($configEntity)) {
            return $configEntity->getValue();
        }
        return null;
    }

    public function set(string $key, $value): mixed
    {
        $configEntity = $this->repository->findOneByKey($key);
        if (true === is_null($configEntity)) {
            $configEntity = $this->createEntity();
        }
        $configEntity->set($key, $value);
        $this->updateEntity($configEntity);
        return $configEntity;
    }

    public function delete(string $key)
    {
        $configEntity = $this->repository->findOneByKey($key);
        if (false === is_null($configEntity)) {
            $this->deleteEntity($configEntity);
        }
    }

    public function deleteByWildcard(string $wildcard): void
    {
        $this->repository->deleteByWildcard($wildcard);
    }
}
