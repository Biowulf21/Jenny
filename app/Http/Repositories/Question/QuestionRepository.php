<?php

namespace App\Http\Repositories\Question;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\ValidatorFailedException;
use App\Models\Question;
use App\Models\Exam;
use App\Models\ApplicantQuestion;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function createQuestion(array $data)
    {
        Exam::findOrFail($data['exam_id']);

        $validated = $this->validateQuestion($data);
        $question = Question::create($validated);
        return response()->pass('Successfully created question', $question);
    }

    public function editQuestion(array $data, int $id)
    {
        Exam::findOrFail($data['exam_id']);
        $question = Question::findOrFail($id);

        $validator = [];
        $keys = ['exam_id', 'type', 'problem', 'options', 'answer'];
        foreach($keys as $key) {
            (array_key_exists($key, $data)) ? $validator[$key] = $data[$key] : $validator[$key] = NULL;
        }

        $validated = $this->validateQuestion($validator);
        Question::where('id', $id)->update($validated);

        $question = Question::find($id);
        return response()->pass('Successfully edited question ' . $id, $question);       
    }

    public function deleteQuestion(int $id)
    {
        Question::findOrFail($id)->delete();  

        $deletedApplicantQuestions = ApplicantQuestion::where('question_id', $id)->delete();
        return response()->pass('Successfully deleted question', $deletedApplicantQuestions);       
    }

    public function getAllQuestions(int $examID)
    {
        Exam::findOrFail($examID);

        $questions = Question::where('exam_id', $examID)->orderBy('created_at', 'asc')->get();
        $message = (!$questions->isEmpty()) ? "Successfully fetched all questions in exam " . $examID : "There is no existing question in exam " . $examID;
        return response()->pass($message, $questions);
    }
 
    public function getSingleQuestion(int $id)
    {
        $question = Question::findOrFail($id);
        return response()->pass('Successfully fetched question ' . $id, $question);
    }

    private function validateQuestion(array $data)
    {
        $validator = Validator::make($data, [
                'exam_id' => 'required', 
                'type' => 'required|in:radio,single,paragraph',
                'problem' => 'required',
                'options' => 'nullable|prohibited_unless:type,radio|required_if:type,radio|array',
                'answer' => 'nullable|prohibited_if:type,paragraph|required_unless:type,paragraph|string',
            ], 
        );

        if($validator->fails()) {
            $error_message = $validator->errors()->all();
            throw new ValidatorFailedException($error_message[0], $validator->errors());
        }

        return $validator->validated();
    }

}