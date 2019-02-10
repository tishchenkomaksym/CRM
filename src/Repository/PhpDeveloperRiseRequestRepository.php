<?php

namespace App\Repository;

use App\Entity\PhpDeveloperRiseRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperRiseRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperRiseRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperRiseRequest[]    findAll()
 * @method PhpDeveloperRiseRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperRiseRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperRiseRequest::class);
    }

    // /**
    //  * @return PhpDeveloperRiseRequest[] Returns an array of PhpDeveloperRiseRequest objects
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
    public function findOneBySomeField($value): ?PhpDeveloperRiseRequest
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
