<?php

namespace App\Repository;

use App\Entity\CandidateManagerDeny;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CandidateManagerDeny|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidateManagerDeny|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidateManagerDeny[]    findAll()
 * @method CandidateManagerDeny[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateManagerDenyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CandidateManagerDeny::class);
    }

    // /**
    //  * @return CandidateManagerDeny[] Returns an array of CandidateManagerDeny objects
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
    public function findOneBySomeField($value): ?CandidateManagerDeny
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
