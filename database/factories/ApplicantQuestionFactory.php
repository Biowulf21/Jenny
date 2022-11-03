<?php

namespace Database\Factories;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; 
use App\Models\Exam;
use App\Models\Question;

class ApplicantQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {   
        $user = User::where('role', 'applicant')->inRandomOrder()->first();

        $exam = Exam::where('for_position', $user->for_position)->inRandomOrder()->first();
        if(!$exam) { 
            $exam = Exam::factory()->state(['for_position' => $user->for_position],)->create();
        } 

        $question = Question::where('exam_id', $exam->id)->inRandomOrder()->first();
        if(!$question) { 
            $question = Question::factory()->state(['exam_id' => $exam->id],)->create();
        }

        $answer = ($question->type === 'paragraph') ? " " : $this->faker->randomElement([$question->answer, 'Answer 1', 'Answer 2']);
        
        return [
            'applicant_id' => $user->id,
            'question_id' => $question->id,
            'answer' => $answer,
        ];
    }
}
