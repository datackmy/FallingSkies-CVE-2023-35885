<?php

namespace App\Entity\Manager;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserManager extends BaseManager
{
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(EntityManagerInterface $entityManager, $class, PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($class);
        $this->passwordHasherFactory = $passwordHasherFactory;
        $metadata = $entityManager->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    public function updateUser(User $user, $andFlush = true, $updatePassword = false)
    {
        if (true === is_null($user->getId()) || (true === $updatePassword)) {
            $plainPassword = $user->getPlainPassword();
            $passwordHasher = $this->passwordHasherFactory->getPasswordHasher($user);
            $password = $passwordHasher->hash($plainPassword);
            $user->setPassword($password);
            $user->eraseCredentials();
        }
        $this->entityManager->persist($user);
        if (true === $andFlush) {
            $this->entityManager->flush();
        }
    }

    public function deleteUser(User $user, $andFlush = true)
    {
        $this->entityManager->remove($user);
        if (true === $andFlush) {
            $this->entityManager->flush();
        }
    }

    public function findOneByUserName(string $userName)
    {
        return $this->repository->findOneByUserName($userName);
    }

    public function findOneByEmail(string $email)
    {
        return $this->repository->findOneByEmail($email);
    }

    public function countAll()
    {
        return $this->repository->countAll();
    }
}
