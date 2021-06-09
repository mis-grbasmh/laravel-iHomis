<?php

namespace App\Http\Controllers;
use App\Diagosis;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function autocomplete_diagnosis(Request $request)
    {
        $term = $request->term ;
        $data =  Diagosis::where('diagdesc','LIKE','%'.$term.'%')

        ->orWhere('diagcode','LIKE','%'.$term.'%')
        ->take(10)
        ->get();
        $results = array();
        foreach ($data as $value) {
            $results[] = ['label' => $value->name .'-'. $value->pphone ,'id' => $value->id];
        }
        return response()->json($results);
    }
}
