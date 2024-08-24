<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\QuestionCollection;
use App\Models\Question;
use Illuminate\Support\Facades\Cache;

class QuestionController extends Controller
{
    public function getQuestions(): QuestionCollection
    {
        return Cache::remember('questions', 60, function () {
            $questions = Question::with('options')->get();
            return new QuestionCollection($questions);
        });
    }
}
