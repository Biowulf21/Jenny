<?php

namespace App\Http\Repositories\Question;

use Illuminate\Http\Request;

use App\Models\Question;

interface QuestionRepositoryInterface 
{
    public function createQuestion(array $data): Question;
    public function deleteQuestion(int $id): void;
}