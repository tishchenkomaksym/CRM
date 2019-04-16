<?php

namespace App\Repository;

use App\Entity\PhpDeveloperLevelTestPassed;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpDeveloperLevelTestPassed|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpDeveloperLevelTestPassed|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpDeveloperLevelTestPassed[]    findAll()
 * @method PhpDeveloperLevelTestPassed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpDeveloperLevelTestPassedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpDeveloperLevelTestPassed::class);
    }

    public function getByUser(User $user)
    {
        return $this->findBy(['user' => $user]);
    }

}
