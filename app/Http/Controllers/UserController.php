<?php

public function update(Request $request)
{
    $request->validate([
        'contact_num' => 'nullable|string|max:255',
        'birthdate' => 'nullable|string|max:255',
        'role' => 'nullable|string|max:255',
        'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = Auth::user();
    $user->contact_num = $request->contact_num;
    $user->birthdate = $request->birthdate;
    $user->role = $request->role;

    if ($request->hasFile('profile_pic')) {
        $profilePicPath = $request->file('profile_pic')->store('profile_pics', 'public');
        $user->profile_pic = $profilePicPath;
    }

    if ($request->hasFile('cover_photo')) {
        $coverPhotoPath = $request->file('cover_photo')->store('cover_photos', 'public');
        $user->cover_photo = $coverPhotoPath;
    }

    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully.');
}