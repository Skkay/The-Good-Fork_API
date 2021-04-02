<?php

namespace App\DataPersister;

use App\Entity\Food;
use App\Entity\Menu;
use App\Entity\Drink;
use App\Entity\Order;
use App\Entity\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\EntityNotFoundException;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class OrderDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Order;
    }

    public function persist($data, array $context = [])
    {
        $totalPrice = 0;

        // Convert menu ids to menu object
        $menuRepository = $this->em->getRepository(Menu::class);
        foreach ($data->getMenuIds() as $menuId) {
            $menu = $menuRepository->find($menuId);
            if ($menu === null) {
                throw new EntityNotFoundException(sprintf('Menu with id "%s" is not found', $menuId));
            }
            $data->addMenu($menu);
            $totalPrice += $menu->getPrice();
        }
        
        // Convert food ids to food object
        $foodRepository = $this->em->getRepository(Food::class);
        foreach ($data->getFoodIds() as $foodId) {
            $food = $foodRepository->find($foodId);
            if ($food === null) {
                throw new EntityNotFoundException(sprintf('Food with id "%s" is not found', $menuId));
            }
            $data->addFood($food);
            $totalPrice += $food->getPrice();
        }
        
        // Convert drink ids to drink object
        $drinkRepository = $this->em->getRepository(Drink::class);
        foreach ($data->getDrinkIds() as $drinkId) {
            $drink = $drinkRepository->find($drinkId);
            if ($drink === null) {
                throw new EntityNotFoundException(sprintf('Drink with id "%s" is not found', $menuId));
            }
            $data->addDrink($drink);
            $totalPrice += $drink->getPrice();
        }

        $orderStatusRepository = $this->em->getRepository(OrderStatus::class);
        $status = $orderStatusRepository->findOneByLabel('En attente'); // TODO: Set default status via admin panel

        $data->setDateOrder(new \DateTime());
        $data->setPrice($totalPrice);
        $data->setStatus($status);
        $data->setUser($this->security->getUser());

        $this->em->persist($data);
        $this->em->flush($data);
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
