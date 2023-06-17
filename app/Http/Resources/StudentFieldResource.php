<?php

namespace App\Http\Resources;

use App\Http\Resources\api\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentFieldResource extends JsonResource
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
            'description'=>$this->description,
            'courses_count'=>$this->course->count(),
            'progress'=>$this->pivot->progress,
            // 'courses'=>CourseStudentPivotResource::collection($this->course)
        ];
    }
}
