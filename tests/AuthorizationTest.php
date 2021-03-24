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

    /**
     * @depends App\Tests\AuthenticationTest::testLogin
     */
    public function testAuthorizationForDrinks(): void
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


        // Tests for non authenticate user
        $client->request('GET', $this->api_prefix_url.'/drinks');
        $this->assertResponseStatusCodeSame(401);

        $client->request('POST', $this->api_prefix_url.'/drinks', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Drink"]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('GET', $this->api_prefix_url.'/drinks/1');
        $this->assertResponseStatusCodeSame(401);

        $client->request('PUT', $this->api_prefix_url.'/drinks/1', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Drink"]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('PATCH', $this->api_prefix_url.'/drinks/1', ['headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Drink"]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('DELETE', $this->api_prefix_url.'/drinks/1');
        $this->assertResponseStatusCodeSame(401);


        // Test for authenticate STANDARD USER
        $client->request('GET', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('POST', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Drink"]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('GET', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PUT', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Drink"]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('PATCH', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Drink"]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('DELETE', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(403);


        // Test for authenticate ADMIN USER
        $client->request('GET', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('POST', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Drink"]]);
        $this->assertResponseStatusCodeSame(201);

        $client->request('POST', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Drink"]]);
        $this->assertResponseStatusCodeSame(400);

        $client->request('GET', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PUT', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Drink"]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PATCH', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Drink"]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('DELETE', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(204);
    }

    /**
     * @depends App\Tests\AuthenticationTest::testLogin
     */
    public function testAuthorizationForFoods(): void
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


        // Tests for non authenticate user
        $client->request('GET', $this->api_prefix_url.'/foods');
        $this->assertResponseStatusCodeSame(401);

        $client->request('POST', $this->api_prefix_url.'/foods', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Food"]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('GET', $this->api_prefix_url.'/foods/1');
        $this->assertResponseStatusCodeSame(401);

        $client->request('PUT', $this->api_prefix_url.'/foods/1', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Food"]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('PATCH', $this->api_prefix_url.'/foods/1', ['headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Food"]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('DELETE', $this->api_prefix_url.'/foods/1');
        $this->assertResponseStatusCodeSame(401);


        // Test for authenticate STANDARD USER
        $client->request('GET', $this->api_prefix_url.'/foods', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('POST', $this->api_prefix_url.'/foods', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Food"]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('GET', $this->api_prefix_url.'/foods/1', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PUT', $this->api_prefix_url.'/foods/1', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Food"]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('PATCH', $this->api_prefix_url.'/foods/1', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Food"]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('DELETE', $this->api_prefix_url.'/foods/1', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(403);


        // Test for authenticate ADMIN USER
        $client->request('GET', $this->api_prefix_url.'/foods', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('POST', $this->api_prefix_url.'/foods', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Food"]]);
        $this->assertResponseStatusCodeSame(201);

        $client->request('POST', $this->api_prefix_url.'/foods', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Food"]]);
        $this->assertResponseStatusCodeSame(400);

        $client->request('GET', $this->api_prefix_url.'/foods/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PUT', $this->api_prefix_url.'/foods/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Food"]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PATCH', $this->api_prefix_url.'/foods/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Food"]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('DELETE', $this->api_prefix_url.'/foods/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(204);
    }
}
