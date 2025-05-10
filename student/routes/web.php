<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentAppointmentController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/appointments', [StudentAppointmentController::class, 'index'])->name('student.appointments.index');
    Route::post('/appointments/book', [StudentAppointmentController::class, 'book'])->name('student.appointments.book');
    Route::post('/appointments/{id}/cancel', [StudentAppointmentController::class, 'cancel'])->name('student.appointments.cancel');
    Route::post('/appointments/{id}/reschedule', [StudentAppointmentController::class, 'reschedule'])->name('student.appointments.reschedule');
    Route::get('/appointments/schedules-by-date', [StudentAppointmentController::class, 'schedulesByDate']);
    Route::get('/appointments/calendar-events', [StudentAppointmentController::class, 'calendarEvents']);
    Route::post('/appointments/{appointment}/mark-present', [StudentAppointmentController::class, 'markAsPresent'])->name('student.appointments.markPresent');

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    
    // Account routes
    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');

});
