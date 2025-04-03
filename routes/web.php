<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Welcome::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
//    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('logout', function () {
        auth()->logout();
        return redirect(route('home'));
    })->name('logout');
});
