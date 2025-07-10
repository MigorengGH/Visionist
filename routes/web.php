<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/workshop/{id}', [WelcomeController::class, 'workshop'])->name('workshop.detail');
Route::get('/artwork/{id}', [WelcomeController::class, 'artwork'])->name('artwork.detail');
