<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'gpa_t1',
        'gpa_t2',
        'gpa_t3',
        'gpa_t4',
        'gpa_t5',
        'gpa_t6',
        'gpa_t7',
        'gpa_t8',
    ];

    public function student(){
        $this->belongsTo(Student::class);
    }
}
