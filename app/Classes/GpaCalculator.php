<?php

namespace App\Classes;


use App\Http\Controllers\Crud\StudentController;
use App\Models\Term;

trait GpaCalculator
{

    //********** reference for GPA calculator ************
    //https://www.calculator.net/gpa-calculator.html

    private static $gpa = 0;
    private static $student;
    //GPA will be start if there is at least 1 student registered course 

    public static function calcGPA(string $term)
    {

        self::$student = auth()->user()->student;

        //fetch all student registered courses ( finshed - related to term GPA ) 
        $registerdCourses = self::$student->course()
            ->wherePivot('status', 'finshed')
            ->wherePivot('term', $term)
            ->get();

        //start GpA calculation
        $grade_points = 0;
        $credit = 0;
        foreach ($registerdCourses as $course) {
            $grade_points += $course->pivot->score * $course->credit_hours;
            $credit += $course->credit_hours;
        }

        ($credit != 0) ?
            self::$gpa = $grade_points / $credit : 0;

        // if student terms not exist create terms , else update
        $column = 'gpa_t' . $term;

       
        if (!$term = self::$student->term()->first()) {
            $term = new Term([
                $column  => self::$gpa,
            ]);
        } else {
            $term->update([
                $column  => self::$gpa,
            ]);
        }
        self::$student->term()->save($term);
        // ................................


        $result = [
            'gpa' =>  self::$gpa,
            'cgpa' => self::calcCGPA(),
        ];
        //calcCGPA  after calcGPA called

        return $result;
    }
    /* CGPA will be start if there is at least 1 student registered course term value different 
    and GPA foreach term was caculated */

    private static function calcCGPA()
    {
        //fetch all student registered courses ( finshed ) 
        $max = 0;
        $term_GPA_sum = 0;
        $countHasValue = 0;
        $i = 0;
        //iterate around each student element and select only avaliable term gpa and count them to find average
        foreach (self::$student->term->toArray() as $key => $value) {

            $column = 'gpa_t' . ($i + 1);
            if ($key == $column) {
                if ($value != null) {
                    (($i + 1) > $max) ? $max = $i + 1 : ''; //get max calculeted gpa from db
                    $countHasValue++;
                }
                $term_GPA_sum += $value;
                $i++;
            }
        }
        (new StudentController)->updateStudentLevel(self::$student, $max);  //update student level according to max calculated term gpa 

        //if there is GPA then there isn\'t CGPA
        if ($countHasValue != 0) {
            $cgpa = $term_GPA_sum / $countHasValue;
        } else {
            $cgpa = 0;
        }

        self::$student->cgpa = $cgpa;
        self::$student->save();
        return $cgpa;
    }
}
