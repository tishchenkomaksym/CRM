<?php

namespace App\Repository;

use App\Entity\SalaryReportInfo;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @throws NonUniqueResultException
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


    /**
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getTodaySalaryReport()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.createDate < :date')
            ->setParameter('date', new DateTime())
            ->orderBy('p.createDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @param DateTime $dateTime
     * @return SalaryReportInfo|null
     * @throws NonUniqueResultException
     */
    public function getNextSalaryReport(DateTime $dateTime): ?SalaryReportInfo
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.createDate > :date')
            ->setParameter('date', $dateTime)
            ->orderBy('p.createDate', 'asc')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }
}
