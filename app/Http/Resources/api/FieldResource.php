<?php

namespace App\Http\Resources\api;

use App\Http\Resources\StudentFieldResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'name'=>$this->name,
            'description'=>$this->description,
            'img'=>$this->img,
            'pivot'=>$this->whenPivotLoaded('field_student',$this->pivot),
            'courses_related_count'=>$this->course->count(),
        ];
    }
}

//'sub_fields'=>FieldResource::collection($this->whenLoaded('sub_fields'))
// 'sub_fields'=>(!$this->sub_fields->isEmpty())?FieldResource::collection($this->sub_fields):null, 