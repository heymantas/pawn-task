<?php

namespace App\Services;

use App\Models\QuestionAnswer;
use Illuminate\Contracts\Auth\Authenticatable;

class QuestionAnswerService
{
    /**
     * @param $answers
     * @param Authenticatable|null $user
     * @return void
     */
    public function saveQuestionAnswers($answers, ?Authenticatable $user): void
    {
        foreach ($answers as $answerData) {
            $questionAnswer = new QuestionAnswer();
            $questionAnswer->user_id = $user->id;
            $questionAnswer->question_id = $answerData['question_id'];
            $questionAnswer->answer_text = $answerData['answer_text'];
            $questionAnswer->save();
        }
    }
}
