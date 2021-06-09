<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employees;
use App\User;
use DB;
class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //  $query='Salacup, Jozzle';
        $active = Employees::getEmployeeCount('A');
        $inactive = Employees::getEmployeeCount('I');
        $all = Employees::count();
        $employees = Employees::all();
        
       // $keywords = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/", $query);
        
        
        //   ->where('lastname', 'like', '%'.$keywords[0].'') 
        //   ->where('firstname', 'like', '%'.$keywords[1].'%') 
          

        //$employees = Employees::select(DB::raw("LASTNAME+', '+FIRSTNAME as name"))
         //$employees = DB::table('hpersonal2')

       // $employees = Employees::select(DB::raw("(LASTNAME) AS name"))

        return view('admin.employees.index',compact('employees','inactive'));
    }

/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employees = Employees::where('employeeid',$id)->first();
        return view('admin.employees.show', compact('employees'));
    }
 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employees::where('employeeid',$id);
        return view('admin.employees.edit', compact('employees'));
    }


}
