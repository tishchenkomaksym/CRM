<?php

namespace App\Repository;

use App\Entity\ConfRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ConfRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfRoom[]    findAll()
 * @method ConfRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfRoomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ConfRoom::class);
    }

    // /**
    //  * @return ConfRoom[] Returns an array of ConfRoom objects
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
    public function findOneBySomeField($value): ?ConfRoom
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
