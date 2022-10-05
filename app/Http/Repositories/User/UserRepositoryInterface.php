<?php

namespace App\Http\Repositories\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface {
    public function getAllUsers();
    public function authenticateUser(array $data);
    public function createUser(array $data, string $role);
    public function createApplicantUser(array $data);
}