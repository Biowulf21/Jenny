<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Position;

class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(rand(3, 6), true),
            'description' => $this->faker->paragraph(),
            'for_position' => Position::inRandomOrder()->first()->id, 
        ];
    }

    public function forPosition(int $id)
    {
        return [
            'name' => $this->faker->words(rand(3, 6), true),
            'description' => $this->faker->paragraph(),
            'for_position' => $id,
        ];
    }
}
