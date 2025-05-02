<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Return the authenticated user's profile details.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile(Request $request)
    {
        $user = $request->user(); // Using Sanctum authentication
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return response()->json(['user' => $user]);
    }


    /**
     * Update profile details for the authenticated user.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Allowed fields to update
        $allowedFields = ['username'];
        $data = $request->only($allowedFields);

        // Validate the data; for date_of_birth, we require YYYY-MM-DD format
        $validator = Validator::make($data, [
            'username'      => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user->update($data);
        return response()->json(['message' => 'Profile updated successfully.', 'user' => $user]);
    }


    /**
     * Update the profile photo for the authenticated user.
     */
    public function updateProfilePhoto(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Validate that a file is provided and it's an image
        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|max:2048', // max 2MB
        ]);

        if ($validator->fails()) {
            Log::error('Profile photo validation errors: ', $validator->errors()->toArray());
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Store the image in 'profile_photos' folder on the public disk
        $path = $request->file('profile_photo')->store('profile_photos', 'public');
        Log::info("Profile photo stored at: " . $path);

        // Update user's profile_photo field and save
        $user->profile_photo = $path;
        $user->save();
        Log::info("User profile updated with photo: ", $user->toArray());

        return response()->json([
            'message' => 'Profile photo updated successfully.',
            'user' => $user
        ]);
    }

    // Delete profile photo for the authenticated user
    public function deleteProfilePhoto(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // If a profile photo exists, delete the file from storage
        if ($user->profile_photo) {
            // Assuming your profile photos are stored in storage/app/public/profile_photos
            Storage::delete('public/profile_photos/' . $user->profile_photo);
        }

        // Remove the profile photo from the user record
        $user->profile_photo = null;
        $user->save();

        return response()->json([
            'message' => 'Profile photo deleted successfully.',
            'user' => $user
        ]);
    }
}
