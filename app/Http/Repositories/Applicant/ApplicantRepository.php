<?php

namespace App\Http\Repositories\Applicant;

use Illuminate\Support\Facades\Log;

use App\Http\Repositories\User\UserRepository;
use App\Models\User;

class ApplicantRepository implements ApplicantRepositoryInterface
{
  public function getAllApplicants()
  {
    $applicants = User::where('role', 'applicant')->get();
    return response()->pass('Successfully fetched all applicants', $applicants);   
  }

  public function getSingleApplicant(int $id)
  {
    $applicant = User::where([
        ['id', $id],
        ['role', 'applicant']
      ])->firstOrFail();
    return response()->pass('Successfully fetched one applicant', $applicant);   
  }
}