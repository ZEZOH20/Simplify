<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory;

    protected $primaryKey = 'name';
    protected $keyType = 'string';
    public $incrementing = false;

   protected $fillable =[
    'name',
    'description',
    'sub_field_name'
   ];
   
   public function student(){
    return $this->belongsToMany(Student::class,'field_student','field_name','student_id')->withPivot(
        'progress',
        'active',
        'panding',
    )->withTimestamps();
   }
   public function course(){
    return $this->belongsToMany(Course::class)->withTimestamps();
   }

   // field has many sub fields
   public function sub_fields(){
    return $this->hasMany(Field::class,'sub_field_name');
   }
   // field has many sub fields
   public function field_related_to(){
    return $this->belongsTo(Field::class,'sub_field_name');
   }
}
