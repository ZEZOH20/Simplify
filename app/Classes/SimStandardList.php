<?php

namespace App\Classes;

use App\Models\Student;

trait SimStandardList
{

    public static $scores = [
        "A+" => 4.3,
        "A" => 4,
        "A-" => 3.7,
        "B+" => 3.3,
        "B" => 3,
        "B-" => 2.7,
        "C+" => 2.3,
        "C" => 2,
        "C-" => 1.7,
        "D+" => 1.3,
        "D" => 1,
        "D-" => 0.7,
        "F" => 0,
    ];

// check allowed register term hours for student 
    public function GPA_CGPA_Rules(Student $student, string $term)
    {
        if ($student->cgpa == 0 && $student->gpa . $term == 0) {
            return 17; // save into student max credit for this term
        } elseif ($student->cgpa < 2 && $student->gpa . $term < 2) {
            return 12;
        } elseif ($student->cgpa < 2 && $student->gpa . $term > 2) {
            return 14;
        } elseif ($student->cgpa < 3.5 && $student->gpa . $term > 2) {
            return 17;
        } elseif ($student->cgpa > 3.5) {
            return 19;
        }
    }
}
