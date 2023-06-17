<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;

trait StudentFieldProgress
{

    public static function automatedCheckProgress()
    {
        // student fields 
        $fields = auth()->user()->student->field;
        // getting student id
        $student_Id = auth()->user()->student->id;
        foreach ($fields as $field) {
            // joining table between course_field and course student table to get status of every course
            $join_table = DB::table('course_field')
                ->join('course_student', 'course_field.course_code', '=', 'course_student.course_code')->select('course_student.*', 'course_field.*');
            // get field courses
            $courses = $field->course;
            //check if the field has courses or not
            if ($courses->count() == 0) {
                continue;
            }
            // field courses registered by this student
            $join_courses = $join_table->where(['student_id' => $student_Id, 'field_name' => $field->name]);
            // get number of finished courses in this field by this student
            $finished_courses = $join_courses->where(['status' => 'finished']);
            $finished_courses_count = $finished_courses->count();
            $progress = (($finished_courses_count) / ($courses->count())) * 100;
            $field->pivot->update(['progress' => $progress]);
        }

    }
}
