<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Field extends Model
{
    use HasFactory;

   protected $fillable =[
    'name',
    'description',
   ];
   
   public function student(){
    return $this->belongsToMany(Student::class)->withTimestamps();
   }
   public function course(){
    return $this->belongsToMany(Course::class)->withTimestamps();
   }
}
