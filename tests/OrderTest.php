<?php

use App\Core\HttpClient;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private $testToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestData();
    }

    /**
     * @covers \App\Core\HttpClient::get
     */
    public function testUserCanGetOrders()
    {
        $client = (new HttpClient());
        $response = $client->get('/api/orders', [], $this->testToken);

        $this->assertEquals(200, $response['status']);
        $this->assertIsArray($response['data']);
    }

    /**
     * @covers \App\Core\HttpClient::post
     */
    public function testUserCanCheckout()
    {
        $client = new HttpClient();
        $response = $client->post('/api/checkout', [], $this->testToken);

        $this->assertEquals(201, $response['status']);
        $this->assertIsArray($response['data']);
    }

    private function createTestData()
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
    }
}
