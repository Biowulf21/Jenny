<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'exam_id' => $this->faker->numberBetween(1, 3),     // Change the values here according to how many exams there is to be made in the seeder
            'type' => $this->faker->randomElement(['radio', 'single', 'paragraph']),
            'problem' => $this->faker->paragraph(),
            'options' => [
                'A' => $this->faker->paragraph, 
                'B' => $this->faker->paragraph, 
                'C' => $this->faker->paragraph,
            ],
            'answer' => $this->faker->randomElement(['A', 'B', 'C']),
        ];
    }
}
