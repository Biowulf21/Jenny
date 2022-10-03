<?php

namespace App\Http\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface{

    public function getAllUsers()
    {
        return User::all();
    }

    public function authenticateUser(array $data)
    {

    }

}