<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserLoginRequest;

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
}
