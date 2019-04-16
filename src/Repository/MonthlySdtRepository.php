<?php

namespace App\Repository;

use App\Entity\MonthlySdt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MonthlySdt|null find($id, $lockMode = null, $lockVersion = null)
 * @method MonthlySdt|null findOneBy(array $criteria, array $orderBy = null)
 * @method MonthlySdt[]    findAll()
 * @method MonthlySdt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonthlySdtRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MonthlySdt::class);
    }

}
