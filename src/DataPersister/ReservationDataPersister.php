<?php

namespace App\DataPersister;

use App\Entity\Table;
use App\Entity\Service;
use App\Entity\Reservation;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Exception\TableAlreadyReservedException;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class ReservationDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $security;
    private $logger;

    public function __construct(EntityManagerInterface $em, Security $security, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->security = $security;
        $this->logger = $logger;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Reservation;
    }

    public function persist($data, array $context = [])
    {
        $reservationRepository = $this->em->getRepository(Reservation::class);
        $reservation = $reservationRepository->findOneBy(['service' => $data->getServiceId(), 'table_' => $data->getTableId(), 'date' => $data->getDate()]);

        if ($reservation) {
            throw new TableAlreadyReservedException(\sprintf('TableAlreadyReservedException//The table with id "%d" for the service with id "%d" for the date "%s" is already reserved', $data->getTableId(), $data->getServiceId(), $data->getDate()->format('Y-m-d')));
        }
        
        $serviceRepository = $this->em->getRepository(Service::class);
        $tableRepository = $this->em->getRepository(Table::class);

        $user = $this->security->getUser();
        $service = $serviceRepository->find($data->getServiceId());
        $table = $tableRepository->find($data->getTableId());

        $data->setUser($user);
        $data->setService($service);
        $data->setTable($table);

        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
