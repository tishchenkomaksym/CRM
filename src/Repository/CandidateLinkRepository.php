<?php

namespace App\Repository;

use App\Entity\CandidateLink;
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
}
