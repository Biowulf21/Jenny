<?php

namespace App\Http\Repositories\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\UserRequest;
use App\Models\User;

class AdminRepository implements AdminRepositoryInterface
{

   public function createAdminUser(UserRequest $request)
   {
        $validated = $request->validated();
        
        $validated['role'] = 'admin';
        $validated['password'] = Hash::make($validated['password']);

        return User::create($validated);
   }
}