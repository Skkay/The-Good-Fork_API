<?php

namespace App\DataFixtures\Providers;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordProvider
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function hashPassword($plainPassword)
    {
        return $this->passwordEncoder->encodePassword(new User(), $plainPassword);
    }
}