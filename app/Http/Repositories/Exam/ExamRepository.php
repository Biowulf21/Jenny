<?php

namespace App\Http\Repositories\Exam;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\ValidatorFailedException;
use App\Models\Exam;
use App\Models\Position;
use App\Models\User;
use App\Models\Question;
use App\Models\ApplicantQuestion;

class ExamRepository implements ExamRepositoryInterface
{

   public function createExam(array $data)
   {
     try {        
          $validated = $this->validateExam($data);
          $exam = Exam::create($validated);

          return response()->pass('Successfully created exam', $exam);
     } catch (Exception $e) {
          return response()->pass($e->getMessage());
     }
       
   }

   public function deleteExam(int $id)
   {
     try {
          Exam::findOrFail($id)->delete();

          return response()->pass('Successfully deleted exam');
     } catch (Exception $e) {
          return response()->pass($e->getMessage());
     }

   }

   public function editExam(array $data, int $id)
   {
     try {        
          $validated = $this->validateExam($data);
          Exam::where('id', $id)->update($validated);
          $exam = Exam::find($id);

          return response()->pass('Successfully edited exam', $exam);
     } catch (Exception $e) {
          return response()->pass($e->getMessage());
     }
       
   }

   public function showAllExams()
   {
     try { 
          $exams = Exam::orderBy('created_at', 'asc')->paginate(10);
          $message = (count($exams) !== 0) ? "Successfully fetched all exams" : "There is no existing exam";

          return response()->pass($message, $exams);
     } catch (Exception $e) {
          return response()->pass($e->getMessage());
     }

   }

   public function showSingleExam(int $id)
   {
     try { 
          $exam = Exam::where('id', $id)->firstOrFail();
          return response()->pass('Successfully fetched exam ID ' . $id, $exam);
     } catch (Exception $e) {
          return response()->pass($e->getMessage());     
     }

   }

   private function validateExam(array $data)
   {
     $validator = Validator::make($data, 
          [
               'name' => 'required|string', 
               'description' => 'nullable|string',
               'for_position' => 'required'
          ]
     );

     if($validator->fails())
     {
          $error_message = $validator->errors()->all();
          throw new ValidatorFailedException($error_message[0], $validator->errors());
     }

     return $validator->validated();
   }
   
   public function showApplicantExams()
   {
     try { 
          $user = Auth::user();
          $exams = Exam::where('for_position', $user->for_position)->paginate(10);
          $exams = $this->ifTaken($exams, $user->id);
          $message = ($exams->total() !== 0) ? "Successfully fetched all exams for applicant position" : "There is no existing exam";

          return response()->pass($message, $exams);
     } catch (Exception $e) {
          return response()->pass($e->getMessage());     
     }

   }

   public function showSingleApplicantExam(int $id)
   {
     try { 
          $user = Auth::user();
          $exam = Exam::findOrFail($id);

          if ($user->for_position === $exam->for_position) {
               $exam = $this->ifTaken(Exam::where('id', $id)->get(), $user->id);
               return response()->pass('Successfully fetched specific exam for applicant', $exam);
          } else {
               return response()->json([
                    'message' => 'Forbidden: Applicant is not permitted to view this specific exam',
                    'data' => [],
               ], 401);
          }

     } catch (Exception $e) {
          return response()->pass($e->getMessage());   
     }
   }

   public function ifTaken($exams, $userID)
   {
      foreach($exams as $exam)
      {
          $question = Question::where('exam_id', $exam->id)->first();
          log::info($question);
          if(!$question) { 
               $exam->setAttribute('hasTaken', false);
               break;
          }

          $examRecord = ApplicantQuestion::where([
               ['applicant_id', $userID],
               ['question_id', $question->id],
          ])->first();
          log::info($examRecord);
          (!$examRecord) ? $exam->setAttribute('hasTaken', false) : $exam->setAttribute('hasTaken', true);
      }

      return $exams;
   }

}