<?php

namespace App\DataPersister;

use App\Entity\Food;
use App\Entity\Menu;
use App\Entity\Drink;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\EntityNotFoundException;
use App\Exception\FieldAlreadyUsedException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class MenuDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Menu;
    }

    public function persist($data, array $context = [])
    {
        // Try to find the drink passed to $data. Throw exception if it's not found, otherwise replace by found drink
        $drinkRepository = $this->em->getRepository(Drink::class);
        foreach($data->getDrinks() as $requestedDrink) {
            $drink = $drinkRepository->findOneByName($requestedDrink->getName());
            if ($drink === null) {
                throw new EntityNotFoundException(sprintf('Drink "%s" not found', $requestedDrink->getName()));
            }

            $data->removeDrink($requestedDrink);
            $data->addDrink($drink);
        }

        // Try to find the food passed to $data. Throw exception if it's not found, otherwise replace by found food
        $foodRepository = $this->em->getRepository(Food::class);
        foreach($data->getFoods() as $requestedFood) {
            $food = $foodRepository->findOneByName($requestedFood->getName());
            if ($food === null) {
                throw new EntityNotFoundException(sprintf('Food "%s" not found', $requestedFood->getName()));
            }

            $data->removeFood($requestedFood);
            $data->addFood($food);
        }

        try {
            $this->em->persist($data);
            $this->em->flush();
        }
        catch (UniqueConstraintViolationException $e)
        {
            throw new FieldAlreadyUsedException(sprintf('The name "%s" is already used', $data->getName()));
        }
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
