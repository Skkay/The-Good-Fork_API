<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findReservedTables()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT table__id AS `table`, service_id AS `service`, date FROM reservation WHERE date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL +6 DAY)';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
}
