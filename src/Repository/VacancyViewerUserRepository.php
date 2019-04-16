<?php

namespace App\Repository;

use App\Entity\VacancyViewerUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VacancyViewerUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyViewerUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyViewerUser[]    findAll()
 * @method VacancyViewerUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyViewerUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VacancyViewerUser::class);
    }

    // /**
    //  * @return VacancyViewerUser[] Returns an array of VacancyViewerUser objects
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
    public function findOneBySomeField($value): ?VacancyViewerUser
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
