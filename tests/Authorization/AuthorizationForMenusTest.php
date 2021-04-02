<?php

namespace App\Tests\Authorization;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class AuthorizationForMenusTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    private $api_prefix_url = '/api';

    /**
     * @depends App\Tests\Authentication\AuthenticationTest::testLogin
     */
    public function testAuthorizationForMenus(): void
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
        $client->request('GET', $this->api_prefix_url.'/menus');
        $this->assertResponseStatusCodeSame(401);

        $client->request('POST', $this->api_prefix_url.'/menus', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Menu", 'description' => "Description for new menu"]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('GET', $this->api_prefix_url.'/menus/1');
        $this->assertResponseStatusCodeSame(401);

        $client->request('PUT', $this->api_prefix_url.'/menus/1', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Menu", 'description' => "Description for replaced menu"]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('PATCH', $this->api_prefix_url.'/menus/1', ['headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Menu", 'description' => "Description for updated menu"]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('DELETE', $this->api_prefix_url.'/menus/1');
        $this->assertResponseStatusCodeSame(401);


        // Test for authenticate STANDARD USER
        $client->request('GET', $this->api_prefix_url.'/menus', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('POST', $this->api_prefix_url.'/menus', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Menu", 'description' => "Description for new menu"]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('GET', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PUT', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Menu", 'description' => "Description for replaced menu"]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('PATCH', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Menu", 'description' => "Description for updated menu"]]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('DELETE', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(403);


        // Test for authenticate ADMIN USER
        $client->request('GET', $this->api_prefix_url.'/menus', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        // All should be good
        $client->request('POST', $this->api_prefix_url.'/menus', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Menu", 'description' => "Description for new menu", 'price' => 10.99, 'drinkIds' => [1, 2], 'foodIds' => [1, 2]]]);
        $this->assertResponseStatusCodeSame(201);

        // Should throw exception for duplicate menu name
        $client->request('POST', $this->api_prefix_url.'/menus', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Menu", 'description' => "Description for new menu", 'price' => 10.99, 'drinkIds' => [1, 2], 'foodIds' => [1, 2]]]);
        $this->assertResponseStatusCodeSame(400);

        // Should throw exception for unknown drink
        $client->request('POST', $this->api_prefix_url.'/menus', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Menu bis", 'price' => 10.99, 'drinkIds' => [1, 1664], 'foodIds' => [1, 2]]]);
        $this->assertResponseStatusCodeSame(400);

        // Should throw exception for unknown food
        $client->request('POST', $this->api_prefix_url.'/menus', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "New Menu bis", 'price' => 10.99, 'drinkIds' => [1, 2], 'foodIds' => [1, 1938]]]);
        $this->assertResponseStatusCodeSame(400);

        $client->request('GET', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        // All should be good
        $client->request('PUT', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Menu", 'description' => "Description for replaced menu", 'price' => 10.99, 'drinkIds' => [3, 4], 'foodIds' => [4, 2]]]);
        $this->assertResponseStatusCodeSame(200);

        // Should throw exception for unknown drink
        $client->request('PUT', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Menu", 'price' => 10.99, 'drinkIds' => [1664, 4], 'foodIds' => [4, 2]]]);
        $this->assertResponseStatusCodeSame(400);

        // Should throw exception for unknown food
        $client->request('PUT', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['name' => "Replaced Menu", 'price' => 10.99, 'drinkIds' => [3, 4], 'foodIds' => [1938, 2]]]);
        $this->assertResponseStatusCodeSame(400);

        // All should be good
        $client->request('PATCH', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['name' => "Updated Menu", 'description' => "Description for updated menu"]]);
        $this->assertResponseStatusCodeSame(200);

        // Should throw exception for unknown drink
        $client->request('PATCH', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['drinkIds' => [1664]]]);
        $this->assertResponseStatusCodeSame(400);

        // Should throw exception for unknown food
        $client->request('PATCH', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['foodIds' => [1938]]]);
        $this->assertResponseStatusCodeSame(400);

        $client->request('DELETE', $this->api_prefix_url.'/menus/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(204);
    }
}
