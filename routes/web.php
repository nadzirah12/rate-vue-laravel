<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RateController;

// Route::get('/', function () {
//     return 'here';
// });

Route::get('/', [RateController::class, 'index'])->name('rate.index');

