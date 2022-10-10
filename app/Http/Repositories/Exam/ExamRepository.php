<?php

namespace App\Http\Repositories\Exam;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\ValidatorFailedException;
use App\Models\Exam;

class ExamRepository implements ExamRepositoryInterface
{

   public function createExam(array $data)
   {
     try {
          $validator = Validator::make($data, 
               [
                    'name' => 'required|string|unique:exams,name', 
                    'description' => 'nullable'
               ]
          );

          if($validator->fails())
          {
               $error_message = $validator->errors()->all();
               throw new ValidatorFailedException($error_message[0], $validator->errors());
          }

          $validated = $validator->validated();
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

   public function showAllExams()
   {
     try { 
          $exams = Exam::orderBy('created_at', 'asc')->get();
          $message = (!empty($exam)) ? "Successfully fetched all exams" : "There is no existing exam";

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

}