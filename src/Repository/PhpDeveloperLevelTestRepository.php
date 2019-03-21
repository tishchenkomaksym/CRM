<?php

namespace App\Repository;

use App\Entity\PhpDeveloperLevelTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperLevelTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperLevelTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperLevelTest[]    findAll()
 * @method PhpDeveloperLevelTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperLevelTestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperLevelTest::class);
    }

}
