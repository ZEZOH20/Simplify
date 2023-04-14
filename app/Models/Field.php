<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

   // field has many sub fields
   public function sub_fields(){
    return $this->hasMany(Field::class);
   }
   // field has many sub fields
   public function field_related_to(){
    return $this->belongsTo(Field::class,'field_id');
   }
}
