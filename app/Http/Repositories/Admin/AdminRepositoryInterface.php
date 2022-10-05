<?php

namespace App\Http\Repositories\Admin;

use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;

interface AdminRepositoryInterface {
    public function createAdminUser(UserRequest $request);
}