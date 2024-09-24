<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Laravel\Socialite\Two\InvalidStateException;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        // Remove the 'prompt' option to avoid forcing account selection
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            // Retrieve the user from Google without stateless() for session handling
            $googleUser = Socialite::driver('google')->user();

            // Find or create the user
            $existingUser = User::where('google_id', $googleUser->id)->first();

            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                // Create a new user if not found
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    // Ensure role is not assigned at this stage
                ]);

                Auth::login($newUser);
            }

            // Set session variable for username
            session(['username' => Auth::user()->name, 'id' => Auth::id()]); // Save the ID of the logged-in user under the key 'id'

            // Check if the user has a role assigned
            if (empty(Auth::user()->role)) {
                return redirect()->route('user.form'); // Redirect to user form if no role
            }

            // Redirect to the intended page after successful login
            return redirect()->intended('postjob'); // Adjust the route name as needed

        } catch (InvalidStateException $e) {
            return redirect()->route('login')->with('error', 'Invalid state. Please try logging in again.');
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Log the user out and clear session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Clear session data
        $request->session()->flush();

        // Optionally, invalidate the session
        $request->session()->invalidate();

        // Redirect to the login page
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
