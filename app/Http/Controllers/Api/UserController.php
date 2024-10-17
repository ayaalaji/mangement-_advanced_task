<?php

namespace App\Http\Controllers\Api;


use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api');
        $this->middleware('admin');
        $this->middleware('security');
        $this->userService = $userService;
    }
    /**
     * Display a listing of the Users by admin.
     * @return /Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();
            return $this->success($users);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Store a newly created Users by admin.
     * @param StoreRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $user = $this->userService->createUser($validatedData);
            return $this->success($user,'User created successfully', 201);
        } catch (Exception $th) {
            Log::error($th);
            return $this->error($th->getMessage(), 500);
        }
    }


    /**
     * Update the specified User by admin.
     * @param UpdateRequest $request
     * @param string $id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            $user =$this->userService->updateUser($validatedData, $id);
            return $this->success($user,'User updated successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->error('Unable to update user at this time. Please try again later.', 500);
        }
    }

    /**
     * Remove the specified User by admin.
     * @param string $id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
       try {
            $this->userService->deleteUser($id);
            return $this->success(null,'User deleted successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return error($th->getMessage(), 500);
        }
    }
}
