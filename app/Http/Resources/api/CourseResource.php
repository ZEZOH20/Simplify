<?php

namespace App\Http\Resources\api;

use App\Http\Resources\AcademicStaffResource;
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
        $user = auth()->user();
        $isAdmin = (Auth::check()&&$user->type == 'admin') ; //check if auth user type admin
        $route_name=$request->Route()->getName();
        $default = [
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
        $recommendtion =  [
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
            'score'=>$user->student->id
        ];
        return ($route_name == 'avaliable.Course')? $recommendtion : $default;
       
    }
}
