<?php

namespace App\Http\Repositories\Exam;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\ValidatorFailedException;
use App\Http\Repositories\ApplicantQuestion\ApplicantQuestionRepository;
use App\Models\Exam;
use App\Models\Position;
use App\Models\User;
use App\Models\Question;
use App\Models\ApplicantQuestion;

class ExamRepository implements ExamRepositoryInterface
{
   public function createExam(array $data)
   {
     $validated = $this->validateExam($data);
     $exam = Exam::create($validated);
     return response()->pass('Successfully created exam', $exam);       
   }

   public function deleteExam(int $id)
   {
     Exam::findOrFail($id)->delete();

     $results = []; 
     $deletedApplicantQuestions = 0;
     $deletedQuestions = 0;

     $questions = Question::where('exam_id', $id)->get();
     if(!$questions->isEmpty()) {               
          foreach($questions as $question) {
               $deletedApplicantQuestions += ApplicantQuestion::where('question_id', $question->id)->delete();
          }

          $deletedQuestions = Question::where('exam_id', $id)->delete();
     }
     
     $results['applicant_questions'] = $deletedApplicantQuestions;
     $results['questions'] = $deletedQuestions;
     
     return response()->pass('Successfully deleted exam', $results);
   }

   public function editExam(array $data, int $id)
   {
     $validated = $this->validateExam($data);
     Exam::where('id', $id)->update($validated);

     $exam = Exam::findOrFail($id);
     return response()->pass('Successfully edited exam', $exam);       
   }

   public function getAllExams()
   {
     $exams = Exam::orderBy('created_at', 'asc')->paginate(10);
     $message = (count($exams) !== 0) ? "Successfully fetched all exams" : "There is no existing exam";
     return response()->pass($message, $exams);
   }

   public function showAllExamResults(int $examID)
   {
     try {
          $exam = Exam::findOrFail($examID);
          $applicants = User::where('for_position', $exam->for_position)->get();

          $results = [];
          $count = 0;
          foreach ($applicants as $applicant) {
               $status = $this->ifTaken(Exam::where('id', $exam->id)->get(), $applicant->id);
               if($status[0]->hasTaken === false) 
               {
                    continue;
               }

               $questions = Question::where('exam_id', $examID)->get();

               $aqRepository = new ApplicantQuestionRepository;
               $applicantScore = $aqRepository->calculateExamResults($questions, $applicant->id);
               log::info($applicantScore);
               $results[$count] = [
                    'applicant' => User::find($applicant->id),
                    'score' => $applicantScore['score'],
                    'checked' => $applicantScore['checked'],
                    'unchecked' => $applicantScore['unchecked'],
                    'total' => $applicantScore['total'],
               ];
          }

          $message = (count($results) > 0) ? 'Successfully fetched all results for exam' : 'No applicant has taken this exam';

          return response()->pass($message, $results);
     } catch (Exception $e) {
          return response()->pass($e->getMessage());
     }
   }

   public function getSingleExam(int $id)
   {
     $exam = Exam::findOrFail($id);
     return response()->pass('Successfully fetched exam ID ' . $id, $exam);
   }

   private function validateExam(array $data)
   {
     $validator = Validator::make($data, [
               'name' => 'required|string', 
               'description' => 'nullable|string',
               'for_position' => 'required'
          ]
     );

     if($validator->fails()) {
          $error_message = $validator->errors()->all();
          throw new ValidatorFailedException($error_message[0], $validator->errors());
     }

     return $validator->validated();
   }
   

   public function showApplicantExams()
   {
     $user = Auth::user();
     $exams = Exam::where('for_position', $user->for_position)->paginate(10);
     $exams = $this->ifTaken($exams, $user->id);
     $message = ($exams->total() !== 0) ? "Successfully fetched all exams for applicant position" : "There is no existing exam";
     return response()->pass($message, $exams);
   }

   public function showSingleApplicantExam(int $id)
   {
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
   }

   public function ifTaken($exams, $userID)
   {
      foreach($exams as $exam)
      {
          $question = Question::where('exam_id', $exam->id)->first();
          if(!$question) { 
               $exam->setAttribute('hasTaken', false);
               break;
          }

          $examRecord = ApplicantQuestion::where([
               ['applicant_id', $userID],
               ['question_id', $question->id],
          ])->first();
          (!$examRecord) ? $exam->setAttribute('hasTaken', false) : $exam->setAttribute('hasTaken', true);
      }

      return $exams;
   }

}