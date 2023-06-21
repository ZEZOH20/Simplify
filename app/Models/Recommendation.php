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
        'cloud',
        'design',
        'empeded system',
        'mobile',
        'network',
        'security',
        'web',
        'score',
       ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    } 
    
}
