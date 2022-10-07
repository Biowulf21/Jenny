<?php

namespace App\Http\Repositories\Question;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Models\Question;

interface QuestionRepositoryInterface 
{
    public function createQuestion(array $data): Question;
    public function editQuestion(array $data, int $id): Question;
    public function deleteQuestion(int $id): void;
    public function showAllQuestions(int $exam_id): Collection;
    public function showSingleQuestion(int $id): Question;
}