<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Repositories\User\UserRepositoryInterface;
use App\Models\User;

class UserController extends Controller
{
    private $repository; 
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index() 
    {
        return $this->repository->getAllUsers();
    }
}
