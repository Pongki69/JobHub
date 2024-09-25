<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Auth;

// Default home route (login page view)
Route::get('/', function () {
    return view('JobHub.src.login');
})->name('home');

// Default authentication routes
Auth::routes();

// Group routes that require authentication
Route::middleware(['auth'])->group(function () {
    
    Route::get('/postjob', [JobController::class, 'showPostJobForm'])->name('postjob.form');
    Route::post('/postjob', [JobController::class, 'storePostJob'])->name('postjob.store');

    Route::get('/profile/{id}', [JobController::class, 'showProfile'])->name('profile.show');
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

    Route::post('upload-cover-photo', [JobController::class, 'uploadCoverPhoto'])->name('upload.cover.photo');
    Route::post('upload-profile-pic', [JobController::class, 'uploadProfilePic'])->name('upload.profile.pic');

    Route::get('/userform', [JobController::class, 'showUserForm'])->name('user.form');
    Route::post('/userform', [JobController::class, 'submitUserForm'])->name('submit.user.form');
});

// Google login route
Route::get('/login', [GoogleController::class, 'redirectToGoogle'])->name('login');

// Google authentication routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
