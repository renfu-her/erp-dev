<?php

use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Backend\AttendanceManagementController;
use App\Http\Controllers\Frontend\AttendanceController;
use App\Http\Controllers\Backend\CompanyManagementController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DepartmentManagementController;
use App\Http\Controllers\Backend\EmployeeManagementController;
use App\Http\Controllers\Backend\HolidayManagementController;
use App\Http\Controllers\Backend\InsuranceBracketController;
use App\Http\Controllers\Backend\HumanResourceController as BackendHumanResourceController;
use App\Http\Controllers\Backend\LeaveRequestManagementController;
use App\Http\Controllers\Backend\LeaveTypeManagementController;
use App\Http\Controllers\Backend\PositionManagementController;
use App\Http\Controllers\Backend\PayrollController;
use App\Http\Controllers\Frontend\EmployeeLeaveController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\HumanResourceController as FrontendHumanResourceController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'create'])->name('login');
    Route::post('/login', [WebAuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [WebAuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/', FrontendController::class)->name('frontend.home');
Route::get('/frontend', FrontendController::class)->name('frontend.index');
Route::middleware(['auth', 'permission:frontend.portal.access'])->group(function () {
    Route::get('/frontend/hr', FrontendHumanResourceController::class)->name('frontend.hr.self-service');
    Route::post('/frontend/attendance/{action}', [AttendanceController::class, 'store'])
        ->whereIn('action', ['check-in', 'check-out'])
        ->name('frontend.attendance.store');
});

Route::middleware(['auth', 'permission:frontend.leave.submit'])->group(function () {
    Route::get('/frontend/hr/leave-request', [EmployeeLeaveController::class, 'create'])->name('frontend.hr.leave-request');
    Route::post('/frontend/hr/leave-request', [EmployeeLeaveController::class, 'store'])->name('frontend.hr.leave-request.submit');
});

Route::prefix('backend')->name('backend.')->middleware(['auth', 'permission:backend.access'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/hr', BackendHumanResourceController::class)->name('hr.dashboard');

    Route::get('attendance', [AttendanceManagementController::class, 'index'])->name('attendance.index');
    Route::post('attendance', [AttendanceManagementController::class, 'store'])->name('attendance.store');
    Route::get('payroll', [PayrollController::class, 'index'])
        ->middleware('permission:payroll.view')
        ->name('payroll.index');
    Route::post('payroll/periods', [PayrollController::class, 'storePeriod'])
        ->middleware('permission:payroll.manage')
        ->name('payroll.periods.store');
    Route::post('payroll/runs', [PayrollController::class, 'storeRun'])
        ->middleware('permission:payroll.manage')
        ->name('payroll.runs.store');
    Route::get('insurance-brackets', [InsuranceBracketController::class, 'index'])
        ->middleware('permission:payroll.view')
        ->name('insurance-brackets.index');

    Route::resource('companies', CompanyManagementController::class)->except(['show']);
    Route::get('companies/{company}/schedule', [CompanyManagementController::class, 'editSchedule'])->name('companies.schedule.edit');
    Route::post('companies/{company}/schedule', [CompanyManagementController::class, 'storeSchedule'])->name('companies.schedule.store');
    Route::put('companies/{company}/schedule/{workSchedule}', [CompanyManagementController::class, 'updateSchedule'])->name('companies.schedule.update');
    Route::resource('holidays', HolidayManagementController::class)->except(['show']);
    Route::resource('departments', DepartmentManagementController::class)->except(['show']);
    Route::resource('positions', PositionManagementController::class)->except(['show']);
    Route::resource('employees', EmployeeManagementController::class)->except(['show']);
    Route::post('employees/{employee}/block', [EmployeeManagementController::class, 'block'])->name('employees.block');
    Route::post('employees/{employee}/unblock', [EmployeeManagementController::class, 'unblock'])->name('employees.unblock');
    Route::resource('leave-types', LeaveTypeManagementController::class)->except(['show']);
    Route::get('leave-requests', [LeaveRequestManagementController::class, 'index'])->name('leave-requests.index');
    Route::put('leave-requests/{leave_request}', [LeaveRequestManagementController::class, 'update'])->name('leave-requests.update');
});
