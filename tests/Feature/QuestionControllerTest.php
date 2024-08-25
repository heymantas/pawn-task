<?php

namespace Tests\Feature;

use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{

    public function test_get_questions_requires_authentication()
    {
        $response = $this->getJson('/api/get-questions');
        $response->assertStatus(401);
    }

    public function test_get_questions_with_authentication()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/get-questions');

        $user->delete();

        $response->assertStatus(200);
    }

    public function test_a_option_belongs_to_a_question()
    {
        $question = Question::where('id', 1)->first();
        $option = Option::where('question_id', $question->id)->first();

        $this->assertInstanceOf(Question::class, $option->question);
    }
}
