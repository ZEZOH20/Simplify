<?php

namespace App\Classes;


use App\Http\Controllers\Crud\StudentController;

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

        //if student didn't registered or finshed any course related to term then there is notheing to calculate
        if ($registerdCourses->isEmpty()) {
            return [
                'gpa' => 0,
                'cgpa' => 0,
            ];
        }
        //start GpA calculation
        $grade_points = 0;
        $credit = 0;
        foreach ($registerdCourses as $course) {
            $grade_points += $course->pivot->score * $course->credit_hours;
            $credit += $course->credit_hours;
        }

        self::$gpa = $grade_points / $credit;

        $column = 'gpa_t' . $term;
        self::$student->$column = self::$gpa;
        self::$student->save();

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
        foreach (self::$student->toArray() as $key => $value) {

            $column = 'gpa_t' . ($i + 1);
            if ($key == $column) {
                if($value != null){
                   (($i + 1) > $max) ? $max = $i+1 : ''; //get max calculeted gpa from db
                    $countHasValue++;
                }
                $term_GPA_sum += $value;
                $i++;
            }
        }
        (new StudentController)->updateStudentLevel(self::$student,$max);  //update student level according to max calculated term gpa 

        //if there is GPA then there isn\'t CGPA
        return ($countHasValue != 0) ?
            $term_GPA_sum / $countHasValue :
            0;
    }

  

}
