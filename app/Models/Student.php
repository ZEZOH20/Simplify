<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        't_credit',
        'cgpa',
        'gpa_t1',
        'gpa_t2',
        'gpa_t3',
        'gpa_t4',
        'gpa_t5',
        'gpa_t6',
        'gpa_t7',
        'gpa_t8',
        'elec_sim',
        'man_sim',
        'elec_univ',
        'man_univ',
        'level', 
        'collage_id',
    ];

     // one to one relation between student and user
    public function user(){
        return $this->belongsTo(User::class);  //hasOne
    }

    public function course(){
        return $this->belongsToMany(Course::class,'course_student','student_id', 'course_code')->withPivot(
            'score',
            'term',
            'status',
        )->withTimestamps();
    }

    public function field(){
        return $this->belongsToMany(Field::class,'field_student','student_id','field_name')->withPivot(
            'progress',
            'active',
            'panding',
        )->withTimestamps();
       }

}
