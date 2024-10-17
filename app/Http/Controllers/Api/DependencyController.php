<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dependency\StoreRequest;
use App\Services\DependencyService;

class DependencyController extends Controller
{
    protected $dependencyService;
    public function __construct(DependencyService $dependencyService)
    {
        $this->dependencyService = $dependencyService;
        $this->middleware('auth:api');
        $this->middleware('admin');
        $this->middleware('security');
    }

    /**
     * this method to add dependency task to another task by admin
     * @param StoreRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $validatedData =$request->validated();

        
        $this->dependencyService->addDependency($validatedData);

        return $this->success(null,'Dependency added successfully',201);
    }

    /**
     * update the status of task based on its dependencies
     * @param int $taskId The ID of the task whose status needs to be updated.
     * @return /Illuminate\Http\JsonResponse
     */
    public function updateTaskStatus($taskId)
    {
        $this->dependencyService->checkAndUpdateTaskStatus($taskId);

        return $this->success('Task status updated based on dependencies');
    }
}
