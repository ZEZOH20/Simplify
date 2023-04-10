<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      // 2

      Course::factory(3)->create();
      $courses = Course::all();

      // students already created from StudentSeeder
     Student::all()->each(function($student) use ($courses){
        $student->course()->saveMany($courses);
     }); 
      
    }
}
