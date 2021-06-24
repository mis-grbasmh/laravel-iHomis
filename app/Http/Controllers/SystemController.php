<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Emergencyroom;
use App\Outpatient;
use App\Inpatients;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class SystemController extends Controller
{
    Public Function cancel_encounter(Request $request){
        if($request->ajax())
        {

        $date = Carbon::now()->format('Y-m-d');

        // $er = emergencyroom::where('herlog.hpercode', '=', ''.trim($query).'')
        $er = emergencyroom::where('herlog.erdate', '>=', ''.$date.'')
        ->join('htypser','herlog.tscode','htypser.tscode')
        ->select('enccode','tsdesc as service','erdate as encdate','hpercode','erdtedis as dischargedate','licno as doctor',
        DB::raw("'ER' as type"));
    $opd = Outpatient::where('hopdlog.opddate', '=', ''.$date.'')
        ->join('htypser','hopdlog.tscode','htypser.tscode')
        ->select('enccode','tsdesc as service','opddate as encdate','hpercode','opddtedis as dischargedate','licno as doctor',
        DB::raw("'OPD' as type"));
    $data    = Inpatients::where('admdate', '=', ''.$date.'')
        ->join('htypser','hadmlog.tscode','htypser.tscode')
        ->select('enccode','tsdesc as service','admdate as encdate','hpercode','disdate as dischargedate','licno as doctor',
        DB::raw("'ADM' as type"))
        ->union($er)
        ->union($opd)
    ->orderby('encdate','DESC')
    ->get();




        //$data = DB::table('henctr');
        //Inpatients::Inpatient_canceladmission(Auth::user()->employeeid);

        if (request()->ajax()) {
            return Datatables::of($data)
            ->addColumn('admission', function($inpatient) {
                return getFormattedDate($inpatient->admdate) .' at '. asDateTime($inpatient->admdate).'</strong><br/>
                ';
            })
            ->addColumn('doctor', function($inpatient) {
                return getdoctorinfo($inpatient->licno) .'<br/><small><strong>'. $inpatient->tsdesc.'</strong></small><br/>
                ';
            })
            ->addColumn('patient',function ($inpatient){
                return '<strong>'.getpatientinfo($inpatient->hpercode).'</strong><br/><small>
                '.$inpatient->hpercode.'</small>';
            })

            ->addColumn('msstype',function ($inpatient){
                $drugmeds = DB::table('hrxo')
                ->join('hdmhdrprice', function($join)
                {
                    $join->on('hrxo.dmdcomb','=','hdmhdrprice.dmdcomb');
                    $join->on('hrxo.dmdprdte','=','hdmhdrprice.dmdprdte');
                })
                ->select('hrxo.enccode',
                DB::raw("qtyissued*dmduprice as amount"))
                ->where('hrxo.enccode',$inpatient->enccode)
                ->where('hrxo.rxostatus','A' )
                ->orderby('hrxo.dmdcomb', 'ASC')
                ->first();

                $itemscharges = DB::table('hpatchrg')
                ->join('hcharge','hcharge.chrgcode','hpatchrg.chargcode')
                ->select('hpatchrg.enccode',DB::raw("pchrgqty*pchrgup as amount"))
                ->where('hpatchrg.enccode',$inpatient->enccode)
                ->orderby('hpatchrg.pcchrgdte','DESC')
                ->first();
                return '<small>Drugs and Meds. - Php '. number_format($drugmeds->amount,2).'</br>Charges - Php '. number_format($itemscharges->amount,2).'</small>'
              ;
            })

            ->addColumn('actions',function ($inpatient){
                $enccode = str_replace("-","/",$inpatient->enccode);
                return '
                       <div class="dropdown">
                           <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                               <i class="tim-icons icon-settings-gear-63"></i>
                           </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                               <h6 class="dropdown-header">Select Action</h6>
                                    <a class="dropdown-item btnCoversheet" data-toggle="tooltip" title="Click to view clinical cover sheet " data-placement="bottom" data-id="'.$enccode.'" data-coversheet="/admission/coversheet">Cover Sheet</a>
                                    <a class="dropdown-item btnAdmissionSlip" data-toggle="tooltip" title="Click to view admission slip " data-placement="bottom" data-id="'.$enccode.'" data-admissionslip="/admission/admissionslip">Admission Slip</a>
                                    <a class="dropdown-item btnAdmissionDoctors" data-toggle="tooltip" title="Click to view admission slip " data-placement="bottom" data-id="'.$enccode.'" data-admissiondoctor="/admission/admissionslip">View Doctors</a>
                                    <a class="dropdown-item btnAdmissionRooms" data-toggle="tooltip" title="Click to view admission rooms " data-placement="bottom" data-id="'.$enccode.'" data-admissionrooms="/admission/admissionslip">View Rooms</a>
                                    <a class="dropdown-item btnEdit" data-toggle="tooltip" data-placement="bottom" data-id="'.$enccode.'" data-edit="/admission/edit">Edit Admission</a>
                                    <a class="dropdown-item btnDischarge" data-toggle="tooltip" title="Click to discharge patient" data-placement="bottom" data-id="'.$enccode.'" data-discharge="/admission/discharge">Discharge</a>


                                </div>
                        </div>';
            })

            ->rawColumns(['patient','admission','doctor','msstype','actions'])
            ->make(true);
    }
    return view('administration.cancel_encounter');
}
    }//end function canceladmission
}
