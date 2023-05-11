<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


class GpaCalculatorController extends Controller
{
     //********** reference for GPA calculator ************
    //https://www.calculator.net/gpa-calculator.html

    // private static $grade_point = []; // dynamic array declaration

    private $grade_points;
    private $credit;
    // private  $gpa;

      //GPA will be start if there is at least 1 student registered course 

    public function calcGPA(string $term){

        //fetch all student registered courses ( finshed - related to term GPA ) 
        $registerdCourses = auth()->user()->student->course()
        ->wherePivot('status','finshed')
        ->wherePivot('term',$term)
        ->get();
       
        dd($registerdCourses); 

        foreach ($registerdCourses as $course){
            // array_push(self::$grade_point,[
            //     $course->course_code => $course->pivot->score*$course->credit_hours
            // ]); 
            $this->grade_points+=$course->pivot->score*$course->credit_hours;
            $this->credit+=$course->credit_hours; 
        }

        //calcCGPA  after calcGPA called
       $gpa= $this->calcCGPA($this->grade_points/ $this->credit);
        return $gpa;
        
    //   dd(self::$credit);   
    }
    /* CGPA will be start if there is at least 1 student registered course term value different 
    and GPA foreach term was caculated */

    private function calcCGPA(float $gpa){
        //fetch all student registered courses ( finshed ) 
        return $gpa;
    }

}
