<?php

namespace App\Repository;

use App\Entity\QaRequiredSkillTestMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QaRequiredSkillTestMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method QaRequiredSkillTestMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method QaRequiredSkillTestMark[]    findAll()
 * @method QaRequiredSkillTestMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QaRequiredSkillTestMarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QaRequiredSkillTestMark::class);
    }

}
