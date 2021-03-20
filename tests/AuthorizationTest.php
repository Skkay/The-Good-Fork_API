<?php

namespace App\Tests;

use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class AuthorizationTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    private $api_prefix_url = '/api';

    /**
     * @depends App\Tests\AuthenticationTest::testLogin
     */
    public function testAuthorizationForUsers(): void
    {
        $client = self::createClient();

        // Retrieve a token for STANDARD USER
        $response = $client->request('POST', $this->api_prefix_url.'/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => "test_user@example.com",
                'password' => "a_weak_user_password"
            ]
        ]);
        $tokenStandardUser = $response->toArray()['token'];

        // Retrieve a token for ADMIN USER
        $response = $client->request('POST', $this->api_prefix_url.'/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => "test_admin@example.com",
                'password' => "a_strong_admin_password"
            ]
        ]);
        $tokenAdminUser = $response->toArray()['token'];

        
        // Test not authorized (without authentication)
        $client->request('GET', $this->api_prefix_url.'/users');
        $this->assertResponseStatusCodeSame(401);
        
        // Test not authorized (with authentication)
        $client->request('GET', $this->api_prefix_url.'/users', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(403);

        // Test authorized (with authentication)
        $client->request('GET', $this->api_prefix_url.'/users', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);
    }
}
