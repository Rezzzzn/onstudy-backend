<?php

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserTest extends TestCase
{
    private $faker;
    protected function setUp(): void
    {
        $this->faker = Faker\Factory::create();
        parent::setUp();
    }

    private function register_new_user()
    {
        // Register new user
        $registerResponse = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/auth/register', [
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
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
        ])->post($this->baseUrl.'/users/'.$this->register_new_user(), [
            'username' => $this->faker->userName,
            'institution' => $this->faker->word,
            // 'photo' => Storage::get('penguin.jpg')
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
