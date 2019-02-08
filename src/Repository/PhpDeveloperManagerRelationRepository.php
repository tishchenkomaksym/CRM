<?php

namespace App\Repository;

use App\Entity\PhpDeveloperManagerRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperManagerRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperManagerRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperManagerRelation[]    findAll()
 * @method PhpDeveloperManagerRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperManagerRelationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperManagerRelation::class);
    }

    // /**
    //  * @return PhpDeveloperManagerRelation[] Returns an array of PhpDeveloperManagerRelation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhpDeveloperManagerRelation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
