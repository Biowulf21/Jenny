<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Repositories\ApplicantQuestion\ApplicantQuestionRepositoryInterface;

class ApplicantQuestionController extends Controller
{
    private $repository; 
    public function __construct(ApplicantQuestionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function onSubmitCheck(Request $request)
    {
        return $this->repository->checkOnSubmit($request->all());
    }
}
