<?php

namespace App\Repository;

use App\Entity\DenyChoiceDepartment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DenyChoiceDepartment|null find($id, $lockMode = null, $lockVersion = null)
 * @method DenyChoiceDepartment|null findOneBy(array $criteria, array $orderBy = null)
 * @method DenyChoiceDepartment[]    findAll()
 * @method DenyChoiceDepartment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DenyChoiceDepartmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DenyChoiceDepartment::class);
    }

    // /**
    //  * @return DenyChoiceDepartment[] Returns an array of DenyChoiceDepartment objects
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
    public function findOneBySomeField($value): ?DenyChoiceDepartment
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
