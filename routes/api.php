<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\UserController;
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
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('code-verification', [AuthController::class, 'codeVerification']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('logout', [AuthController::class, 'logout']);

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'create']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::post('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'delete']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'create']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/{id}', [UserController::class, 'update']);
        Route::post('/status', [UserController::class, 'status']);
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::post('/', [ProfileController::class, 'update']);
    });

    Route::prefix('colleges')->group(function () {
        Route::get('/', [CollegeController::class, 'index']);
        Route::post('/', [CollegeController::class, 'create']);
        Route::get('/{id}', [CollegeController::class, 'show']);
        Route::post('/{id}', [CollegeController::class, 'update']);
        Route::delete('/{id}', [CollegeController::class, 'delete']);
    });

    Route::prefix('grades')->group(function () {
        Route::get('/', [GradeController::class, 'index']);
    });

    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
    });

    Route::prefix('summaries')->group(function () {
        Route::get('/', [SummaryController::class, 'index']);
    });
});
