<?php

namespace Tests\Feature\Api\V1;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/tasks', [
            'title' => 'Estudar testes',
            'description' => 'Laravel testing',
            'status' => 'pending'
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'description', 'status']
        ]);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Estudar testes',
            'description' => 'Laravel testing',
            'status' => 'pending'
        ]);
    }

    public function test_guest_cannot_create_task(): void
    {
        $response = $this->postJson('/api/v1/tasks', [
            'title' => 'Estudar testes',
            'description' => 'Laravel testing',
            'status' => 'pending'
        ]);

        $response->assertUnauthorized();
    }

    public function test_user_can_list_tasks(): void
    {
        $user = User::factory()->create();

        Task::factory()->count(10)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->getJson('/api/v1/tasks');

        $response->assertOk();
    }
}
