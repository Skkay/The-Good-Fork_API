<?php

namespace App\Tests\Authorization;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class AuthorizationForDrinksTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    private $api_prefix_url = '/api';

    /**
     * @depends App\Tests\Authentication\AuthenticationTest::testLogin
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

        $client->request('POST', $this->api_prefix_url.'/drinks', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Drink", 'description' => "Description for new drink", 'price' => 0.99]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('GET', $this->api_prefix_url.'/drinks/1');
        $this->assertResponseStatusCodeSame(401);

        $client->request('PUT', $this->api_prefix_url.'/drinks/1', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Drink", 'description' => "Description for replaced drink", 'price' => 1.99]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('PATCH', $this->api_prefix_url.'/drinks/1', ['headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Drink", 'description' => "Description for updated drink", 'price' => 2.99]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('DELETE', $this->api_prefix_url.'/drinks/1');
        $this->assertResponseStatusCodeSame(401);


        // Test for authenticate STANDARD USER
        $client->request('GET', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('POST', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Drink", 'description' => "Description for new drink", 'price' => 0.99]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('GET', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PUT', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Drink", 'description' => "Description for replaced drink", 'price' => 1.99]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('PATCH', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Drink", 'description' => "Description for updated drink", 'price' => 2.99]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('DELETE', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(403);


        // Test for authenticate ADMIN USER
        $client->request('GET', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('POST', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Drink", 'description' => "Description for new drink", 'price' => 0.99]]);
        $this->assertResponseStatusCodeSame(201);

        $client->request('POST', $this->api_prefix_url.'/drinks', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Drink", 'description' => "Description for new drink", 'price' => 0.99]]);
        $this->assertResponseStatusCodeSame(400);

        $client->request('GET', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PUT', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Drink", 'description' => "Description for replaced drink", 'price' => 1.99]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PATCH', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Drink", 'description' => "Description for updated drink", 'price' => 2.99]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('DELETE', $this->api_prefix_url.'/drinks/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(204);
    }
}
