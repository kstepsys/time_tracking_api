<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    
    public function testIfAuthenticationIsRequired()
    {
        $response = $this->get('/tasks');
        $response->assertStatus(401);
        $response = $this->get('/tasks/export');
        $response->assertStatus(401);
        $response = $this->post('/tasks');
        $response->assertStatus(401);
    }

    public function testIfTaskCanBeCreated()
    {
        $token = $this->getJwtTokenString();
        //then try to create task
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->json(
            'POST',
            '/tasks',
            ['title' => 'Title', 'comment' => 'comment', 'date'=> '2020-01-01']
        );
        $response->assertStatus(201);
        //check if created
        $this->assertDatabaseHas('tasks', ['title' => 'Title', 'comment' => 'comment', 'date'=> '2020-01-01']);
    }

    public function testIfTasksCanBeExported()
    {
        $token = $this->getJwtTokenString();
        $tasks = Task::factory()->times(10)->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->json(
            'GET',
            '/tasks/export'
        );
        $response->assertStatus(200);
    }

    public function testIfTasksCanBeRetrieved()
    {
        $token = $this->getJwtTokenString();
        $tasks = Task::factory()->times(10)->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->json(
            'GET',
            '/tasks'
        );
        $response->assertStatus(200);
    }

    private function getJwtTokenString(): string
    {
        //create user
        $email = 'email@mail.com';
        $password = 'password';
        $user = User::factory(['email' => $email, 'password' => Hash::make($password)])->create();
        //login
        $response = $this->json(
            'POST',
            '/users/login',
            ['email' => 'email@mail.com', 'password' => $password]
        );
        $response->assertStatus(200);
        return str_replace('"', "", $response->getContent());
    }
}
