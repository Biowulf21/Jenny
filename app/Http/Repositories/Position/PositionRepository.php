<?php

namespace App\Http\Repositories\Position;

use Illuminate\Support\Facades\Log;

use App\Models\Position; 

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

}