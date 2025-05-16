<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\LogViewerController;
use App\Http\Controllers\AdminAppointmentController;


use App\Http\Controllers\WalkinNotificationController;


Route::get('/', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth.admin')->group(function () {
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');

    Route::resource('schedules', ScheduleController::class)->except(['show']);
    Route::get('/schedules/bulk-create', [ScheduleController::class, 'showBulkCreate'])->name('schedules.bulk.create');
    Route::post('/schedules/bulk-create', [ScheduleController::class, 'bulkStore'])->name('schedules.bulk.store');
    Route::post('/schedules/bulk-delete', [ScheduleController::class, 'bulkDelete'])->name('schedules.bulk.delete');



    Route::get('/appointments/create', [AdminAppointmentController::class, 'create'])->name('admin.appointments.create');
    Route::post('/appointments/create', [AdminAppointmentController::class, 'store'])->name('admin.appointments.store');
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
    Route::post('/appointments/{appointment}/mark', [AdminAppointmentController::class, 'mark'])->name('admin.appointments.mark');
    Route::post('/appointments/bulk-delete', [AdminAppointmentController::class, 'bulkDelete'])->name('admin.appointments.bulkDelete');
    Route::get('/appointments/calendar-events', [AdminAppointmentController::class, 'calendarEvents']);
    Route::get('/appointments/schedules-by-date', [AdminAppointmentController::class, 'schedulesByDate']);

    Route::get('/walkin-notifications', [WalkinNotificationController::class, 'create'])->name('admin.walkin.create');
    Route::post('/walkin-notifications', [WalkinNotificationController::class, 'store'])->name('admin.walkin.store');


    Route::get('/admin/logs', [LogViewerController::class, 'index'])->name('admin.logs');
});

