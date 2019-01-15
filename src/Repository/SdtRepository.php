<?php

namespace App\Repository;

use App\Entity\Sdt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sdt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sdt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sdt[]    findAll()
 * @method Sdt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SdtRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sdt::class);
    }

    // /**
    //  * @return Sdt[] Returns an array of Sdt objects
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
    public function findOneBySomeField($value): ?Sdt
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
