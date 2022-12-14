<?php

namespace App\Http\Repositories\ApplicantQuestion;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\Exceptions\ValidatorFailedException;
use App\Models\ApplicantQuestion;
use App\Models\Question;
use App\Models\Exam;
use App\Models\User;

class ApplicantQuestionRepository implements ApplicantQuestionRepositoryInterface
{

    public function getParagraphQuestions(int $applicantID, int $examID)
    {
        Exam::findOrFail($examID);
        User::where([
            ['id', $applicantID],
            ['role', 'applicant']
        ])->firstOrFail();
        
        $data = []; 

        $paragraphQuestions = Question::where([
            ['exam_id', $examID],
            ['type', 'paragraph']
        ])->get();
        if ($paragraphQuestions->isEmpty()) {
            return response()->json([
                'message' => 'Exam does not have a paragraph-type question',
                'data' => [],
            ], 502);
        }

        $data['questions'] = $paragraphQuestions;

        $answers = [];
        foreach($paragraphQuestions as $paragraphQuestion) {
            $answer = ApplicantQuestion::where([
                ['applicant_id', $applicantID],
                ['question_id', $paragraphQuestion->id],
                ['isChecked', 0]
            ])->first();

            if(!$answer) {
                continue;
            } 

            $answers[] = $answer;
        }

        if(!$answers) {
            $message = "No answers to questions fetched"; 
            $data = [];
        } else {
            $message = "Successfully retrieved questions with type paragraph";
            $data['answers'] = $answers;
        }        

        return response()->pass($message, $data);
    }

    public function fetchExamResults(int $applicantID, int $examID)
    {
        $exam = Exam::findOrFail($examID);
        $applicant = User::where([
            ['id', $applicantID],
            ['role', 'applicant'],
        ])->firstOrFail();

        if($applicant->for_position !== $exam->for_position) {
            return response()->json([
                'message' => 'This exam is not for applicant position',
                'data' => [],
            ], 502);
        }

        $questions = Question::where('exam_id', $examID)->get();
        if ($questions->isEmpty()) {
            return response()->json([
                'message' => 'This exam does not have questions',
                'data' => [],
            ], 502);
        }

        $results = $this->calculateExamResults($questions, $applicantID);
        $message = ($results['total'] === 0) ? 'Applicant has not finished the exam, partial score is as follows' : 'Successfully calculated exam results';

        return response()->pass($message, $results);
    }

    public function calculateExamResults($questions, $applicantID)
    {
        try {
             /** Keep all pieces of code under this comment for future use, in case there is a change of mind **/

            /* Code for: having accepted single answer data at a time */ 
            $records = [];
            $results = [];
            $score = $checked = $unchecked = $count = 0;            

            foreach ($questions as $question)
            {
                $record = ApplicantQuestion::where([ 
                    ['applicant_id', $applicantID], 
                    ['question_id', $question->id] 
                ])->first();
                
                if(!$record) {
                    $results['records'] = $records;
                    $results['score'] = $score;
                    $results['checked'] = $checked;
                    $results['unchecked'] = $unchecked;
                    $results['total'] = 0;

                    return $results;
                }

                $record->setAttribute('question_type', $question->type);
                ($record->question_type === 'radio') ? $record->setAttribute('question_options', $question->options) : " ";
                $record->setAttribute('question_problem', $question->problem);
                $record->setAttribute('question_key', $question->answer);
                $records[] = $record;

                if($record->isChecked) {
                    ($record->isCorrect) ? $score++ : $score;
                    $checked++;
                } else {
                    $unchecked++;
                }
            }

            $results['records'] = $records;
            $results['score'] = $score;
            $results['checked'] = $checked;
            $results['unchecked'] = $unchecked;
            $results['total'] = $checked + $unchecked; 

            return $results;

            /* Code for: having accepted bulk answer data at a time */ 
            // $results = [];
            // $score = $checked = $unchecked = $count = 0;

            // $examExists = Exam::where('id', $examID)->first();
            // if(!$examExists)
            // {
            //     return response()->json([
            //         'message' => 'This exam does not exists',
            //         'data' => [],
            //     ], 502);
            // }

            // $questions = Question::where('exam_id', $examID)->get();
            // if ($questions->isEmpty())
            // {
            //     return response()->json([
            //         'message' => 'This exam does not have questions',
            //         'data' => [],
            //     ], 502);
            // }

            // $applicantExam = ApplicantQuestion::where([ 
            //     ['applicant_id', $applicantID], 
            //     ['question_id', $questions[0]->id] 
            //     ])->first();
            // if(!$applicantExam)
            // {
            //     return response()->json([
            //         'message' => 'This applicant has not taken this exam',
            //         'data' => [],
            //     ], 502);
            // }

            // foreach($questions as $question) {
            //     $results[] = ApplicantQuestion::where([ 
            //         ['applicant_id', $applicantID], 
            //         ['question_id', $question->id] 
            //         ])->first();

            //     if ($results[$count]->isChecked)
            //     {
            //         ($results[$count]->isCorrect) ? $score++ : $score;
            //         $checked++;
            //     } else {
            //         $unchecked++;
            //     }

            //     $count++;
            // }
            
            // $results[] = $score; 
            // $results[] = $checked;
            // $results[] = $unchecked;
            // return response()->pass('Successfully retrieved exam results', $results);
        } catch (Exception $e) {
            return $results = [];
        }

    }

