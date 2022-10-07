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
        $validator = Validator::make($data, 
            [
                'exam_id' => 'required', 
                'type' => 'required|in:radio,single,paragraph',
                'problem' => 'required',
                'options' => 'nullable|array|required_if:type,radio',
                'answer' => 'nullable|required_unless:type,paragraph',
            ]
        );

        if($validator->fails())
        {
            throw new ValidatorFailedException('Failed creating exam', $validator->errors());
        }

        $validated = $validator->validated();

        return Question::create($validated);

    }

    public function deleteQuestion(int $id): void
    {
        Question::findOrFail($id)->delete();
    }

}