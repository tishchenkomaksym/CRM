<?php

namespace App\Repository;

use App\Entity\CandidateManagerApproval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CandidateManagerApproval|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidateManagerApproval|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidateManagerApproval[]    findAll()
 * @method CandidateManagerApproval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateManagerApprovalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CandidateManagerApproval::class);
    }

    // /**
    //  * @return CandidateManagerApproval[] Returns an array of CandidateManagerApproval objects
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
    public function findOneBySomeField($value): ?CandidateManagerApproval
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
