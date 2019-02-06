<?php

namespace App\Repository;

use App\Entity\PhpDeveloperLevelTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperLevelTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperLevelTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperLevelTest[]    findAll()
 * @method PhpDeveloperLevelTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperLevelTestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperLevelTest::class);
    }

    // /**
    //  * @return PhpDeveloperLevelTest[] Returns an array of PhpDeveloperLevelTest objects
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
    public function findOneBySomeField($value): ?PhpDeveloperLevelTest
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
