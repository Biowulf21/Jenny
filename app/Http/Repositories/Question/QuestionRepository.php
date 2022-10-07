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

    public function editQuestion(array $data, int $id): Question
    {
        $validator = [];
        $keys = ['exam_id', 'type', 'problem', 'options', 'answer'];
        foreach($keys as $key) {
            (array_key_exists($key, $data)) ? $validator[$key] = $data[$key] : $validator[$key] = NULL;
        }

        $validated = $this->validateQuestion($validator);

        Question::where('id', $id)->update($validated);
        return Question::find($id);
    }

    public function deleteQuestion(int $id): void
    {
        Question::findOrFail($id)->delete();
    }

    public function showAllQuestions(int $exam_id): Collection
    {
         return Question::where('exam_id', $exam_id)->orderBy('created_at', 'asc')->get();
    }
 
    public function showSingleQuestion(int $id): Question
    {
         return Question::where('id', $id)->firstOrFail();
    }

    private function validateQuestion(array $data)
    {
        $validator = Validator::make($data, 
            [
                'exam_id' => 'required', 
                'type' => 'required|in:radio,single,paragraph',
                'problem' => 'required',
                'options' => 'nullable|prohibited_unless:type,radio|required_if:type,radio|array',
                'answer' => 'nullable|prohibited_if:type,paragraph|required_unless:type,paragraph|string',
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