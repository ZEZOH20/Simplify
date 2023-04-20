<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Field;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $fields =Field::factory(3)->create();
      
      $fields->each(function($field){
          $field->sub_fields()->saveMany(
            Field::factory(2)->create()
          );
      });
      // students already created from StudentSeeder
      //many to many fields  Students
      Student::all()->each(function($student) use ($fields){
          $student->field()->saveMany($fields);
      }); 

      //many to many fields  courses
      Course::all()->each(function($course) use ($fields){
        $course->field()->saveMany($fields);
     }); 
      
    }

}
  // // create factory with (one to one) and ( many to many ) relationship
        // Field::factory()->hasAttached(     // Student::factory(3)->create()
        //     User::factory(3)->create(),
        //     // [
                
        //     //  FieldStudent::factory(4)->create()
        //     // ]
        // )->count(4)->create();

    