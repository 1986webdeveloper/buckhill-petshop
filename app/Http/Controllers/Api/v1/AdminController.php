<?php

namespace App\Http\Controllers\Api\v1;

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
}
