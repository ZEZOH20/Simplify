<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        // response json Show the form for creating  new student 
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)  //change request according to validation form data
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(['message'=>'show form page']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
