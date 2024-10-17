<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateDependencyRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use App\Services\taskService;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(taskService $taskService)
    {
        $this->middleware('auth:api');
        $this->middleware('security');
        $this->middleware('admin',['except'=>['index','update']]);
        $this->taskService = $taskService;
    }
    /**
     * Display a listing of Tasks.
     * admin can see all tasks ، and manager can see the task that he managed it 
     *and user can see only task that he worked in
     * @return /Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        return $this->success($tasks ,'This is All Tasks');
    }

    /**
     * Store a newly Task.
     * admin can only add Task
     * @param StoreRequest $request
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request,Task $task)
    {
        $validatedData = $request->validated();
        $taskCreated =$this->taskService->createTask($validatedData,$task);
        return $this->success($taskCreated,'Task Created Successfully',201);  
    }

    /**
     * Update the specified Task by admin.
     * @param UpdateRequest $request
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        $taskUpdated = $this->taskService->updateTask($validatedData,$task);
        if ($taskUpdated === false) {
            return $this->error('Unable to update task. This Task not yours.');
        }
        return $this->success($taskUpdated,'Task Updated Successfully');
    }

    /**
     * Remove the specified Task by admin (soft delete).
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return $this->success(null,'You delete Task Temporarily');
    }
    /**
     * Restore specifice Task that the admin deleted at to a record in the table in database
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function reStore(Task $task)
    {
        $task->restore();
        return $this->success($task,'You restore a task that admin deleted at');
    }
    /**
     * delete the task permanently
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete(Task $task)
    {
        $task->forceDelete();
        return $this->success(null,'You delete the task permanently');
    }

    
}
