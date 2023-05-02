<?php

use App\Core\HttpClient;
use App\Tests\TestCase;

class OrderTest extends TestCase
{
    public function setUp(): void
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
}
