<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateOrderStatusController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
    }

    /**
     * @Route("/api/orders/{orderId}/update_status/{statusId}", name="update_order_status", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index($orderId, $statusId): Response
    {
        $statusRepository = $this->em->getRepository(OrderStatus::class);
        $orderRepository = $this->em->getRepository(Order::class);

        $status = $statusRepository->find($statusId);
        if ($status === null) {
            throw new EntityNotFoundException(sprintf('EntityNotFoundException//OrderStatus with id "%s" is not found', $statusId));
        }

        $order = $orderRepository->find($orderId);
        if ($order === null) {
            throw new EntityNotFoundException(sprintf('EntityNotFoundException//Order with id "%s" is not found', $orderId));
        }

        $order->setStatus($status);

        $this->em->persist($order);
        $this->em->flush();

        return $this->json($order);
    }
}
