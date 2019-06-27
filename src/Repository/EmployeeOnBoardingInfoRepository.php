<?php

namespace App\Repository;

use App\Entity\EmployeeOnBoardingInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EmployeeOnBoardingInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeOnBoardingInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeOnBoardingInfo[]    findAll()
 * @method EmployeeOnBoardingInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeOnBoardingInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EmployeeOnBoardingInfo::class);
    }

    // /**
    //  * @return EmployeeOnBoardingInfo[] Returns an array of EmployeeOnBoardingInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmployeeOnBoardingInfo
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
