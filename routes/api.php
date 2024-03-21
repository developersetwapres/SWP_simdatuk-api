<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
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

Route::post('/users', [UserController::class, 'createNewUser']);
Route::get('/users', [UserController::class, 'userList']);
Route::patch('/users/{userId}', [UserController::class, 'update'])->where('userId', '[0-9]+');
Route::get('/users/{userId}', [UserController::class, 'userDetail'])->where('userId', '[0-9]+');
Route::delete('/users/{userId}/deactivate', [UserController::class, 'deactivate'])->where('userId', '[0-9]+');

Route::get('/roles', [RoleController::class, 'list']);
Route::post('/roles', [RoleController::class, 'createNewRole']);
Route::get('/roles/{roleId}', [RoleController::class, 'detail'])->where('roleId', '[0-9]+');
Route::put('/roles/{roleId}', [RoleController::class, 'updateRole'])->where('roleId', '[0-9]+');
Route::patch('/roles/{roleId}', [RoleController::class, 'delete'])->where('roleId', '[0-9]+');

Route::get('/permissions/group', [PermissionController::class, 'listGroup']);

