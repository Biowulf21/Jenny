<?php

namespace App\Http\Repositories\Exam;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\ValidatorFailedException;
use App\Models\Exam;

class ExamRepository implements ExamRepositoryInterface
{

   public function createExam(array $data): Exam
   {
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

        return Exam::create($validated);
   }

   public function deleteExam(int $id): void
   {
        Exam::findOrFail($id)->delete();
   }

   public function showAllExams(): Collection
   {
        return Exam::orderBy('created_at', 'asc')->get();
   }

   public function showSingleExam(int $id): Exam 
   {
        return Exam::where('id', $id)->firstOrFail();
   }

}