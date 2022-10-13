<?php

namespace App\Http\Repositories\ApplicantQuestion;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\Models\ApplicantQuestion;

class ApplicantQuestionRepository implements ApplicantQuestionRepositoryInterface
{
    public function onSubmitCheck(array $data) 
    {
        $answer = $data[0]['answer'];
        return $answer;
    }

    private function validateAnswers(array $data)
    {
        try { 
            $validator = Validator::make($data, 
                [
                    'applicant_id' => 'required', 
                    'question_id' => 'required',
                    'answer' => 'required',
                ]
            );

            
            if($validator->fails())
            {
                $error_message = $validator->errors()->all();
                throw new ValidatorFailedException($error_message[0], $validator->errors());
            }

           return $validator->validated(); 
        } catch (Exception $e) {
            return response()->pass($e->getMessage());
        }
    }
}