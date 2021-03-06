<?php

namespace App\DataPersister;

use App\Entity\Food;
use App\Entity\Menu;
use App\Entity\Drink;
use App\Entity\Order;
use App\Entity\Discount;
use App\Entity\OrderFood;
use App\Entity\OrderMenu;
use App\Entity\OrderDrink;
use App\Entity\OrderStatus;
use App\Entity\Reservation;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\EntityNotFoundException;
use Symfony\Component\Security\Core\Security;
use App\Exception\NotEnoughLoyaltyPointsException;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class OrderDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Order;
    }

    public function persist($data, array $context = [])
    {
        $totalPrice = 0;

        // Default values
        $data->setChefHasValidated(false);
        $data->setBarmanHasValidated(false);

        // Convert menu ids to orderedMenu object with number of menus, then set it to data
        if (!empty($data->getMenuIds())) {
            $menuRepository = $this->em->getRepository(Menu::class);
            $occurencies = array_count_values($data->getMenuIds());

            foreach ($occurencies as $menuId => $quantity) {
                $menu = $menuRepository->find($menuId);
                if ($menu === null) {
                    throw new EntityNotFoundException(sprintf('EntityNotFoundException//Menu with id "%s" is not found', $menuId));
                }
                $orderMenu = new OrderMenu();
                $orderMenu->setMenu($menu);
                $orderMenu->setQuantity($quantity);

                $data->addOrderedMenu($orderMenu);
                $totalPrice += $menu->getPrice() * $quantity;
            }
        }
        
        // Convert food ids to orderedFood object with number of foods, then set it to data
        if (!empty($data->getFoodIds())) {
            $foodRepository = $this->em->getRepository(Food::class);
            $occurencies = array_count_values($data->getFoodIds());

            foreach ($occurencies as $foodId => $quantity) {
                $food = $foodRepository->find($foodId);
                if ($food === null) {
                    throw new EntityNotFoundException(sprintf('EntityNotFoundException//Food with id "%s" is not found', $foodId));
                }
                $orderFood = new OrderFood();
                $orderFood->setFood($food);
                $orderFood->setQuantity($quantity);

                $data->addOrderedFood($orderFood);
                $totalPrice += $food->getPrice() * $quantity;
            }
        }
        
        // Convert drink ids to orderedDrink object with number of drinks, then set it to data       
        if (!empty($data->getDrinkIds())) {
            $drinkRepository = $this->em->getRepository(Drink::class);
            $occurencies = array_count_values($data->getDrinkIds());

            foreach ($occurencies as $drinkId => $quantity) {
                $drink = $drinkRepository->find($drinkId);
                if ($drink === null) {
                    throw new EntityNotFoundException(sprintf('EntityNotFoundException//Drink with id "%s" is not found', $drinkId));
                }
                $orderDrink = new OrderDrink();
                $orderDrink->setDrink($drink);
                $orderDrink->setQuantity($quantity);

                $data->addOrderedDrink($orderDrink);
                $totalPrice += $drink->getPrice() * $quantity;
            }
        }

        // Set order as validated for Chef if there is no Food and no Menu 
        // Set order as validated for Barman if there is no Drink and no Menu
        // (assuming that a menu necessarily contains at least one food and one drink)
        if (empty($data->getFoodIds()) && empty($data->getMenuIds())) {
            $data->setChefHasValidated(true);
        }
        if (empty($data->getDrinkIds()) && empty($data->getMenuIds())) {
            $data->setBarmanHasValidated(true);
        }

        
        if ($data->getOrderedByStaff()) {
            $user = null;
        }
        else {
            $user = $this->security->getUser();
            $userLoyaltyPoints = $user->getLoyaltyPoints();
        }

        // Calculate new price if a discount is selected, throw an error if user has not enough loyalty points, update user's loyalty points
        if ($data->getDiscountId() !== 0) {
            $discountRepository = $this->em->getRepository(Discount::class);
            $discountId = $data->getDiscountId();
            $discount = $discountRepository->find($discountId);
            if ($discount === null) {
                throw new EntityNotFoundException(sprintf('EntityNotFoundException//Discount with id "%s" is not found', $discountId));
            }
            if ($discount->getCost() > $userLoyaltyPoints) {
                throw new NotEnoughLoyaltyPointsException(sprintf('NotEnoughLoyaltyPointsException//User has not enough loyalty points'));
            }
            $totalPrice = round($totalPrice * (1 - $discount->getValue() / 100), 2);
            $userLoyaltyPoints -= $discount->getCost();
        }

        $orderStatusRepository = $this->em->getRepository(OrderStatus::class);
        $status = $orderStatusRepository->findOneByLabel('En attente'); // TODO: Set default status via admin panel

        $data->setDateOrder(new \DateTime());
        $data->setPrice($totalPrice);
        $data->setStatus($status);
        $data->setUser($user);

        $rawString = $data->getExtraInformations();
        $cleanedString = \preg_replace('/\s+/', ' ', trim($rawString));
        $data->setExtraInformations($cleanedString);

        if ($user) {
            $userLoyaltyPoints += \floor($totalPrice);
            $user->setLoyaltyPoints($userLoyaltyPoints);
            $this->em->persist($user);
        }

        if ($data->getReservationId() !== 0) {
            $reservationRepository = $this->em->getRepository(Reservation::class);
            $reservation = $reservationRepository->find($data->getReservationId());
            $data->setReservation($reservation);
        }

        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
