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
        // Convert drink ids to drink objects
        if (!empty($data->getDrinkIds())) {
            $drinkRepository = $this->em->getRepository(Drink::class);
            foreach ($data->getDrinkIds() as $drinkId) {
                $drink = $drinkRepository->find($drinkId);
                if ($drink === null) {
                    throw new EntityNotFoundException(sprintf('EntityNotFoundException//Drink with id "%s" is not found', $drinkId));
                }
                $data->addDrink($drink);
            }
        }

        // Convert food ids to food objects
        if (!empty($data->getFoodIds())) {
            $foodRepository = $this->em->getRepository(Food::class);
            foreach ($data->getFoodIds() as $foodId) {
                $food = $foodRepository->find($foodId);
                if ($food === null) {
                    throw new EntityNotFoundException(sprintf('EntityNotFoundException//Food with id "%s" is not found', $foodId));
                }
                $data->addFood($food);
            }
        }

        try {
            $this->em->persist($data);
            $this->em->flush();
        }
        catch (UniqueConstraintViolationException $e)
        {
            throw new FieldAlreadyUsedException(sprintf('FieldAlreadyUsedException//The name "%s" is already used', $data->getName()));
        }
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
