<?php

use App\Http\Controllers\Api\AttachementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DependencyController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;

Route::apiResource('role',RoleController::class); 

Route::apiResource('user',UserController::class);

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(TaskController::class)->group(function () {
    Route::get('tasks','index');
    Route::post('task','store');
    Route::put('task/{task}','update');
    Route::delete('soft_delete_task/{task}','destroy');
    Route::post('task/{task}','reStore')->withTrashed();
    Route::delete('task/{task}','forceDelete');
});


Route::controller(AttachementController::class)->group(function () {
    Route::get('tasks/attachements','index'); 
    Route::post('tasks/{task}/attachements','store'); 
    Route::post('tasks/{task}/attachements/{attachment}','update'); 
    Route::delete('tasks/{task}/attachements/{attachment}','destroy'); 
});
Route::post('tasks/dependencies', [DependencyController::class, 'store']);

Route::controller(CommentController::class)->group(function () {
    Route::post('/tasks/{task}/comments','store');
    Route::put('/tasks/{task}/comments/{comment}','update');
    Route::delete('/tasks/{task}/comments/{comment}','update');
});
