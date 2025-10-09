<?php

use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Operator\DashboardOperatorController;
use App\Http\Controllers\Operator\StudentController;
use App\Http\Controllers\Operator\StudentOperatorController;
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

});
