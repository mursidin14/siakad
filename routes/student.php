<?php

use App\Http\Controllers\Student\DashboardStudentController;
use App\Http\Controllers\Student\FeeStudentController;
use App\Http\Controllers\Student\ScheduleStudentController;
use App\Http\Controllers\Student\StudyPlanController;
use Illuminate\Support\Facades\Route;









Route::prefix('student')->middleware(['auth', 'role:Student'])->group(function () {

    Route::get('dashboard', DashboardStudentController::class)->name('student.dashboard');


    // Kartu Rencana Navigation
    Route::controller(StudyPlanController::class)->group(function() {
        Route::get('study-plans', 'index')->name('student.study-plans.index');
        Route::get('study-plans/create', 'create')->name('student.study-plans.create');
        Route::post('study-plans/create', 'store')->name('student.study-plans.store');
        Route::get('study-plans/detail/{studyPlan}', 'show')->name('student.study-plans.show');
    });

    
    // Jadwal Navigation
    Route::get('schedules', ScheduleStudentController::class)->name('student.schedules.index');


    // fees Navigation
    Route::get('fees', FeeStudentController::class)->name('student.fees.index');


});