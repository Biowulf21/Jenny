<?php

namespace App\Http\Repositories\ApplicantQuestion;

use Illuminate\Http\Request;

interface ApplicantQuestionRepositoryInterface {
    public function getParagraphQuestions(int $applicantID, int $examID);
    public function getExamResults(int $applicantID, int $examID);
    public function adminChecking(array $data);
    public function applicantChecking(array $data);
}