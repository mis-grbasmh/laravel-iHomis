<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DB;

class LaravelGoogleGraphController extends Controller
{
    
    public function __construct()
    {
      $this->middleware('auth', ['except' => ['show']]);
    }
    
    public function index()
    {
        $data = DB::table('hperson')
                ->select(
                    DB::raw('patsex as gender'),
                    DB::raw('count(*) as number'))
                ->groupBy('gender')
                ->get();
        $array[] = ['Gender','Number'];
        foreach($data as $key => $value)
        {
            $array[++$key] = [$value->gender, $value->number];
        }
        return view('patient.google_pie_chart')->with('gender', json_encode($array));
    }
}   
