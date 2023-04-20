<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function extractText()
    {
        $text = (new Pdf(public_path('pdf\xpdf-tools-win-4.04\bin64\pdftotext.exe')))
        ->setPdf(public_path('pdf\pdf1.pdf'))
        ->text();
        
        return $text;
    }
    public function index()
    {
        
        //  return response([
        //     'text'=>$this->extractText()
        //  ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)  //change request according to validation form data
    {
        // Define the Python interpreter and script file path
        $command = 'C:\Users\ziead\AppData\Local\Microsoft\WindowsApps\python.exe example.py'; // storage_path('app\example.py')

        // Execute the command and capture the output
        $output = shell_exec($command);

        return response([
            "output" => $output
        ]);
        // dd($output);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
