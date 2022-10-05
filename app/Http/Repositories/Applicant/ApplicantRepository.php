<?php

namespace App\Http\Repositories\Applicant;

use Illuminate\Support\Facades\Log;

use App\Http\Repositories\User\UserRepository;

class ApplicantRepository implements ApplicantRepositoryInterface
{
  public function createApplicantUser(array $data)
  {
    $user = new UserRepository; 
    return $user->createUser($data, 'applicant');
  }
}