<?php

namespace App\Repository;

use App\Entity\CandidateVacancyHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CandidateVacancyHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidateVacancyHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidateVacancyHistory[]    findAll()
 * @method CandidateVacancyHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateVacancyHistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CandidateVacancyHistory::class);
    }

    // /**
    //  * @return CandidateVacancyHistoryDataProvider[] Returns an array of CandidateVacancyHistoryDataProvider objects
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
    public function findOneBySomeField($value): ?CandidateVacancyHistoryDataProvider
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
