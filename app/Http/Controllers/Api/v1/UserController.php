<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * Handles user login.
     */
    public function login(UserLoginRequest $request)
    {
        return $this->loginUser(false, $request);
    }

    /**
     * Creates a new user
     */
    public function create(CreateUserRequest $request)
    {
        return $this->createUser(false, $request);
    }

    /**
     * Edits current user's details.
     */
    public function edit(EditUserRequest $request)
    {
        return $this->editUser($request, $request->user_uuid);
    }

    /**
     * Deletes current user account.
     */
    public function delete(Request $request)
    {
        return $this->deleteUser($request->user_uuid);
    }
}
