<?php

namespace App\Http\Repositories\Position;

use Illuminate\Http\Request;

interface PositionRepositoryInterface {
    public function getAllPositions();
    public function createPosition(array $data);
    public function deletePosition(int $id);
}