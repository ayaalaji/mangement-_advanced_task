<?php

namespace Tests\Unit;

use Mockery;
use App\Models\Task;
use App\Services\TaskService;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    // use RefreshDatabase;
    // protected $taskService;
    // protected $task;

    // public function setUp() : void
    // {
    //     parent::setUp();
        
    //     $this->taskService = new TaskService();
    //     $this->task = [
    //         'title' => 'Test Task',
    //         'description' => 'This is a test description',
    //         'type' => 'feature',
    //         'priority' => 'high',
    //         'due_date' => now()->addDays(7)->format('Y-m-d'),
    //         'assigned_to' => 6,
    //     ];
    // }
    // /**
    //  * A basic unit test example.
    //  */
    // public function test_create_task_in_database(): void
    // {
    //     $createdTask = $this->taskService->createTask($this->task,new Task());
    //     $this->assertEquals('Test Task',$createdTask->title);
       
    // }
        
}
