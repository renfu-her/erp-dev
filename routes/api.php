<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\PositionController;
use App\Http\Controllers\Backend\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('backend')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('guest');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('positions', PositionController::class);
        Route::apiResource('employees', EmployeeController::class);

        Route::post('employees/{employee}/block', [EmployeeController::class, 'block'])->name('employees.block');
        Route::post('employees/{employee}/unblock', [EmployeeController::class, 'unblock'])->name('employees.unblock');

        Route::apiResource('roles', RoleController::class)->except(['edit', 'create']);
        Route::post('roles/{role}/assign', [RoleController::class, 'assign']);
    });
});
