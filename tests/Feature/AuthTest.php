<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    /** @test */
    public function incorrect_user_registration_validation(){
        $requests = [
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(1, 5)
        ];
        $responseRegister = $this->postJson('/api/v1/auth/register', $requests);
        $responseRegister->assertStatus(422);
    }

    /** @test */
    public function a_new_registered_user()
    {
        $requests = [
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(6, 20)
        ];
        $responseRegister = $this->postJson('/api/v1/auth/register', $requests);
        // checked if exist that register in the database
        $this->assertDatabaseCount('users', 1);
        // checked if it has that data
        $this->assertDatabaseHas('users', [
            'username' => $requests['username']
        ]);
        // created a new user successfully.
        $responseRegister->assertCreated(); 
    }

    /** @test */
    public function an_incorrect_login_validation(){
        $requests = [
            'username' => $this->faker->unique()->userName,
            'password' => ''
        ];
        $responseLogin = $this->postJson('/api/v1/auth/login', $requests);
        $responseLogin->assertStatus(422);
    }

    /** @test */
    public function an_incorrect_login_credential(){
        $requests = [
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(6, 20)
        ];
        /***** 
         * CREATING USER 
        *****/
        $responseRegister = $this->postJson('/api/v1/auth/register', $requests);
        // checked if exist that register in the database
        $this->assertDatabaseCount('users', 1); 
        // created a new user successfully.
        $responseRegister->assertCreated();

        /***** 
         * LOGIN USER
        *****/ 
        $responseLogin = $this->postJson('/api/v1/auth/login', [
            'username' => $requests['username'],
            'password' => $requests['password'] . '123'
        ]);
        $responseLogin->assertNotFound();
    }

    /** @test */
    public function a_successful_login(){
        $requests = [
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(6, 20)
        ];
        /***** 
         * CREATING USER 
         *****/
        $responseRegister = $this->postJson('/api/v1/auth/register', $requests);
        // checked if exist that register in the database
        $this->assertDatabaseCount('users', 1); 
        // created a new user successfully.
        $responseRegister->assertCreated();

        /***** 
         * LOGIN USER
         *****/ 
        $responseLogin = $this->postJson('/api/v1/auth/login', [
            'username' => $requests['username'],
            'password' => $requests['password']
        ]);
        $responseLogin->assertOk();
    }

}
