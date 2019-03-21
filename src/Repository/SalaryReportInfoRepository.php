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
}
