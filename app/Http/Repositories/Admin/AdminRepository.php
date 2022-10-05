<?php

namespace App\Http\Repositories\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\ValidatorFailedException;
use App\Models\User;

class AdminRepository implements AdminRepositoryInterface
{
   public function createAdminUser(array $data)
   {
        $validator = Validator::make($data, 
            [
               'name' => 'required|string', 
               'email' => 'required|email|unique:users,email', 
               'password' => 'required|string|min:8',
               'password_confirmation' => 'required|string|same:password',
            ]
         );

         if($validator->fails())
         {
            throw new ValidatorFailedException('Failed creating the user', $validator->errors());
         }

         $validated = $validator->validated();

         $validated['role'] = 'admin'; 
         $validated['password'] = Hash::make($validated['password']);

         return User::create($validated);
   }
}