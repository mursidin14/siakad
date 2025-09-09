<?php


use App\Http\Controllers\Teacher\DashboardTeacherController;
use Illuminate\Support\Facades\Route;









Route::prefix('teacher')->group(function () {

    Route::get('dashboard', DashboardTeacherController::class)->name('teacher.dashboard');

});
