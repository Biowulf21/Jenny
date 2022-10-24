<?php

namespace App\Http\Repositories\Applicant;

use Illuminate\Http\Request;

interface ApplicantRepositoryInterface 
{
    public function showAllApplicants();
    public function showOneApplicant(int $id);
}