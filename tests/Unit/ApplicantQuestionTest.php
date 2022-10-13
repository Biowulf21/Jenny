<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

use App\Models\Position;
use App\Models\User;
use App\Models\Exam;
use App\Models\Question;

class ApplicantQuestionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

     /**
     * @test
     */
    public function sample_method()
    {
        $position = Position::factory()->create(['name' => 'Position']);
        $user = User::factory()->create(['role' => 'applicant', 'for_position' => $position->id]);
        $exam = Exam::factory()->create(['for_position' => $position->id]);
        $questions = Question::factory(5)->create(['exam_id' => $exam->id]);

        Log::info($position); 
        Log::info($user);
        Log::info($exam);
        Log::info($questions);

    }
}
