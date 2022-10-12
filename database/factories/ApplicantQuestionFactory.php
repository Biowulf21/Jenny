<?php

namespace Database\Factories;

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
        $examID = Exam::where('for_position', $user->for_position)->inRandomOrder()->first()->id;
        $questionID = Question::where('exam_id', $examID)->inRandomOrder()->first()->id;

        return [
            'applicant_id' => $user->id,
            'question_id' => $questionID,
            'answer' => $this->faker->words(rand(1, 5), true), 
            'isCorrect' => $this->faker->boolean(),
        ];
    }
}
