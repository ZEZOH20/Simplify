<?php

namespace App\Imports;

use App\Models\Field;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FieldImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    { 
       
         Field::create([
            'name'=>$row['name'],
            'description'=>$row['description'],
            'sub_field_name'=>$row['sub_field_name'],
            'img'=>$row['img']
        ]);
    
    }
}
