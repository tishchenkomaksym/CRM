<?php

namespace App\Repository;

use App\Entity\CandidateLink;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CandidateLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidateLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidateLink[]    findAll()
 * @method CandidateLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateLinkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CandidateLink::class);
    }

    // /**
    //  * @return CandidateLink[] Returns an array of CandidateLink objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CandidateLink
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function letterBeforeDay()
    {
        $date = new DateTime();
        $date->modify('+1 days');
        $date->setTime(00, 00,00);
        $date2 = new DateTime();
        $date2->modify('+1 days');
        $date2->setTime(23, 59,00);
        return $this->createQueryBuilder('c')
            ->where('c.dateInterview IS NOT NULL')
            ->andWhere('c.dateInterview BETWEEN :from AND :to')
            ->setParameter('from', $date)
            ->setParameter('to', $date2)
            ->getQuery()
            ->getResult();
    }

    public function letterAfterInterview()
    {
        $date = new DateTime();
        $date2 = new DateTime();
        $date2->modify('+1 hour');
        return $this->createQueryBuilder('c')
            ->where('c.dateInterview IS NOT NULL')
            ->andWhere('c.dateInterview BETWEEN :from AND :to')
            ->setParameter('from', $date)
            ->setParameter('to', $date2)
            ->getQuery()
            ->getResult();
    }

    public function letterBeforeTwoDay()
    {
        $date = new DateTime();
        $date->modify('+2 days');
        $date->setTime(00, 00,00);
        $date2 = new DateTime();
        $date2->modify('+2 days');
        $date2->setTime(23, 59,00);
        return $this->createQueryBuilder('c')
            ->where('c.dateStartWork BETWEEN :from AND :to')
            ->setParameter('from', $date)
            ->setParameter('to', $date2)
            ->getQuery()
            ->getResult();
    }
}
