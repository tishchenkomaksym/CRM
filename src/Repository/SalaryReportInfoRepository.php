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

    /**
     * @param SalaryReportInfo $salaryReportInfo
     * @return SalaryReportInfo
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPreviousReport(SalaryReportInfo $salaryReportInfo): SalaryReportInfo
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.createDate < :date')
            ->setParameter('date', $salaryReportInfo->getCreateDate())
            ->orderBy('p.createDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }
}
