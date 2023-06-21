<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    //
    public function start()
    {
        $student=auth()->user()->student;
        // get all courses codes
        $courses=collect(DB::table('courses')->pluck('course_code')->all());
        foreach($courses as $course )
        {
            dd($course);
        }
    }
}
