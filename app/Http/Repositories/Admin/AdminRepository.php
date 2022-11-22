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

   public function getAllAdmins()
   {
      $admins = User::where('role', 'admin')->get();
      return response()->pass('Successfully fetched all admins', $admins);   
   }

   public function getSingleAdmin(int $id)
   {
      $admin = User::where([
         ['id', $id], 
         ['role', 'admin']
      ])->firstOrFail();
      return response()->pass('Successfully fetched one admin', $admin); 
   }
}