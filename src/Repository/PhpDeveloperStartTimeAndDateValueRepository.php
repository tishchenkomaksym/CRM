<?php

namespace App\Repository;

use App\Entity\PhpDeveloperStartTimeAndDateValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperStartTimeAndDateValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperStartTimeAndDateValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperStartTimeAndDateValue[]    findAll()
 * @method PhpDeveloperStartTimeAndDateValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperStartTimeAndDateValueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperStartTimeAndDateValue::class);
    }
}
