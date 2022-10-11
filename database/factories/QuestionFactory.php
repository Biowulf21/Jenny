<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Exam;

class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $examID = Exam::inRandomOrder()->first()->id;
        
        return [
            'exam_id' => $examID,
            'type' => $this->faker->randomElement(['radio', 'single', 'paragraph']),
            'problem' => $this->faker->paragraph(),
            'options' => [
                'A' => $this->faker->paragraph, 
                'B' => $this->faker->paragraph, 
                'C' => $this->faker->paragraph,
                'D' => $this->faker->paragraph
            ],
            'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
        ];
    }
}
