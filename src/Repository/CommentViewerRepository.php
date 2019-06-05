<?php

namespace App\Repository;

use App\Entity\CommentViewer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommentViewer|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentViewer|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentViewer[]    findAll()
 * @method CommentViewer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentViewerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommentViewer::class);
    }

    // /**
    //  * @return CommentViewer[] Returns an array of CommentViewer objects
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
    public function findOneBySomeField($value): ?CommentViewer
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
