<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Emergencyroom extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'herlog';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

 public static function getERLogs(){
    $data = Emergencyroom::
      where('erstat','A')
     ->join('hperson','hperson.hpatcode','=','herlog.hpercode')
     ->join('hprovider','hprovider.licno','=','herlog.licno')
     ->join('htypser','htypser.tscode','=','herlog.tscode')
     ->select('hperson.patsex',
             'herlog.enccode',
             'herlog.hpercode',
             'htypser.tsdesc',
             'herlog.patage',
             'herlog.erdate',
             'herlog.licno',
             'herlog.entryby',
              DB::raw("(select history from hmrhisto as A where A.enccode = herlog.enccode and A.histype='COMPL') as complaint"));
   return $data;
 }
 
 Public Static Function  eradmission(){
   $admitted = Inpatients::wherenull('disdate')->where('admstat','A')
    ->join('hpatroom as A','A.enccode','hadmlog.enccode')
      ->where('A.patrmstat','A')->pluck('hadmlog.hpercode')->all();
      
      
      
      $data = Emergencyroom::
      where('erstat','A')
        ->join('hperson','hperson.hpatcode','=','herlog.hpercode')
        ->join('hprovider','hprovider.licno','=','herlog.licno')
        ->join('htypser','htypser.tscode','=','herlog.tscode')
        ->select('hperson.patsex',
                'herlog.enccode',
                'herlog.hpercode',
                'htypser.tsdesc',
                'herlog.patage',
                'herlog.erdate',
                'herlog.licno',
                'herlog.entryby',
                  DB::raw("(select history from hmrhisto as A where A.enccode = herlog.enccode and A.histype='COMPL') as complaint"))->get();


// $data = DB::table("herlog")->select('*')
//    ->where('dispcode','ÁDMIT')
//    ->where('erstat','I')
//    ->wherenotnull('erdtedis')
//    ->whereNotIn('hpercode',function($query) {
//       $query->select('hpercode')->from('hadmlog')->wherenull('disdate')->where('admstat','A');
//    })->get();
 return $data;
  //return $admitted;
 }


    Public  Static Function get_erforadmission(){
        try{
            $doctors = Emergencyroom::wherenotnull('erdtedis')
            ->where('dispcode','ADMIT')
            //where not in current inpatient
            
            
                ->orderby('erdtedis','DESC')
                    // ->select('hprovider.licno')
                    // DB::raw("LASTNAME+', '+FIRSTNAME as name"
                    ->get();
                if($doctors->count() <> 0){
                    return response()->json($doctors);
                    // echo json_encode($results);
                }
    
    
             }catch(\Exception $excpetion){
                return redirect()->back()
                ->with('type','warning')
                ->with('msg','error.'.$excpetion);
            }
    }// get_erforadmission


    Public Static FUnction GetErDeaths($month,$year){
      $data = Emergencyroom::where('dispcode','EXPIR')
         ->wheremonth('erdtedis',$month)
         ->whereyear('erdtedis',$year)
         ->join('htypser','htypser.tscode','herlog.tscode')
         ->Select('herlog.enccode',
                  'herlog.hpercode',
                  'herlog.erdate',
                  'herlog.erdtedis',
                  'herlog.patage',
                  'herlog.licno',
                  'herlog.entryby',
                  'htypser.tsdesc',
                  DB::raw("(select diagtext from hencdiag as A where A.enccode = herlog.enccode and A.tdcode='FINDX') as diagnosis"))
         ->get();
      return $data;
   }//GetErDeaths
}//Model Emergency Room
