<?php

use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    private function register_new_user()
    {
        // Register new user
        $faker = Faker\Factory::create();
        $registerResponse = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/register', [
            'email' => $faker->unique()->safeEmail,
            'username' => $faker->userName,
            'password' => $faker->password,
            'method' => 'Email'
        ]);

        return json_decode($registerResponse->getContent())->user->id;
    }

    // Happy path
    public function test_get_users()
    {
        $usersResponse = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->baseUrl.'/users');

        $this->assertEquals(200, $usersResponse->getStatusCode());
        $this->assertStringContainsString('users', $usersResponse->getContent());
    }
    public function test_get_user_by_id()
    {
        $userResponse = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->baseUrl.'/users/'.$this->register_new_user());

        $this->assertEquals(200, $userResponse->getStatusCode());
        $this->assertStringContainsString('user', $userResponse->getContent());
    }
    public function test_update_user()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put($this->baseUrl.'/users/'.$this->register_new_user(), [
            'email' => 'testuserapi@test',
            'username' => 'testuserapi',
            'password' => 'test',
            'institution' => 'School',
            'banned' => false,
            'photo' => null
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
    public function test_delete_user()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete($this->baseUrl.'/users/'.$this->register_new_user());

        $this->assertEquals(200, $response->getStatusCode());
    }
}
