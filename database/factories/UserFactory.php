<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Position;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $role = $this->faker->randomElement(['admin', 'applicant']);
        $positionID = ($role === 'applicant') ? Position::inRandomOrder()->first()->id : null;

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('Password21!'), // password
            'role' => $role,
            'for_position' => $positionID,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => Hash::make('Password21!'), // password
                'role' => 'admin',
                'for_position' => null,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ];
        });
    }

    public function applicant()
    {
        return $this->state(function (array $attributes) {
            $positionID = Position::inRandomOrder()->first()->id;

            return [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => Hash::make('Password21!'), // password
                'role' => 'applicant',
                'for_position' => $positionID,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
