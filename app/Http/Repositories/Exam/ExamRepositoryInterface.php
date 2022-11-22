<?php

namespace App\Http\Repositories\Exam;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Models\Exam;

interface ExamRepositoryInterface {
    // Admin-side
    public function createExam(array $data);
    public function deleteExam(int $id);
    public function editExam(array $data, int $id);
    public function getAllExams();
    public function showAllExamResults(int $examID);
    public function getSingleExam(int $id);

    // Applicant-side
    public function showApplicantExams();
    public function showSingleApplicantExam(int $id);
}