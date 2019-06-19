<?php

namespace App\Repository;

use App\Entity\QaJiraComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QaJiraComponent|null find($id, $lockMode = null, $lockVersion = null)
 * @method QaJiraComponent|null findOneBy(array $criteria, array $orderBy = null)
 * @method QaJiraComponent[]    findAll()
 * @method QaJiraComponent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QaJiraComponentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QaJiraComponent::class);
    }

}
