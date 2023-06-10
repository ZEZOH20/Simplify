<?php

namespace App\Classes;

use App\Http\Resources\CourseStudentPivotResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Filtering
{

    private $query;
    private $tableNames = array('users', 'courses', 'course_student','academic_staffs'); //table allowed names for filtering
    private $fillable = [];
    private $queryParams = [];
    private $conditions = [];
    private $operators = [];
    private $isAuth;
    function __construct($queryParams, $tableName,  $fillable)
    {
        $this->isAuth = (Auth::check()) ? true : false;
        $this->fillable = $fillable;
        $this->queryParams = $queryParams;    // $request->query()
        $this->query = (in_array($tableName, $this->tableNames)) ? DB::table($tableName) : '';
        ($this->query == '') ? throw new \Exception('table name not exist (custom filtering class)') : '';
        $this->operators = $this->query->operators; //dd($this->query->operators)
      
    }

    public function start()
    {

        $this->convertQueryParamtersToConditions();

        foreach ($this->conditions as $condition) {

            $this->query->where(...$condition);
        }
        // dd($this->query);
        $result = $this->query->get();
        return $result;
    }
    public function convertQueryParamtersToConditions()
    {
        // dd($this->queryParams); //before
        foreach ($this->queryParams as $column => $value) {
            // if queryParams has value (filled)
            if ($value && in_array($column, $this->fillable)) {  //check if : value && column name exist 
                $operator = substr($value, 0, strpos($value, "-"));
                $operand = substr($value, strlen($operator) + 1, strlen($value)); //strlen($value)
                if (!in_array($operator, $this->operators)) { //if $column value string or the condition equal 
                    $operand = $value;
                    $operator = '=';
                }
                array_push($this->conditions, [$column, $operator, $operand]);
            }
        }
        // dd($this->conditions); //after
    }
}
