<?php

namespace App\Repository;

use App\Entity\DenyChoiceCandidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DenyChoiceCandidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method DenyChoiceCandidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method DenyChoiceCandidate[]    findAll()
 * @method DenyChoiceCandidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DenyChoiceCandidateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DenyChoiceCandidate::class);
    }

    // /**
    //  * @return DenyChoiceCandidate[] Returns an array of DenyChoiceCandidate objects
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
    public function findOneBySomeField($value): ?DenyChoiceCandidate
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
