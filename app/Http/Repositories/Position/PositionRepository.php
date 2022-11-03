<?php

namespace App\Http\Repositories\Position;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\ValidatorFailedException;
use App\Models\Position; 
use App\Models\User;
use App\Models\Exam;

class PositionRepository implements PositionRepositoryInterface
{
    public function getAllPositions()
    {
          $positions = Position::all();               
          return response()->pass('Successfully fetched all positions', $positions);
    }

    public function getSinglePosition(int $id)
    {
          $position = Position::findOrFail($id);
          return response()->pass('Successfully fetched position', $position);
    }

    public function createPosition(array $data)
    {
          $validated = $this->validatePosition($data);
          $position = Position::create($validated);                    
          return response()->pass('Successfully created position', $position);
    }

    public function editPosition(array $data, int $id)
    {
          $validated = $this->validatePosition($data);
          Position::where('id', $id)->update($validated);
          $position = Position::findOrFail($id);                    
          return response()->pass('Successfully edited position', $position);
    }

    public function deletePosition(int $id)
    {
          $applicantsWith = User::where('for_position', $id)->count(); 
          $examsWith = Exam::where('for_position', $id)->count();

          if ($applicantsWith + $examsWith === 0) {
               Position::findOrFail($id)->delete();
               return response()->pass('Successfully deleted position', []);
          } else {
               return response()->json([
                    'message' => 'Unable to delete position because an applicant or exam is using this reference',
                    'data' => null,
                    ], 502);
          }
    }

    private function validatePosition(array $data)
    {
          $validator = Validator::make($data, [
               'name' => 'required|string|unique:positions,name'
          ], 
          );

          if($validator->fails()) {
               $error_message = $validator->errors()->all();
               throw new ValidatorFailedException($error_message[0], $validator->errors());
          }

          return $validator->validated();
    }

}