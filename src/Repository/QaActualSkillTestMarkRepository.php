<?php

namespace App\Repository;

use App\Entity\QaActualSkillTestMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QaActualSkillTestMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method QaActualSkillTestMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method QaActualSkillTestMark[]    findAll()
 * @method QaActualSkillTestMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QaActualSkillTestMarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QaActualSkillTestMark::class);
    }

}
