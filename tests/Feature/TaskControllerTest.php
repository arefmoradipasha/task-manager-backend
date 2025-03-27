<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use PHPUnit\Framework\Attributes\Test;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_list_all_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_create_a_task()
    {
        $data = [
            'title' => 'تسک تست',
            'description' => 'توضیحات تسک',
            'status' => 'pending',
            'completionDate' => now()->toDateTimeString(),
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'تسک تست'])
                 ->assertJsonFragment(['message' => 'تسک با موفقیت ساخته شد']);

        $this->assertDatabaseHas('tasks', ['title' => 'تسک تست']);
    }

    #[Test]
    public function it_can_show_a_task()
    {
        $task = Task::factory()->create(['title' => 'تسک نمونه']);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'تسک نمونه']);
    }

    #[Test]
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create();

        $data = [
            'title' => 'تسک آپدیت‌شده',
            'status' => 'completed',
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'تسک آپدیت‌شده'])
                 ->assertJsonFragment(['message' => 'تسک با موفقیت اپدیت شد']);

        $this->assertDatabaseHas('tasks', ['title' => 'تسک آپدیت‌شده']);
    }

    #[Test]
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'تسک با موفقیت حذف شد']);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function it_validates_task_creation()
    {
        $response = $this->postJson('/api/tasks', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'description']);
    }
}