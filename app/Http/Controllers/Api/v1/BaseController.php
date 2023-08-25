<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

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
}
