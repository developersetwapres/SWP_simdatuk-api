<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreditScoreController;
use App\Http\Controllers\DecreeController;
use App\Http\Controllers\DiagramController;
use App\Http\Controllers\DisciplinaryController;
use App\Http\Controllers\DisciplinaryHistoryController;
use App\Http\Controllers\EchelonController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmploymentTypeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradeHistoryController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PerformanceHistoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PositionHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecapitulationAsnController;
use App\Http\Controllers\RecapitulationController;
use App\Http\Controllers\RecapitulationNonAsnController;
use App\Http\Controllers\RecapitulationOutsourceController;
use App\Http\Controllers\RecognitionHistoryController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\TargetHistoryController;
use App\Http\Controllers\TrainingHistoryController;
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

    Route::prefix('summaries')->group(function () {
        Route::get('/', [SummaryController::class, 'index']);
    });

    Route::prefix('recapitulations')->group(function () {
        Route::get('/', [RecapitulationController::class, 'index']);
        Route::get('/{category}', [RecapitulationController::class, 'show']);
    });

    Route::prefix('recapitulations-asn')->group(function () {
        Route::get('/', [RecapitulationAsnController::class, 'index']);
        Route::get('/{category}', [RecapitulationAsnController::class, 'show']);
    });

    Route::prefix('recapitulations-nonasn')->group(function () {
        Route::get('/', [RecapitulationNonAsnController::class, 'index']);
        Route::get('/{category}', [RecapitulationNonAsnController::class, 'show']);
    });

    Route::prefix('recapitulations-outsource')->group(function () {
        Route::get('/', [RecapitulationOutsourceController::class, 'index']);
        Route::get('/{category}', [RecapitulationOutsourceController::class, 'show']);
    });

    Route::prefix('notes')->group(function () {
        Route::get('/{userid}', [NoteController::class, 'show']);
        Route::post('/{userid}', [NoteController::class, 'update']);
    });

    Route::prefix('diagrams')->group(function () {
        Route::get('/', [DiagramController::class, 'index']);
    });

    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('/', [EmployeeController::class, 'create']);
        Route::get('/{id}', [EmployeeController::class, 'show']);
        Route::post('/{id}', [EmployeeController::class, 'update']);
    });

    Route::prefix('position-histories')->group(function () {
        Route::get('/', [PositionHistoryController::class, 'index']);
        Route::post('/', [PositionHistoryController::class, 'create']);
        Route::get('/{id}', [PositionHistoryController::class, 'show']);
        Route::post('/{id}', [PositionHistoryController::class, 'update']);
    });

    Route::prefix('grade-histories')->group(function () {
        Route::get('/', [GradeHistoryController::class, 'index']);
        Route::post('/', [GradeHistoryController::class, 'create']);
        Route::get('/{id}', [GradeHistoryController::class, 'show']);
        Route::post('/{id}', [GradeHistoryController::class, 'update']);
    });

    Route::prefix('training-histories')->group(function () {
        Route::get('/', [TrainingHistoryController::class, 'index']);
        Route::post('/', [TrainingHistoryController::class, 'create']);
        Route::get('/{id}', [TrainingHistoryController::class, 'show']);
        Route::post('/{id}', [TrainingHistoryController::class, 'update']);
    });

    Route::prefix('recognition-histories')->group(function () {
        Route::get('/', [RecognitionHistoryController::class, 'index']);
        Route::post('/', [RecognitionHistoryController::class, 'create']);
        Route::get('/{id}', [RecognitionHistoryController::class, 'show']);
        Route::post('/{id}', [RecognitionHistoryController::class, 'update']);
    });

    Route::prefix('target-histories')->group(function () {
        Route::get('/', [TargetHistoryController::class, 'index']);
        Route::post('/', [TargetHistoryController::class, 'create']);
        Route::get('/{id}', [TargetHistoryController::class, 'show']);
        Route::post('/{id}', [TargetHistoryController::class, 'update']);
    });

    Route::prefix('performance-histories')->group(function () {
        Route::get('/', [PerformanceHistoryController::class, 'index']);
        Route::post('/', [PerformanceHistoryController::class, 'create']);
        Route::get('/{id}', [PerformanceHistoryController::class, 'show']);
        Route::post('/{id}', [PerformanceHistoryController::class, 'update']);
    });

    Route::prefix('disciplinary-histories')->group(function () {
        Route::get('/', [DisciplinaryHistoryController::class, 'index']);
        Route::post('/', [DisciplinaryHistoryController::class, 'create']);
        Route::get('/{id}', [DisciplinaryHistoryController::class, 'show']);
        Route::post('/{id}', [DisciplinaryHistoryController::class, 'update']);
    });

    Route::prefix('positions')->group(function () {
        Route::get('/', [PositionController::class, 'index']);
    });

    Route::prefix('grades')->group(function () {
        Route::get('/', [GradeController::class, 'index']);
    });

    Route::prefix('institutions')->group(function () {
        Route::get('/', [InstitutionController::class, 'index']);
        Route::post('/', [InstitutionController::class, 'create']);
        Route::get('/{id}', [InstitutionController::class, 'show']);
        Route::post('/{id}', [InstitutionController::class, 'update']);
        Route::delete('/{id}', [InstitutionController::class, 'delete']);
    });

    Route::prefix('employment-types')->group(function () {
        Route::get('/', [EmploymentTypeController::class, 'index']);
        Route::post('/', [EmploymentTypeController::class, 'create']);
        Route::get('/{id}', [EmploymentTypeController::class, 'show']);
        Route::post('/{id}', [EmploymentTypeController::class, 'update']);
        Route::delete('/{id}', [EmploymentTypeController::class, 'delete']);
    });

    Route::prefix('decrees')->group(function () {
        Route::get('/', [DecreeController::class, 'index']);
    });

    Route::prefix('echelons')->group(function () {
        Route::get('/', [EchelonController::class, 'index']);
    });

    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index']);
    });

    Route::prefix('disciplinaries')->group(function () {
        Route::get('/', [DisciplinaryController::class, 'index']);
    });

    Route::prefix('residences')->group(function () {
        Route::get('/', [ResidenceController::class, 'index']);
    });

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

    Route::prefix('export')->group(function () {
        Route::get('/recapitulations', [ExportController::class, 'recapitulations']);
        Route::get('/employees', [ExportController::class, 'employees']);
        Route::get('/employees/{id}', [ExportController::class, 'detailEmployee']);
        Route::get('/employees-zip', [ExportController::class, 'zipDetailEmployee']);
        Route::get('/preview', [ExportController::class, 'exportExcelsPreview']);
        Route::get('/rekapitulasi', [ExportController::class, 'rekapitulasi']);
        Route::get('/rekapitulasi-non-asn', [ExportController::class, 'rekapitulasiNonASN']);
        Route::get('/rekapitulasi-asn', [ExportController::class, 'rekapitulasiASN']);
    });

    Route::prefix('credit-scores')->group(function () {
        Route::post('/', [CreditScoreController::class, 'create']);
        Route::get('/{id}', [CreditScoreController::class, 'show']);
        Route::post('/{id}', [CreditScoreController::class, 'update']);
        Route::delete('/{id}', [CreditScoreController::class, 'delete']);
    });
});
