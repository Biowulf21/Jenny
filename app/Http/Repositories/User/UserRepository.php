<?php

namespace App\Http\Repositories\User;

use Illuminate\Http\Response; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use App\Http\Resources\AuthenticatedUserResource as AuthenticatedUser;
use App\Exceptions\ValidatorFailedException;
use App\Exceptions\InvalidCredentialException;
use App\Models\User;
use App\Models\Position;

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
            $error_message = $validator->errors()->all();
            throw new ValidatorFailedException($error_message[0], $validator->errors());
        }

        $validated = $validator->validated();

        if (Auth::attempt($validated)) {
            $user = Auth::user();
            $user = new AuthenticatedUser($user, $user->createToken('authToken')->plainTextToken);
            return response()->pass('Authentication successful', $user);
        }

        throw new InvalidCredentialException;
    }

    public function createUser(array $data, string $role)
    {
        try {
            $validator = Validator::make($data, 
                [
                    'name' => 'required|string', 
                    'email' => 'required|email|unique:users,email',                     
                    'for_position' => $role === 'applicant' ? 'required' : 'nullable',
                    'password' => 'required|string|min:8',
                    'password_confirmation' => 'required|string|same:password',
                ]
            );

            if($validator->fails())
            {
                $error_message = $validator->errors()->all();
                throw new ValidatorFailedException($error_message[0], $validator->errors());
            }

            $validated = $validator->validated();      

            $validated['role'] = $role;
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);
            ($role === 'applicant') ? $user = new AuthenticatedUser($user, $user->createToken('authToken')->plainTextToken) : $user;
            return response()->pass('Successfully created ' . $role . ' user', $user);
        } catch (Exception $e) { 
            return response()->pass($e->getMessage());
        }
        
    }

    public function createApplicantUser(array $data)
    {
        return $this->createUser($data, 'applicant');
    }
}