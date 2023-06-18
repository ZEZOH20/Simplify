<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'course_code';
    public $incrementing = false;

    protected $fillable=[
        'name',
        'course_code',
        'course_type',
        'credit_hours',
        'brief_info' ,
        'prereq_code',
        'material',
        'img'
    ];
// course has many registerded students 
    public function student(){
        return $this->belongsToMany(Student::class,'course_student','course_code','student_id')->withPivot(
            'score',
            'term',
            'status',
        )->withTimestamps();
    }

    // course has many realated fields
    public function field(){
        return $this->belongsToMany(Field::class,'course_field','course_code','field_name')->withTimestamps();
       }

// course has many prerequest coursers
    public function prereq(){
        return $this->hasMany(Course::class,'prereq_code');
    }
// prerequest course belongs to course 
    public function prereq_related_to(){
        return $this->belongsTo(Course::class,'prereq_code');
    }    
    
    // student-admin make professor responsible on specific number of courses 
   function whosResponsible(){
    return $this->belongsToMany(AcademicStaff::class ,'staff_responsible_on_courses','course_code','academic_staff_id')
    ->withTimestamps();
   } 
    
}
