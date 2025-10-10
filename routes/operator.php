<?php

use App\Http\Controllers\Admin\OperatorController;
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

});
