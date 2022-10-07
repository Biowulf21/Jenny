<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; 

class ApplicantQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {        
        $applicant_ids = [];
        $applicants = User::where('role', 'applicant')->get();
        foreach($applicants as $applicant) {
            $applicant_ids[] = $applicant['id'];
        }

        return [
            'applicant_id' => $this->faker->numberBetween(reset($applicant_ids), end($applicant_ids)),
            'question_id' => $this->faker->numberBetween(1, 5),
            'answer' => $this->faker->words(rand(1, 5), true), 
            'isCorrect' => $this->faker->boolean(),
        ];
    }
}
