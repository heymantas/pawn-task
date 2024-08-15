<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genderQuestion = new Question();
        $genderQuestion->question_text = 'Gender';
        $genderQuestion->type = 'single_choice';
        $genderQuestion->save();

        $maleOption = new Option();
        $maleOption->option_text = 'Male';
        $maleOption->question_id = $genderQuestion->id;
        $maleOption->save();

        $femaleOption = new Option();
        $femaleOption->option_text = 'Female';
        $femaleOption->question_id = $genderQuestion->id;
        $femaleOption->save();

        $dobQuestion = new Question();
        $dobQuestion->question_text = 'Date of Birth';
        $dobQuestion->type = 'date';
        $dobQuestion->save();
    }
}
