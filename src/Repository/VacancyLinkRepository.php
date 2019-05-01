<?php

namespace App\Repository;

use App\Entity\VacancyLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VacancyLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyLink[]    findAll()
 * @method VacancyLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyLinkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VacancyLink::class);
    }

    // /**
    //  * @return VacancyLink[] Returns an array of VacancyLink objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VacancyLink
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
