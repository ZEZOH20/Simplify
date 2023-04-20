<?php

namespace App\Http\Controllers;

use App\Imports\CourseImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    //
    public function import()
    {
        # code...
        Excel::import(new CourseImport,public_path('Excel\Book1.xlsx'));
    }
    // public function showList()
    // {
    //     $courses=Course::get();
    //     return view('showExcel',compact('courses'));
    // }
}
 