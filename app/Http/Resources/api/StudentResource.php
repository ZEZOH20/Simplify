<?php

namespace App\Http\Resources\api;

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
            'sex'=>$this->sex,
            'img'=>$this->img,
            't_credit'=>$this->t_credit,
            'cgpa'=>$this->cgpa,
            'elec_sim'=>$this->elec_sim,
            'man_sim'=>$this->man_sim,
            'elec_univ'=>$this->elec_univ,
            'man_univ'=>$this->man_univ
            
        ];
    }
}
