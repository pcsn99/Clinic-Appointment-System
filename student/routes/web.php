<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentAppointmentController;
use App\Http\Controllers\ForgotPasswordController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//forgetpass routes 
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('forgot.password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'handleReset'])->name('forgot.password.submit');

Route::middleware(['auth.student'])->group(function () {

    //Kat added
    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [StudentAppointmentController::class, 'index'])->name('student.appointments.index');
    Route::post('/appointments/book', [StudentAppointmentController::class, 'book'])->name('student.appointments.book');
    Route::post('/appointments/{id}/cancel', [StudentAppointmentController::class, 'cancel'])->name('student.appointments.cancel');
    Route::post('/appointments/{id}/reschedule', [StudentAppointmentController::class, 'reschedule'])->name('student.appointments.reschedule');
    Route::get('/appointments/schedules-by-date', [StudentAppointmentController::class, 'schedulesByDate']);
    Route::get('/appointments/calendar-events', [StudentAppointmentController::class, 'calendarEvents']);
    Route::post('/appointments/{appointment}/mark-present', [StudentAppointmentController::class, 'markAsPresent'])->name('student.appointments.markPresent');

    // Kat added Student Profile Routes
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [AccountController::class, 'update'])->name('profile.update');

    //notifs routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);


    

});
