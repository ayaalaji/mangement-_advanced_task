<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $request->validated();
        $credentials = $request->only('email', 'password');

        $result = $this->authService->login($credentials);
        if (!$result) {
            return $this->error('Invalid login');
        }

        return $this->success([
            'user' => $result['user'],
            'authorisation' => [
                'token' => $result['token'],
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
         $this->authService->logout();
        return $this->success('Successfully logged out');
    }

    public function refresh()
    {
         $result = $this->authService->refresh();

        return $this->success([
            'user' => $result['user'],
            'authorisation' => [
                'token' => $result['token'],
                'type' => 'bearer',
            ]
        ]);
    }
}

