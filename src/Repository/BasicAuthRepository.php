<?php
 namespace App\Repository; use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; use Doctrine\Persistence\ManagerRegistry; use App\Entity\BasicAuth; class BasicAuthRepository extends ServiceEntityRepository { public function __construct(ManagerRegistry $registry) { parent::__construct($registry, BasicAuth::class); } }
