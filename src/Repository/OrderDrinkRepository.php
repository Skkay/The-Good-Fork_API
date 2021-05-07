<?php

namespace App\Repository;

use App\Entity\OrderDrink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderDrink|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDrink|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDrink[]    findAll()
 * @method OrderDrink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDrinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDrink::class);
    }
}
