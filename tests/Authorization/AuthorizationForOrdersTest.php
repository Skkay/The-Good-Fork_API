<?php

namespace App\Tests\Authorization;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class AuthorizationForOrdersTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    private $api_prefix_url = '/api';

    /**
     * @depends App\Tests\Authentication\AuthenticationTest::testLogin
     */
    public function testAuthorizationForOrders(): void
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
        $client->request('GET', $this->api_prefix_url.'/orders');
        $this->assertResponseStatusCodeSame(401);

        $client->request('POST', $this->api_prefix_url.'/orders', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['eatIn' => true, 'menuIds' => [1], 'foodIds' => [1], 'drinkIds' => [1]]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('GET', $this->api_prefix_url.'/orders/1');
        $this->assertResponseStatusCodeSame(401);

        $client->request('PUT', $this->api_prefix_url.'/orders/1', ['headers' => ['Content-Type' => 'application/json'], 'json' => ['eatIn' => false, 'menuIds' => [1], 'foodIds' => [1], 'drinkIds' => [1]]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('PATCH', $this->api_prefix_url.'/orders/1', ['headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['eatIn' => true, 'menuIds' => [1], 'foodIds' => [1], 'drinkIds' => [1]]]);
        $this->assertResponseStatusCodeSame(401);

        $client->request('DELETE', $this->api_prefix_url.'/orders/1');
        $this->assertResponseStatusCodeSame(401);


        // Tests for authenticate STANDARD USER
        $client->request('GET', $this->api_prefix_url.'/orders', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(403);

        $response = $client->request('POST', $this->api_prefix_url.'/orders', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['eatIn' => true, 'menuIds' => [1], 'foodIds' => [1], 'drinkIds' => [1]]]);
        $this->assertResponseStatusCodeSame(201);

        $client->request('GET', $this->api_prefix_url.'/orders/2', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('GET', $this->api_prefix_url.'/orders/1', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(403);

        $client->request('PUT', $this->api_prefix_url.'/orders/2', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['eatIn' => false, 'menuIds' => [1], 'foodIds' => [1], 'drinkIds' => [1]]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PATCH', $this->api_prefix_url.'/orders/2', ['auth_bearer' => $tokenStandardUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['eatIn' => true, 'menuIds' => [1], 'foodIds' => [1], 'drinkIds' => [1]]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('DELETE', $this->api_prefix_url.'/orders/2', ['auth_bearer' => $tokenStandardUser]);
        $this->assertResponseStatusCodeSame(403);


        // Tests for authentitcate ADMIN USER
        $client->request('GET', $this->api_prefix_url.'/orders', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('POST', $this->api_prefix_url.'/orders', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['eatIn' => true, 'menuIds' => [1], 'foodIds' => [1], 'drinkIds' => [1]]]);
        $this->assertResponseStatusCodeSame(201);

        $client->request('GET', $this->api_prefix_url.'/orders/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PUT', $this->api_prefix_url.'/orders/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/json'], 'json' => ['eatIn' => false, 'menuIds' => [1], 'foodIds' => [1], 'drinkIds' => [1]]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('PATCH', $this->api_prefix_url.'/orders/1', ['auth_bearer' => $tokenAdminUser, 'headers' => ['Content-Type' => 'application/merge-patch+json'], 'json' => ['eatIn' => true, 'menuIds' => [1], 'foodIds' => [1], 'drinkIds' => [1]]]);
        $this->assertResponseStatusCodeSame(200);

        $client->request('DELETE', $this->api_prefix_url.'/orders/1', ['auth_bearer' => $tokenAdminUser]);
        $this->assertResponseStatusCodeSame(204);
    }
}
