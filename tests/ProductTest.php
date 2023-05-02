<?php

use App\Tests\TestCase;
use App\Core\HttpClient;

class ProductTest extends TestCase
{
    /**
     * @covers \App\Core\HttpClient::get
     */
    public function testGetAllProducts()
    {
        $client = new HttpClient();
        $response = $client->get('/api/products');

        $this->assertEquals(200, $response['status']);
        $this->assertIsArray($response['data']);
    }
}
