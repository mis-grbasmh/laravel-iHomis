<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Emergencyroom;
use App\Outpatient;
use App\inpatients;
use Yajra\DataTables\Facades\DataTables;

class AnimalbiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){


        return view('animalbite.index');
    }//end function index()

    public function get_patientlist(Request $request){
            $er = emergencyroom::where('herlog.tscode', '=', "012")
            ->join('htypser','herlog.tscode','htypser.tscode')
            ->select('herlog.enccode as id','tsdesc as service','erdate as encdate','herlog.patage','herlog.hpercode as healthrecno','erdtedis as dischargedate','herlog.dispcode as disposition','licno as doctor',
            DB::raw("'ER' as type"));
            $data = Outpatient::where('hopdlog.tscode', '=',  "012")
             ->join('htypser','hopdlog.tscode','htypser.tscode')
            ->select('hopdlog.enccode as id','tsdesc as service','opddate as encdate','hopdlog.patage','hopdlog.hpercode as healthrecno','opddtedis as dischargedate','hopdlog.opddisp as disposition','licno as doctor',
             DB::raw("'OPD' as type"))
            ->union($er)
            ->orderby('encdate','DESC')
            ;

           // $inpatients=$er->limit(20)
            $inpatients=$data->limit(20)
              ->get();
              if (request()->ajax()) {
                  return Datatables::of($inpatients)
                  ->addColumn('admission', function($inpatient) {
                      return ' <small>'.getFormattedDate($inpatient->encdate) .' at '. asDateTime($inpatient->encdate).'<br/><strong>'.$inpatient->service.'</small>'
                   ;
                  })
                  ->addColumn('doctor', function($inpatient) {
                    if($inpatient->doctor)
                    return '<small>'.getdoctorinfo($inpatient->doctor) .'</small>';
                    else
                    return 'No Doctor Assigned';
                  })
                  ->addColumn('patient',function ($inpatient){
                      return '<small><strong>'.getpatientinfo($inpatient->healthrecno).'</strong><br/> '. $inpatient->patsex.', '.number_format($inpatient->patage).' year(s) old <br/>
                      '.$inpatient->healthrecno.'</small>';
                  })
                  ->editColumn('dischargedate',function ($inpatient){
                    // ClinicalDisposition(
                    if($inpatient->dischargedate && $inpatient->disposition)
                    return '<small>'.getFormattedDate($inpatient->dischargedate).' at '. asDateTime($inpatient->dischargedate). '<br/>'.$inpatient->disposition.'</small>';
                    else
                    return '<button type="submit" class="btn btn-default btn-sm btnDischarge" data-remove="/animalbite/'.$inpatient->enccode.'/discharge">Discharge</button>';
                  })

                  ->addColumn('actions',function ($inpatient){
                      $enccode = str_replace("-","/",$inpatient->id);
                      return '
                             <div class="dropdown">
                                 <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                                     <i class="tim-icons icon-settings-gear-63"></i>
                                 </button>
                                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                                     <h6 class="dropdown-header">Select Action</h6>
                                          <a class="dropdown-item"   href="#" onclick=patientcharges("'.$enccode.'") title="Click to do view Patient Charges">Patient Charges</a>
                                          <a class="dropdown-item"   href="#" onclick=doctorsorder("'.$enccode.'") title="Click to do view Doctors Order">Doctors Order</a>
                                          <a class="dropdown-item"   href="#" onclick=patientdoctors("'.$enccode.'") title="Click to do view Doctors">View Doctor</a>
                                          <a class="dropdown-item btnDischarge" data-toggle="tooltip" title="Click to discharge patient" data-placement="bottom" data-id="'.$enccode.'" data-discharge="/admission/discharge">Discharge</a>
                                      </div>
                              </div>';
                  })
                  ->rawColumns(['patient','admission','doctor','type','dischargedate','actions'])
                  ->make(true);

    }
    }

}
