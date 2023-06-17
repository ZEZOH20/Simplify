<?php

namespace App\Http\Resources\api;

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
            'pivot'=>$this->whenPivotLoaded('field_student',$this->pivot),
            'sub_fields'=>FieldResource::collection($this->whenLoaded('sub_fields')), 
            "sub_fields_count"=>$this->sub_fields->count(), 
            'courses_count'=>$this->course->count(),
        ];
    }
}

//'sub_fields'=>FieldResource::collection($this->whenLoaded('sub_fields'))
// 'sub_fields'=>(!$this->sub_fields->isEmpty())?FieldResource::collection($this->sub_fields):null, 