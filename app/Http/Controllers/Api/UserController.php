<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\FileUploadHelper;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    // API 4: Get User Profile
    public function getProfile(Request $request)
    {

        $user = auth('api')->user();
       

       return response()->json([
        'success' => true,
        'user' => new UserResource($user),
    ]);
    }

    // API 5: Update Profile
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|min:2|max:50',
            'username' => 'sometimes|string|min:3|max:30|unique:users,username,' . auth('api')->id(),
            'bio' => 'nullable|string|max:150',
            'gender' => 'nullable|string|max:150',
            'dob' => 'nullable|string|max:150',
            'email' => 'nullable|email|unique:users,email,' . auth('api')->id(),
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $user = auth('api')->user();
       if ($request->hasFile('profile_photo')) {
            if (!empty($user->profile_photo)) {
                    $oldPath = 'profile/' . $user->profile_photo;
                    if (Storage::disk('s3')->exists($oldPath)) {
                        Storage::disk('s3')->delete($oldPath);
                    }
            }
            $file = $request->file('profile_photo');
            $fileName = uniqid('', true).'.'.$file->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs(
                'profile',        // folder
                $file,
                $fileName
            );
            $validated['profile_photo'] = $fileName;
        }
        $user->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'bio' => $user->bio,
                'gender' => $user->gender,
                'dob' => $user->dob,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'profile_photo' => $user->profile_photo ? Storage::disk('s3')->url('profile/'.$user->profile_photo) : null,
            ],
        ]);
    }

    // View Other User's Profile
    public function getUserById($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User does not exist',
                ]
            ], 404);
        }

        $authenticatedUser = auth('api')->user();
        $userData = $user->toArray();
        $userData['is_following'] = $authenticatedUser && $authenticatedUser->id !== $user->id
            ? $authenticatedUser->isFollowing($user)
            : false;

        return response()->json([
            'success' => true,
            'user' => $userData,
        ]);
    }
    
    public function getChatUsers(Request $request){
        return 'dd';
    }
}
