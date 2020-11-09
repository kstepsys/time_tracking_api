<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testIfUserCanBeCreated()
    {
        $response = $this->json(
            'POST',
            '/users',
            ['email' => 'email@mail.com', 'password' => 'password']
        );

        $response->assertStatus(201);
    }

    public function testIfNoEmailDublicatesAllowed()
    {
        $this->json(
            'POST',
            '/users',
            ['email' => 'email@mail.com', 'password' => 'password']
        );
        $response = $this->json(
            'POST',
            '/users',
            ['email' => 'email@mail.com', 'password' => 'password']
        );

        $response->assertStatus(422);
    }

    public function testIfPasswordRequired()
    {
        $response = $this->json(
            'POST',
            '/users',
            ['email' => 'email@mail.com']
        );

        $response->assertStatus(422);
    }

    public function testIfUserCanLogin()
    {
        $email = 'email@mail.com';
        $password = 'password';
        //first create user
        $this->json(
            'POST',
            '/users',
            ['email' => $email, 'password' => $password]
        );
        //then try to login with same creditentials
        $response = $this->json(
            'POST',
            '/users/login',
            ['email' => 'email@mail.com', 'password' => $password]
        );

        $response->assertStatus(200);
    }

    public function testIfUserCantLoginWithWrongPassword()
    {
        $email = 'email@mail.com';
        $password = 'password';
        //first create user
        $this->json(
            'POST',
            '/users',
            ['email' => $email, 'password' => $password]
        );
        //then try to login with different
        $response = $this->json(
            'POST',
            '/users/login',
            ['email' => 'email@mail.com', 'password' => 'wrong']
        );

        $response->assertStatus(401);
    }
}
