<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

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

    public function login(Request $request)
    {
        return $this->repository->authenticateUser($request->all());       
    }

    public function createApplicant(Request $request)
    {
        return $this->repository->createApplicantUser($request->all());
    }
}
