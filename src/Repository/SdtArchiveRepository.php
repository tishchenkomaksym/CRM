<?php

namespace App\Repository;

use App\Entity\SdtArchive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SdtArchive|null find($id, $lockMode = null, $lockVersion = null)
 * @method SdtArchive|null findOneBy(array $criteria, array $orderBy = null)
 * @method SdtArchive[]    findAll()
 * @method SdtArchive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SdtArchiveRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SdtArchive::class);
    }

}
