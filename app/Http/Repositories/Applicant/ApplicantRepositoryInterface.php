<?php

namespace App\Http\Repositories\Applicant;

use Illuminate\Http\Request;

interface ApplicantRepositoryInterface 
{
    public function createApplicantUser(array $data);
}