<?php

namespace App\Tests\Authentication;

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
                'email' => "test_new_user@example.com",
                'password' => "L'avez vous vu ?"
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);

        // Test register with already used email
        $response = $client->request('POST', $this->api_prefix_url.'/users', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => "test_user@example.com",
                'password' => "L'avez vous vu cette fois ci ?"
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testLogin(): void
    {
        $client = self::createClient();

        // Retrieve a token
        $response = $client->request('POST', $this->api_prefix_url.'/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => "test_user@example.com",
                'password' => "a_weak_user_password"
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $json = $response->toArray();
        $this->assertArrayHasKey('token', $json);
        $this->assertArrayHasKey('data', $json);
    }
}
