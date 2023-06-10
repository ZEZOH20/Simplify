<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseStudentPivotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //check if the course is registered or not 
        // if(auth()->user()->student->course()->wherePivot('course_code',$this->course_code)->first()!=null)
        // {
        //     $status=auth()->user()->student->course()->wherePivot('course_code',$this->course_code)->first()->pivot->status;
        // }
        // else{
        //     $status='not_registered';
        // }
        
        
        return [
            'course_code'=>$this->course_code,
            'score'=>$this->score,
            'term'=>$this->term,
            'status'=>$this->status,
            // 'student_status'=>$status,
        ];
    }
}
