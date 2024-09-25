<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class JobController extends Controller
{  
    /**
     * Show the job posting form for the authenticated user.
     *
     * @return \Illuminate\View\View
     */

    
    public function showUserForm()
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login'); // Redirect to login page if not authenticated
    }

    // Pass the authenticated user to the view
    return view('JobHub.src.userform', ['user' => Auth::user()]);
}

  public function submitUserForm(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'id' => 'nullable|string|exists:users,google_id', // using Google Id
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'contact_num' => 'required|string', // Change to string for manual validation
        'birthdate' => 'required|date',
        'user_role' => 'required|string',
        'job_experience' => 'nullable|string|max:255',
        'job_description' => 'nullable|string|max:500',
        'company_name' => 'nullable|string|max:255',
    ]);

    // Get the Google ID from the request
    $googleId = $request->input('id');

    // Find the authenticated user using the Google ID from the request
    $user = User::where('google_id', $googleId)->first();

    // Update user information
    $user->name = $request->input('name');
    $user->email = $request->input('email');

    // Handle the contact number correctly
    $contactInput = $request->input('contact_num');

    // Check if the input starts with +63 or is a valid 10-digit number
    if (strpos($contactInput, '+63') === 0) {
        // If it starts with +63, replace it with 0
        $user->contact_num = '0' . substr($contactInput, 3); // Strip +63 and add leading 0
    } else if (strlen($contactInput) == 10) {
        // If it's already a 10-digit number, prepend a 0
        $user->contact_num = '0' . $contactInput; // Ensure it becomes 09876543210
    } else {
        // If the number is not in a valid format, handle the error
        return redirect()->back()->withErrors(['contact_num' => 'Please provide a valid contact number.']);
    }

    // Format the birthdate to remove time
    $user->birthdate = \Carbon\Carbon::parse($request->input('birthdate'))->format('Y-m-d');
    
    $user->role = $request->input('user_role');

    // Save additional fields based on user role
    if ($user->role === 'jobseeker') {
        $user->job_experience = $request->input('job_experience');
        $user->job_description = $request->input('job_description');
    } elseif ($user->role === 'employer') {
        $user->company_name = $request->input('company_name'); // Change 'company' to 'company_name' for consistency
    }

    // Attempt to save the user
    try {
        $user->save();
        // Log success message
        \Log::info('User information saved successfully for user: ' . $user->email);
    } catch (\Exception $e) {
        // Log the error message
        \Log::error('Failed to save user information: ' . $e->getMessage());
        // Optionally, you can redirect back with an error message
        return redirect()->back()->withErrors(['error' => 'Failed to save user information. Please try again.']);
    }

    // Redirect to post job page after submission
    return redirect()->route('postjob.form')->with('success', 'User information saved successfully.');
}




    public function showPostJobForm()
    {
        try {
    $user = Auth::user(); // Get the currently authenticated user
    $jobPostings = JobPost::all(); // Retrieve all job postings

    return view('JobHub.src.postjob', compact('user', 'jobPostings'));
} catch (\Exception $e) {
    Log::error('Error loading job postings: ' . $e->getMessage());
    return redirect()->back()->with('error', 'Failed to load the job postings. Please try again.');
}
    }
    
    /**
     * Store a new job posting in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePostJob(Request $request)
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_type' => 'required|string',
            'job_location' => 'required|string|max:255',
            'job_deadline' => 'required|date',
            'postImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000',
        ]);

        $imagePath = null;

        if ($request->hasFile('postImage')) {
            $image = $request->file('postImage');
            $uploaderName = Auth::user()->name;
            $dateTime = now()->format('Y-m-d_H-i-s');
            $folderName = "{$uploaderName}_{$dateTime}";

            $imagePath = $image->store("job_images/{$uploaderName}/{$folderName}", 'public');
        }

        try {
            Log::info('Job post data:', $request->all());

            JobPost::create([
                'job_title' => $request->input('job_title'),
                'company_name' => $request->input('company_name'),
                'job_description' => $request->input('job_description'),
                'job_type' => $request->input('job_type'),
                'job_location' => $request->input('job_location'),
                'job_deadline' => $request->input('job_deadline'),
                'image_path' => $imagePath,
                'user_id' => Auth::id(),
                'uploader_name' => Auth::user()->name,
            ]);

            return redirect('/postjob')->with('success', 'Job posted successfully!');
        } catch (\Exception $e) {
            Log::error('Error saving job post: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to post the job. Please try again.');
        }
    }

    /**
     * Delete a job post by its ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteJobPost($id)
    {
        $jobPost = JobPost::find($id);

        if (!$jobPost || $jobPost->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $folderPath = public_path("storage/{$jobPost->image_path}");
            $deleted = $jobPost->delete();

            if ($deleted && File::exists($folderPath)) {
                File::deleteDirectory(dirname($folderPath));
            }

            return response()->json(['message' => 'Job post deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting job post: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the job post'], 500);
        }
    }

    /**
     * Show the profile page of a user with their job postings.
     *
     * @param int $userId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showProfile($userId)
    {
        // Fetch the user by ID
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('error.page')->with('error', 'User not found.');
        }

        // Fetch all users
        $allUsers = User::all(); // Fetch all users

        // Fetch job postings for the specific user
        $jobPostings = JobPost::where('user_id', $userId)->get(); 

        // Return the view with user, job postings, and all users
        return view('JobHub.src.profile', compact('user', 'jobPostings', 'allUsers')); 
    }

    /**
     * Upload a cover photo for the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadCoverPhoto(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            Log::error('User not authenticated');
            return redirect()->route('login');
        }

        Log::info('Request data: ', $request->all());

        $validatedData = $request->validate([
            'coverPhoto' => 'required|image|mimes:jpeg,jpg,png,gif,bmp,tiff,svg,webp|max:10240',
        ]);

        if ($request->hasFile('coverPhoto')) {
            $file = $request->file('coverPhoto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $uploaderName = Auth::user()->name;

            $directoryPath = public_path("storage/job_images/{$uploaderName}/cover_photo");
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true);
            }

            // Delete old cover photo if exists
            if ($user->cover_photo && File::exists(public_path($user->cover_photo))) {
                Log::info("Deleting old cover photo: " . public_path($user->cover_photo));
                unlink(public_path($user->cover_photo));
            }

            $file->move($directoryPath, $filename);
            $user->cover_photo = "storage/job_images/{$uploaderName}/cover_photo/{$filename}";

            if (!$user->save()) {
                Log::error('Failed to save cover photo for user ID: ' . $user->id);
                return redirect()->back()->withErrors(['coverPhoto' => 'Failed to save cover photo.']);
            }
        } else {
            Log::error('Cover photo upload failed: No file sent.');
            return redirect()->back()->withErrors(['coverPhoto' => 'Cover photo is required.']);
        }

        return redirect()->back()->with('success', 'Cover photo uploaded successfully!');
    }

    /**
     * Upload a profile picture for the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadProfilePic(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            Log::error('User not authenticated');
            return redirect()->route('login');
        }

        Log::info('Request data: ', $request->all());

        $validatedData = $request->validate([
            'profilePic' => 'required|image|mimes:jpeg,jpg,png,gif,bmp,tiff,svg,webp|max:10240',
        ]);

        if ($request->hasFile('profilePic')) {
            $file = $request->file('profilePic');
            $filename = time() . '_' . $file->getClientOriginalName();
            $uploaderName = Auth::user()->name;

            $directoryPath = public_path("storage/job_images/{$uploaderName}/profile_pics");
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true);
            }

            // Delete old profile picture if exists
            if ($user->profile_pic && File::exists(public_path($user->profile_pic))) {
                Log::info("Deleting old profile picture: " . public_path($user->profile_pic));
                unlink(public_path($user->profile_pic));
            }

            $file->move($directoryPath, $filename);
            $user->profile_pic = "storage/job_images/{$uploaderName}/profile_pics/{$filename}";

            if (!$user->save()) {
                Log::error('Failed to save profile picture for user ID: ' . $user->id);
                return redirect()->back()->withErrors(['profilePic' => 'Failed to save profile picture.']);
            }
        } else {
            Log::error('Profile picture upload failed: No file sent.');
            return redirect()->back()->withErrors(['profilePic' => 'Profile picture is required.']);
        }

        return redirect()->back()->with('success', 'Profile picture uploaded successfully!');
    }
}
