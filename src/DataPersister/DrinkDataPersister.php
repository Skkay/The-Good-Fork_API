<?php

namespace App\DataPersister;

use App\Entity\Drink;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class DrinkDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $slugger;

    public function __construct(EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Drink;
    }

    public function persist($data, array $context = [])
    {
        $data->setSlug($this->slugger->slug(strtolower($data->getName())));

        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
