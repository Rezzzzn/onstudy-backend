<?php

use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    // Register Endpoint
    public function test_register_with_valid_input()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/register', [
            'username' => 'validUser123',
            'email' => 'validuser@example.com',
            'password' => 'securePassword123'
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Register berhasil', $response->getContent());
    }
    public function test_register_with_invalid_username()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/register', [
            'username' => 'invalidUser!@#',
            'email' => 'invaliduser@example.com',
            'password' => 'securePassword123'
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('Username hanya boleh memiliki karakter alphanumerik', $response->getContent());
    }
    public function test_register_with_missing_required_fields()
    {
        $response = $this->withHeaders(['Accept' => 'application/json',])->post($this->baseUrl.'/auth/register', [
            'username' => 'incompleteUser',
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('The email field is required', $response->getContent());
        $this->assertStringContainsString('The password field is required', $response->getContent());
    }
}
