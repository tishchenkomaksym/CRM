<?php

namespace App\Repository;

use App\Entity\DenyReasonCandidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DenyReasonCandidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method DenyReasonCandidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method DenyReasonCandidate[]    findAll()
 * @method DenyReasonCandidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DenyReasonCandidateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DenyReasonCandidate::class);
    }

    // /**
    //  * @return DenyReasonCandidate[] Returns an array of DenyReasonCandidate objects
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
    public function findOneBySomeField($value): ?DenyReasonCandidate
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
