<?php

namespace App\Http\Repositories\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Http\Repositories\User\UserRepository;
use App\Exceptions\ValidatorFailedException;
use App\Models\User;

class AdminRepository implements AdminRepositoryInterface
{
   
   public function createAdminUser(array $data)
   {
       $user = new UserRepository; 
       return $user->createUser($data, 'admin');
   }

}