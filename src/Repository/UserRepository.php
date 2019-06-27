<?php

namespace App\Repository;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function todayListUsers()
    {
        $date = new DateTime();
        $date->setTime(00, 00,00);
        $date2 = new DateTime();
        $date2->setTime(23, 59,00);
        return $this->createQueryBuilder('u')
            ->where('u.createDate BETWEEN :from AND :to')
            ->setParameter('from', $date)
            ->setParameter('to', $date2)
            ->getQuery()
            ->getResult();
    }
}
