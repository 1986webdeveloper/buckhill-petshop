<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserListingRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;

class AdminController extends BaseController
{
    /**
     * Handles user login.
     */
    public function login(UserLoginRequest $request)
    {
        return $this->loginUser(true, $request);
    }

    /**
     * Creates a new admin user
     */
    public function create(CreateUserRequest $request)
    {
        return $this->createUser(true, $request);
    }

    /**
     * Edits an existing user's details.
     */
    public function edit(EditUserRequest $request, $uuid)
    {
        return $this->editUser($request, $uuid);
    }

    /**
     * Deletes a user account.
     */
    public function delete($uuid)
    {
        return $this->deleteUser($uuid);
    }

    /**
     * Provides user list
     */
    public function list(UserListingRequest $request)
    {
        // Retrieve listing parameters
        $page        = $request->input('page', 1);
        $limit       = $request->input('limit', 10);
        $sortBy      = $request->input('sort_by', 'created_at');
        $desc        = $request->input('desc', false); // Set to false by default
        $isMarketing = $request->input('is_marketing', false); // Set to false by default

        // Build query for user listing
        $query = User::query()->where('is_admin', 0);

        // If is_marketing parameter is provided, filter by it
        if ($isMarketing !== false) {
            $query->where('is_marketing', $isMarketing);
        }

        // Determine sorting direction
        $orderBy = $desc ? 'desc' : 'asc';

        // Validate and sanitize the sorting column
        $allowedSortColumns = ['created_at', 'last_login_at', 'first_name', 'is_marketing', 'email'];
        $sortBy             = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';

        // Fetch paginated users with the specified sorting and limiting
        $users = $query->orderBy($sortBy, $orderBy)->paginate($limit, ['*'], 'page', $page);

        // Return the paginated users as a response
        return apiResponse($users, 'Users list', 200, true);
    }
}
