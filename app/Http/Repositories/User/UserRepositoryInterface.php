<?php

namespace App\Http\Repositories\User;

interface UserRepositoryInterface {
    public function getAllUsers();
    public function authenticateUser(array $data);
}