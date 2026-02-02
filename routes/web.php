<?php

declare(strict_types=1);

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\CallbackController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RedirectController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Event\RSVPController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JoinController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\Paper\PaperSubmissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SponsorsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::view('/styleguide', 'pages.styleguide')->name('styleguide');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy-policy', 'pages.privacy')->name('privacy');
Route::view('/cookies', 'pages.cookies')->name('cookies');

Route::get('/join', [JoinController::class, 'index'])->name('join.index');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/auth/{provider}', RedirectController::class)->name('auth.provider');
Route::get('/auth/{provider}/callback', CallbackController::class)->name('auth.callback');
Route::post('/logout', LogoutController::class)->name('logout');

Route::get('/members', [MembersController::class, 'index'])->name('members.index');
Route::get('/members/{member}', [MembersController::class, 'show'])->name('members.show');

Route::get('/sponsors', [SponsorsController::class, 'index'])->name('sponsors.index');
Route::get('/sponsors/{sponsor}', [SponsorsController::class, 'show'])->name('sponsors.show');

Route::get('/events', [EventsController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventsController::class, 'show'])->name('events.show');
Route::post('/events/{event}/rsvp', RSVPController::class)->name('events.rsvp');

Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/submit-paper', [PaperSubmissionController::class, 'create'])->name('papers.create');
    Route::post('/events/{event}/submit-paper', [PaperSubmissionController::class, 'store'])->name('papers.store');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
