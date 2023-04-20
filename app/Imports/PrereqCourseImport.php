<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class PrereqCourseImport implements ToModel , WithHeadingRow
{
   
    public function model(array $row)
    {
        // dd($row);
        $course = Course::where('name', $row['name'])->first();
        $course->course_id = $row['course_id']; 
      
        $course->save();
    }
}
