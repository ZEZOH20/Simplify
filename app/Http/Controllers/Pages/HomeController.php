<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function show(){
        return response(['page_name'=>'home']);
    }
}
