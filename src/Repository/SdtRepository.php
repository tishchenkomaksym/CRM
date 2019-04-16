<?php

namespace App\Repository;

use App\Entity\Sdt;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sdt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sdt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sdt[]    findAll()
 * @method Sdt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SdtRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sdt::class);
    }

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @param User $user
     * @return Sdt[]
     */
    public function getSDTFromDateToDate(DateTime $from, DateTime $to, User $user)
    {
        $q = $this->createQueryBuilder('p')
                  ->where('p.create_date >= :dateFrom')
                  ->andWhere('p.create_date <= :dateTo')
                  ->andWhere('p.user = :userId')
                  ->setParameter('dateFrom', $from->format('Y-m-d'))
                  ->setParameter('dateTo', $to->format('Y-m-d'))
                  ->setParameter('userId', $user->getId())
                  ->getQuery();
        return $q->getResult();
    }
}