    public function adminChecking(array $data)
    {
        foreach($data as $checked) {
            $record = ApplicantQuestion::where([
                ['applicant_id', $checked['applicant_id']],
                ['question_id', $checked['question_id']],
            ])->firstOrFail();
            
            $record->isChecked = $checked['isChecked']; 
            $record->isCorrect = $checked['isCorrect'];
            $record->save();
        }

        return response()->pass('Successfully updated applicant answer data', []);
    }

    public function applicantChecking(array $data) 
    {
        $question = Question::findOrFail($data['question_id']);
        $id = Auth::user()->id;
        $toCreate = [];
        $questionIDs = [];

        $recordExists = ApplicantQuestion::where([
            ['applicant_id', $id],
            ['question_id', $data['question_id']]
        ])->first();
        if($recordExists) {
            return response()->json([
                'message' => 'Applicant has answered this question',
                'data' => [],
            ], 502);
        }

        /** Keep all pieces of code under this comment for future use, in case there is a change of mind **/

        /* Code for: accepting single answer data at a time */
        $toCreate['applicant_id'] = $id; 
        $toCreate['question_id'] = $data['question_id'];
        $toCreate['answer'] = $data['answer'];
        $validated = $this->validateAnswers($toCreate);
        $validated['answer'] = ($validated['answer'] === null) ? " " : $validated['answer'];
        ApplicantQuestion::create($validated);

        $applicant_answer = ApplicantQuestion::where([
            ['applicant_id', $id],
            ['question_id', $question->id]
        ])->first();

        if ($question->type !== 'paragraph') {
            if($applicant_answer->answer === $question->answer) {
                $applicant_answer->isChecked = true;
                $applicant_answer->isCorrect = true;
                $applicant_answer->save();
            } else {
                $applicant_answer->isChecked = true;
                $applicant_answer->isCorrect = false;
                $applicant_answer->save();
            }
        } 

        return response()->pass('Successfully created and recorded applicant answer for question #'. $data['question_id'], []);

        /* Code for: accepting bulk answers data at a time*/
        // foreach($data as $answer)
        // {
        //     $toCreate['applicant_id'] = $id;
        //     $toCreate['question_id'] = $answer['question_id'];
        //     $toCreate['answer'] = $answer['answer'];

        //     $questionIDs[] = $answer['question_id'];

        //     $validated = $this->validateAnswers($toCreate);

        //     ApplicantQuestion::create($validated);
        // }

        // // Checking for correctness 
        // $results = [
        //     'score' => 0, 
        //     'checked' => 0,
        //     'unchecked' => 0,
        // ];
        // foreach($questionIDs as $questionID) 
        // {
        //     $question = Question::findOrFail($questionID);
        //     $applicant_answer = ApplicantQuestion::where([
        //         ['applicant_id', $id], 
        //         ['question_id', $questionID],
        //         ])->first();

        //     if ($question->type !== 'paragraph')
        //     {
        //         if($applicant_answer->answer === $question->answer)
        //         {
        //             $applicant_answer->isChecked = true;
        //             $applicant_answer->isCorrect = true;
        //             $applicant_answer->save();

        //             $results['score'] = $results['score']+1;
        //             $results['checked'] = $results['checked']+1;
        //         } else {
        //             $applicant_answer->isChecked = true;
        //             $applicant_answer->isCorrect = false;
        //             $applicant_answer->save();

        //             $results['checked'] = $results['checked']+1;
        //         }
        //     } else {
        //         $results['unchecked'] = $results['unchecked']+1;
        //     }
        // }

        // return response()->pass('Successfully calculated exam results', $results);
    }

    private function validateAnswers(array $data)
    {
        try { 
            $validator = Validator::make($data, [
                    'applicant_id' => 'required', 
                    'question_id' => 'required',
                    'answer' => 'nullable',
                ]
            );
           
            if($validator->fails()) {
                $error_message = $validator->errors()->all();
                throw new ValidatorFailedException($error_message[0], $validator->errors());
            }

           return $validator->validated(); 
        } catch (Exception $e) {
            return response()->pass($e->getMessage());
        }
    }
}