<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Repositories\Exam\ExamRepositoryInterface; 

class ExamController extends Controller
{
    private $repisitory; 
    public function __construct(ExamRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->showAllExams();
    }

    public function getApplicantExams()
    {
        return $this->repository->showApplicantExams();
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        return $this->repository->createExam($request->all());
    }

    public function show($id)
    {
        return $this->repository->showSingleExam($id);
    }

    public function getSingleApplicantExam($id)
    {
        return $this->repository->showSingleApplicantExam($id);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        return $this->repository->editExam($request->all(), $id);
    }

    public function destroy($id)
    {
        return $this->repository->deleteExam($id);
    }
    
}
