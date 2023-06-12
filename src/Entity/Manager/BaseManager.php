<?php

namespace App\Entity\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class BaseManager
{
    protected EntityManagerInterface $entityManager;
    protected ObjectRepository $repository;
    protected ?string $class = null;

    /**
     * Constructor
     *
     ** @param EntityManagerInterface $entityManager
     *  @param string                 $class
     */
    public function __construct(EntityManagerInterface $entityManager, string $class)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($class);
        $metadata = $this->entityManager->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    public function createEntity()
    {
        $entity = $this->getClass();
        $entity = new $entity;
        return $entity;
    }

    public function updateEntity($entity, $andFlush = true): void
    {
        $this->entityManager->persist($entity);
        if (true === $andFlush) {
            $this->entityManager->flush();
        }
    }

    public function deleteEntity($entity, $andFlush = true)
    {
        $this->entityManager->remove($entity);
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    public function findOneById($id)
    {
        return $this->repository->findOneById($id);
    }

    public function findAll(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getRepository()
    {
        return $this->repository;
    }
}