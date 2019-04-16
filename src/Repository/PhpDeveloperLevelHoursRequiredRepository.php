<?php

namespace App\Repository;

use App\Entity\PhpDeveloperLevelHoursRequired;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperLevelHoursRequired|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperLevelHoursRequired|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperLevelHoursRequired[]    findAll()
 * @method PhpDeveloperLevelHoursRequired[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperLevelHoursRequiredRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperLevelHoursRequired::class);
    }
}
