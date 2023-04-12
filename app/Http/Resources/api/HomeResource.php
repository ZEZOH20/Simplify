<?php

namespace App\Http\Resources\api;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this);
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,

            "student" => [
                'sex'=>$this->student->sex,
                'img'=>$this->student->img,
            ],
            "fields" => FieldResource::collection(Field::get()),
                                // Field::with('related_fields')->get()
            // "registered_courses" => CourseCollection::make($this->student->course), 
                                    //CourseResource::collection($this->student->course)
         
        ];
    }
}
