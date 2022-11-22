<?php

namespace App\Http\Repositories\Question;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Models\Question;

interface QuestionRepositoryInterface 
{
    public function createQuestion(array $data);
    public function editQuestion(array $data, int $id);
    public function deleteQuestion(int $id);
    public function getAllQuestions(int $exam_id);
    public function getSingleQuestion(int $id);
}