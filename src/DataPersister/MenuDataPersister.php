<?php

namespace App\DataPersister;

use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
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
