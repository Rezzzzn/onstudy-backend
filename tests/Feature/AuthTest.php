<?php

use Tests\TestCase;

class AuthTest extends TestCase
{
    // Setup
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_auth_happy_path()
    {
        // Register a new user
        $registerResponse = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/register', [
            'username' => 'testUser123',
            'email' => 'validUser@example.com',
            'password' => 'securePassword123'
        ]);
        $this->assertEquals(200, $registerResponse->getStatusCode());
        $this->assertStringContainsString('Register berhasil', $registerResponse->getContent());
        $token = json_decode($registerResponse->getContent())[0]->user->remember_token;

        // Logout the user
        $logoutResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ])->post($this->baseUrl.'/auth/logout');
        $this->assertEquals(200, $logoutResponse->getStatusCode());
        $this->assertStringContainsString('Logout berhasil', $logoutResponse->getContent());

        // Login the user
        $loginResponse = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/login', [
            'email' => 'validUser@example.com',
            'password' => 'securePassword123'
        ]);
        $this->assertEquals(200, $loginResponse->getStatusCode());
        $this->assertStringContainsString('Login berhasil', $loginResponse->getContent());
        $token = json_decode($registerResponse->getContent())[0]->user->remember_token;

        // Verify the user's authentication
        $verifyResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ])->post($this->baseUrl.'/auth/verifyauth');
        $this->assertEquals(200, $verifyResponse->getStatusCode());
        $this->assertStringContainsString('Token terverifikasi', $verifyResponse->getContent());
    }

    // Edge Cases
    public function test_register_edge_case()
    {
        // Check for the same username
        // $response = $this->withHeaders([
        //     'Accept' => 'application/json',
        // ])->post($this->baseUrl.'/auth/register', [
        //     'username' => 'testUser123',
        //     'email' => 'testuser@example.com',
        //     'password' => 'securePassword123'
        // ]);

        // $this->assertEquals(200, $response->getStatusCode());
        // $this->assertStringContainsString('Register berhasil', $response->getContent());

        // $response = $this->withHeaders([
        //     'Accept' => 'application/json',
        // ])->post($this->baseUrl.'/auth/register', [
        //     'username' => 'testUser123',
        //     'email' => 'testuser@example.com',
        //     'password' => 'securePassword123'
        // ]);

        // $this->assertEquals(400, $response->getStatusCode());
        // $this->assertStringContainsString('Username sudah digunakan', $response->getContent());

        // Check for non-alphanumeric username
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/register', [
            'username' => 'invalidUser!@#',
            'email' => 'invaliduser@example.com',
            'password' => 'securePassword123'
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('Username hanya boleh memiliki karakter alphanumerik', $response->getContent());

        // Check for missing required fields
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/register', [
            'username' => 'incompleteUser',
            'email' => 'incompleteuser@example.com',
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('The password field is required', $response->getContent());
    }
    public function test_login_edge_case()
    {
        // Check for missing required fields
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/login', [
            'email' => 'incompleteuser@example.com',
        ]);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('The password field is required', $response->getContent());

        // Check for nonexistent user
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/login', [
            'email' => 'nonexistentuser@example.com',
            'password' => 'securePassword123'
        ]);
        Log::info($response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertStringContainsString('Email tidak ditemukan', $response->getContent());

        // Check for wrong password
        // Register
        $registerResponse = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/register', [
            'username' => 'testUser123',
            'email' => 'testuser@example.com',
            'password' => 'securePassword123'
        ]);
        $this->assertEquals(200, $registerResponse->getStatusCode());
        $this->assertStringContainsString('Register berhasil', $registerResponse->getContent());
        $token = json_decode($registerResponse->getContent())[0]->user->remember_token;
        // Logout
        $logoutResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ])->post($this->baseUrl.'/auth/logout');
        $this->assertEquals(200, $logoutResponse->getStatusCode());
        $this->assertStringContainsString('Logout berhasil', $logoutResponse->getContent());
        // Login
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'wrongPassword123'
        ]);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertStringContainsString('Password salah', $response->getContent());

        // Check for already logged in user
        // Login
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'securePassword123'
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Login berhasil', $response->getContent());
        $token = json_decode($registerResponse->getContent())[0]->user->remember_token;
        // Verify token
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ])->post($this->baseUrl.'/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'securePassword123'
        ]);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertStringContainsString('User ini sudah login', $response->getContent());
    }
}
