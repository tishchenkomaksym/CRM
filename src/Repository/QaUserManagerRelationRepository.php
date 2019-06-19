<?php

namespace App\Repository;

use App\Entity\QaUserManagerRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QaUserManagerRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method QaUserManagerRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method QaUserManagerRelation[]    findAll()
 * @method QaUserManagerRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QaUserManagerRelationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QaUserManagerRelation::class);
    }

    // /**
    //  * @return QaUserManagerRelation[] Returns an array of QaUserManagerRelation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QaUserManagerRelation
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
