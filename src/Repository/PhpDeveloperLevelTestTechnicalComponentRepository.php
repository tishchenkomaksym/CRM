<?php

namespace App\Repository;

use App\Entity\PhpDeveloperLevelTestTechnicalComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperLevelTestTechnicalComponent|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperLevelTestTechnicalComponent|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperLevelTestTechnicalComponent[]    findAll()
 * @method PhpDeveloperLevelTestTechnicalComponent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperLevelTestTechnicalComponentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperLevelTestTechnicalComponent::class);
    }
}
