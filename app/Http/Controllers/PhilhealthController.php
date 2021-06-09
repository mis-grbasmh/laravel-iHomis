<?php

namespace App\Http\Controllers;

use App\Doctors;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Phicmembers;
use App\Inpatients;
use App\Hencdiag;

class PhilhealthController extends Controller
{

    Public  $dispositions = array(
        'DISCH'     =>  'Discharged',
        'TRANS'     =>  'Transferred',
        'DAMA'      =>  'Discharge Against Medical Advise',
        'ABSC'      =>  'Absconded',
        'EXPIR'     =>  'Expired'
    );
    public $membershiptypes = array(
        '01'    => 'Employed - Private Sector',
        '02'   => 'Employed - Govt Sector',
        '03'   => 'Indigent',
        '04'   => 'Individually Paying - Self Employed',
        '05'   => 'Individually Paying - OFW',
        '06'   => 'Individually Paying - Others',
        '07'   => 'Individually Paying - OWWA',
        '08'   => 'Retiree/Pensioner - SSS',
        '09'   => 'Retiree/Pensioner - GSIS',
        '10'   => 'Retiree/Pensioner - Military',
        '11'   => 'Retiree/Pensioner - GSIS',
        '12'   => 'Senior Citizen',
    );

    public $months = array(
        '1'    => 'January',
        '2'    => 'February',
        '3'    => 'March',
        '4'   => 'April',
        '5'   => 'May',
        '6'   => 'June',
        '7'   => 'July',
        '8'   => 'August',
        '9'   => 'September',
        '10'   => 'October',
        '11'   => 'november',
        '12'   => 'December',
    );

    public $membersrelation = array(
        '1'   => 'Legitimate spouse not NHIP member',
        '2'   => 'Unmarried unemployed, legitimate, legitimated, acknowledged and illegitimated children or legally adopted/stepchildren, below 21 years old',
        '3'   => 'Unmarried children below 21 yrs old with physical/mental disability, congenital and/or acquired before reaching 21 yrs old and wholly dependent on member for support',
        '4'   => 'Parent who is 60 years old and above, not NHIP member and wholly dependent on member for support',
        '6'   => 'Self',

    );

    public function norasys($date='')
    {
        if($date==NULL){
            $date = Carbon::now()->format('Y-m-d');
        }
        $discharges = Inpatients::Norasys($date);
        return view('transactions.phic.norasys',compact('discharges','date'))
            ->with('dispositions', $this->dispositions);
    }

    public function mmh_report(Request $request){

        $output ='';
        $date = Carbon::now()->format('Y-m-d');

        $month ='4';
        $year = '2021';
        $date = Carbon::parse($year.'-'.$month.'-1')->format('Y-m-d 12:00:00');
        $nodays = Carbon::parse(Carbon::parse($date)->format('Y-m-d'))->daysInMonth;
        $datestart = Carbon::parse($year.'-'.$month.'-1')->format('Y-m-d 12:00:00');
        $previous = Carbon::parse($datestart)->subdays(1);


        if (request()->ajax()) {
            $data = null;
            if($nodays > 0){
                for($i=1; $i<= $nodays; $i++){
                    $total=0;
                    $nhip=0;
                    $day= $i + 1;

                    $totaladm = DB::table('hadmog')

                        ->where('admdate','<','2016-03-01 00:00:00.000')
                        ->where('disdate','>','2016-03-01 23:59:59.000' )
                        ->get();


                    $totaladmission =  $totaladm->count();
                    $nhip_lastmonth = 63;
                    $nonnhip_lastmonth = 65;


                    $results_nhip = DB::table('hpatcon1')
                        ->join('hadmlog','hpatcon1.enccode','hadmlog.enccode')
                        ->where('hadmlog.admdate','<=',$previous)
                        ->where('hadmlog.disdate','>',$datestart)
                        ->get();
                    $nhip =  $results_nhip->count();


                        $results_nonnhip = DB::table('hadmlog')
//                            ->join('hpatcon1','hadmlog.enccode','hpatcon1.enccode')
                            ->where('hadmlog.admdate','<',$previous)
                            ->where('hadmlog.disdate','>',$datestart)
                            ->get();
                         $total =  $results_nonnhip->count();




                    $datestart = Carbon::parse($year.'-'.$month.'-'.$i)->format('Y-m-d 12:00:00');
                    $dayminus = Carbon::parse($datestart)->subdays(1);
                    $previous = Carbon::parse($dayminus)->format('Y-m-d 12:00:00');




                    $output .= '


                        <tr>
                <td>'. $i .'</td>
                <td>'.$nhip.'</td>
                <td>'.number_format($total - $nhip).'</td>
                <td>'.$total.'</td>
                <td>'. $previous.'</td>
                <td>'. $datestart .'</td>


            </tr>
    ';
//                    $previous = Carbon::parse($datestart)->subdays(1);

                }//for
                $data = array(
                    'table_data'  => $output,
//                    'total_data'  => $total_row
                );


                echo json_encode($data);
            }//endif

        }




    }




