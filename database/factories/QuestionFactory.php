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
        $type = $this->faker->randomElement(['radio', 'single', 'paragraph']);

        $options = null;
        $answer = null;
        if($type === 'radio')
        {
            $options = [
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
            ];

            $answer = $this->faker->randomElement(['A', 'B', 'C', 'D']);
        } elseif ($type === 'single') 
        {
            $answer = 'Answer';
        }

        return [
            'exam_id' => $examID,
            'type' => $type,
            'problem' => $this->faker->paragraph(),
            'options' => $options,
            'answer' => $answer,
        ];
    }
}
