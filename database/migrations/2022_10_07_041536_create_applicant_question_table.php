<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignID('applicant_id')->constrained('users');
            $table->foreignID('question_id')->constrained();
            $table->string('answer');
            $table->boolean('isCorrect');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_questions');
    }
}
