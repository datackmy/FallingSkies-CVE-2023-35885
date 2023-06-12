<?php
 namespace App\Repository; use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; use Doctrine\Persistence\ManagerRegistry; use App\Entity\Site; class SiteRepository extends ServiceEntityRepository { public function __construct(ManagerRegistry $registry) { parent::__construct($registry, Site::class); } }
