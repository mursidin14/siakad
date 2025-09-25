<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\DepartementController;
use Illuminate\Support\Facades\Route;








Route::prefix('admin')->middleware(['auth', 'role:Admin'])->group(function () {
    
// Dashboard Navigation
    Route::get('dashboard', DashboardAdminController::class)->name('admin.dashboard');


    // Faculty Navigation
    Route::controller(FacultyController::class)->group(function() {
        Route::get('faculties', 'index')->name('admin.faculties.index');
        Route::get('faculties/create', 'create')->name('admin.faculties.create');
        Route::post('faculties/create', 'store')->name('admin.faculties.store');
        Route::get('faculties/edit/{faculty:slug}', 'edit')->name('admin.faculties.edit');
        Route::put('faculties/edit/{faculty:slug}', 'update')->name('admin.faculties.update');
        Route::delete('faculties/destroy/{faculty:slug}', 'destroy')->name('admin.faculties.destroy');
    });


    // Departement Navigation
    Route::controller(DepartementController::class)->group(function() {
        Route::get('departements', 'index')->name('admin.departements.index');
        Route::get('departements/create', 'create')->name('admin.departements.create');
        Route::post('departements/create', 'store')->name('admin.departements.store');
        Route::get('departements/edit/{departement:slug}', 'edit')->name('admin.departements.edit');
        Route::put('departements/edit/{departement:slug}', 'update')->name('admin.departements.update');
        Route::delete('departements/destroy/{departement:slug}', 'destroy')->name('admin.departements.destroy');
    });


    // AcademicYear Navigation
    Route::controller(AcademicYearController::class)->group(function() {
        Route::get('academic-years', 'index')->name('admin.academic-years.index');
        Route::get('academic-years/create', 'create')->name('admin.academic-years.create');
        Route::post('academic-years/create', 'store')->name('admin.academic-years.store');
        Route::get('academic-years/edit/{academicYear:slug}', 'edit')->name('admin.academic-years.edit');
        Route::put('academic-years/edit/{academicYear:slug}', 'update')->name('admin.academic-years.update');
        Route::delete('academic-years/destroy/{academicYear:slug}', 'destroy')->name('admin.academic-years.destroy');
    });

});










