<?php

use App\Http\Controllers\Teacher\CourseClassroomController;
use App\Http\Controllers\Teacher\CourseTeacherController;
use App\Http\Controllers\Teacher\DashboardTeacherController;
use Illuminate\Support\Facades\Route;









Route::prefix('teacher')->middleware(['auth', 'role:Teacher'])->group(function () {

    Route::get('dashboard', DashboardTeacherController::class)->name('teacher.dashboard');

    Route::controller(CourseTeacherController::class)->group(function(){
        Route::get('courses', 'index')->name('teacher.courses.index');
        Route::get('courses/{course}/detail', 'show')->name('teacher.courses.show');
    });

    Route::controller(CourseClassroomController::class)->group(function(){
        Route::get('courses/{course}/classrooms/{classroom}', 'index')->name('teacher.classrooms.index');
        Route::put('courses/{course}/classrooms/{classroom}/synchronize', 'sync')->name('teacher.classrooms.sync');
    });

});
