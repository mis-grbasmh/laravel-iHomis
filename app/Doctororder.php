<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Inpatients;
use Carbon;
class Doctororder extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hdocord';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];




    public static function checkfordischarge($id){
        $data = Doctororder::where('enccode',$id)
        ->where('orcode', 'DISCH')
        ->where('dostat','A')
        ->orderby('dodate', 'DESC')
        ->first();

    }
    Public static function getLaboratoryLogs($currentmonth,$currentyear){
      $data = Inpatients::all();

      $results = array();
      foreach ($data as $value) {
        $enccode = $value->enccode;
        $order = Doctororder::join('hproc','hproc.prikey','hdocord.prikey')
        ->join('hprocm','hprocm.proccode','hdocord.proccode')
        ->where('hprocm.costcenter','LABOR')
        ->wheremonth('hdocord.dodate',$currentmonth)
        ->whereyear('hdocord.dodate',$currentyear)
        ->take(10)
        //->where('hdocord.enccode',$enccode)
        ->get();

        $requests[]= $order;

        //$results[] = ['label' => $value->name .'-'. $value->mobile ,'village_id' => $value->id,'marketing_id' =>$value->marketing_id,'marketing_name'=> $value->marketing->name ];
      }

        // $requests = Doctororder
        // ::join('hpatroom as A','A.enccode','hdocord.enccode')
        // // ::join('hadmlog','hadmlog.enccode','hdocord.enccode')
        // ->join('hproc','hproc.prikey','hdocord.prikey')

        // ->join('hprocm','hprocm.proccode','hdocord.proccode')
        // ->where('hprocm.costcenter','LABOR')
        // ->where('hdocord.orderupd','ACTIV')
        // ->wherenull('hdocord.estatus')
        // ->where('hdocord.dostat','A')
        // ->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
        // ->wheremonth('hdocord.dodate',$currentmonth)
        // ->whereyear('hdocord.dodate',$currentyear)
        // ->orderby('hdocord.dodate','DESC')
        // ->get();
        return $requests;
    }

    public static function getExaminations($id,$ordertype){
         return \App\Doctororder
         ::join('hprocm','hprocm.proccode','hdocord.proccode')
         ->where('enccode',$id)
            ->where('orcode',$ordertype)
            ->select('hdocord.dodate','hprocm.procdesc','hdocord.licno','hdocord.pcchrgcod','hdocord.entby','hdocord.acctno','hdocord.remarks')
            ->orderby('hdocord.dodate','DESC')
            ->get();
    }

    public static function getRadiologyorder($id){
        return \App\Doctororder
        ::join('hprocm','hprocm.proccode','hdocord.proccode')
        ->where('orcode','=','RADIO')
        ->orwhere('orcode','=','ULTRA')
           ->orwhere('orcode','=','SCAN0')
           ->orwhere('orcode','=','ANAPA')
           ->where('enccode',$id)
           ->select('hdocord.dodate','hprocm.procdesc','hdocord.licno','hdocord.pcchrgcod','hdocord.entby','hdocord.acctno','hdocord.remarks')
           ->orderby('hdocord.dodate','DESC')
           ->get();
   }

    public static function getDietorders($id){
        return \App\Doctororder::where('enccode',$id)
        ->join('hdiet','hdiet.dietcode','hdocord.dietcode')
        ->select('hdocord.id as id','hdocord.enccode','hdocord.dodate','hdocord.statdate','hdocord.licno','hdocord.dietcode','hdocord.dietlunch','hdocord.dietdinner','hdocord.remarks')
        ->where('orcode','DIETT')
        ->orderby('dodate','DESC')->get();
    }
    public static function getDischargeorders($id){
        return \App\Doctororder::where('enccode',$id)
        ->select('hdocord.dodate','hprocm.procdesc','hdocord.licno','hdocord.pcchrgcod','hdocord.entby','hdocord.acctno','hdocord.remarks')
        ->where('orcode','DISCH')
        ->orderby('dodate','DESC')->get();
    }

    public static function getDietOrderByID($id){
        return \App\Doctororder::where('id',$id)
        ->first();
    }

    public static function checkdodischargeexist($id){
        $data = \App\Doctororder::where('enccode',$id)
        ->where('orcode','DISCH')->first();
        if($data){
            return true;
        }else{
            return false;
        }
    }

    public static function getFordischargeorders(){
        $currentyear  = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now())->year;
        $currentmonth = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now())->month;
        return \App\Doctororder
        // ::join('hadmlog','hdocord.enccode','hadmlog.enccode')
        // ->join('hpatmss','hpatmss.enccode','hadmlog.enccode')
        // ->join('hmssclass','hmssclass.mssikey','hpatmss.mssikey')
        // ->join('hmssmemtype','hmssmemtype.msstypecode','hpatmss.mssphictype')
        ::where('hdocord.orcode','DISCH')
        ->Select('hdocord.id',
                'hdocord.hpercode',
                'hdocord.licno',
                'hdocord.dodate',
                'hdocord.orcode',
                'hdocord.statdate'
                // 'hadmlog.patage',
                // 'hadmlog.entryby',
                // 'hadmlog.licno as admphy',
                // 'hadmlog.admdate',
                // 'hmssclass.mssdesc',
                // 'hmssmemtype.msstypedesc'
            )
        // ->wherenull('hadmlog.disdate')
        // ->where('hadmlog.admstat','A')
        ->whereyear('dodate','2021')
        ->wheremonth('dodate',$currentmonth)
        ->orderby('dodate','DESC')->get();
    }

    public static function getRadiologyoders($id){
        return \App\Doctororder::where('enccode',$id)
        ->join('hprocm','hprocm.proccode','hdocord.proccode')
        ->where('orcode','RADIO')
        ->where('orcode','ULTRA')
        ->orderby('dodate','DESC')->get();
    }

    public static function getRadiologyoderbyid($id){
        return \App\Doctororder::where('id',$id)
        ->join('hradresult','hradresult.docointkey','hdocord.docointkey')
        ->join('hprocm','hprocm.proccode','hdocord.proccode')
        ->join('hradlogbook','hradlogbook.hpercode','hdocord.hpercode')
        ->first();
    }

    public static function getMedicationOrders($id){
       return \App\Doctororder::where('id',$id)
       ->where('orcode','RADIO')
       ->where('orcode','ULTRA')
       ->orderby('dodate','DESC')->get();
    }
}
