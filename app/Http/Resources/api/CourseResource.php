<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'courseCode'=> $this->course_code,
            'name' => $this->name ,
            'creditHours' =>$this->credit_hours ,
            'briefInfo'=>$this->brief_info ,
            "type"=>$this->course_type
        ];
    }
}
