<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    public function getQuestions(): JsonResponse
    {
        $questions = Question::with('options')->get();
        return response()->json(
            [
                'message' => 'success',
                'questions' => $questions
            ],
        );
    }
}
