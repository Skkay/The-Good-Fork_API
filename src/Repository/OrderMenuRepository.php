<?php

namespace App\Repository;

use App\Entity\OrderMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderMenu[]    findAll()
 * @method OrderMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderMenu::class);
    }
}
