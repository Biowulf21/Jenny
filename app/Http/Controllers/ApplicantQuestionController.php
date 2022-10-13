<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // return $request->all();
        $this->repository->checkOnSubmit($request->all());
    }
}
