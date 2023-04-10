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
        'elec_sim',
        'man_sim',
        'elec_univ',
        'man_univ',
        'level', 
    ];

     // one to one relation between student and user
    public function user(){
        return $this->belongsTo(User::class);  //hasOne
    }

    public function course(){
        return $this->belongsToMany(Course::class)->withPivot(
            'gpa',
            'score',
            'grade_point',
            'year'
        )->withTimestamps();
    }

    public function field(){
        return $this->belongsToMany(Field::class)->withTimestamps();
       }

}
