<?php

namespace App\Http\Resources\api;

use App\Http\Resources\AcademicStaffResource;
use App\Http\Resources\CourseStudentPivotResource;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isAdmin = (Auth::check()&&auth()->user()->type == 'admin') ; //check if auth user type admin
        
        $default_resources=[
            'course_code'=> $this->course_code,
            'name' => $this->name ,
            'credit_hours' =>$this->credit_hours ,
            'brief_info'=>$this->brief_info ,
            "type"=>$this->course_type,
            "img"=>$this->img,
            'staffs'=> AcademicStaffResource::collection($this->whenLoaded('whosResponsible')),
            'prereq'=> CourseResource::collection($this->whenLoaded('prereq')),
            'status'=>$isAdmin ? $this->status:null,
            'material'=>$this->material,
    ];
        return $default_resources;
        
    }
}
