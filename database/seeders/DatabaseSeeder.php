<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; 
use App\Models\Exam;
use App\Models\Question;
use App\Models\Position;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Position::factory(5)->create();
        User::factory(10)->create();        
        Exam::factory(10)->create();
        Question::factory(25)->create();
    }
}
