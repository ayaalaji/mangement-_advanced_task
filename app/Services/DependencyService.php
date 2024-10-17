<?php
namespace App\Services;

use App\Models\Task;
use App\Models\Dependency;
use Exception;
use Illuminate\Support\Facades\Log;

class DependencyService {

    /**
     * add Dependency by admin.
     * @param array $data
     */
    public function addDependency(array $data)
    {
        try {

            if (isset($data['task_id']) && isset($data['depends_on_task_id'])) {
    
                Dependency::create([
                    'task_id' => $data['task_id'],
                    'depends_on_task_id' => $data['depends_on_task_id'],
                ]);
    
                $this->checkAndUpdateTaskStatus($data['task_id']);
            } else {
                throw new \Exception('Task ID or Depends On Task ID is missing');
            }
        } catch(Exception $e){
            Log::error($e);
            throw new Exception('You cannot add Dependency.');
        }
    }


    /**
     * Check the status of the task's dependencies and update the task status accordingly.
     * If any of the dependent tasks are not completed, the task's status will be set to 'blocked'.
     * If all dependencies are completed, the task's status will be set to 'open'.
     * This method ensures that a task cannot proceed if it has unresolved dependencies.
     *@param int $taskId The ID of the task to check and update its status based on its dependencies.
     */
    public function checkAndUpdateTaskStatus($taskId)
    {
        try {

            $task = Task::find($taskId);
    
            $dependencies = $task->dependencies;
    
            foreach ($dependencies as $dependency) {
                if ($dependency->dependsOn->status !== 'completed') {
                    $task->update(['status' => 'blocked']);
                    return;
                }
            }
    
            $task->update(['status' => 'open']);
        }catch(Exception $e){
            Log::error($e);
            throw new Exception('sorry try again');
        }
    }
}