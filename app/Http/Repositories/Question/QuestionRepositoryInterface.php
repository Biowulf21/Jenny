<?php

namespace App\Http\Repositories\Question;

use Illuminate\Http\Request;

use App\Models\Question;

interface QuestionRepositoryInterface 
{
    public function createQuestion(array $data): Question;
    public function editQuestion(array $data, int $id);
    public function deleteQuestion(int $id): void;
}