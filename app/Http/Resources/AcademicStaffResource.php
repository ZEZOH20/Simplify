<?php

namespace App\Http\Resources;

use App\Http\Resources\api\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicStaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'=>$this->name,
            'verbose_title'=>$this->verbose_title,
            'email'=>$this->email,
            'phone_number'=>$this->phone_number,
            'img'=>$this->img,
            'department'=>$this->department,
            'degree'=>$this->degree,
            'title'=>$this->title,
            'courses'=>CourseResource::collection($this->whenLoaded('makeResponsible'))
        ];
    }
}
