<?php

namespace App\Repository;

use App\Entity\UserPhpDeveloperLevelRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserPhpDeveloperLevelRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPhpDeveloperLevelRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPhpDeveloperLevelRelation[]    findAll()
 * @method UserPhpDeveloperLevelRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPhpDeveloperLevelRelationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserPhpDeveloperLevelRelation::class);
    }

}
