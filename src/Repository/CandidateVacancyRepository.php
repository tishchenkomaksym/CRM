<?php

namespace App\Repository;

use App\Entity\CandidateVacancy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CandidateVacancy|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidateVacancy|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidateVacancy[]    findAll()
 * @method CandidateVacancy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateVacancyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CandidateVacancy::class);
    }

    // /**
    //  * @return CandidateLinkCheckExistenceUpdateCandidate[] Returns an array of CandidateLinkCheckExistenceUpdateCandidate objects
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
    public function findOneBySomeField($value): ?CandidateLinkCheckExistenceUpdateCandidate
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
