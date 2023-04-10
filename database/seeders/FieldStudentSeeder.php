<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::all()->each(function($student){
            $fields = Field::all();
            $student->field()->saveMany($fields);
        });
    }
}
