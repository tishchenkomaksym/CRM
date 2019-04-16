<?php

namespace App\Repository;

use App\Entity\PhpDeveloperManagerRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperManagerRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperManagerRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperManagerRelation[]    findAll()
 * @method PhpDeveloperManagerRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperManagerRelationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperManagerRelation::class);
    }

}
