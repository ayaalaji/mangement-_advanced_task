<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\TaskStatusUpdate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class taskService {
    /**
     * Display a listing of Tasks.
     * admin can see all tasks ، and manager can see the task that he managed it 
     *and user can see only task that he worked in
     */
    public function getAllTasks()
    {
        try {
            $user =Auth::user();
            if($user->role=='admin')
            {
                $tasks = Cache::remember('tasks',3600,function(){
                    return Task::all();
                });
            } elseif($user->role=='manager') {
                $tasks = Task::where('type',$user->manages_type)->get();
            } else {
                $tasks =Task::where('assigned_to',$user->id)->get();
            }
            return $tasks;
        } catch(Exception $e) {
            Log::error($e);
            throw new Exception('Unable to get all Tasks...try again');
        }
    }
    /**
     * create new Task by admin.
     * @param array $data
     */
    public function createTask(array $data,Task $task)
    {
        try {
            return Task::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'type' => $data['type'],
                'priority' => $data['priority'],
                'due_date' => isset($data['due_date']) ? Carbon::parse($data['due_date'])->format('Y-m-d') : $task->due_date ,
                'assigned_to' => isset($data['assigned_to']) ? (int)$data['assigned_to'] :$task->assigned_to,
            ]);
        } catch(Exception $e) {
            Log::error($e);
            throw new Exception('Unable to Add Task...try again');
        }

    }
    /**
     * update Task by admin.
     * user can update status of task but the task that he assigned to
     * @param array $data
     * @param Task $task
     */
    public function updateTask(array $data,Task $task)
    {
        try {
            $user =Auth::user();

            if($user->role =='admin')
            {
                if (isset($data['status'])) {
                    return false;
                }
                $updateTask = array_filter([
                    'title' => $data['title'] ?? $task->title,
                    'description' => $data['description']?? $task->description,
                    'type' => $data['type']?? $task->type,
                    'priority' => $data['priority']?? $task->priority,
                    'due_date' => isset($data['due_date']) ? Carbon::parse($data['due_date'])->format('Y-m-d') : $task->due_date ,
                    'assigned_to' => isset($data['assigned_to']) ? (int)$data['assigned_to'] :$task->assigned_to,  
                ]);
            }
            if($user->role =='user')
            {
                if ($task->assigned_to !== $user->id) {
                    return false;
                }
                
                $updateTask = array_filter([
                    'status' => $data['status']
                ]);
    
            }
            if (isset($updateTask['status'])) {
                $task->update($updateTask);
            } else {
                $task->update(array_diff_key($updateTask, ['status' => '']));
            }
            
            $task->update($updateTask);
           
            
            return $task;
        } catch(Exception $e) {
            Log::error('Task update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw new Exception('Unable to Update Task...try again');
        }
    }

    

    }