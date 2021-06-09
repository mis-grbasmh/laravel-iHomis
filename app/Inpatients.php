<?php
namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class Inpatients extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hadmlog';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    public function users(){
      return $this->belongsToMany('\App\User');
  }

Public Static function Inpatientbydoctor($licno=''){
   $results = Inpatients::wherenull('disdate')->where('admstat','A')
            ->join('hperson','hperson.hpatcode','hadmlog.hpercode')
            ->join('hprovider','hprovider.licno','hadmlog.licno')
            ->join('hpersonal','hpersonal.employeeid','hprovider.employeeid')
            ->join('hpatroom as A','A.enccode','hadmlog.enccode')
            ->join('hbed','A.bdintkey','hbed.bdintkey')
            ->join('hroom','hroom.rmintkey','A.rmintkey')
            ->join('hward','hward.wardcode','A.wardcode' )
            ->join('htypser','htypser.tscode','hadmlog.tscode')
            ->join('hadmcons','hadmcons.enccode','A.enccode')
            ->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
            ->where('hadmcons.licno',$licno)
            ->orderby('hperson.patlast','ASC');

            return $results;
}

Public Static Function Admissions($month, $year){
   $data = Inpatients::wheremonth('admdate',$month)
   ->whereyear('admdate',$year)
   ->select('');
}

Public Static function Inpatientlist($ward){
  $data = Inpatients::wherenull('disdate')->where('admstat','A')
   ->join('hperson','hperson.hpatcode','hadmlog.hpercode')
   ->join('hpatroom as A','A.enccode','hadmlog.enccode')
   ->join('hbed','A.bdintkey','hbed.bdintkey')
   ->join('hroom','hroom.rmintkey','A.rmintkey')
   ->join('hward','hward.wardcode','A.wardcode' )
   ->join('htypser','htypser.tscode','hadmlog.tscode')

   ->select('hadmlog.enccode','hadmlog.hpercode','hadmlog.patage','hadmlog.admdate','hadmlog.licno','hadmlog.admclerk','hadmlog.admtxt','hadmlog.hsepriv','hperson.patsex','htypser.tsdesc','hward.wardname','hroom.rmname','hbed.bdname')
   ->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
   // ->orderby('hperson.patlast','ASC')
   ;
   //->orderby('hperson.patfirst','ASC')
   if($ward==''){
      $results = $data->get();
   }else{
      $results = $data->where('wardname',$ward)->get();
   }
  //DB::raw("(select top(1) dietcode from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as breakfast"),

   return $results;
}//inpatientlist

Public Static function InpatientlistforDiet($ward){

   $data = Inpatients::wherenull('disdate')->where('admstat','A')
    ->join('hperson','hperson.hpatcode','hadmlog.hpercode')
    ->join('hpatroom as A','A.enccode','hadmlog.enccode')
    ->join('hbed','A.bdintkey','hbed.bdintkey')
    ->join('hroom','hroom.rmintkey','A.rmintkey')
    ->join('hward','hward.wardcode','A.wardcode' )
    ->join('htypser','htypser.tscode','hadmlog.tscode')
    ->join('hpatmss','hpatmss.enccode','hadmlog.enccode')
    ->join('hmssclass','hmssclass.mssikey','hpatmss.mssikey')
    ->join('hmssmemtype','hmssmemtype.msstypecode','hpatmss.mssphictype')
    ->join('hreligion','hreligion.relcode','hperson.relcode')
    ->select('hadmlog.enccode','hadmlog.hpercode',
         DB::raw("(select top(1) dietcode from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as breakfast"),
         DB::raw("(select top(1) dietlunch from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as lunch "),
         DB::raw("(select top(1) dietdinner from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as supper"),
         DB::raw("(select top(1) licno from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as doctor"),
         DB::raw("(select top(1) remarks from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as dietremarks"),
         DB::raw("(SELECT TOP (1) hvsothr.vsbmi FROM hvsothr WHERE  (hvsothr.enccode = A.enccode) ORDER BY hvsothr.othrdte DESC) as bmi"),
         DB::raw("(SELECT TOP (1) hvsothr.vsbmicat FROM hvsothr WHERE  (hvsothr.enccode = A.enccode) ORDER BY hvsothr.othrdte DESC) as bmicat"),
             'hreligion.reldesc',
            'hadmlog.patage',
            'hadmlog.admdate',
            'hperson.patsex','htypser.tsdesc','hward.wardname','hroom.rmname','hbed.bdname','hmssclass.mssdesc','hmssmemtype.msstypedesc')
      ->where('A.patrmstat','A')
    //->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
    ;
    if($ward==''){
       $results = $data->get();
    }else{
       $results = $data->where('wardname',$ward)->get();
    }
    //->selectRaw("(CASE WHEN (gender = 1) THEN 'M' ELSE 'F' END) as gender_text")
    return $results;
 }//inpatientlist


