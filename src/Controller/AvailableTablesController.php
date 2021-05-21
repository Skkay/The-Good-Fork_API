<?php
/* I am sorry for this garbage. */

namespace App\Controller;

use App\Entity\Table;
use App\Entity\Service;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvailableTablesController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(): Response
    {
        $reservationRepository = $this->em->getRepository(Reservation::class);
        $serviceRepository  = $this->em->getRepository(Service::class);
        $tableRepository = $this->em->getRepository(Table::class);
        
        $reservations = $reservationRepository->findAll();
        $services = $serviceRepository->findAll();
        $tables = $tableRepository->findAll();        
        $allTables = []; // One table per service per day (7 days max)
        for ($i = 0; $i < 7; $i++) {
            foreach ($services as $service) {
                foreach ($tables as $table) {
                    $uniqueTable = ["table" => strval($table->getId()), "service" => strval($service->getId()), "date" => date('Y-m-d', strtotime("+$i day"))];
                    $allTables[] = $uniqueTable;
                }
            }
        }

        $reservedTables = $reservationRepository->findReservedTables();

        // Used to "subtract" $reservedTables from $allTables and get only available tables
        $availableTablesAssoc = array_diff(array_map('json_encode', $allTables), array_map('json_encode', $reservedTables->fetchAll()));
        $availableTablesAssoc = array_map('json_decode', $availableTablesAssoc);

        // Because $availableTablesAssoc is on format "{1: {}, 2: {}, ...}" and I want a format like "[{}, {}, ...]"
        $availableTables = [];
        foreach ($availableTablesAssoc as $key => $value) {

            // To get object instead of its ID
            $tableObject = [];
            foreach ($value as $field => $id) {
                if ($field === "table") {
                    $tableObject[$field] = $tableRepository->find($id);
                }
                else if ($field === "service") {
                    $tableObject[$field] = $serviceRepository->find($id);
                }
                else if ($field === "date") {
                    $tableObject[$field] = $id;
                }
            }
            $availableTables[] = $tableObject;
        }
 
        return $this->json($availableTables);
    }
}
