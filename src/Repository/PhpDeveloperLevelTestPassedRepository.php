<?php

namespace App\Repository;

use App\Entity\PhpDeveloperLevelTestPassed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperLevelTestPassed|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperLevelTestPassed|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperLevelTestPassed[]    findAll()
 * @method PhpDeveloperLevelTestPassed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperLevelTestPassedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperLevelTestPassed::class);
    }

    // /**
    //  * @return PhpDeveloperLevelTestPassed[] Returns an array of PhpDeveloperLevelTestPassed objects
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
    public function findOneBySomeField($value): ?PhpDeveloperLevelTestPassed
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
