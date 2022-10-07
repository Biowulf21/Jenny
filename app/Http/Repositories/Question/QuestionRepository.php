<?php

namespace App\Http\Repositories\Question;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\ValidatorFailedException;
use App\Models\Question;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function createQuestion(array $data): Question
    {
        $validated = $this->validateQuestion($data);
        return Question::create($validated);
    }

    public function deleteQuestion(int $id): void
    {
        Question::findOrFail($id)->delete();
    }

    private function validateQuestion(array $data)
    {
        $validator = Validator::make($data, 
            [
                'exam_id' => 'required', 
                'type' => 'required|in:radio,single,paragraph',
                'problem' => 'required',
                'options' => 'nullable|array|prohibited_unless:type,radio|required_if:type,radio',
                'answer' => 'nullable|prohibited_if:type,paragraph|required_unless:type,paragraph',
            ], 
        );

        if($validator->fails())
        {
            $error_message = $validator->errors()->all();
            throw new ValidatorFailedException($error_message[0], $validator->errors());
        }

        return $validator->validated();
    }

}