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

class ApplicantQuestionRepository implements ApplicantQuestionRepositoryInterface
{

    public function getParagraphQuestions(int $applicantID, int $examID)
    {
        try {
            $data = []; 

            $paragraphQuestions = Question::where([
                ['exam_id', $examID],
                ['type', 'paragraph']
            ])->get();
            if ($paragraphQuestions->isEmpty())
            {
                return response()->json([
                    'message' => 'Exam does not have a paragraph-type question',
                    'data' => [],
                ], 502);
            }

            $data['questions'] = $paragraphQuestions;

            $answers = [];
            foreach($paragraphQuestions as $paragraphQuestion) {
                $answers[] = ApplicantQuestion::where([
                        ['applicant_id', $applicantID],
                        ['question_id', $paragraphQuestion->id],
                ])->first();
            }
            $data['answers'] = $answers;

            return response()->pass('Successfully retrieved questions with type paragraph', $data);
        } catch (Exception $e) {
            return response()->pass($e->getMessage());
        } 
    }

    public function getExamResults(int $applicantID, int $examID)
    {
        try {
             /** Keep all pieces of code under this comment for future use, in case there is a change of mind **/

            /* Code for: having accepted single answer data at a time */ 
            $results = [];
            $score = $checked = $unchecked = $count = 0;

            $examExists = Exam::where('id', $examID)->first();
            if(!$examExists)
            {
                return response()->json([
                    'message' => 'This exam does not exists',
                    'data' => [],
                ], 502);
            }

            $questions = Question::where('exam_id', $examID)->get();
            if ($questions->isEmpty())
            {
                return response()->json([
                    'message' => 'This exam does not have questions',
                    'data' => [],
                ], 502);
            }

            foreach ($questions as $question)
            {
                $record = ApplicantQuestion::where([ 
                    ['applicant_id', $applicantID], 
                    ['question_id', $question->id] 
                ])->first();
                $results[] = $record;

                if(!$record)
                {
                    $results[] = $score;
                    $results[] = $checked;
                    $results[] = $unchecked;

                    return response()->pass('User has not completed this exam, partial score is as follows', $results);
                }

                if($record->isChcecked)
                {
                    ($record->isCorrent) ? $score++ : $score;
                    $checked++;
                } else {
                    $unchecked++;
                }
            }

            $results[] = $score;
            $results[] = $checked;
            $results[] = $unchecked;
            $results[] = $checked + $unchecked; //total number of items

            return response()->pass('Successfully calculated user exam results', $results);

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
            return response()->pass($e->getMessage());
        }

    }

    public function adminChecking(array $data)
    {
        try { 
            foreach($data as $checked) {
                ApplicantQuestion::where([
                    ['applicant_id', $checked->applicant_id],
                    ['question_id', $checked->question_id],
                ])->update([
                    'isChecked' => $checked->isChecked,
                    'isCorrect' => $checked->isCorrect,
                ]);
            }

            return response()->pass('Successfully updated applicant answer data', []);
        } catch (Exception $e) {
            return response()->pass($e->getMessage());
        }
    }

    public function applicantChecking(array $data) 
    {
        $id = Auth::user()->id;
        $toCreate = [];
        $questionIDs = [];

        /** Keep all pieces of code under this comment for future use, in case there is a change of mind **/

        /* Code for: accepting single answer data at a time */
        $toCreate['applicant_id'] = $id; 
        $toCreate['question_id'] = $data['question_id'];
        $toCreate['answer'] = $data['answer'];
        $validated = $this->validateAnswers($toCreate);
        ApplicantQuestion::create($validated);

        $question = Question::findOrFail($data['question_id']);
        $applicant_answer = ApplicantQuestion::where([
            ['applicant_id', $id],
            ['question_id', $question->id]
        ])->first();

        if ($question->type !== 'paragraph')
        {
            if($applicant_answer->answer === $question->answer)
            {
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
            $validator = Validator::make($data, 
                [
                    'applicant_id' => 'required', 
                    'question_id' => 'required',
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