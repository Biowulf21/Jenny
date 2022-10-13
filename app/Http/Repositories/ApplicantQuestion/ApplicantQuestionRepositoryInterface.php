<?php

namespace App\Http\Repositories\ApplicantQuestion;

use Illuminate\Http\Request;

interface ApplicantQuestionRepositoryInterface {
    public function checkOnSubmit(array $data);
}