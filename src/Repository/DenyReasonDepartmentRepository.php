<?php

namespace App\Repository;

use App\Entity\DenyReasonDepartment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DenyReasonDepartment|null find($id, $lockMode = null, $lockVersion = null)
 * @method DenyReasonDepartment|null findOneBy(array $criteria, array $orderBy = null)
 * @method DenyReasonDepartment[]    findAll()
 * @method DenyReasonDepartment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DenyReasonDepartmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DenyReasonDepartment::class);
    }

    // /**
    //  * @return DenyReasonDepartment[] Returns an array of DenyReasonDepartment objects
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
    public function findOneBySomeField($value): ?DenyReasonDepartment
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
