<?php

namespace App\Repository;

use App\Entity\Candidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Candidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidate[]    findAll()
 * @method Candidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Candidate::class);
    }

    // /**
    //  * @return Candidate[] Returns an array of Candidate objects
    //  */

//    public function findByExampleName($value)
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.name = :name')
//            ->setParameter('id', $value)
////            ->orderBy('c.id', 'ASC')
////            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findByExampleName()
    {
        return $this->findBy(array(), array('name' => 'ASC'));
    }


    /*
    public function findOneBySomeField($value): ?Candidate
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
