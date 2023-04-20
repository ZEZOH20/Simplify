<?php

namespace App\Imports;

use App\Models\Field;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RelatedFieldImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $field = Field::where('name', $row['name'])->first();
        $field->field_id = $row['field_id']; 
        $field->save();
    }
}
