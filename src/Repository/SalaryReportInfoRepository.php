<?php

namespace App\Repository;

use App\Entity\SalaryReportInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SalaryReportInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalaryReportInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalaryReportInfo[]    findAll()
 * @method SalaryReportInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalaryReportInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SalaryReportInfo::class);
    }

    // /**
    //  * @return SalaryReportInfo[] Returns an array of SalaryReportInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SalaryReportInfo
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
