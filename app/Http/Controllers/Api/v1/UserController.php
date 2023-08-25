<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserLoginRequest;

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
}
