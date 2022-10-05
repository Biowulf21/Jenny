<?php

namespace App\Http\Repositories\Admin;

use Illuminate\Support\Facades\Log;

use App\Http\Repositories\User\UserRepository;

class AdminRepository implements AdminRepositoryInterface
{

   public function createAdminUser(array $data)
   {
       $user = new UserRepository; 
       return $user->createUser($data, 'admin');
   }

}