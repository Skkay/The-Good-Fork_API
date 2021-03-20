<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\FieldAlreadyUsedException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        if ($data->getPlainPassword()) {
            $data->setPassword($this->passwordEncoder->encodePassword($data, $data->getPlainPassword()));

            $data->eraseCredentials();
        }
        try {
            $this->em->persist($data);
            $this->em->flush();
        }
        catch (UniqueConstraintViolationException $e) {
            throw new FieldAlreadyUsedException(sprintf('The email "%s" is already used.', $data->getEmail()));
        }
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
