<?php

namespace App\Http\Repositories\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface {
    public function getAllUsers();
    public function authenticateUser(array $data);
}