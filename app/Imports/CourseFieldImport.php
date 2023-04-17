<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseFieldImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    { 
              
            $course = Course::find($row["course_id"]);
           
            $course->field()->attach($row['field_id'],[
                'course_id'=>$row['course_id'],
                'field_id'=>$row['field_id'],
           ]);
       
       
    }
   
}
