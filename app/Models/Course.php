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
        'brief_info'
    ];
    public function student(){
        return $this->belongsToMany(Student::class)->withPivot(
            'gpa',
            'score',
            'grade_point',
            'year'
        )->withTimestamps();
    }

    public function field(){
        return $this->belongsToMany(Field::class)->withTimestamps();
       }

    public function prereq(){
        // return $this->hasMany($this);
        return $this->belongsToMany(Course::class, 'course_prereq','prereq_id','course_id');
        // return $this->belongsToMany(Post::class, 'related_posts', 'post_id', 'related_id');
    }
    public function has_prereq(){
        return $this->belongsToMany(Course::class, 'course_prereq','course_id','prereq_id');
    }      
}
