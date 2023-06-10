<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicStaff extends Model
{
    use HasFactory;

   protected $table = 'academic_staffs';
   protected $fillable = [
    'name',
    'verbose_title',
    'email',
    'phone_number',
    'img',
    'department',
    'degree',
    'title',
   ];

   // student-admin  professor responsible on specific number of courses 
   function makeResponsible(){  
        return $this->belongsToMany(Course::class ,'staff_responsible_on_courses','academic_staff_id','course_code')
        ->withTimestamps();
   } 

}

