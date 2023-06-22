<?php

namespace App\Http\Resources\api;

use App\Http\Resources\StudentFieldResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       
        return [
            'gender'=>$this->gender,
            'img'=>public_path().$this->img,
            't_credit'=>$this->t_credit,
            'cgpa'=>$this->cgpa,
            'elec_sim'=>$this->elec_sim,
            'man_sim'=>$this->man_sim,
            'terms_gpa'=>new TermResource($this->whenLoaded('term')),
            // 'fields_progress'=>FieldResource::collection($this->whenLoaded('field')),    
        ];
    }
}
