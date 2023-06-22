<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentRegisteredCoursesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
            $default_resources=[
                'course_code'=> $this->course_code,
                'name' => $this->name ,
                'credit_hours' =>$this->credit_hours ,
                'brief_info'=>$this->brief_info ,
                "type"=>$this->course_type,
                "img"=>$this->img,
                'pivot'=> new CourseStudentPivotResource($this->pivot),
                'material'=>$this->material,
        ];
        return $default_resources;
    }
}
