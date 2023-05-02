<?php

use App\Tests\TestCase;
use App\Core\HttpClient;

class AuthTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->createTestUser();
    }
    /**
     * @covers \App\Core\HttpClient::post
     */
    public function testUserCanLogin()
    {
        $loginData = [
            'email' => $this->testUser['email'],
            'password' => 'password',
        ];

        $client = (new HttpClient());
        $response = $client->post('/api/auth/login', $loginData);

        $this->assertEquals(200, $response['status']);
        $this->assertArrayHasKey('user', $response['data']);
        $this->assertArrayHasKey('token', $response['data']);
        $this->assertNotEmpty($response['data']['token']);
    }

    /**
     * @covers \App\Core\HttpClient::post
     */
    public function testUserCanRegister()
    {
        $registerData = [
            'name' => 'Test User',
            'email' => 'testing_user_' . uniqid() . '@example.com',
            'password' => 'password'
        ];

        $client = new HttpClient();
        $response = $client->post('/api/auth/register', $registerData);

        $this->assertEquals(201, $response['status']);
        $this->assertArrayHasKey('user', $response['data']);
        $this->assertArrayHasKey('token', $response['data']);
        $this->assertNotEmpty($response['data']['token']);
    }

    /**
     * @covers \App\Core\HttpClient::post
     */
    public function testUserCanLogout()
    {
        $client = (new HttpClient());
        $response = $client->post('/api/auth/logout', [],  $this->testToken);

        $this->assertEquals(200, $response['status']);
    }
}
