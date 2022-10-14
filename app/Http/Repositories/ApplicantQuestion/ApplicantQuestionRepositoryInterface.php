<?php

namespace App\Http\Repositories\ApplicantQuestion;

use Illuminate\Http\Request;

interface ApplicantQuestionRepositoryInterface {
    public function applicantChecking(array $data);
    public function getExamResults(int $applicantID, int $examID);
}