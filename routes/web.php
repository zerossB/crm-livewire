<?php

use App\Livewire\Auth\{Login, PasswordRecovery, PasswordReset, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
    Route::get('/password/recovery', PasswordRecovery::class)->name('password.recovery');
    Route::get('/password/reset/{token}', PasswordReset::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('home');
});
