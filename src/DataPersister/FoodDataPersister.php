<?php

namespace App\DataPersister;

use App\Entity\Food;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\FieldAlreadyUsedException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class FoodDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Food;
    }

    public function persist($data, array $context = [])
    {
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
