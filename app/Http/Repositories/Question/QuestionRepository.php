<?php

namespace App\Http\Repositories\Question;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\ValidatorFailedException;
use App\Models\Question;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function createQuestion(array $data)
    {
        try {
            $validated = $this->validateQuestion($data);
            $question = Question::create($validated);
            return response()->pass('Successfully created question', $question);
        } catch (Exception $e) {
            return response()->pass($e->getMessage);
        }
        
    }

    public function editQuestion(array $data, int $id)
    {
        try {
            $validator = [];
            $keys = ['exam_id', 'type', 'problem', 'options', 'answer'];
            foreach($keys as $key) {
                (array_key_exists($key, $data)) ? $validator[$key] = $data[$key] : $validator[$key] = NULL;
            }
    
            $validated = $this->validateQuestion($validator);
            Question::where('id', $id)->update($validated);
            $question = Question::find($id);
    
            return response()->pass('Successfully edited question ' . $id, $question);

        } catch (Exception $e) {
            return response()->pass($e->getMessage);
        }
       
    }

    public function deleteQuestion(int $id)
    {
        try {
            Question::findOrFail($id)->delete();
  
            return response()->pass('Successfully deleted question');
       } catch (Exception $e) {
            return response()->pass($e->getMessage);
       }
       
    }

    public function showAllQuestions(int $exam_id)
    {
        try { 
            $questions = Question::where('exam_id', $exam_id)->orderBy('created_at', 'asc')->get();

            return response()->pass('Successfully fetched all questions', $questions);
        } catch (Exception $e) {
            return response()->pass($e->getMessage);
       }

    }
 
    public function showSingleQuestion(int $id)
    {
        try { 
            $question = Question::where('id', $id)->firstOrFail();

            return response()->pass('Successfully fetched question ' . $id, $question);
        } catch (Exception $e) {
            log::info($e->getMessage);
            return response()->pass($e->getMessage);
       }

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