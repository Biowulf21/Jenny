<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

use App\Models\Position;
use App\Models\User;
use App\Models\Exam;
use App\Models\Question;
use App\Models\ApplicantQuestion;

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
    public function case_one()
    {
        $score = 0; 
        $questionsArray = [];

        $position = Position::factory()->create(['name' => 'Position']);
        $user = User::factory()->create(['role' => 'applicant', 'for_position' => $position->id]);
        $exam = Exam::factory()->create(['for_position' => $position->id]);
        $questions = Question::factory(5)->create(['exam_id' => $exam->id, 'type' => $this->faker->randomElement(['radio', 'single'])]);
        
        foreach ($questions as $question) {
            ApplicantQuestion::factory()->create(['applicant_id' => $user->id, 'question_id' => $question->id, 'answer' => $question->answer]);
        }

        // checking 
        foreach ($questions as $question) {
            $answer = ApplicantQuestion::where([ ['applicant_id', $user->id], ['question_id', $question->id] ])->first();
            
            if ($question->type !== 'paragraph') 
            {
                if ($answer->answer === $question->answer) 
                {
                    $score++; 
                    $answer->isCorrect = true;
                }
                
                $answer->isChecked = true;
                $answer->save(); 
                log::info($answer);
            }
        }
    }
}
