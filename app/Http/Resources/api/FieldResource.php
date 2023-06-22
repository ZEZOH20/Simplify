<?php

namespace App\Http\Resources\api;

use App\Http\Resources\StudentFieldResource;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // {start} make a progress function to measure the field progress

        // $finshed_courses=$this->course->
        // $progress=($this->course->count());

        // {end}
        $progress = $this->automatedCheckProgress();
        return [
            'name' => $this->name,
            'description' => $this->description,
            'img' => $this->img,
            'pivot' => $this->whenPivotLoaded('field_student', $this->pivot),
            'courses_related_count' => $this->course->count(),
            'progress' => $progress
        ];
    }
    public function automatedCheckProgress()
    {
        // student fields 
        $field = $this;
        // getting student id
        $student_Id = auth()->user()->student->id;

        // joining table between course_field and course student table to get status of every course
        $join_table = DB::table('course_field')
            ->join('course_student', 'course_field.course_code', '=', 'course_student.course_code')->select('course_student.*', 'course_field.*');
        // get field courses
        $courses = $field->course;
        // dd($courses);
        //check if the field has courses or not
        //   if ($courses->count() == 0) {
        //      continue;
        //   }
        // field courses registered by this student
        $join_courses = $join_table->where(['student_id' => $student_Id, 'field_name' => $field->name]);
        // get number of finished courses in this field by this student
        $finished_courses = $join_courses->where(['status' => 'finished']);
        //   dd($finished_courses->count());
        $finished_courses_count = $finished_courses->count();
        $progress = (($finished_courses_count) / ($courses->count())) * 100;
        return $progress;
    }
}