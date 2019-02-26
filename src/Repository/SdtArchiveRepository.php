<?php

namespace App\Repository;

use App\Entity\SdtArchive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SdtArchive|null find($id, $lockMode = null, $lockVersion = null)
 * @method SdtArchive|null findOneBy(array $criteria, array $orderBy = null)
 * @method SdtArchive[]    findAll()
 * @method SdtArchive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SdtArchiveRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SdtArchive::class);
    }

    // /**
    //  * @return SdtArchive[] Returns an array of SdtArchive objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SdtArchive
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
