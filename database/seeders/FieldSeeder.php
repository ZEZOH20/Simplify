<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\FieldStudent;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
      Field::factory(3)->create();
      $field = Field::all();

      // students already created from StudentSeeder
     Student::all()->each(function($student) use ($field){
        $student->field()->saveMany($field);
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

    