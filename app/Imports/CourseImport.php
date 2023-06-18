<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Course([
            //
            'name'=>$row['name'],
            'course_code'=>$row['course_code'],
            'credit_hours'=>$row['credit_hours'],
            'course_type'=>$row['course_type'],
            'brief_info'=>$row['brief_info'],
            'prereq_code'=>$row['prereq_code'],
            'img'=>$row['img'],
        ]);
       
       
    }
}
