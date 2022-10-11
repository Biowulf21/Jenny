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
          try {   
               $positions = Position::all();
               
               return response()->pass('Successfully fetched all positions', $positions);
          } catch (Exception $e) {
               return response()->pass($e->getMessage());
          }
    }

    public function createPosition(array $data)
    {
          try {   
               $validated = $this->validatePosition($data);
               $position = Position::create($validated);
                    
               return response()->pass('Successfully created position', $position);
          } catch (Exception $e) {
               return response()->pass($e->getMessage());
          }
    }

    public function editPosition(array $data, int $id)
    {
          try {   
               $validated = $this->validatePosition($data);
               Position::where('id', $id)->update($validated);
               $position = Position::find($id);
                    
               return response()->pass('Successfully edited position', $position);
          } catch (Exception $e) {
               return response()->pass($e->getMessage());
          }
    }

    public function deletePosition(int $id)
    {
          try {
               $applicantsWith = User::where('for_position', $id)->count(); 
               $examsWith = Exam::where('for_position', $id)->count();

               if ($applicantsWith + $examsWith === 0)
               {
                    Position::findOrFail($id)->delete();
                    return response()->pass('Successfully deleted position');
               } else {
                    return response()->json([
                         'message' => 'Unable to delete position because an applicant or exam is using this reference',
                         'data' => null,
                     ], 502);
               }
               
          } catch (Exception $e) {
               return response()->pass($e->getMessage());
          }
    }

    private function validatePosition(array $data)
    {
          $validator = Validator::make($data, 
          [
               'name' => 'required|string|unique:positions,name'
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