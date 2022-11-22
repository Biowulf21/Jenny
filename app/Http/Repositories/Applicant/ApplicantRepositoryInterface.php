<?php

namespace App\Http\Repositories\Applicant;

use Illuminate\Http\Request;

interface ApplicantRepositoryInterface 
{
    public function getAllApplicants();
    public function getSingleApplicant(int $id);
}