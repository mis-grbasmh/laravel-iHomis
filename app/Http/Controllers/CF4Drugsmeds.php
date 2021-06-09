<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Courseward;
use App\Patients;
use App\Hsignsymptoms;
use App\Hexamination;
use App\Hcomplaints;
use app\Hrxo;

class CF4Drugsmeds extends Controller
{



  /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveDrugsMeds(Request $request){
        $input = $request -> all();

        try{
            $id = $request->enccode;
        $drugs = DB::table('hrxo')
        ->where('dmdcomb',$request->dmdcomb)
        ->where('enccode',$request->enccode)
        ->count();

        if($drugs = 1){

        DB::table('hrxo')
        ->where('docointkey','=',$request->docointkey)
        ->update([
        'qtyintake' =>$request->qtyintake,
        'uomintake' =>$request->uomintake,
        'reppatrn1' =>$request->reppatrn1,
        'reppatru1' =>$request->reppatru1,
        ]);
        }else{
            DB::table('hrxo')
        ->where('enccode','=',$request->enccode)
        ->where('dmdcomb',$request->dmdcomb)
        ->update([
            'qtyintake' =>$request->qtyintake,
            'uomintake' =>$request->uomintake,
            'reppatrn1' =>$request->reppatrn1,
            'reppatru1' =>$request->reppatru1,
        ]);
        }

        return redirect()->back()
        ->with('type','success')
        ->with('msg','Drugs and Meds Successfully Updated.');

        }catch(\Exception $exception){
            //try to categorize the error using the exception.
            return redirect()->back()
            ->with('type','warning')
            ->with('msg',$exception.'');
            //return view('admin.CF4.edit',['error' => 'An error occurred!']);
        }
    }

    function getdrugmeds($id=''){
        try{
            $enccode = str_replace("-","/",$id);

        }catch(\Exception $Exception){
            return redirect()->back()
            ->with('type','warning')
            ->with('msg',$Exception,'');
        }
    }
}
