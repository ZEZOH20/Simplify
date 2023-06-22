<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Foundation\Console\RouteCacheCommand;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    private $courses_rating = [
        /*'Web Development' 1
        ,'Mobile Development' 2
        ,'Cloud Engineering' 3
        ,'Design' 4
        ,'Network' 5
        ,'Security', 6
        'Embeded Systems', 7
        'Artificial intelligence', 8
        'Software Testing',  9
        'Programming', 10
        'Data Science', 11
        'Game Programming', 12
        'Database', 13
        'Business Intelligence', 14*/
                    //1  2  3  4  5  6  7  8  9  10 11 12 13 14
        '4170101' => [1, 0, 0, 0, 1, 3, 2, 3, 1, 2, 2, 2, 0, 0],
        '4170102' => [1, 0, 0, 0, 1, 3, 2, 3, 1, 2, 2, 2, 0, 0],
        '4170103' => [0, 0, 0, 0, 2, 2, 1, 0, 0, 2, 0, 0, 0, 0],
        '4170104' => [0, 0, 0, 0, 0, 2, 2, 2, 2, 2, 0, 0, 0, 0],
        '4170105' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0],
        '4170106' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0],
        '4170201' => [1, 0, 0, 0, 1, 3, 2, 4, 1, 2, 2, 2, 0, 0],
        '4170202' => [1, 0, 0, 0, 1, 3, 2, 4, 1, 2, 2, 2, 0, 0],
        '4170107' => [0, 0, 0, 0, 0, 1, 2, 2, 2, 2, 2, 0, 0, 0],
        '4170301' => [0, 0, 0, 0, 1, 2, 0, 2, 1, 2, 2, 3, 1, 0],
        '4170302' => [0, 0, 0, 0, 1, 2, 0, 2, 1, 2, 2, 3, 1, 0],

        //1  2  3  4  5  6  7  8  9  10 11 12 13 14
        '4170108' => [1, 2, 1, 0, 0, 1, 1, 2, 2, 5, 1, 2, 1, 0],
        '4170203' => [1, 2, 1, 0, 0, 1, 1, 2, 2, 5, 1, 2, 1, 0],
        '4170204' => [0, 0, 1, 0, 1, 0, 0, 2, 0, 1, 3, 0, 5, 0],
        '4170205' => [1, 2, 0, 0, 0, 0, 0, 0, 0, 3, 0, 5, 0, 0],
        '4170206' => [1, 2, 1, 0, 0, 1, 1, 2, 2, 5, 1, 2, 1, 0],
        '4170207' => [0, 0, 0, 1, 0, 0, 0, 0, 3, 1, 0, 0, 0, 1],
        '4170208' => [3, 0, 0, 2, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1],
        '4170303' => [0, 0, 1, 0, 0, 0, 4, 0, 0, 3, 0, 0, 0, 1],
        '4170304' => [0, 0, 0, 1, 0, 0, 0, 0, 3, 1, 0, 0, 0, 1],
        '4170305' => [1, 1, 0, 0, 0, 0, 0, 0, 0, 1, 2, 1, 5, 0],
        '4170306' => [0, 1, 0, 1, 0, 0, 0, 4, 0, 4, 1, 5, 1, 0],
        '4170307' => [2, 2, 1, 0, 0, 2, 2, 2, 1, 3, 2, 3, 1, 0],
        '4170308' => [1, 1, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1],
        '4170401' => [0, 0, 2, 0, 0, 0, 2, 2, 0, 1, 1, 2, 0, 0],
        '4170402' => [0, 0, 0, 2, 0, 0, 0, 1, 0, 3, 0, 5, 0, 1],
        '4170403' => [5, 0, 0, 3, 0, 0, 0, 0, 0, 3, 0, 0, 2, 1],
        '4170404' => [0, 5, 0, 3, 0, 0, 1, 0, 1, 4, 0, 0, 2, 1],
        '4170405' => [3, 0, 0, 5, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1],
        '4170409' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        '4170410' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],

        //1  2  3  4  5  6  7  8  9  10 11 12 13 14
        '4170210' => [0, 0, 0, 2, 0, 0, 0, 0, 5, 1, 0, 0, 0, 2],
        '4170211' => [0, 0, 0, 0, 1, 1, 1, 2, 0, 1, 0, 0, 0, 0],
        '4170212' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4],
        '4170213' => [0, 0, 0, 4, 0, 0, 1, 1, 0, 2, 0, 1, 0, 0],
        '4170214' => [0, 0, 0, 2, 1, 0, 3, 1, 0, 0, 0, 1, 0, 0],
        '4170309' => [1, 1, 0, 4, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0],
        '4170310' => [0, 0, 0, 0, 0, 0, 3, 3, 0, 1, 0, 0, 0, 0],
        '4170311' => [1, 1, 0, 4, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0],
        '4170312' => [0, 0, 0, 0, 0, 0, 4, 4, 0, 3, 2, 0, 2, 0],
        '4170313' => [0, 0, 0, 0, 0, 0, 2, 2, 0, 1, 0, 0, 0, 0],
        '4170315' => [5, 0, 0, 2, 1, 1, 0, 0, 0, 2, 0, 0, 2, 0],
        '4170316' => [0, 0, 0, 0, 0, 0, 4, 4, 0, 3, 0, 1, 1, 0],
        '4170317' => [0, 0, 1, 0, 5, 3, 1, 0, 0, 1, 0, 0, 0, 0],
        '4170318' => [1, 1, 1, 0, 4, 5, 0, 1, 0, 2, 0, 0, 0, 0],
        '4170319' => [0, 0, 1, 0, 0, 0, 2, 3, 0, 2, 5, 0, 3, 0],
        '4170320' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5],
        '4170321' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5],
        '4170406' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5],
        '4170407' => [1, 1, 5, 0, 1, 0, 1, 1, 1, 0, 0, 0, 1, 0],
        '4170408' => [0, 0, 0, 0, 1, 0, 2, 2, 2, 3, 5, 1, 3, 0],
        '4170411' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5],
        '4170412' => [0, 0, 1, 0, 0, 0, 2, 2, 0, 2, 3, 0, 5, 0],
        '4170413' => [0, 0, 0, 0, 0, 0, 2, 0, 2, 3, 1, 0, 2, 0],
        '4170414' => [2, 2, 1, 0, 4, 5, 0, 0, 0, 2, 0, 0, 1, 0],

    ];

    // an array that contains the order of the columns in the courses_rating[]
    private $field_names = [
        'Web Development',
        'Mobile Development',
        'Cloud Engineering',
        'Design',
        'Network',
        'Security',
        'Embeded Systems',
        'Artificial intelligence',
        'Software Testing',
        'Programming',
        'Data Science',
        'Game Programming',
        'Database',
        'Business Intelligence',

    ];
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
                    else {
                        $rec_row->update([$this->field_names[$i] => 0]);
                    }
                    $i++;
                }
                // updating data
                // dd($rec_row);
                $rec_row->update(['score' => $super_score]);
            }
        }
    }
}
