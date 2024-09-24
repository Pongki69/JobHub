<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Auth;

// Default home route (login page view)
Route::get('/', function () {
    return view('JobHub.src.login'); // Update this to match your login view
})->name('home');

// Default authentication routes
Auth::routes();

// Group routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Check if the user has a role and redirect if not
    Route::get('/postjob', function () {
        if (empty(Auth::user()->role)) {
            return redirect()->route('user.form'); // Redirect to user form if no role
        }

        return app(JobController::class)->showPostJobForm(); // Call the controller method
    })->name('postjob.form');

    // Store the posted job in the database (protected route)
    Route::post('/postjob', [JobController::class, 'storePostJob'])->name('postjob.store');

    // Delete Job Post (protected route)
    Route::delete('/job-posts/{id}', [JobController::class, 'deleteJobPost'])->name('job.delete');

    // Profile route for displaying user profile (protected route)
    Route::get('/profile/{id}', function ($id) {
        // Check if the authenticated user has a role
        if (empty(Auth::user()->role)) {
            return redirect()->route('user.form'); // Redirect to user form if no role
        }

        // If the user has a role, call the controller method to show the profile
        return app(JobController::class)->showProfile($id); // Pass the ID to the controller method
    })->name('profile.show');

    // Logout (protected route)
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

    // Upload cover photo (protected route)
    Route::post('upload-cover-photo', [JobController::class, 'uploadCoverPhoto'])->name('upload.cover.photo');

    // Upload profile picture (protected route)
    Route::post('upload-profile-pic', [JobController::class, 'uploadProfilePic'])->name('upload.profile.pic');

    // User form route (protected)
    Route::get('/userform', [JobController::class, 'showUserForm'])->name('user.form');
    Route::post('/userform', [JobController::class, 'submitUserForm'])->name('submit.user.form');
});

// Google login route
Route::get('/login', [GoogleController::class, 'redirectToGoogle'])->name('login');

// Google authentication routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
