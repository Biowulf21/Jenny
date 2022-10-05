<?php

namespace App\Http\Repositories\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use App\Http\Resources\AuthenticatedUserResource as AuthenticatedUser;
use App\Exceptions\ValidatorFailedException;
use App\Exceptions\InvalidCredentialException;
use App\Models\User;

class UserRepository implements UserRepositoryInterface{

    public function getAllUsers()
    {
        return User::all();
    }

    public function authenticateUser(array $data)
    {
        $validator = Validator::make($data, 
            [
                'email' => 'required',
                'password' => 'required',
            ], 
        );

        if ($validator->fails()) {
            throw new  ValidatorFailedException('Failed to authenticate user', $validator->errors());
        }

        $validated = $validator->validated();

        if (Auth::attempt($validated)) {
            $user = Auth::user();
            return new AuthenticatedUser($user, $user->createToken('authToken')->plainTextToken);
        }

        throw new InvalidCredentialException;
        // try this: return redirect('login')->withSuccess('Sorry! You have entered invalid credentials'); --> leads to seemingly infinite request
    }
}