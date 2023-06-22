<?php

namespace App\Http\Resources;

use App\Http\Resources\AcademicStaffResource;
use App\Http\Resources\api\CourseResource;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RecommendationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        $score=Recommendation::where([
            'course_code'=>$this->course_code,
            'student_id'=>$user->student->id
        ])->first()->score;
        $isAdmin = (Auth::check()&&$user->type == 'admin') ; //check if auth user type admin
        return [
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
            'score'=>$score
        ];
    }
}
