<?php

namespace App\Http\Repositories\Exam;

use Illuminate\Http\Request;

interface ExamRepositoryInterface {
    public function createExam(array $data);
    public function deleteExam(int $id): void; 
}