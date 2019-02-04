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

    // /**
    //  * @return UserPhpDeveloperLevelRelation[] Returns an array of UserPhpDeveloperLevelRelation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPhpDeveloperLevelRelation
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
