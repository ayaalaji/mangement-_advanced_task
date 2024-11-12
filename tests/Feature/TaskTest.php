<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class TaskTest extends TestCase {

    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_get_all_tasks(): void
    {
        $user = User::factory()->create(); 
        $response = $this->actingAs($user, 'api') ->get('/api/tasks');


        $response->assertStatus(200);
    }

    public function test_create_task_successfull()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }

        $assignedUser = User::where('id', 6)->first();
        if (!$assignedUser) {
            $assignedUser = User::factory()->create(); 
        }
        $task = [
            'title' => 'Test Task',
            'description' => 'This Is A Test Description',
            'type' => 'feature',
            'priority' => 'high',
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'assigned_to' =>$assignedUser->id,
        ];
        $response = $this->actingAs($admin)->post('/api/task',$task);
     

        $response->assertStatus(201);
        
        $latestTask = Task::latest()->first();
        $this->assertEquals($task['title'], $latestTask->title);
        $this->assertEquals($task['description'], $latestTask->description);
        $this->assertEquals($task['type'], $latestTask->type);
        $this->assertEquals($task['priority'], $latestTask->priority);
        $this->assertEquals($task['due_date'], $latestTask->due_date);
        $this->assertEquals($task['assigned_to'], $latestTask->assigned_to);
      
    }

    public function test_edit_task_successfull()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }

        $assignedUser = User::where('id', 6)->first();
        if (!$assignedUser) {
            $assignedUser = User::factory()->create(); 
        }
        //add new task to updated it 
        $task = Task::create([
        'title' => 'Initial Task',
        'description' => 'Initial Description',
        'type' => 'bug',
        'priority' => 'medium',
        'due_date' => now()->addDays(5)->format('Y-m-d'),
        'assigned_to' => $assignedUser->id,
        ]);
        //updated task
        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated Description',
            'type' => 'feature',
            'priority' => 'high',
            'due_date' => now()->addDays(10)->format('Y-m-d'),
            'assigned_to' => $assignedUser->id,
        ];
        $response = $this->actingAs($admin)->put("/api/task/{$task->id}", $updatedData);

        $response->assertStatus(200);

        $task->refresh();

        $this->assertEquals($updatedData['title'], $task->title);
        $this->assertEquals($updatedData['description'], $task->description);
        $this->assertEquals($updatedData['type'], $task->type);
        $this->assertEquals($updatedData['priority'], $task->priority);
        $this->assertEquals($updatedData['due_date'], $task->due_date);
        $this->assertEquals($updatedData['assigned_to'], $task->assigned_to);

    }

    public function test_soft_delete_task_successful() 
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }

        $assignedUser = User::where('id', 6)->first();
        if (!$assignedUser) {
            $assignedUser = User::factory()->create(); 
        }

        //add new task to deleted it 
        $task = Task::create([
        'title' => 'Initial Task',
        'description' => 'Initial Description',
        'type' => 'bug',
        'priority' => 'medium',
        'due_date' => now()->addDays(5)->format('Y-m-d'),
        'assigned_to' => $assignedUser->id,
        ]);

        $response = $this->actingAs($admin)->delete("/api/soft_delete_task/{$task->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_force_delete_task_successful()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }

        $assignedUser = User::where('id', 6)->first();
        if (!$assignedUser) {
            $assignedUser = User::factory()->create(); 
        }

        //add new task to deleted it 
        $task = Task::create([
        'title' => 'Initial Task',
        'description' => 'Initial Description',
        'type' => 'bug',
        'priority' => 'medium',
        'due_date' => now()->addDays(5)->format('Y-m-d'),
        'assigned_to' => $assignedUser->id,
        ]);
        
        //send a force delete request
        $response = $this->actingAs($admin)->delete("/api/task/{$task->id}");

        $response->assertStatus(200);

        //Verify that the task no longer exists in the database
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);



    }

    public function test_restore_task_successful()
    {
        // Retrieve the admin user from the database or create one if not found
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }

        $assignedUser = User::where('id', 6)->first();
            if (!$assignedUser) {
            $assignedUser = User::factory()->create(); 
            }

        // Create a new task and soft delete it
        $task = Task::create([
            'title' => 'Initial Task',
            'description' => 'Initial Description',
            'type' => 'bug',
            'priority' => 'medium',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'assigned_to' => $assignedUser->id,
        ]);

        $this->actingAs($admin)->delete("/api/soft_delete_task/{$task->id}");

        // Verify that the task is soft deleted
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);

        // Send a restore request to bring the task back
        $response = $this->actingAs($admin)->post("/api/task/{$task->id}");

       
        $response->assertStatus(200);

        // Verify that the task has been restored (exists in the database with no deleted_at timestamp)
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'deleted_at' => null,
        ]);
    }
}