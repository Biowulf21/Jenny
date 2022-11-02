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

    public function fetchExamResults(int $applicantID, int $examID)
    {
        return $this->repository->fetchExamResults($applicantID, $examID);
    }
    
    public function adminChecking(Request $request)
    {
        return $this->repository->adminChecking($request->all());
    }
    public function applicantChecking(Request $request)
    {
        return $this->repository->applicantChecking($request->all());
    }

    public function getParagraphQuestions(int $applicantID, int $examID)
    {
        return $this->repository->getParagraphQuestions($applicantID, $examID);
    }
}
