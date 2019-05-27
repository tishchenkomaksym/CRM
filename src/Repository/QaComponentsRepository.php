<?php

namespace App\Repository;

use App\Entity\QaComponents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QaComponents|null find($id, $lockMode = null, $lockVersion = null)
 * @method QaComponents|null findOneBy(array $criteria, array $orderBy = null)
 * @method QaComponents[]    findAll()
 * @method QaComponents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QaComponentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QaComponents::class);
    }
}
