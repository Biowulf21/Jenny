<?php

namespace App\Http\Repositories\Admin;

use Illuminate\Support\Facades\Log;

use App\Http\Repositories\User\UserRepository;
use App\Models\User;

class AdminRepository implements AdminRepositoryInterface
{

   public function createAdminUser(array $data)
   {
    $user = new UserRepository; 
    return $user->createUser($data, 'admin');
   }

   public function showAllAdmins()
   {
    $admins = User::where('role', 'admin')->get();
    return response()->pass('Successfully fetched all admins', $admins);   
   }

   public function showOneAdmin(int $id)
   {
    $admin = User::findOrFail($id);
    return response()->pass('Successfully fetched one admin', $admin); 
   }
}