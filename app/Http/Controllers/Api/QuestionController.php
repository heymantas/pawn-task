<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\QuestionCollection;
use App\Models\Question;

class QuestionController extends Controller
{
    public function getQuestions(): QuestionCollection
    {
        $questions = Question::with('options')->get();
        return new QuestionCollection($questions);
    }
}
