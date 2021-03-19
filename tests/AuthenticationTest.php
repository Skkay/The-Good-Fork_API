<?php

namespace App\Tests;

use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class AuthenticationTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    private $api_prefix_url = "/api";

    public function testRegister(): void
    {
        $client = self::createClient();

        // Test register with non used email
        $response = $client->request('POST', $this->api_prefix_url.'/users', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => "test@example.com",
                'password' => "L'avez vous vu ?"
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);

        // Test register with already used email
        $response = $client->request('POST', $this->api_prefix_url.'/users', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => "test@example.com",
                'password' => "L'avez vous vu ?"
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);
    }
}
/**
