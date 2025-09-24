<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/view-schedules', [DashboardController::class, 'viewSchedules'])->name('view-schedules');

Route::resource('courses', CourseController::class);
Route::resource('professors', ProfessorController::class);
Route::post('professors/{professor}/status', [ProfessorController::class, 'setStatus']);
Route::resource('rooms', RoomController::class);
Route::resource('schedules', ScheduleController::class);
