<?php

use App\Http\Controllers\Student\DashboardStudentController;
use Illuminate\Support\Facades\Route;









Route::prefix('student')->group(function () {

    Route::get('dashboard', DashboardStudentController::class)->name('student.dashboard');

});
