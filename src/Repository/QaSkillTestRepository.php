<?php

namespace App\Repository;

use App\Entity\QaSkillTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QaSkillTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method QaSkillTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method QaSkillTest[]    findAll()
 * @method QaSkillTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QaSkillTestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QaSkillTest::class);
    }
}
