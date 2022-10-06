<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Http\Repositories\User\UserRepository; 
use App\Http\Repositories\User\UserRepositoryInterface; 
use App\Http\Repositories\Admin\AdminRepository; 
use App\Http\Repositories\Admin\AdminRepositoryInterface; 
use App\Http\Repositories\Applicant\ApplicantRepository; 
use App\Http\Repositories\Applicant\ApplicantRepositoryInterface; 
use App\Http\Repositories\Exam\ExamRepository; 
use App\Http\Repositories\Exam\ExamRepositoryInterface; 
use App\Http\Repositories\Question\QuestionRepository; 
use App\Http\Repositories\Question\QuestionRepositoryInterface; 

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(ApplicantRepositoryInterface::class, ApplicantRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
    }
}
