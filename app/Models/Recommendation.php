<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable =[
        'student_id',
        'course_code',
        'Web Development',
        'Mobile Development',
        'Cloud Engineering',
        'Design',
        'Network',
        'Security',
        'Embeded Systems',
        'Artificial intelligence',
        'Software Testing',
        'Programming',
        'Data Science',
        'Game Programming',
        'Database',
        'Business Intelligence',
        'score',
       ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    } 
    
}
