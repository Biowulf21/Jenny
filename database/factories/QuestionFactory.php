<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Exam;

class QuestionFactory extends Factory
{
    protected $casts = [
        'options' => 'array',
    ];

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
                [   
                    'key' => 'A',
                    'value' => $this->faker->paragraph,
                ], 
                [   
                    'key' => 'B',
                    'value' => $this->faker->paragraph,
                ],
                [   
                    'key' => 'C',
                    'value' => $this->faker->paragraph,
                ],
                [   
                    'key' => 'D',
                    'value' => $this->faker->paragraph,
                ],
            ],
            'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
        ];
    }
}
