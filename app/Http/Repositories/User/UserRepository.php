<?php

namespace App\Http\Repositories\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\User;

class UserRepository implements UserRepositoryInterface{

    public function getAllUsers()
    {
        return User::all();
    }

    public function authenticateUser(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return false;
        }

        $validated = $validator->validated();

        if (Auth::attempt($validated)) {
            $user = Auth::user();
            return true;
        }
    }
}