<?php

namespace App\Tests;

use App\Core\HttpClient;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public $testUser;
    public $testToken;
    public $cartId;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function createTestData()
    {
        $testUserData = [
            'name' => 'Test User',
            'email' => 'testing_user_' . uniqid() . '@example.com',
            'password' => 'password',
        ];

        $client = new HttpClient();
        $response = $client->post('/api/auth/register', $testUserData);
        $token = $response['data']['token'];

        $this->testToken = $token;

        $productData = [
            'product_id' => rand(1, 500),
            'quantity' => 1,
        ];

        $client = new HttpClient();
        $response = $client->post('/api/cart', $productData, $token);
        $this->cartId = $response['data']['id'];
    }

    public function createTestUser()
    {
        $testUserData = [
            'name' => 'Test User',
            'email' => 'testing_user_' . uniqid() . '@example.com',
            'password' => 'password',
        ];

        $client = new HttpClient();
        $response = $client->post('/api/auth/register', $testUserData);
        $user = $response['data']['user'];
        $token = $response['data']['token'];

        $this->testUser = $user;
        $this->testToken = $token;
    }
}

