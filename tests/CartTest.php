<?php

use App\Core\HttpClient;
use App\Tests\TestCase;

class CartTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->createTestData();
    }

    /**
     * @covers \App\Core\HttpClient::get
     */
    public function testUserCanGetCartItems()
    {
        $client = (new HttpClient());
        $response = $client->get('/api/cart', [], $this->testToken);

        $this->assertEquals(200, $response['status']);
        $this->assertIsArray($response['data']);
    }

    /**
     * @covers \App\Core\HttpClient::post
     */
    public function testUserCanStoreCartItem()
    {
        $productData = [
            'product_id' => rand(1, 500),
            'quantity' => 1,
        ];

        $client = new HttpClient();
        $response = $client->post('/api/cart', $productData, $this->testToken);

        $this->assertEquals(201, $response['status']);
        $this->assertIsArray($response['data']);
    }

    /**
     * @covers \App\Core\HttpClient::put
     */
    public function testUserCanUpdateCartItem()
    {
        $productData = [
            'quantity' => 3,
        ];

        $client = new HttpClient();
        $response = $client->put("/api/cart/{$this->cartId}", $productData, $this->testToken);

        $this->assertEquals(200, $response['status']);
        $this->assertIsArray($response['data']);
        $this->assertEquals(3, $response['data']['quantity']);
    }

    /**
     * @covers \App\Core\HttpClient::delete
     */
    public function testUserCanRemoveCartItem()
    {
        $client = new HttpClient();
        
        $response = $client->delete("/api/cart/{$this->cartId}", [], $this->testToken);

        $this->assertEquals(200, $response['status']);
    }

    /**
     * @covers \App\Core\HttpClient::delete
     */
    public function testUserCanClearCart()
    {
        $client = new HttpClient();
        $response = $client->delete('/api/cart', [], $this->testToken);

        $this->assertEquals(200, $response['status']);
    }
}
