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

    public function testLogin(): void
    {
        $client = self::createClient();

        $user = new User();
        $user->setEmail("test@example.com");
        $user->setPassword(
            self::$container->get('security.password_encoder')->encodePassword($user, "L'avez vous vu ?")
        );

        $manage = self::$container->get('doctrine')->getManager();
        $manage->persist($user);
        $manage->flush();

        // Retrieve a token
        $response = $client->request('POST', $this->api_prefix_url.'/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => "test@example.com",
                'password' => "L'avez vous vu ?"
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $json = $response->toArray();
        $this->assertArrayHasKey('token', $json);
        $this->assertArrayHasKey('data', $json);
    }
}
