<?php

namespace App\Repository;

use App\Entity\DepartmentTeamSdtViewRules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DepartmentTeamSdtViewRules|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepartmentTeamSdtViewRules|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepartmentTeamSdtViewRules[]    findAll()
 * @method DepartmentTeamSdtViewRules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentTeamSdtViewRulesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DepartmentTeamSdtViewRules::class);
    }

    // /**
    //  * @return DepartmentTeamSdtViewRules[] Returns an array of DepartmentTeamSdtViewRules objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DepartmentTeamSdtViewRules
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
