<?php

namespace App\Repository;

use App\Entity\CandidateOfferDeny;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CandidateOfferDeny|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidateOfferDeny|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidateOfferDeny[]    findAll()
 * @method CandidateOfferDeny[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateOfferDenyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CandidateOfferDeny::class);
    }

    // /**
    //  * @return CandidateOfferDeny[] Returns an array of CandidateOfferDeny objects
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
    public function findOneBySomeField($value): ?CandidateOfferDeny
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
