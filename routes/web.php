<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\CallbackController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RedirectController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::view('/styleguide', 'pages.styleguide')->name('styleguide');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy-policy', 'pages.privacy')->name('privacy');

Route::view('/login', 'auth.login')->name('login');
Route::get('/auth/{provider}', RedirectController::class)->name('auth.provider');
Route::get('/auth/{provider}/callback', CallbackController::class)->name('auth.callback');
Route::post('/logout', LogoutController::class)->name('logout');
