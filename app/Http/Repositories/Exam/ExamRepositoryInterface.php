<?php

namespace App\Http\Repositories\Exam;

use Illuminate\Http\Request;

interface ExamRepositoryInterface {
    public function createExam(array $data): Exam;
    public function deleteExam(int $id): void; 
    public function showAllExams(): Collection; 
    public function showSingleExam(int $id): Exam; 
}