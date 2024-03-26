<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return 'api enabled!';
});

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::delete('logout', [AuthController::class, 'logout']);
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'userList']);
    Route::get('/{userId}', [UserController::class, 'userDetail'])->where('userId', '[0-9]+');
    Route::patch('/{userId}', [UserController::class, 'update'])->where('userId', '[0-9]+');
    Route::delete('/{userId}/deactivate', [UserController::class, 'deactivate'])->where('userId', '[0-9]+');
});

Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'list']);
    Route::post('/', [RoleController::class, 'createNewRole']);
    Route::get('/{roleId}', [RoleController::class, 'detail'])->where('roleId', '[0-9]+');
    Route::put('/{roleId}', [RoleController::class, 'updateRole'])->where('roleId', '[0-9]+');
    Route::patch('/{roleId}', [RoleController::class, 'delete'])->where('roleId', '[0-9]+');
});

Route::prefix('permissions')->group(function () {
    Route::get('/', [PermissionController::class, 'list']);
    Route::get('/group', [PermissionController::class, 'listGroup']);
});

Route::prefix('register')->group(function () {
    Route::post('/', [UserRegistrationController::class, 'register']);
    Route::get('/{key}', [UserRegistrationController::class, 'validateKey']);
});
