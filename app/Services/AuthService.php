<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService{
    public function login(array $credentials)
    {
        $token = JWTAuth::attempt($credentials);
       
        if (!$token) {
            return false;
        }
        
        $user = Auth::user();
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout()
    {
        Auth::logout();
    }

    public function refresh()
    {
        $token = Auth::refresh();
        $user = Auth::user();
        return [
            'user' => $user,
            'token' => $token
        ];
    }
}