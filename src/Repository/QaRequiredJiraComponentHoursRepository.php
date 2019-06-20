<?php

namespace App\Repository;

use App\Entity\QaRequiredJiraComponentHours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QaRequiredJiraComponentHours|null find($id, $lockMode = null, $lockVersion = null)
 * @method QaRequiredJiraComponentHours|null findOneBy(array $criteria, array $orderBy = null)
 * @method QaRequiredJiraComponentHours[]    findAll()
 * @method QaRequiredJiraComponentHours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QaRequiredJiraComponentHoursRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QaRequiredJiraComponentHours::class);
    }
}
