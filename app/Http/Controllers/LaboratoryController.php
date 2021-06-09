<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inpatients;
use App\Doctororder;
use DB;
use Carbon;

class LaboratoryController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function request_logs($month='',$year=''){

      if($month == NULL || $year == NULL){
        $currentMonth = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now())->month;
        $currentYear  = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now())->year;    
       }else{   
        $currentMonth = March;
        $currentYear = 2019;
      }
     // $currentMonth = 10;// date('m');
      //$currentYear = date('Y');
    //  $currentMonth = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now())->month;
    //  $currentYear = 2019;
       //  $requests = Doctororder::getLaboratoryLogs($currentMonth, $currentYear);
         $requests =  DB::table('hdocord')
         // ->join('hadmlog','hadmlog.enccode','hdocord.enccode')
         ->join('hproc','hproc.prikey','hdocord.prikey')
         ->join('hprocm','hprocm.proccode','hdocord.proccode')
         // ->where('hdocord.dostat','A')
         ->where('orcode','LABOR')
         ->wheremonth('hdocord.dodate',$currentMonth)
         ->whereyear('hdocord.dodate',$currentYear)
         ->take(20)
         ->orderby('hdocord.dodate','DESC')
         ->get();
         return view('admin.laboratory.index',compact('requests','currentYear','currentMonth'));
    }

    public function upload(Request $request, $id){
      $this->validate(request(), [
         'enccode' => 'required',
         'document'=> 'required'
     ]);
     
  $examresult = Doctororder::find($id);

      if ($request->hasFile('avatar')) {
         $avatar = time().'.'.request()->avatar->getClientOriginalExtension();
         request()->avatar->move(public_path('storage\documents'), $avatar);
         $examresult->url = $avatar;
     }
    }
}
