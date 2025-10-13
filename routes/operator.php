<?php

use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Operator\ClassroomOperatorController;
use App\Http\Controllers\Operator\CourseOperatorController;
use App\Http\Controllers\Operator\DashboardOperatorController;
use App\Http\Controllers\Operator\StudentController;
use App\Http\Controllers\Operator\StudentOperatorController;
use App\Http\Controllers\Operator\TeacherOperatorController;
use Illuminate\Support\Facades\Route;









Route::prefix('operator')->middleware(['auth', 'role:Operator'])->group(function () {

    Route::get('dashboard', DashboardOperatorController::class)->name('operator.dashboard');


    // Mahasiswa Navigation
    Route::controller(StudentOperatorController::class)->group(function() {
        Route::get('students', 'index')->name('operator.students.index');
        Route::get('students/create', 'create')->name('operator.students.create');
        Route::post('students/create', 'store')->name('operator.students.store');
        Route::get('students/edit/{student:student_number}', 'edit')->name('operator.students.edit');
        Route::put('students/edit/{student:student_number}', 'update')->name('operator.students.update');
        Route::delete('students/destroy/{student:student_number}', 'destroy')->name('operator.students.destroy');
    });


    // Dosen Navigation
    Route::controller(TeacherOperatorController::class)->group(function() {
        Route::get('teachers', 'index')->name('operator.teachers.index');
        Route::get('teachers/create', 'create')->name('operator.teachers.create');
        Route::post('teachers/create', 'store')->name('operator.teachers.store');
        Route::get('teachers/edit/{teacher:teacher_number}', 'edit')->name('operator.teachers.edit');
        Route::put('teachers/edit/{teacher:teacher_number}', 'update')->name('operator.teachers.update');
        Route::delete('teachers/destroy/{teacher:teacher_number}', 'destroy')->name('operator.teachers.destroy');
    });


    // Kelas Navigation
    Route::controller(ClassroomOperatorController::class)->group(function() {
        Route::get('classrooms', 'index')->name('operator.classrooms.index');
        Route::get('classrooms/create', 'create')->name('operator.classrooms.create');
        Route::post('classrooms/create', 'store')->name('operator.classrooms.store');
        Route::get('classrooms/edit/{classroom:slug}', 'edit')->name('operator.classrooms.edit');
        Route::put('classrooms/edit/{classroom:slug}', 'update')->name('operator.classrooms.update');
        Route::delete('classrooms/destroy/{classroom:slug}', 'destroy')->name('operator.classrooms.destroy');
    });


    // Mata Kuliah Navigation
    Route::controller(CourseOperatorController::class)->group(function() {
        Route::get('courses', 'index')->name('operator.courses.index');
        Route::get('courses/create', 'create')->name('operator.courses.create');
        Route::post('courses/create', 'store')->name('operator.courses.store');
        Route::get('courses/edit/{course:code}', 'edit')->name('operator.courses.edit');
        Route::put('courses/edit/{course:code}', 'update')->name('operator.courses.update');
        Route::delete('courses/destroy/{course:code}', 'destroy')->name('operator.courses.destroy');
    });
});
