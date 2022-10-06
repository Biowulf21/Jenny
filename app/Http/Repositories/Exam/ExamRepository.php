<?php

namespace App\Http\Repositories\Exam;

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
            throw new ValidatorFailedException('Failed creating exam', $validator->errors());
        }

        $validated = $validator->validated();

        return Exam::create($validated);
   }

   public function deleteExam(int $id): void
   {
        Exam::findOrFail($id)->delete();
   }

}