<?php

use App\Http\Controllers\Operator\DashboardOperatorController;
use Illuminate\Support\Facades\Route;









Route::prefix('operator')->middleware(['auth', 'role:Operator'])->group(function () {

    Route::get('dashboard', DashboardOperatorController::class)->name('operator.dashboard');

});