Public Static Function DischargesList($date){
   $date1 = Carbon::parse($date. '00:00:00');
   $date2 = Carbon::parse($date. '23:59:59');
   $results = Inpatients::
   //whereBetween('hadmlog.disdate',[$date1,$date2])
   where('hadmlog.disdate','>=',$date1)
   ->where('hadmlog.disdate','<=',$date2)
      ->select('hadmlog.enccode','hadmlog.hpercode','hperson.patsex','hadmlog.patage','hadmlog.admdate','hadmlog.disdate','hadmlog.admtxt','hadmlog.licno','hadmlog.admclerk','hadmlog.dispcode','htypser.tsdesc','hadmlog.user_id','hadmlog.hsepriv','hadmlog.tacode',
      'hward.wardname','hroom.rmname','hbed.bdname',DB::raw("(select top(1) upper(diagtext+' ('+ diagcode_ext +')') from hencdiag where hencdiag.enccode = hadmlog.enccode and hencdiag.tdcode='FINDX' order by encdate DESC) as findx") )
      ->join('hperson','hperson.hpercode','hadmlog.hpercode')
      ->join('hpatroom as A','A.enccode','hadmlog.enccode')
      ->join('hbed','A.bdintkey','hbed.bdintkey')
      ->join('hroom','hroom.rmintkey','A.rmintkey')
      ->join('hward','hward.wardcode','A.wardcode' )
      ->join('htypser','htypser.tscode','hadmlog.tscode')
      ->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
   ->orderby('disdate','ASC')
   ->get();
   return $results;
}

// $date1 = Carbon::parse($date. '00:00:00');
// $date2 = Carbon::parse($date. '23:59:59');
// $results = Inpatients::
// //whereBetween('hadmlog.disdate',[$date1,$date2])
// where('hadmlog.disdate','>=',$date1)
// ->where('hadmlog.disdate','<=',$date2)
//    ->select('hadmlog.enccode','hadmlog.hpercode','hperson.patsex','hadmlog.patage','hadmlog.admdate','hadmlog.disdate','hadmlog.admtxt','hadmlog.licno','hadmlog.admclerk','hadmlog.dispcode','htypser.tsdesc','hadmlog.user_id','hadmlog.hsepriv',
//    'hward.wardname','hroom.rmname','hbed.bdname',DB::raw("(select top(1) upper(diagtext+' ('+ diagcode_ext +')') from hencdiag where hencdiag.enccode = hadmlog.enccode and hencdiag.tdcode='FINDX' order by encdate DESC) as findx") )
//    ->join('hperson','hperson.hpercode','hadmlog.hpercode')
//    ->join('hpatroom as A','A.enccode','hadmlog.enccode')
//    ->join('hbed','A.bdintkey','hbed.bdintkey')
//    ->join('hroom','hroom.rmintkey','A.rmintkey')
//    ->join('hward','hward.wardcode','A.wardcode' )
//    ->join('htypser','htypser.tscode','hadmlog.tscode')
// ->orderby('disdate','ASC')
// ->get();
// return $results;


