<?php

namespace App\Imports;

use App\Models\AcademicStaff;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AcademicStaffImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new AcademicStaff([
            'name' => $row['name'],
            'verbose_title' => $row['verbose_title'],
            'email' => $row['email'],
            'phone_number' => $row['phone_number'],
            'img' => $row['img'],
            'department' => $row['department'],
            'degree' => $row['degree'],
            'title' => $row['title'],
        ]);
    }
}
