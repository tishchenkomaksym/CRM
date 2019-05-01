<?php

namespace App\Repository;

use App\Entity\SDTEmailAssignee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SDTEmailAssignee|null find($id, $lockMode = null, $lockVersion = null)
 * @method SDTEmailAssignee|null findOneBy(array $criteria, array $orderBy = null)
 * @method SDTEmailAssignee[]    findAll()
 * @method SDTEmailAssignee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SDTEmailAssigneeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SDTEmailAssignee::class);
    }

    // /**
    //  * @return SDTEmailAssignee[] Returns an array of SDTEmailAssignee objects
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
    public function findOneBySomeField($value): ?SDTEmailAssignee
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
