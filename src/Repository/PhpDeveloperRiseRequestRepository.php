<?php

namespace App\Repository;

use App\Entity\PhpDeveloperRiseRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperRiseRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperRiseRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperRiseRequest[]    findAll()
 * @method PhpDeveloperRiseRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperRiseRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperRiseRequest::class);
    }
}
