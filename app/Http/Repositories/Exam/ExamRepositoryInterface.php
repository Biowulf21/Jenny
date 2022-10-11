<?php

namespace App\Http\Repositories\Exam;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Models\Exam;

interface ExamRepositoryInterface {
    public function createExam(array $data);
    public function deleteExam(int $id);
    public function showAllExams();
    public function showSingleExam(int $id);
    public function showApplicantExams();
}