    public function mmh_reporting(Request $request)
    {
        $output ='';
        $date = Carbon::now()->format('Y-m-d');

       // $month =$request->month;
       $month =3;
       $year ='2021';
        //$year =$request->year;
        $date = Carbon::parse($year.'-'.$month.'-1')->format('Y-m-d 12:00:00');
        $nodays = Carbon::parse(Carbon::parse($date)->format('Y-m-d'))->daysInMonth;
        $datestart = Carbon::parse($year.'-'.$month.'-1')->format('Y-m-d 12:00:00');
        $previous = Carbon::parse($datestart)->subdays(1);





        if (request()->ajax()) {
            $data = null;
            if($nodays > 0){
                for($i=1; $i<= $nodays; $i++){
                    $total=0;
                    $nhip=0;
                    $day= $i + 1;

                    $totaladm = DB::table('hadmog')

                        ->where('admdate','<','2016-03-01 00:00:00.000')
                        ->where('disdate','>','2016-03-01 23:59:59.000' )
                        ->get();


                    $totaladmission =  $totaladm->count();
                    $nhip_lastmonth = 63;
                    $nonnhip_lastmonth = 65;


                    $results_nhip = DB::table('hpatcon1')
                        ->join('hadmlog','hpatcon1.enccode','hadmlog.enccode')
                        ->where('hadmlog.admdate','<=',$previous)
                        ->where('hadmlog.disdate','>',$datestart)
                        ->get();
                    $nhip =  $results_nhip->count();


                        $results_nonnhip = DB::table('hadmlog')
//                            ->join('hpatcon1','hadmlog.enccode','hpatcon1.enccode')
                            ->where('hadmlog.admdate','<',$previous)
                            ->where('hadmlog.disdate','>',$datestart)
                            ->get();
                         $total =  $results_nonnhip->count();




                    $datestart = Carbon::parse($year.'-'.$month.'-'.$i)->format('Y-m-d 12:00:00');
                    $dayminus = Carbon::parse($datestart)->subdays(1);
                    $previous = Carbon::parse($dayminus)->format('Y-m-d 12:00:00');




                    $output .= '


                        <tr>
                <td>'. $i .'</td>
                <td>'.$nhip.'</td>
                <td>'.number_format($total - $nhip).'</td>
                <td>'.$total.'</td>
                <td>'. $previous.'</td>
                <td>'. $datestart .'</td>


            </tr>
    ';
//                    $previous = Carbon::parse($datestart)->subdays(1);

                }//for
                $data = array(
                    'table_data'  => $output,
//                    'total_data'  => $total_row
                );


                echo json_encode($data);
            }//endif

        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function phicsoa($id)
    {
        $enccode = str_replace("-","/",$id);
        $inpatients = Inpatients::wherenull('disdate')
            ->where('admstat','A')
            ->join('hpatroom as A','A.enccode','hadmlog.enccode')
            ->join('hward','hward.wardcode','=','A.wardcode')
            ->join('hroom','hroom.rmintkey', '=','A.rmintkey' )
            ->join('hbed','hbed.bdintkey','=','A.bdintkey')
            ->join('htypser','htypser.tscode','=','hadmlog.tscode')
            ->join('hpatcon1','hpatcon1.enccode','=','hadmlog.enccode')
            ->join('hpatcon','hpatcon.enccode','=','hadmlog.enccode')
            ->get();
        return view('admin.philhealth.profile',compact('inpatients','enccode'))
            ->with('membersrelation', $this->membersrelation);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function phicsoadetails($id=''){

        if($id) {
            $enccode = str_replace("-", "/", $id);
            $admdiagnosis = Inpatients::where('hadmlog.enccode', $enccode)
                ->join('hpatcon1', 'hpatcon1.enccode', '=', 'hadmlog.enccode')
                ->join('hpatacct', 'hpatacct.enccode', '=', 'hadmlog.enccode')
                ->join('hphcont', 'hphcont.enccode', '=', 'hadmlog.enccode')
                ->join('hpatroom', 'hpatroom.enccode', 'hadmlog.enccode')
                ->join('hbed', 'hpatroom.bdintkey', 'hbed.bdintkey')
                ->join('hroom', 'hroom.rmintkey', 'hpatroom.rmintkey')
                ->join('hward', 'hward.wardcode', 'hpatroom.wardcode')
                ->first();
            //lenght of stay
            //  if($admdiagnosis->admdate <> NULL) {
            // $los = \Carbon\Carbon::parse($admdiagnosis->admdate)->diffInDays(\Carbon\Carbon::parse($admdiagnosis->disdate));
            // }else{
            // $los = \Carbon\Carbon::parse($admdiagnosis->admdate)->diffInDays(\Carbon\Carbon::now());
            // }
            $los = 0;

            $finaldiagnosis = Hencdiag::getFinalDiagnosis($enccode);
            $actualcharges = "";
            $profservices = DB::table('hprofserv')
                ->select('hprofserv.licno', 'hprofserv.pftotamt',DB::raw('sum(pftotamt) as pftotcharges'))
                ->groupby('hprofserv.licno','hprofserv.pftotamt')
                ->where('hprofserv.enccode', '=', $enccode)->get();
            $sc_disc = DB::table('hpatdisc')->where('discikey', 'SENRC01012013')->where('discikey', '000208092012')->where('enccode', '=', $enccode)->count();
            $pvao = 0;
            $or_act = 0;
            //room

            $test1='';
            $rb = DB::table('hphcont')
                ->select('totchrm')
                ->where('hphcont.enccode', '=', $enccode)
                ->first('totchrm');

            $rb = $rb->totchrm;

            $rooms= DB::table('hbed')
                ->join('hrmacc','hbed.rmaccikey','hrmacc.rmaccikey')
                ->join('hroom','hbed.rmintkey','hroom.rmintkey')
                ->join('hpatroom','hbed.bdintkey','hpatroom.bdintkey')
                ->select(DB::raw('ROW_NUMBER() OVER(ORDER BY hprdate DESC) AS Row'),'hrmacc.rmrate','hpatroom.hprdate')
                ->where('hpatroom.enccode',$enccode)
                ->orderby('hprdate','ASC')->get();
            $days=0;
            $rows = $rooms->count();
            if($rows == 1){
                if($admdiagnosis->disdate){
                    //    $days = \Carbon\Carbon::parse($rooms[0]->hprdate)->diffInDays(\Carbon\Carbon::parse($admdiagnosis->disdate));
                    //$days = \Carbon\Carbon::parse($admdiagnosis->admdate)->diffInDays(\Carbon\Carbon::parse($admdiagnosis->disdate))+1;
                    //Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestemp)->day
                    $days = \Carbon\Carbon::parse($admdiagnosis->disdate)->format('d') - \Carbon\Carbon::parse($admdiagnosis->admdate)->format('d');
                    $rate =  $rooms[0]->rmrate;
                    $rbfinal = $rate * ($days+1);
                    //    $rbfinal = $days+1;
                }
            }else{
                $rb1=0;
                foreach($rooms as $key => $room){
                    for($i=0; $i<$rows; $i++){
                        ${'rb'.$key} = $room->hprdate;
                        ${'rm'.$key} = $room->rmrate;
                    }
                }
                $rbfinal = $rb1;


            }


            // foreach($rooms as $room)
            //{
            //     $rbfinal =  implode("`, `", array_keys($rooms));
            //}



//                    $days = \Carbon\Carbon::parse($rooms[0]->hprdate)->diffInDays(\Carbon\Carbon::parse($admdiagnosis->disdate));

            $dreg = DB::table('hrxo')
                ->select(DB::raw('sum(pcchrgamt) as dregtot'))
                ->where('enccode', '=', $enccode)
                ->where('orderfrom', 'DRUME')
                ->get()->first()->dregtot;


//drugsmeds
            $dmreg_1 = DB::table('hrxo')
                ->join('hpatchrg', 'hrxo.enccode', '=', 'hpatchrg.enccode')
                ->select(DB::raw('sum(distinct hrxo.pcchrgamt) as sumdreg'))
                ->wherenotnull('hrxo.pcchrgcod')
                ->where('hrxo.orderfrom', '=', 'DRUME')
                ->where('hrxo.enccode', '=', $enccode)
                ->get()->first()->sumdreg;

            $dmh = DB::table('hrxo')
                ->join('hpatchrg', 'hrxo.enccode', '=', 'hpatchrg.enccode')
                ->select(DB::raw('sum(distinct hrxo.pcchrgamt) as sumdreg'))
                ->wherenotnull('hrxo.pcchrgcod')
                ->where('hrxo.orderfrom', '=', 'DRUMP')
                ->where('hrxo.enccode', '=', $enccode)
                ->get()->first()->sumdreg;

            $dmt = DB::table('hrxo')
                ->join('hpatchrg', 'hrxo.enccode', '=', 'hpatchrg.enccode')
                ->select(DB::raw('sum(distinct hrxo.pcchrgamt) as sumdreg'))
                ->wherenotnull('hrxo.pcchrgcod')
                ->where('hrxo.orderfrom', '=', 'DRUMT')
                ->where('hrxo.enccode', '=', $enccode)
                ->get()->first()->sumdreg;
            $tot_dr = $dmreg_1 +$dmh + $dmt;
//medsup
            $msreg = DB::table('hpatchrg')
                ->select(DB::raw('sum(distinct pcchrgamt) as msregtot'))
                ->where('chargcode', '=', 'NNDRR')
                ->where('enccode', '=', $enccode)
                ->get()->first()->msregtot;

            $oxytf = DB::table('hcharge')
                ->join('hpatchrg', 'hpatchrg.chargcode', 'hcharge.chrgcode')
                ->select('hcharge.chrgdesc', DB::raw('sum(hpatchrg.pcchrgamt) as charges'))
                ->where('hpatchrg.enccode', $enccode)
                ->where('hcharge.chrgdesc', 'Oxygen (TF)')
                ->groupby('hpatchrg.chargcode', 'hcharge.chrgdesc')
                ->get();
            if ($oxytf->count() > 0) {
                $oxytf = $oxytf['charges'];
            } else {
                $oxytf = 0;
            }

            $msreg = DB::table('hcharge')
                ->join('hpatchrg', 'hpatchrg.chargcode', 'hcharge.chrgcode')
                ->select('hcharge.chrgdesc', DB::raw('sum(hpatchrg.pcchrgamt) as charges'))
                ->where('hpatchrg.enccode', $enccode)
                ->where('hcharge.chrgdesc', 'Medical Supplies(REGULAR)')
                ->groupby('hpatchrg.chargcode', 'hcharge.chrgdesc')
                ->get();
            if ($msreg->count() > 0) {
                $msreg = $msreg['charges'];
            } else {
                $msreg = 0;
            }
            $dmreg1 = DB::table('hcharge')
                ->join('hpatchrg', 'hpatchrg.chargcode', 'hcharge.chrgcode')
                ->select('hcharge.chrgdesc', DB::raw('sum(hpatchrg.pcchrgamt) as charges'))
                ->where('hpatchrg.enccode', $enccode)
                ->where('hcharge.chrgdesc', 'Drugs and Medicine (REGULAR)')
                ->groupby('hpatchrg.chargcode', 'hcharge.chrgdesc')
                ->get();
            if ($dmreg1->count() > 0) {
                $dmreg1 = $dmreg1['charges'];
            } else {
                $dmreg1 = 0;
            }

            $dmreg2 = DB::table('hrxo')
                ->join('hcharge','hcharge.chrgcode','hrxo.orderfrom')
                ->select('hcharge.chrgdesc', DB::raw('sum(hrxo.pcchrgamt) as charges'))
                ->where('hrxo.enccode', $enccode)
                ->where('hcharge.chrgdesc', 'Drugs and Medicine (REGULAR)')
                ->groupby('hrxo.orderfrom', 'hcharge.chrgdesc')
                ->get();
            if ($dmreg2->count() > 0) {
                $dmreg2 = $dmreg2['charges'];
            } else {
                $dmreg2 = 0;
            }
            $dmreg = $dmreg1 + $dmreg2;


            $dmphic = DB::table('hcharge')
                ->join('hpatchrg', 'hpatchrg.chargcode', 'hcharge.chrgcode')
                ->select('hcharge.chrgdesc', DB::raw('sum(hpatchrg.pcchrgamt) as charges'))
                ->where('hpatchrg.enccode', $enccode)
                ->where('hcharge.chrgdesc', ' Drugs and Medicines (PHIC)')
                ->groupby('hpatchrg.chargcode', 'hcharge.chrgdesc')
                ->get();

            if ($dmphic->count() > 0) {
                $dmphic = $dmphic['charges'];
            } else {
                $dmphic = 0;
            }


//radio
            $finalxray = DB::table('hdocord')
                ->select(DB::raw('sum(hdocord.pcchrgamt) as sum1_count'))
                ->where('hdocord.enccode', '=', $enccode)
                ->where('hdocord.orcode', '=', 'RADIO')
                ->get()->first()->sum1_count;

            $or_act = DB::table('hpatchrg')
                ->select(DB::raw('sum(pcchrgamt) as or_acttot'))
                ->where('chargcode', '=', 'PROC')
                ->where('enccode', '=', $enccode)
                ->first()->or_acttot;

            $lab_rf = DB::table('hdocord')
                ->join('hcharge', 'hdocord.orcode', 'hcharge.chrgcode')
                ->select('hcharge.chrgdesc', DB::raw('sum(hdocord.pcchrgamt) as charges'))
                ->where('hdocord.enccode', $enccode)
                ->where('hcharge.chrgdesc', 'Laboratory')
                ->where('estatus','P')
                ->groupby('hdocord.orcode', 'hcharge.chrgdesc')
                ->get();

            if ($lab_rf->count() > 0) {
                $lab_rf =  $lab_rf['charges'];
            } else {
                $lab_rf = 0;
            }



            $dmtf=0;
            $msphic=0;
            //misce
//        $finalmisce = DB::table('hpatchrg')
//            ->select(DB::raw('sum(distinct pcchrgamt) as sum2_count'))
////            ->wherenull('pcchrgamt')
////            ->where('srcchrg','WARD')
//            ->where('chargcode','=','MISCE')
//            ->where('enccode','=',$enccode)
//            ->get()->first()->sum2_count;


//firstcase
            $fcase = DB::table('hpatcon1')
                ->join('hallcase', 'hallcase.casecode', '=', 'hpatcon1.firstcase')
                ->select('hallcase.firsthosp')
                ->where('hpatcon1.enccode', $enccode)
                ->first('firsthosp');

            $scase = DB::table('hpatcon1')
                ->join('hallcase2', 'hallcase2.casecode', '=', 'hpatcon1.secondcase')
                ->select('hallcase2.secondhosp')
                ->where('hpatcon1.enccode', $enccode)
                ->first('secondhosp');

        }else{
            $enccode ="";
            $admdiagnosis="";
            $finaldiagnosis ="";
            $totchrm=0;
            $oxytf=0;
            $rb=0;
            $sc_disc=0;
            $rooms=0;
            $test1=0;$fcase=0;$dmreg=0;$dmphic=0;$msreg=0;$finalxray=0;$or_act=0;$profservices="";$lab_rf=0;$dmtf=0;$msphic=0;$los=0;$scase=0;$rbfinal=0;
        }


        $return= DB::table('hrxoreturn')
            ->join('hrxo', 'hrxo.enccode','=','hrxoreturn.enccode')
            ->select(DB::raw('sum(hrxo.pcchrgamt) as returntot'))
            ->where('hrxoreturn.enccode',$enccode)
            ->where('hrxoreturn.chrgcode','DRUME')
            ->where('hrxo.docointkey','hrxoreturn.docointkey')
            ->get()->first()->returntot;

        $rb_total = $return;


        $act_chrg =
            $rb
            +$dmphic
            +$dmreg
            +$msreg
            +$lab_rf
            +$finalxray
//            +$admdiagnosis->totchot
            +$or_act
            +$oxytf;


        return view('admin.philhealth.profile',compact('admdiagnosis',
            'profservices',
            'enccode',
            'finaldiagnosis',
            'rb',
            'rbfinal',
            'dmreg',
            'dmtf',
            'dmphic',
            'msreg','los',
            'finalxray',
            'or_act', 'lab_rf','msphic','test1',
            'oxytf',
//            'finalmisce',
            'charges1','$msreg',
            'fcase','scase','sc_disc','act_chrg','rb_total','tot_dr'
            ,'rooms','rbfinal'
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function phicmembers()
    {
        $phicmembers = Phicmembers::orderby('hphiclog.datemod','DESC')
            ->limit(200)
            ->join('hbrgy','hbrgy.bgycode  ','=','hphiclog.membrgy')
            ->join('hcity','hcity.ctycode','=','hphiclog.memcity')
            ->join('hprov','hprov.provcode','=','hphiclog.memprov')
            ->get();
        return view('admin.philhealth.phicmember',compact('phicmembers'));
    }

    function search_member(Request $request)
    {
        $phicmembers = Phicmembers::where('phicnum', 'LIKE',"%{$request->search}%")
            ->orwhere('hpercode', 'LIKE',"%{$request->search}")
            ->orwhere('memlast', 'LIKE',"%{$request->search}%")
            ->paginate();
        return view('admin.philhealth.phicmember',compact('phicmembers'));
    }

    function norasys_summary(Request $request){
        if($request->ajax()){
            $output='';
            $month = $request->get('month');
            $year = $request->get('year');




            // $results = DB::table('hpatcon1')
            //     ->join('hadmlog','hpatcon1.enccode','hadmlog.enccode')
            //     ->join('hphicclaimmap','hphicclaimmap.enccode','hpatcon1.enccode')
            //     ->join('hPhicClaimPayee','hPhicClaimPayee.pClaimSeriesLhio','hPhicClaimMap.pClaimSeriesLhio')
            //     ->whereyear('hadmlog.disdate','=',$year)
            //     ->wheremonth('hadmlog.disdate','=',$month)
            //     ->where('hPhicClaimMap.pStatus','WITH CHEQUE')
            //     ->select(DB::raw('sum(pclaimamount) as amt'))
            //     ->first();


            $withcheque = DB::table('hpatcon1')
            ->join('hadmlog','hpatcon1.enccode','hadmlog.enccode')
            ->join('hPhicClaimMap','hPhicClaimMap.enccode','hpatcon1.enccode')
            ->wheremonth('hadmlog.disdate',$month)
            ->whereyear('hadmlog.disdate',$year)
            ->where('hphicclaimmap.pstatus','WITH CHEQUE');

                 $row_withcheque = number_format($withcheque->count());

                 $results = $withcheque->select(DB::raw('sum(hpatcon1.philhealthbenehci) + sum(hpatcon1.philhealthbenepf) as amt'))
                      ->first();

                    //   SELECT        dbo.hpatcon1.enccode, dbo.hPhicClaimMap.pStatus, dbo.hadmlog.disdate, dbo.hpatcon1.philhealthbenepf + dbo.hpatcon1.philhealthbenehci AS amount)
                    //   FROM            dbo.hpatcon1 INNER JOIN
                    //                            dbo.hadmlog INNER JOIN
                    //                            dbo.hPhicClaimMap ON dbo.hadmlog.enccode = dbo.hPhicClaimMap.enccode ON dbo.hpatcon1.enccode = dbo.hadmlog.enccode
                    //   WHERE        (MONTH(dbo.hadmlog.disdate) = '7') AND (dbo.hPhicClaimMap.pStatus = 'RETURN') AND (YEAR(dbo.hadmlog.disdate) = '2020')



                $inprocess = DB::table('hpatcon1')
               ->join('hadmlog','hpatcon1.enccode','hadmlog.enccode')
               ->join('hPhicClaimMap','hPhicClaimMap.enccode','hpatcon1.enccode')
               ->wheremonth('hadmlog.disdate',$month)
               ->whereyear('hadmlog.disdate',$year)
               ->where('hphicclaimmap.pstatus','IN PROCESS');
               $row_inprocess = number_format($inprocess->count());
               $results2 = $inprocess->select(DB::raw('sum(hpatcon1.philhealthbenehci) + sum(hpatcon1.philhealthbenepf) as amt'))
               ->first();

               $denied = DB::table('hpatcon1')
               ->join('hadmlog','hpatcon1.enccode','hadmlog.enccode')
               ->join('hPhicClaimMap','hPhicClaimMap.enccode','hpatcon1.enccode')
               ->wheremonth('hadmlog.disdate',$month)
               ->whereyear('hadmlog.disdate',$year)
               ->where('hphicclaimmap.pstatus','DENIED');
               $row_denied = number_format($denied->count());
               $results3 = $denied->select(DB::raw('sum(hpatcon1.philhealthbenehci) + sum(hpatcon1.philhealthbenepf) as amt'))
               ->first();
            //    $results3 = $data->where('hphicclaimmap.pstatus','DENIED')
            //    ->first();
               $returned =  DB::table('hpatcon1')
               ->join('hadmlog','hpatcon1.enccode','hadmlog.enccode')
               ->join('hPhicClaimMap','hPhicClaimMap.enccode','hpatcon1.enccode')
               ->wheremonth('hadmlog.disdate',$month)
               ->whereyear('hadmlog.disdate',$year)
               ->where('hphicclaimmap.pstatus','RETURN');
               $row_returned  = number_format($returned->count());
               $results4 =   $returned->select(DB::raw('sum(hpatcon1.philhealthbenehci) + sum(hpatcon1.philhealthbenepf) as amt'))
                        ->first();

                //->SELECT(DB::raw("(select Sum(hPhicClaimPayee.pClaimAmount) from hPhicClaimMap where hPhicClaimMap.enccode = A.enccode) as amount"))
                $withcheque =0;
                $denied =0;
                $returned =0;
                if($results){
                    $withcheque ='Php '. number_format($results->amt);
                }
                if($results2){
                    $inprocess = 'Php '. number_format($results2->amt);
                }
                if($results3){
                    $denied = 'Php '. number_format($results3->amt);
                }
                if($results4){
                    $returned = 'Php '. number_format($results4->amt);
                }

                $total_row =  $row_returned;

         return response()->json(
        [
            'withcheque'        => $withcheque,
            'denied' => $denied,
            'returned' =>$returned,
            'inprocess' => $inprocess,
            'row_withcheque'  => $row_withcheque,
            'row_returned'  =>$row_returned,
            'row_denied'    => $row_denied,
            'row_inprocess' => $row_inprocess,
            'total_row'     => $total_row,
        ]
    );
        }


}
    function norasys_report($date=''){
        $pdf=App::make('dompdf.wrapper');
      //  $pdf->setPaper('executive','landscape');
        $pdf->setPaper(array(0, 0, 612.00, 900.00),'landscape');
        $pdf->loadHTML($this->convert_norasys_report_data_to_html($date));
        return $pdf->stream();
    }
    function convert_norasys_report_data_to_html($date)
    {
        if($date==NULL){
            $date = Carbon::now()->format('Y-m-d');
        }
        $discharges = Inpatients::Norasys($date);



        //
        $output='
       <style>
       .pic{
        position: absolute;
        top: 0px;
        left: 375px;
      }

      .pic1{
        position: absolute;
        top: 0px;
        left: 530px;
      }

      body {font-family: sans-serif; margin: 8; text-align: justify; font-size: 0.8em;}
        p {text-align: justify; margin-left: 5px;margin-right: 5px; margin-top: 5px; margin-bottom: 5px; padding-left: 0px; font-size: 0.8em}
        td {text-alin: right;}
        @page { margin:2px;

        }




    </style>
    <img src="assets/img/logo.png" class="pic"  width="60" height="60">
    <table style="border-collapse: collapse; width: 100%; height: 100px;" border="0">
    <tr style="height: 12px;">
        <td style="height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="13">&nbsp;<strong>HSP-01-PHIC-5&nbsp;</strong></td>
    </tr>
    <tr style="height: 12px;">
        <td style="height: 12px; vertical-align: top; text-align: center; width: 70%;" colspan="13">&nbsp;Republic of the Philippines&nbsp;</td>
    </tr>
    <tr style="height: 12px;">
        <td style="height: 12px; vertical-align: top; text-align: center; width: 70%;" colspan="13">&nbsp;Province of Ilocos Norte&nbsp;</td>
    </tr>
    <tr style="height: 12px;">
        <td style="height: 12px; vertical-align: top; text-align: center; width: 70%;" colspan="13">&nbsp;<strong>Gov. Roque B. Ablan Sr. Memorial Hospital&nbsp;</strong></td>
    </tr>
    <tr style="height: 12px;">
    <td style="height: 15px; vertical-align: top; text-align: center; width: 70%;" colspan="13">&nbsp;<strong>NORA SYSTEM REPORT&nbsp;</strong></td>
    </tr>
    <tr style="height: 12px;">
    <td style="height: 15px; vertical-align: top; text-align: center; width: 70%;" colspan="13">&nbsp;<strong>AS OF DISCHARGE DATE &nbsp;<span style="text-decoration: underline;">'.getLongDateFormat($date).'</span></strong></td>
    </tr>
<br/>
</table>


       <table style="border-collapse:collapse;border=0px;width=100px;" >

        <tr align="center">
            <th width ="5px" style="border: 1px solid">#</th>
            <th style="border: 1px solid">Patient Details</th>
            <th style="border: 1px solid">Admission Details</th>
            <th style="border: 1px solid">Philhealt <br/> Member</th>
            <th width ="20%" style="border: 1px solid">Final Diagnosis</th>
            <th width ="15%" style="border: 1px solid">Physician</th>
            <th width ="5%" style="border: 1px solid">Actual<br/>HCI</th>
            <th width ="5%" style="border: 1px solid">Actual<br/>PF</th>
            <th width ="5%" style="border: 1px solid">HCI</th>
            <th width ="5%" style="border: 1px solid">PF</th>
            <th width ="5%" style="border: 1px solid"><p>Philhealth<br/>Claim Amt.</p></th>
            <th width ="5%" style="border: 1px solid"><p>Claim<br/>Status</p></th>
        </tr>';
        $totalphicclaim=0;
        $totalhci=0;
        $totalpf=0;
        foreach ($discharges as $key => $patient)
        {


          //  $totalphicclaim =  $totalphicclaim + get_philhealthamount($patient->enccode);

          $totalphicclaim = $totalphicclaim + $patient->amt;
          $totalhci = $totalhci + $patient->sumhci;
          $totalpf = $totalpf + $patient->sumpf;
        //    + number_format($patient->ptotalactualchargespf,2);
            $output .= '
                <tr>
                    <td width="5px" align="right" style="padding-top:.2em;padding-bottom:.5em;border: 1px solid;">'.($key+1).'.</td>
                    <td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid"><strong>'.getpatientinfo($patient->hpercode).'</strong><br/><small>
                        '.$patient->patsex.', '.number_format($patient->patage).' year(s) old <br/>'.$patient->hpercode.'</small></td>
                    <td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid"><small>'.getFormattedDate($patient->admdate).'<br/>'
                        .getFormattedDate($patient->disdate).'<br/>'
                        .$patient->wardname.'-'.$patient->rmname.'-'.$patient->bdname.'<br/>
                        '.$patient->tacode.'</small></td>
                    <td style="padding-top: .5em;padding-bottom:.5em;border: 1px solid"><strong>'.$patient->memfirst.' '.$patient->memmid.' '.$patient->memlast.
                        '<br/><small>'.$patient->phicnum.
                        '</strong><br/>'.PhicMembershiptype($patient->typemem).'</small></td>
                    <td style="text-align:justify; padding-top:.5em;padding-bottom:.5em;padding-left:.5em; border: 1px solid"><small>'.getPhicDiagosis($patient->enccode).'</small></td>
                    <td style="padding-top:.5em;padding-bottom:.5em;padding-bottom:.5em;border: 1px solid">'.getdoctorinfo($patient->licno).'<br/><p>'.$patient->tsdesc.'</p></td>
                    <td style="text-align:right; padding-top:.5em;padding-bottom:.5em;padding-bottom:.5em;border: 1px solid">'.number_format($patient->ptotalactualchargeshci,2).'</td>
                    <td style="text-align:right; padding-top:.5em;padding-bottom:.5em;padding-bottom:.5em;border: 1px solid">'.number_format($patient->ptotalactualchargespf,2).'</td>
                    <td style="text-align:right; padding-top:.5em;padding-bottom:.5em;padding-bottom:.5em;border: 1px solid">'.number_format($patient->philhealthbenehci,2).'</td>
                    <td style="text-align:right; padding-top:.5em;padding-bottom:.5em;padding-bottom:.5em;border: 1px solid">'.number_format($patient->philhealthbenepf,2).'</td>
                    <td style="text-align:right; padding-top:.5em;padding-bottom:.5em;padding-bottom:.5em;border: 1px solid">'.get_philhealthamount($patient->enccode).'</td>
                    <td style="text-align:center; padding-top:.5em;padding-bottom:.5em;padding-bottom:.5em;border: 1px solid"><small>'.get_eclaimstatus($patient->enccode).'</small></td>
                </tr>

                ';
        }
        $output .= '
        <tr style="height: 12px;">

        <td style="height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="8">&nbsp;<strong>TOTAL HCI:&nbsp;</strong></td>
        <td style="height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="2">&nbsp;<strong>P&nbsp;'.number_format($totalhci,2).'</strong></td>
        <td style="height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="2">&nbsp;</td>
    </tr>
    <tr style="height: 12px;">

    <td style="height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="8">&nbsp;<strong>TOTAL PF:&nbsp;</strong></td>
    <td style="height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="2">&nbsp;<strong>P&nbsp;'.number_format($totalpf,2).'</strong></td>
    <td style="height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="2">&nbsp;</td>
</tr>
        <tr style="height: 12px;">
        <td style="height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="8">&nbsp;<strong>TOTAL CLAIM AMT:&nbsp;</strong></td>
        <td style="border: 1px solid; height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="2">&nbsp;<strong>P&nbsp;'.number_format($totalphicclaim,2).'</strong></td>
        <td style=" height: 12px; vertical-align: top; text-align: right; width: 70%;" colspan="2">&nbsp;</td>
    </tr>
        </table>
        <p><i><i></p>

        ';
        return $output;
    }
}
