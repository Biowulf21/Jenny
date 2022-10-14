<?php

namespace App\Http\Repositories\ApplicantQuestion;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\Exceptions\ValidatorFailedException;
use App\Models\ApplicantQuestion;
use App\Models\Question;

class ApplicantQuestionRepository implements ApplicantQuestionRepositoryInterface
{
    public function checkOnSubmit(array $data) 
    {
        $id = Auth::user()->id;
        $toCreate = [];
        $questionIDs = [];
        foreach ($data as $answer)
        {
            $toCreate['applicant_id'] = $id;
            $toCreate['question_id'] = $answer['question_id'];
            $toCreate['answer'] = $answer['answer'];

            $questionIDs[] = $answer['question_id'];

            $validated = $this->validateAnswers($toCreate);

            ApplicantQuestion::create($validated);
        }

        // Checking for correctness 
        $results = [
            'score' => 0, 
            'checked' => 0,
            'unchecked' => 0,
        ];
        foreach ($questionIDs as $questionID) 
        {
            $question = Question::find($questionID);
            $applicant_answer = ApplicantQuestion::where([
                ['applicant_id', $id], 
                ['question_id', $questionID],
                ])->first();

            if ($question->type !== 'paragraph')
            {
                if($applicant_answer->answer === $question->answer)
                {
                    $applicant_answer->isChecked = true;
                    $applicant_answer->isCorrect = true;
                    $applicant_answer->save();

                    $results['score'] = $results['score']+1;
                    $results['checked'] = $results['checked']+1;
                } else {
                    $applicant_answer->isChecked = true;
                    $applicant_answer->isCorrect = false;
                    $applicant_answer->save();

                    $results['checked'] = $results['checked']+1;
                }
            } else {
                $results['unchecked'] = $results['unchecked']+1;
            }
        }

        return $results;
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