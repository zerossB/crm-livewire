<?php

use App\Enums\Can;
use App\Livewire\{Admin, Welcome};
use App\Livewire\Auth\{Login, PasswordRecovery, PasswordReset, Register};
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
    Route::get('/password/recovery', PasswordRecovery::class)->name('password.recovery');
    Route::get('/password/reset/{token}', PasswordReset::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('home');

    // Region Admin
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('can:' . Can::BE_AN_ADMIN->value)
        ->group(function () {
            Route::get('/', Admin\Dashboard::class)->name('dashboard');
            Route::get('/users', Admin\Users\ListUsers::class)->name('users.list');
        });
    // End Region Admin
});
