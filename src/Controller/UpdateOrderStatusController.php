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

    /**
     * @Route("/api/orders/{orderId}/chef_validate", name="chef_validate_order", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function chefValidation($orderId): Response
    {
        $orderRepository = $this->em->getRepository(Order::class);
        $order = $orderRepository->find($orderId);
        if ($order === null) {
            throw new EntityNotFoundException(sprintf('EntityNotFoundException//Order with id "%s" is not found', $orderId));
        }
        $order->setChefHasValidated(true);

        if ($order->getBarmanHasValidated()) { // Chef and Barman have both validated, update the status
            $statusRepository = $this->em->getRepository(OrderStatus::class);
            $status = $statusRepository->find(4);
            if ($status === null) {
                throw new EntityNotFoundException(sprintf('EntityNotFoundException//OrderStatus with id "%s" is not found', $statusId));
            }
            $order->setStatus($status);
        }

        $this->em->persist($order);
        $this->em->flush();

        return $this->json($order);
    }

    /**
     * @Route("/api/orders/{orderId}/barman_validate", name="barman_validate_order", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function barmanValidation($orderId): Response
    {
        $orderRepository = $this->em->getRepository(Order::class);
        $order = $orderRepository->find($orderId);
        if ($order === null) {
            throw new EntityNotFoundException(sprintf('EntityNotFoundException//Order with id "%s" is not found', $orderId));
        }
        $order->setBarmanHasValidated(true);

        if ($order->getChefHasValidated()) { // Chef and Barman have both validated, update the status
            $statusRepository = $this->em->getRepository(OrderStatus::class);
            $status = $statusRepository->find(4);
            if ($status === null) {
                throw new EntityNotFoundException(sprintf('EntityNotFoundException//OrderStatus with id "%s" is not found', $statusId));
            }
            $order->setStatus($status);
        }

        $this->em->persist($order);
        $this->em->flush();

        return $this->json($order);
    }
}
