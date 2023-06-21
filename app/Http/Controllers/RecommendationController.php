<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Foundation\Console\RouteCacheCommand;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    //
    private $courses_rating = [
        //  cloud , design , embb , mobile , network , security , web  -->>    order of the columns
        '04170102' => [2, 1, 1, 0, 0, 1, 1],
        '04170103' => [0, 1, 0, 0, 1, 1, 0],
        '04170104' => [0, 1, 0, 0, 0, 1, 1],
        '04170105' => [1, 1, 0, 1, 1, 0, 0],
        '04170106' => [1, 1, 0, 1, 1, 0, 0],
        '04170201' => [1, 1, 0, 1, 1, 0, 0],
        '04170202' => [1, 1, 0, 1, 1, 0, 0],
        '04170107' => [1, 1, 0, 1, 1, 0, 0],
        '04170301' => [1, 1, 0, 1, 1, 0, 0],
        '04170302' => [1, 1, 0, 1, 1, 0, 0],
        '04170101' => [1, 1, 0, 1, 1, 0, 0],
    ];
    // an array that contains the order of the columns in the courses_rating[]
    private $field_names = ['cloud', 'design', 'embb', 'mobile', 'network', 'security', 'web'];
    public function start()
    {
        $student = auth()->user()->student;

        // iterate over each course code in courses_rating[]
        foreach ($this->courses_rating as $index => $value_array) {
            // iterator that iterates over $field_names[] to access the arrangement
            $i = 0;
            // super score is the variable that have the summation of ratings for each course
            $super_score = 0;
            // it's the model on which the filling or the update of the data will occur on, in the recommendation table
            $rec_row = new Recommendation;
            // the current row in the courses loop
            $current_Rec = Recommendation::where(['student_id' => $student->id, 'course_code' => $index]);
            // check if the current model already exist or not in the recommendation db
            if (!$current_Rec->exists()) {
                // (first update on the model)
                $rec_row->fill(['course_code' => $index, 'student_id' => $student->id]);
                // iterate over the values of course rating in the course_rating[]
                foreach ($value_array as $value) {
                    // check if the student added this field or not in the student_field db
                    if ($student->field()->where('field_name', $this->field_names[$i])->exists()) {
                        // get score from student_field db (student's rating for this field) 
                        $score = $student->field()->where('field_name', $this->field_names[$i])->first()->pivot->score;
                        // final score for every field in the course by multiplying field score in student field db and it's value in courses_rating[]
                        $final_score = $score * $value;
                        $super_score += $final_score;
                        // (second update on the model)
                        $rec_row->fill([$this->field_names[$i] => $final_score]);
                    }
                    $i++;
                }
                // (last update on the model)
                $rec_row->fill(['score' => $super_score]);
                $rec_row->save();
                // if the current model already exist update it rather than create it
            } else {
                foreach ($value_array as $value) {
                    $rec_row = $current_Rec->first();
                    if ($student->field()->where('field_name', $this->field_names[$i])->exists()) {

                        $score = $student->field()->where('field_name', $this->field_names[$i])->first()->pivot->score;
                        $final_score = $score * $value;
                        $super_score += $final_score;
                        // updating data
                        $rec_row->update([$this->field_names[$i] => $final_score]);
                    }
                    // in case field was removed it's ratings become zeroes
                    else
                    {
                        $rec_row->update([$this->field_names[$i] => 0]);
                    }
                    $i++;
                }
                // updating data
                $rec_row->update(['score' => $super_score]);
            }


        }
    }

}