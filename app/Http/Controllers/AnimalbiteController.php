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
use App\Hencdiag;
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
            ->select('hopdlog.id as id','tsdesc as service','opddate as encdate','hopdlog.patage','hopdlog.hpercode as healthrecno','opddtedis as dischargedate','hopdlog.opddisp as disposition','licno as doctor',
             DB::raw("'OPD' as type"))
         //   ->union($er)
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
                    return '<button type="submit" class="btn btn-default btn-sm btnDischarge" data-id="'.$inpatient->id.'" data-discharge="/animalbite/'.$inpatient->id.'/discharge">Discharge</button>';
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
                                     <a class="dropdown-item btnEdit" data-toggle="tooltip" data-placement="bottom" data-id="'.$enccode.'" data-edit="/opdlog/edit">Edit OPD Log</a>
                                     <a class="dropdown-item btnAnimalBiteForm" data-toggle="tooltip" title="Click to discharge patient" data-placement="bottom" data-id="'.$enccode.'" data-animalbiteform="/er/animalbiteform">Animal Bite Form</a>
                                     <a class="dropdown-item "   href="#" onclick=animalbiteform("'.$enccode.'") title="Click to do view Animal Bite Form">Animal Bite Form</a>
                                     <a class="dropdown-item"   href="#" onclick=doctorsorder("'.$enccode.'") title="Click to do view Doctors Order">View Immunization Book</a>
                              </div>';
                  })
                  ->rawColumns(['patient','admission','doctor','type','dischargedate','actions'])
                  ->make(true);

    }
    }


    Public Function Animalbitelog_edit($id){
        if (request()->ajax()) {
            $data = Outpatient::where('hopdlog.id',$id)
                ->join('hanimalbites','hanimalbites.enccode','=','hopdlog.enccode','LEFT')
                ->first();
            return response()->json(
                [
                    'enccode'        => $data->enccode,
                    'hpercode'       => $data->hpercode,
                    'patientname'    => getpatientinfo($data->hpercode),
                                        'licno'         => $data->licno

                    // 'bed' => $bed
                ]
            );//end response
        }
    }
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function animalbite_pdf($id){

        //$enccode = str_replace("-","/",$id);
        $pdf=App::make('dompdf.wrapper');
        $pdf->setPaper('short', 'portrait');
       // $pdf->setPaper(array(0, 0, 612.00, 900.00),'landscape');
        $pdf->loadHTML($this->convert_animalbite_data_to_html($id));
        return $pdf->stream();
    }//end function animabite_pdf

    function convert_animalbite_data_to_html($id)
    {   $data = DB::table('hopdlog')
        ->join('hperson','hperson.hpercode','hopdlog.hpercode')
        ->join('htypser','htypser.tscode','hopdlog.tscode')

        ->where('hopdlog.id',$id)
        ->first();

        $animalbite_data = DB::table('hanimalbites')->where('enccode',$data->enccode)->first();

        $operations = getOperationproc($id);
        if($operations){
                $operation_done = $operations->procdesc .', '. $operations->hplrem;
        }else{
            $operation_done='None';
        }

        $telno = DB::table('htelep')->where('htelep.hpercode',$data->hpercode)
        ->where('patteltype','RESID')->first();
        if($telno){
            $tel_no=$telno->pattel;
        }else{
            $tel_no ='None';
        }

        $vitals = DB::table('hvitalsign')->select('vsbp','vstemp','vspulse','vsresp')->orderby('datelog','DESC')->first();
        $informant = DB::table('henctr')->where('enccode',$id)
            ->select('patinform','patinfadd','patinftel','relacode')
            ->first();

            $findx = Hencdiag::getPatientDiagnosis($id,'FINDX');
            if($findx){
                $finaldiagnosis = $findx->diagtext;
                $final_icd = $findx->diagcode_ext;
            }else{
                $finaldiagnosis='';
                $final_icd ='';
            }

        //     if($data->disdate){
        //     $findx = hencdiag::where('enccode',$id)->where('tdcode','FINDX')->
        //         orderby('encdate','DESC')->first();
        //         if($findx){
        //             $finaldiagnosis = $findx->admtxt;
        //             $final_icd = $findx->diagcode_ext;
        //         }
        // }else{
        //     $finaldiagnosis = '';
        //     $final_icd = '';
        // }

        if($data->newold == 'N'){ $new = 'X';}else{$new = '&nbsp;&nbsp;';}
        if($data->newold == 'O'){ $old = 'X';}else{$old = '&nbsp;&nbsp;';}
        if($data->opddisp == 'DISCH'){ $discharge = 'X';}else{$discharge = '&nbsp;&nbsp;';}
        if($data->opddisp == 'TRANS'){ $transfered = 'X';}else{$transfered = '&nbsp;&nbsp;';}
        if($data->opddisp == 'DAMA'){ $dama = 'X';}else{$dama = '&nbsp;&nbsp;';}
        if($data->opddisp == 'DIEDD'){ $diedd = 'X';}else{$diedd = '&nbsp;&nbsp;';}
        if($data->opddisp == 'ABSC'){ $absconded = 'X';}else{ $absconded = '&nbsp;&nbsp;';}
        if($data->opddisp == 'EXPIR'){ $expired = 'X';}else{ $expired = '&nbsp;&nbsp;';}

        if($data->opddtedis){
            $disc_date = getFormattedDate($data->opddtedis);
            $disc_time = asDateTime($data->opddtedis);
        }else{
            $disc_date ='';
            $disc_time = '';
        }

        // if($data->condcode =='RECOV'){ $recovered = 'X'; }else{ $recovered = '&nbsp;&nbsp;';}
        // if($data->condcode =='DIENA'){ $diedna = 'X'; }else{ $diedna = '&nbsp;&nbsp;'; }
        // if($data->condcode =='IMPRO'){ $improved = 'X'; }else{ $improved = '&nbsp;&nbsp;'; }
        // if($data->condcode =='UNIMP'){ $unimproved = 'X'; }else{ $unimproved = '&nbsp;&nbsp;'; }
        // if($data->condcode =='DIEMI'){ $diemi = 'X'; }else{ $diemi = '&nbsp;&nbsp;'; }
        // if($data->condcode =='DIENA'){ $diena = 'X'; }else{ $diena = '&nbsp;&nbsp;'; }
        // if($data->condcode =='DIEPO'){ $diepo = 'X'; }else{ $diepo = '&nbsp;&nbsp;'; }
        // if($data->condcode =='DPONA'){ $dpona = 'X'; }else{ $dpona = '&nbsp;&nbsp;'; }

        $output='
<style>
    body {font-family: sans-serif; margin: 0; text-align: justify; font-size: 0.8em;}
    p {text-align: justify; margin-left: 5px;margin-right: 5px; margin-top: 5px; margin-bottom: 5px; padding-left: 0px;}
    @page { margin:20px;
}

table td, table th
{
    padding-left: 3px;
}
</style>

    <table style="border-collapse: collapse; width: 100%; height: 100px;" border="1">
        <tbody style="font-size:12px">
        <tr style="height: 18px;">
            <td style="height: 18px; vertical-align: top; text-align: right;" colspan="15"><strong>HSP-033-NUR-0</strong></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top; text-align: center; width: 70%;" colspan="15"><strong>Republic of the Philippines</strong></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top; text-align: center; width: 70%;" colspan="15"><strong>PROVINCE OF ILOCOS NORTE</strong></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top; text-align: center; width: 70%;" colspan="15"><strong>Laoag City</strong></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top; text-align: center; width: 70%;" colspan="15"><strong>GOV. ROQUE B. ABLAN SR. MEMORIAL HOSPITAL</strong></td>
        </tr>
        <tr style="height: 18px;">
            <td style="height: 18px; vertical-align: top; text-align: center; width: 70%;" colspan="15"><strong>" PHIC Accredited Health Care Provider "   <br/> <br/>ANIMAL BITES<br/></strong></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top; width: 60%; text-align: right;" colspan="9"><p>HEALTH RECORD NO. : <strong>'.$data->hpercode.'</strong></p></td>
            <td style="vertical-align: top; width: 40%;" colspan="6"><p style="text-align: right">DATE: <strong>'.getFormattedDate($data->opddate).'</strong></p></td>
        </tr>


        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="12"><p style="text-align:left;">PATIENT NAME: <strong>'.$data->patlast.', '.$data->patfirst.' '.$data->patmiddle.'<strong></p></td>
            <td style="vertical-align: top;  colspan="2"><p>AGE: <strong>' .number_format($data->patage).' yr(s) old</strong></p></td>
            <td style="vertical-align: top;  colspan="1"><p>SEX:<strong>' .$data->patsex.'</strong></p></td>
            <td style="vertical-align: top;  colspan="2"><p>CIVIL STATUS: <strong>' .getcivilstatusdesc($data->patcstat).'</strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="5"><p>WEIGHT (Kg) :<strong> 73</strong></p></td>
            <td style="vertical-align: top;" colspan="4"><p>CIVIL STATUS: <strong></strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="15"><p>ADDRESS :<strong>'.getpatientaddress($data->hpercode).'</strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="15"><strong><p>Status of Immunization:</p></strong></td>
         </tr>
        <tr style="height: 18px;">

            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p style="text-align: right" >Tetanus: </p><strong></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="5"><p>[&nbsp;&nbsp;&nbsp;] Within the last 5 Years</p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="5"><p>[&nbsp;&nbsp;&nbsp;] More than 5 Years</p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="2"><p>[&nbsp;&nbsp;&nbsp;] Unknown</p></td>
        </tr>


        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p  style="text-align: right">Rabies: </p><strong></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="9"><p>[&nbsp;&nbsp;&nbsp;] Yes, (pls, specify date of last Immunization) </p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="2"><p> asdasd </p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="1"><p>[&nbsp;&nbsp;&nbsp;] None </p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="4"><strong><p>Date of Bite :</p></strong></td>

            <td style="vertical-align: top;" colspan="3"><strong><p>Site of Bite :</p></strong></td>
            <td style="vertical-align: top;" colspan="8"><p style="text-align:justify;">sadsadasds</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="8"><p>Type of Exposure (Category) :</p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="2"><p>[&nbsp;&nbsp;&nbsp;]<strong> I</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p>[&nbsp;&nbsp;&nbsp;]<strong> II</strong></p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="1"><p>[&nbsp;&nbsp;&nbsp;] III </p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="6"><p>BITING ANIMAL :</p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="2"><p>[&nbsp;&nbsp;&nbsp;]<strong> Dog</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p>[&nbsp;&nbsp;&nbsp;]<strong> Cat</strong></p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="4"><p>[&nbsp;&nbsp;&nbsp;] Others, pls. specify </p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="5"><p>Status :</p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p>[&nbsp;&nbsp;&nbsp;]<strong> Healthy</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p>[&nbsp;&nbsp;&nbsp;]<strong> Sick</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="2"><p>[&nbsp;&nbsp;&nbsp;]<strong> Unknown</strong></p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="2"><p>[&nbsp;&nbsp;&nbsp;] Dead (Date of Death)</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="5"><p>Owner :</p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p>[&nbsp;&nbsp;&nbsp;]<strong> Family</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="4"><p>[&nbsp;&nbsp;&nbsp;]<strong> Relative</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="1"><p>[&nbsp;&nbsp;&nbsp;]<strong> Neighbor</strong></p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="2"><p>[&nbsp;&nbsp;&nbsp;] Others, pls. specify</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 70px; vertical-align: top; height: 18px;" colspan="7"><p>Status as to Rabies Immunization:</p></td>
            <td style="width: 30%; vertical-align: top; height: 18px;" colspan="5"><p>[&nbsp;&nbsp;&nbsp;] <strong>Unimmunized</strong></p>
            </td>
            <td style="width: 30%; vertical-align: top; height: 18px;" colspan="3"><p><strong>[&nbsp;&nbsp;&nbsp;] <strong>Immunized</strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="7"><p>If Immunized: No. of times :<strong>3 times</strong></p></td>
            <td style="vertical-align: top;" colspan="8"><p>Date of last Immunization</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p>Moblity :</p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="5"><p>[&nbsp;&nbsp;&nbsp;]<strong> Caged</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="7"><p>[&nbsp;&nbsp;&nbsp;]<strong> Unleashed and roamed the streets</strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="5"><p>[&nbsp;&nbsp;&nbsp;]<strong> Leashed Always</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="7"><p>[&nbsp;&nbsp;&nbsp;]<strong> Unleashed but confined to the yard</strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="5"><p>[&nbsp;&nbsp;&nbsp;]<strong> Caged</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="7"><p>[&nbsp;&nbsp;&nbsp;]<strong> Unleashed and roamed the streets</strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="12"><p>[&nbsp;&nbsp;&nbsp;]<strong> Leashed most of the time but regularly roams the streets</strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 70px; vertical-align: top; height: 18px;" colspan="7"><p>* Laboratory Examinations :</p></td>
            <td style="width: 30%; vertical-align: top; height: 18px;" colspan="3"><p>[&nbsp;&nbsp;&nbsp;] <strong>Yes</strong></p></td>
            <td style="width: 30%; vertical-align: top; height: 18px;" colspan="5"><p><strong>[&nbsp;&nbsp;&nbsp;] <strong>Yes (result)</strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="15"><strong><p>POST EXPOSURE TREATMENT :</p></strong></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="7"><p>Wound Care :</p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="3"><p>[&nbsp;&nbsp;&nbsp;]<strong> Yes</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="5"><p>[&nbsp;&nbsp;&nbsp;]<strong> No</strong></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="7">If Yes, how </td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="6"><p>[&nbsp;&nbsp;&nbsp;] Wound wash with soap and water</p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="2"><p>[&nbsp;&nbsp;&nbsp;] Tandok Applied </p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="7"></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="4"><p>[&nbsp;&nbsp;&nbsp;] Others</p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="4"><p>[&nbsp;&nbsp;&nbsp;] Please specify</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="4"><p><strong>PLANS :</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="5"><p>[&nbsp;&nbsp;&nbsp;]<strong> Antibiotic</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="4"><p>[&nbsp;&nbsp;&nbsp;]<strong> Rabies Immunization :</strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="1"><p>[&nbsp;&nbsp;&nbsp;]<strong> Passive</strong></p></td>
            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="1"><p>[&nbsp;&nbsp;&nbsp;] Active</p></td>
        </tr>
         <tr style="height: 18px;">
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="4"><p><strong></strong></p></td>
            <td style="width: 5px; vertical-align: top; height: 18px;" colspan="5"><p>[&nbsp;&nbsp;&nbsp;]<strong> Observe Animal for</strong></p></td>

            <td style="width: 15px; vertical-align: top; height: 18px;" colspan="6"><p>[&nbsp;&nbsp;&nbsp;] Advised</p></td>
        </tr>
        </tbody>
        </table>
<br/><br/><br/>
        <table style="border-collapse: collapse; width: 100%; height: 100px;" border="1">
        <thead class=" text-primary">
        <th>DAY</th>
        <th>TYPE OF VACCINE</th>
        <th>SITE</th>
        <th>DATE GIVEN</th>
        <th>DUE DATE</th>
        <th>GIVEN BY</th>
        </thead>
    <tbody style="font-size:12px">
        <tr>
            <td><p>0</p></td>
            <td><p>0</p></td>
            <td><p>0</p></td>
            <td><p>0</p></td>
            <td><p>0</p></td>
            <td><p>0</p></td>
        </tr>
        <tr>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        </tr>
        <tr>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        </tr>
        <tr>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        </tr>
        <tr>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        <td><p>0</p></td>
        </tr>
    </tbody>
    </table>

        <p style="font-size:9px"><em>Report generated by i-Homis WEB 1.0</em></p>
        ';

        return $output;
    }



}