Public Static Function Norasys($date){
   $date1 = Carbon::parse($date. '00:00:00');
   $date2 = Carbon::parse($date. '23:59:59');
   $results = DB::table('hpatcon1')
     ->join('hadmlog','hpatcon1.enccode','hadmlog.enccode')
     ->join('hperson','hperson.hpercode','hadmlog.hpercode')

     ->join('htypser','htypser.tscode','hadmlog.tscode')
      //  ->join('hphicclaimmap','hphicclaimmap.enccode','hpatcon1.enccode','left outer')
   //whereBetween('hadmlog.disdate',[$date1,$date2])
    ->where('hadmlog.disdate','>=',$date1)
   ->where('hadmlog.disdate','<=',$date2)
   ->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
   ->select('hperson.patsex','hadmlog.enccode','hadmlog.hsepriv','hadmlog.tacode','hadmlog.hpercode','hward.wardname','hadmlog.patage','hadmlog.admdate','hadmlog.disdate','hadmlog.admtxt','hadmlog.licno','hadmlog.admclerk','hadmlog.dispcode','htypser.tsdesc','hadmlog.hsepriv','hward.wardname','hroom.rmname','hbed.bdname',
   DB::raw("(select upper(pdischargediag1+' ('+ picdcode1 +')') from hpatcon1 where hpatcon1.enccode = hadmlog.enccode) as findx"),'hpatcon.reltomem','hpatcon.memphicnum','hphiclog.memfirst','hphiclog.memlast','hphiclog.memmid','hphiclog.phicnum','hphiclog.typemem','hpatcon1.philhealthbenehci','hpatcon1.philhealthbenepf','hpatcon1.ptotalactualchargeshci','hpatcon1.ptotalactualchargespf',
   DB::raw('sum(hpatcon1.philhealthbenehci) + sum(hpatcon1.philhealthbenepf) as amt'),DB::raw('sum(hpatcon1.philhealthbenehci) as sumhci'),DB::raw('sum(hpatcon1.philhealthbenepf) as sumpf'))

   //,'hphicclaimmap.pclaimserieslhio'

   // select pdischargediag1, picdcode1  * from hpatcon1


   ->join('hpatroom as A','A.enccode','hadmlog.enccode')
    ->join('hbed','A.bdintkey','hbed.bdintkey')
    ->join('hroom','hroom.rmintkey','A.rmintkey')
    ->join('hward','hward.wardcode','A.wardcode' )
    ->join('hpatcon','hpatcon.enccode','hadmlog.enccode')
    ->join('hphiclog','hphiclog.phicnum','hpatcon.memphicnum')
    ->groupBy('hadmlog.enccode','hperson.patsex','hadmlog.hsepriv','hadmlog.tacode','hadmlog.hpercode','hward.wardname','hadmlog.patage','hadmlog.admdate','hadmlog.disdate','hadmlog.admtxt','hadmlog.licno','hadmlog.admclerk','hadmlog.dispcode','htypser.tsdesc','hadmlog.hsepriv','hward.wardname','hroom.rmname','hbed.bdname','hpatcon.reltomem','hpatcon.memphicnum','hphiclog.memfirst','hphiclog.memlast','hphiclog.memmid','hphiclog.phicnum','hphiclog.typemem','hpatcon1.philhealthbenehci','hpatcon1.philhealthbenepf','hpatcon1.ptotalactualchargeshci','hpatcon1.ptotalactualchargespf')

   // ->join('hphicclaimmap','hphicclaimmap.enccode','hadmlog.enccode')
   ->orderby('disdate','ASC')
   ->get();
   return $results;
}
//$estatus = DB::table('hphicclaimmap')
//->where('enccode',$enccode)
//->first();



Public Static Function Dishchargebyid($id=''){
   $results = Inpatients::wheremonth('hadmlog.enccode',$id)
   ->join('hperson','hperson.hpatcode','hadmlog.hpercode')
   ->join('hpersonal','hpersonal.employeeid','hprovider.employeeid')
   ->join('hpatroom as A','A.enccode','hadmlog.enccode')
   ->join('hbed','A.bdintkey','hbed.bdintkey')
   ->join('hroom','hroom.rmintkey','A.rmintkey')
   ->join('hward','hward.wardcode','A.wardcode' )
   ->join('htypser','htypser.tscode','hadmlog.tscode')
   ->select('hadmlog.enccode','hadmlog.hpercode','hadmlog.patage','hadmlog.admdate','hadmlog.disdate','hadmlog.admtxt','hadmlog.hsepriv','hadmlog.tacode','hadmlog.licno','hadmlog.admclerk','hadmlog.dispcode','hperson.patsex','htypser.tsdesc','hward.wardname','hroom.rmname','hbed.bdname')
   ->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
   // ->take(5)
   ->orderby('hperson.patlast','ASC')->get();
}


Public Static Function Inpatient_hist($id){
   $results = Inpatients::where('hpercode',$id)
   ->join('htypser','htypser.tscode','=','hadmlog.tscode')
   ->orderby('hadmlog.admdate','DESC')
   ->get();
   return $results;
}

   Public Static Function Inpatientlatest(){
      $results = \App\Inpatients::wherenull('disdate')->where('admstat','A')
      ->orderby('admdate','DESC')
      ->limit(5)->get();
      return $results;
   }

   Public Static Function NewbornInpatient(){
      $results = \App\Inpatients::wherenull('disdate')->where('admstat','A')
         ->where('tscode','002')
      ->orderby('admdate','DESC')
      ->limit(5)->get();
      return $results;
   }

   Public Static Function getAdmissionbyId($id){
      $results =Inpatients::where('enccode','=',$id)
      ->join('hperson','hperson.hpatcode','hadmlog.hpercode')
      ->select('admtxt as admitdiag','hadmlog.hpercode','hadmlog.tscode','hadmlog.tacode','hadmlog.licno','hperson.hpatcode','hperson.patbdate','hperson.patsex','hadmlog.admdate as encdate','hadmlog.disdate as disdate','hadmlog.patage','hadmlog.condcode')
      ->limit(1)
      ->first();

      return $results;
   }

   Public Static Function gettopnewadmpatients(){
      $results =Inpatients::where('newold','N')
      ->orderby('admdate','DESC')->take(5)->get();
      return $results;
   }

}
