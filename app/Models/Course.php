<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'name',
        'course_code',
        'course_type',
        'credit_hours',
        'brief_info' ,
        'course_id'
    ];
// course has many registerded students 
    public function student(){
        return $this->belongsToMany(Student::class)->withPivot(
            'gpa',
            'score',
            'grade_point',
            'year'
        )->withTimestamps();
    }

    // course has many realated fields
    public function field(){
        return $this->belongsToMany(Field::class)->withTimestamps();
       }

// course has many prerequest coursers
    public function prereq(){
        return $this->hasMany(Course::class);
    }
// prerequest course belongs to course 
    public function prereq_related_to(){
        return $this->belongsTo(Course::class,'course_id');
    }      
}
