<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BaseController extends Controller
{
    /**
     * Handles user login.
     */
    public function loginUser($isAdmin, UserLoginRequest $request)
    {
        // Fetch the user based on the provided email
        $user = User::where('is_admin', $isAdmin)->where('email', $request->input('email'))->first();

        // Verify password and existence of user
        if ( ! $user || ! password_verify($request->input('password'), $user->password)) {
            return apiResponse(null, 'Invalid credentials', 401, false);
        }

        // Update last login timestamp
        $user->last_login_at = now();
        $user->save();

        // Generate and return JWT token
        $token = generateToken($user);

        return apiResponse(['user' => $user, 'token' => $token], 'User logged in successfully', 200, true);
    }

    /**
     * Handles user logout.
     */
    public function logout(Request $request)
    {
        // Extract token from Authorization header
        $token = str_replace('Bearer ', '', $request->header('Authorization'));

        // Invalidate token by adding to blacklist
        revokeToken($token);

        return apiResponse(null, 'Logged out successfully', 200, true);
    }

    /**
     * Creates a new user
     */
    public function createUser($isAdmin, CreateUserRequest $request)
    {
        // Create a new User instance and fill it with request data
        $user = new User($request->all());

        // Set UUID and password for the user
        $user->uuid     = Str::uuid();
        $user->password = Hash::make($request->input('password'));

        // set user role
        $user->is_admin = $isAdmin;

        // Handle avatar file upload if present
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $uuid       = Str::uuid();
            $filename   = $uuid . '.' . $avatarFile->getClientOriginalExtension();
            $filePath   = 'pet-shop/' . $filename;
            // Store the avatar file
            Storage::disk('public')->putFileAs('', $avatarFile, $filePath);

            // create associated File record
            $file = File::create([
                'uuid' => $uuid,
                'name' => $avatarFile->getClientOriginalName(),
                'path' => $filePath,
                'size' => $avatarFile->getSize(),
                'type' => $avatarFile->getMimeType(),
            ]);

            // Associate the file with the user's avatar
            $user->avatar = $uuid;
        }

        // Save the user
        $user->save();

        // Return a success response
        return apiResponse(['user' => $user], 'Account created successfully', 201, true);
    }
}
