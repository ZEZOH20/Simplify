<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TermResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'gpa_t1'=>$this->gpa_t1,
            'gpa_t2'=>$this->gpa_t2,
            'gpa_t3'=>$this->gpa_t3,
            'gpa_t4'=>$this->gpa_t4,
            'gpa_t5'=>$this->gpa_t6,
            'gpa_t6'=>$this->gpa_t7,
            'gpa_t7'=>$this->gpa_t8,
            'gpa_t8'=>$this->gpa_t5,
        ];
    }
}
