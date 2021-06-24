<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inpatients;
use App\Patients;
use App\Servicetype;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Wards;
use App\Doctors;
use App\Patientrooms;
use App\Hencdiag;
use App\Syslogs;
use App\Translogs;
use Yajra\DataTables\Facades\DataTables;
use App\Export;
use App\DataTables\ExportDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\ExportDataTables;
use Validator;

class InpatientController extends Controller
{
    Public  $dispositions = array(
        'DISCH'     =>  'Discharged',
        'TRANS'     =>  'Transferred',
        'DAMA'      =>  'Discharge Against Medical Advise',
        'ABSC'      =>  'Absconded',
        'EXPIR'     =>  'Expired'
    );

    Public $admissiontypes = array(
        'ADPAY'     => 'Pay',
        'SERVI'     => 'Service'
    );


    // Public $servicecasetypes = array(
    //     'HP'    => 'House Private (HP)',
    //     'PW'    => 'Private Walkin (PW)',
    //     'VP'    => 'Visiting Private (VP)',
    //     'CP'    => 'Charity (CP)'
    // );

    Public $conditions = array(
        'RECOV'     =>  'Recovered',
        'IMPRO'     =>  'Improved',
        'UNIMP'     =>  'Unimproved',
        'DIEMI'     =>  'Died < 48 hours Autopsied',
        'DIENA'     =>  'Died < 48 hours not autopsied',
        'DIEPO'     =>  'Died > 48 hours autopsied',
        'DPONA'     =>  'Died > 48 hours not autopsied'
    );


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id='')
    {
        $doctors = Doctors::getActiveDoctors('RESID');
        $diagnosis = DB::table('hdiag')
        ->where('diagstat','A')
        ->get();
        $reasonsfortrans = DB::table('herlog')->select('reftxt')->distinct('reftxt')->get();
        $inpatients =  Inpatients::Inpatientlist($id);

        $count = $inpatients->countBy(function ($item) {
                    return $item['patsex'];
                 });

                 $males = $count->get('M');
                 $females = $count->get('F');

                $count_patientsbyservice = $inpatients->countBy(function ($item) {
                    return $item['tsdesc'];
                });
                $pedia = $count_patientsbyservice->get('PEDIATRICS');
                $ob = $count_patientsbyservice->get('OBSTETRICS');
                $sur =$count_patientsbyservice->get('SURGERY');
                $meds =$count_patientsbyservice->get('MEDICAL');

        if (request()->ajax()) {
            return Datatables::of($inpatients)
            ->addColumn('admission', function($inpatient) {
                return getFormattedDate($inpatient->admdate) .' at '. asDateTime($inpatient->admdate).'<br/><strong>'.$inpatient->wardname.'-'.$inpatient->rmname.'-'.$inpatient->bdname.'</strong><br/>
                <small>LOS: '. \Carbon\Carbon::parse($inpatient->admdate)->diffInDays(\Carbon\Carbon::now()).'day(s)</small>';
            })
            ->addColumn('doctor', function($inpatient) {
                return getdoctorinfo($inpatient->licno) .'<br/><small><strong>'. $inpatient->tsdesc.'</strong></small><br/>
                <span class="badge badge-primary">'.$inpatient->hsepriv.'</span>';
            })
            ->addColumn('patient',function ($inpatient){
                return '<strong>'.getpatientinfo($inpatient->hpercode).'</strong><br/><small> '. getGender($inpatient->patsex).', '.number_format($inpatient->patage).' year(s) old <br/>
                '.$inpatient->hpercode.'</small>';
            })

            ->addColumn('msstype',function ($inpatient){
                return getmssclassification($inpatient->enccode)
              ;
            })
            ->addColumn('clerk',function ($inpatient){
                return getemployeeinfo($inpatient->admclerk);
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
                               <a class="dropdown-item btnCoversheet" data-toggle="tooltip" title="Click to view clinical cover sheet " data-placement="bottom" data-id="'.$enccode.'" data-coversheet="/admission/coversheet">Coversheet</a>
                               <a class="dropdown-item btnClinicalAbstract" data-toggle="tooltip" title="Click to view clinical abstract " data-placement="bottom" data-id="'.$enccode.'" data-clinicalabstract="/admission/clinicalabstract">Clinical Abstract</a>

                                    <a class="dropdown-item btnAdmissionSlip" data-toggle="tooltip" title="Click to view admission slip " data-placement="bottom" data-id="'.$enccode.'" data-admissionslip="/admission/admissionslip">Admission Slip</a>
                                    <a class="dropdown-item btnAdmissionDoctors" data-toggle="tooltip" title="Click to view admission slip " data-placement="bottom" data-id="'.$enccode.'" data-admissiondoctor="/admission/admissionslip">View Doctors</a>
                                    <a class="dropdown-item btnAdmissionRooms" data-toggle="tooltip" title="Click to view admission rooms " data-placement="bottom" data-id="'.$enccode.'" data-admissionrooms="/admission/admissionslip">View Rooms</a>
                                    <a class="dropdown-item btnEdit" data-toggle="tooltip" data-placement="bottom" data-id="'.$enccode.'" data-edit="/admission/edit">Edit Admission</a>
                                    <a class="dropdown-item btnDischarge" data-toggle="tooltip" title="Click to discharge patient" data-placement="bottom" data-id="'.$enccode.'" data-discharge="/admission/discharge">Discharge</a>


                                </div>
                        </div>';
            })

            ->rawColumns(['patient','admission','doctor','msstype','clerk','actions'])
            ->make(true);
    }

 // $tag = "<center><a class='btn btn-info btn-xs' onclick=editNewModal('".$index->id."')><i class='fa fa-pencil'></i> Ubah</a>";
                // $tag .= "<a class='btn btn-danger btn-xs' onclick=delNewModal('".$index->id."')><i class='fa fa-trash'></i> Hapus</a></center>";
               // return $tag;




             $countall = count($inpatients);

    //    //     $inpatients = $data->get();
    //     if($id==''){
    //        $inpatients = $inpatients->paginate(25);

    //     }else{
    //        $inpatients = $inpatients->where('hward.wardname',$id)->paginate(5);
    //      //  $countall = count($data->where('hward.wardname',$id)->get());
    //     }
    //

        return view('transactions.admitting.index',compact('id','countall','males','females','pedia','ob','sur','meds'))
            ->with('wards',Wards::all())
            ->with('doctors',$doctors)
            ->with('dispositions', $this->dispositions)
            ->with('conditions', $this->conditions)
            ->with('reasonsfortrans',$reasonsfortrans)
            ->with('diagnosis',$diagnosis)
            ->with('admissiontypes',$this->admissiontypes)
            // ->with('servicecasetypes',$this->servicecasetypes)
            ->with('servicetypes',DB::table('htypser')->get());
    }


 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id = '')
    {
        $doctors = Doctors::getActiveDoctors('RESID');
        $diagnosis = DB::table('hdiag')
        ->where('diagstat','A')
        ->get();
        $reasonsfortrans = DB::table('herlog')->select('reftxt')->distinct('reftxt')->get();
        $inpatients =  Inpatients::Inpatientlist($id);

        if (request()->ajax()) {
            return Datatables::of($inpatients)

            ->addColumn('admission', function($inpatient) {
                return getFormattedDate($inpatient->admdate) .' at '. asDateTime($inpatient->admdate).'<br/><strong>'.$inpatient->wardname.'-'.$inpatient->rmname.'-'.$inpatient->bdname.'</strong><br/>
                <small>Length of Stay: '. \Carbon\Carbon::parse($inpatient->admdate)->diffInDays(\Carbon\Carbon::now()).'day(s)</small>';
            })
            ->addColumn('doctor', function($inpatient) {
                return getdoctorinfo($inpatient->licno) .'<br/><small><strong>'. $inpatient->tsdesc.'</strong></small><br/>
                <span class="badge badge-primary">'.$inpatient->hsepriv.'</span>';
            })
            ->addColumn('patient',function ($inpatient){
                return '<strong>'.getpatientinfo($inpatient->hpercode).'</strong><br/> '. $inpatient->patsex.', '.number_format($inpatient->patage).' year(s) old <br/><small>
                '.$inpatient->hpercode.'</small>';
            })

            ->addColumn('msstype',function ($inpatient){
                return getmssclassification($inpatient->enccode)
              ;
            })
            ->addColumn('clerk',function ($inpatient){
                return getemployeeinfo($inpatient->admclerk);
            })
            ->addColumn('actions',function ($inpatient){
                $enccode = str_replace("-","/",$inpatient->enccode);
                return '

                <div class="dropdown">
                           <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                               <i class="tim-icons icon-settings-gear-63"></i>
                           </button>
                           <div class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                               <h6 class="dropdown-header">Select Action</h6>
                                    <a class="dropdown-item" data-toggle="tooltip" title="Click to do view Patient Charges" onclick="patientcharges('.$inpatient->enccode.');return false;" href="#">Patient Charges</a>
                                    <a class="dropdown-item btnAdmissionDoctors" data-toggle="tooltip" title="Click to view admission slip " data-placement="bottom" data-id="'.$enccode.'" data-admissiondoctor="/admission/admissionslip">View Doctors</a>
                                    <a class="dropdown-item discharge"
                                                data-toggle="modal" data-toggle="tooltip" title="Click to discharge patient" data-placement="right" data-target="#discharge" data-keyboard="false" data-backdrop="static"
                                                data-id="{{ $inpatient->enccode}}"
                                                data-hpercode="{{ $inpatient->hpercode}}"
                                                data-licno="{{ $inpatient->licno}}"
                                                data-patient="'.getpatientinfo($inpatient->hpercode).'"
                                                href="#">Discharge</i>
                                    </a>
                                </div>
                        </div>';
            })
            ->rawColumns(['patient','admission','doctor','msstype','clerk','actions'])
            ->make(true);

    }

 // $tag = "<center><a class='btn btn-info btn-xs' onclick=editNewModal('".$index->id."')><i class='fa fa-pencil'></i> Ubah</a>";
                // $tag .= "<a class='btn btn-danger btn-xs' onclick=delNewModal('".$index->id."')><i class='fa fa-trash'></i> Hapus</a></center>";
               // return $tag;




    //         $countall = count($inpatients->get());

    //    //     $inpatients = $data->get();
    //     if($id==''){
    //        $inpatients = $inpatients->paginate(25);

    //     }else{
    //        $inpatients = $inpatients->where('hward.wardname',$id)->paginate(5);
    //      //  $countall = count($data->where('hward.wardname',$id)->get());
    //     }
    //     $count = $inpatients->countBy(function ($item) {
    //         return $item['patsex'];
    //     });

    //     $count_patientsbyservice = $inpatients->countBy(function ($item) {
    //         return $item['tsdesc'];
    //     });
    //     $pedia = $count_patientsbyservice->get('PEDIATRICS');
    //     $males = $count->get('M');
    //     $females = $count->get('F');


        return view('transactions.admitting.index',compact('id'))
            ->with('wards',Wards::all())
            ->with('doctors',$doctors)
            ->with('dispositions', $this->dispositions)
            ->with('conditions', $this->conditions)
            ->with('reasonsfortrans',$reasonsfortrans)
            ->with('diagnosis',$diagnosis)
            ;
    }

/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($id){
        $data = Patients::getPatientInfo($id);
        $recordexist = DB::table('hencdiag')->where('hpercode',$id)->get();
        if($recordexist){
            $oldnew = 'O';
        }else{
            $oldnew = 'N';
        }

        return response()->json(
            [
                'hpercode'        => $data->hpercode,
                'patient_name'      => getpatientinfo($data->hpercode),
                'servicetypes'  => DB::table('htypser')->where('tsstat','A')->get(),
                'oldnew'        => getNewold($oldnew),
            ]
        );
    }

/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $enccode = str_replace("-","/",$id);
        $data = DB::table('hadmlog')
        ->where('enccode',$enccode)
        // ->select('hadmlog.enccode','hadmlog.hpercode','hadmlog.casenum','hadmlog.patage','hadmlog.patagemo','hadmlog.patagedy','hadmlog.hsepriv','hadmlog.patagehr','hadmlog.newold','hadmlog.tacode','hadmlog.tscode','hadmlog.licno','hadmlog.admpreg','hadmlog.admtxt')
        ->first();
        if($data->newold =='N'){
           $newold = 'New Patient ';
        }else{
                $newold = 'Old Patient';
        }//endif
        return response()->json(
            [
                'enccode'        => $data->enccode,
                'patientname'    => getpatientinfo($data->hpercode),
                'patientage' => number_format($data->patage).' year(s) old '.number_format($data->patagemo).' Month(s) '.$data->patagedy.' Day(s)',
                'admdate'    => date('Y-m-d\TH:i', strtotime($data->admdate)),
                'admtxt'    => $data->admtxt,
                'admnotes'  => $data->admnotes,
                'doctor'    => 'DR. '. Getdoctorinfo($data->licno),
                'licno'     => $data->licno,
                'tacode'    => $data->tacode,
                'hsepriv'   => $data->hsepriv,
                'servicetype' => getservicetype($data->hsepriv),
                'tscode'=> $data->tscode,
                'newold'    => $newold
            ]
        );
    }//end function edit



    Public Function canceladmission(Request $request){
        $inpatients =  Inpatients::Inpatient_canceladmission(Auth::user()->employeeid);

        if (request()->ajax()) {
            return Datatables::of($inpatients)
            ->addColumn('admission', function($inpatient) {
                return getFormattedDate($inpatient->admdate) .' at '. asDateTime($inpatient->admdate).'<br/><strong>'.$inpatient->wardname.'-'.$inpatient->rmname.'-'.$inpatient->bdname.'</strong><br/>
                ';
            })
            ->addColumn('doctor', function($inpatient) {
                return getdoctorinfo($inpatient->licno) .'<br/><small><strong>'. $inpatient->tsdesc.'</strong></small><br/>
                ';
            })
            ->addColumn('patient',function ($inpatient){
                return '<strong>'.getpatientinfo($inpatient->hpercode).'</strong><br/><small> '. getGender($inpatient->patsex).', '.number_format($inpatient->patage).' year(s) old <br/>
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
            ->addColumn('clerk',function ($inpatient){
                return getemployeeinfo($inpatient->admclerk);
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

            ->rawColumns(['patient','admission','doctor','msstype','clerk','actions'])
            ->make(true);
    }
    return view('transactions.admitting.admission_cancel');
    }//end function canceladmission
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admissionslip_pdf($id){
        $enccode = str_replace("-","/",$id);
        $pdf=App::make('dompdf.wrapper');
    //   $pdf->setPaper('A3', 'portrait');
      $pdf->setPaper(array(0, 0, 297.00, 420.00),'portratit');
        $pdf->loadHTML($this->convert_admissionslip_data_to_html($enccode));
        return $pdf->stream();

        // $dompdf = new Dompdf();
        // $dompdf->loadHtml($this->input);
        // $dompdf->setPaper('letter');
        // $dompdf->render();
        // //Page numbers
        // $font = $dompdf->getFontMetrics()->getFont("Arial", "bold");
        // $dompdf->getCanvas()->page_text(16, 770, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0, 0, 0));
        // echo $dompdf->output();

    }
function convert_admissionslip_data_to_html($id){
    {   $data = DB::table('hadmlog')
        ->join('hperson','hperson.hpercode','hadmlog.hpercode')
        ->join('hpatroom as A','A.enccode','hadmlog.enccode')
        ->join('hbed','A.bdintkey','hbed.bdintkey')
        ->join('hroom','hroom.rmintkey','A.rmintkey')
        ->join('hward','hward.wardcode','A.wardcode' )
        ->join('htypser','htypser.tscode','hadmlog.tscode')
        ->where('hadmlog.enccode',$id)
        ->first();
        if($data->tacode =='SERVI'){
            $service = 'X';
            $pay = '&nbsp;&nbsp;';
        }else{
            $service = '&nbsp;&nbsp;';
            $pay = 'X';
        }
        if($data->hsepriv){
            $type = $data->hsepriv;
        }else{
            $type = 'N/A';
        }
        if($data->patsex =='M'){
            $male = 'X';
            $female = '&nbsp;&nbsp;';
        }else{
            $female = 'X';
            $male = '&nbsp;&nbsp;';
        }


    $output ='
      <style>
   body {font-family: sans-serif; margin: 0; text-align: justify; font-size: 0.8em;}
   p {text-align: justify; margin-left: 5px;margin-right: 5px; margin-top: 5px; margin-bottom: 5px; padding-left: 0px;}
   @page { margin:20px;
   }


</style>
<table style="width: 100%; border-collapse: collapse; height: 400px;" border="0" cellspacing="1" cellpadding="1">
<tbody style="font-size:12px">
<tr style="height: 142px;">
<td style="width: 141.597%; vertical-align: top; height: 10px; text-align: right;" colspan="7" scope="rowgroup"><strong>HSP-002-NUR-1</strong></td>
</tr>
<tr style="height: 18px;">
<td style="width: 141.597%; text-align: center; height: 15px;" colspan="7">Republic of the Philippines</td>
</tr>
<tr style="height: 18px;">
<td style="text-align: center; height: 10px; width: 141.597%;" colspan="7"><strong>PROVINCE OF ILOCOS NORTE</strong></td>
</tr>
<tr style="height: 18px;">
<td style="text-align: center; width: 141.597%;" colspan="7"><strong>GOV. ROQUE B. ABLAN SR. MEMORIAL HOSPITAL</strong></td>
</tr>
<tr style="height: 18px;">
<td style="width: 141.597%; text-align: center;" colspan="7">P. Gomez Street Brgy. 21, Laoag City</td>
</tr>
<tr style="height: 23px;">
<td style="text-align: center;" colspan="7"><strong><span style="text-decoration: underline;">ADMISSION SLIP</span></strong></td>
</tr>

<tr style="height: 20px;">
<td style="text-align: left;" colspan="3">TO: <em>SOCIAL SERVICE OFFICE</em></td>
<td style="text-align: right; width: 75.8242%; height: 10px;" colspan="4">DATE:<span style="text-decoration: underline;"> '.getformatteddate($data->admdate).'</span></td>
</tr>
<tr style="height: 10px;">
<td style="height: 10px; text-align: left; width: 50.7728%;" colspan="3">OLD HOSPITAL NO.</td>
<td style="text-align: right; width: 75.8242%; height: 20px;" colspan="4">TIME: <span style="text-decoration: underline;"> '.asDateTime($data->admdate).'</span></td>
</tr>
<tr style="height: 25px;">

<td style="width: 122.661%; height: 25px; border-style: inset;" colspan="6">NAME:<span style="text-decoration: underline;"> '.getpatientinfo($data->hpercode).'</span></td>
</tr>
<tr style="height: 25px;">

<td colspan="2">AGE:&nbsp;'.number_format($data->patage).' yr(s) old</td>
<td style="text-align: right;">SEX:['.$male.'] M ['.$female.'] F</td>
<td style="text-align: right;" colspan="4">C. STATUS <span style="text-decoration: underline;"> '.getcivilstatusdesc($data->patcstat).'</span></td>
</tr>
<tr style="height: 0px;">
<td style="width: 141.597%; height: 40px; vertical-align: top;" colspan="6">ADDRESS:<br/><span style="text-decoration: underline;">'.getpatientaddress($data->hpercode).'&nbsp;</td>
</span></tr>
<tr style="height: 25px;">
<td style="height: 25px; width: 141.597%;" colspan="6">WARD/ROOM/BED:<span style="text-decoration: underline;"> '.$data->wardname.'/'.$data->rmname.'/'.$data->bdname.'/'.$data->tsdesc.'</span></td>
</tr>
<tr style="height: 18px;">
<td style="width: 141.597%; height: 18px;" colspan="6">HOSPITAL NO.: <span style="text-decoration: underline;">&nbsp;'.$data->hpercode.'</span></td>
</tr>
<tr style="height: 18px;">
<td style="height: 18px; width: 141.597%;" colspan="6"><strong>TYPE OF ACCOMODATION/SERVICE</strong></td>
</tr>
<tr style="height: 25px;">
<td style="height: 25px; width: 141.597%;" colspan="6">['.$pay.'] PAY ['.$service.'] SERVICE &nbsp;CASE TYPE:&nbsp;'.$type.'</td>
</tr>
<tr style="height: 50px;">
<td style="height: 80px; width: 141.597%;" colspan="6">ADMTTING DIAGNOSIS<BR/><br/><span style="text-decoration: underline;">'.$data->admtxt.'</span></td>
</tr>
<tr style="height: 25px;">
<td style="width: 141.597%; height: 25px;" colspan="6">ADMITTING NURSE: <span style="text-decoration: underline;"> </span></td>
</tr>
<tr style="height: 25px;">
<td style="width: 141.597%; height: 25px;" colspan="6">ADMITTING CLERK: <span style="text-decoration: underline;">'.getemployeeinfo($data->entryby).'</span></td>
</tr>

<tr style="height: 25px;">
<td style="width: 141.597%; height: 25px;" colspan="6">ADMITTING PHYSICIAN: <span style="text-decoration: underline;">NEMA S. QUEJA, MD</span></td>
</tr>


</tbody>
</table>
';
return $output;
    }


}


/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inpatientslist_pdf(){

        $pdf=App::make('dompdf.wrapper');
       // $pdf->setPaper('long', 'landscape');
        $pdf->setPaper(array(0, 0, 612.00, 936.00),'landscape');
        $pdf->loadHTML($this->convert_patientlist_to_html());
        return $pdf->stream();

    }
    function convert_patientlist_to_html()
    {
        $inpatients =  Inpatients::Inpatientlist('');
        $output='
       <style>
       @page {margin: 1cm 1cm 1cm;}
        body {font-family: sans-serif; margin: 0; text-align: justify; font-size: 0.8em;}
        #header, #footer {position: fixed; left: 0; right: 0; font-size: 0.7em;}
        #header {top: 0;}
        #footer {bottom: 0; text-align: center;}
        #editorial_title {font-size: 18px; font-weight: bold;}
        ul {padding: 0; margin: 0 0 20px 0; list-style-type:square; }
        ul#editorial_assistant {margin-bottom: 50px;}
        ul#editorial_assistant li {margin-left: 13px;}
        #date {font-size: 22px; font-weight: bold; color: #113D76; margin: 0; padding: 0;}
        #office_title {font-size: 20px; font-weight: bold;}
        #links a {font-size: 20px; font-weight: bold; color: #113D76; text-decoration: underline; line-height: 30px;}
        #links li {list-style-type: none; padding-bottom: 10px;}
        h2 {font-size: 20px;}
        hr {border: 0 none; page-break-after: always;}
        p {text-align: justify; margin-left: 0px; padding-left: 0px;}
        #first_page {padding-left: 118px; width: 600px;}
        #content {font-family: sans-serif; font-size: 1.1em; padding-left: -40px; width: 740px;}
    #content p {line-height: 20px;}
        #last_page {font-size: 0.8em; padding-left: -40px;}
    #last_page p {margin-bottom: 20px; text-align: left;}
        #footer_text {font-size: 0.9em;}
        .line {border-bottom: 2px solid #113D76; width:581px;}
        #head_table {position: fixed; bottom: 230px; right: 0; width:552px; font-size: 0.8em;}
        #head_table td p {text-align: justify; }
        #head_table td {line-height: 15px;}
        table.report_title h2 {text-align: left; width: 550px;}
        .report_date span {margin: 0; text-align: right; width: 180px;}
    </style>
    <img src="assets/img/logo.png" alt="" sizes="(min-width: 36em) 33.3vw, 100vw">

        <h5 align="center" style="color:#201D1E">In-Patients List</h5>
       <table style="border-collapse:collapse;border=0px;">

        <tr align="center">
        <th width = "10px" style="border: 1px solid">#</th>
            <th width = "40px" style="border: 1px solid">Health Rec. No.</th>
            <th  width = "30px" style="border: 1px solid">Patient Name</th>
            <th width = "10px" style="border: 1px solid">Admission <br/>Date & Time</th>
            <th width = "10px" style="border: 1px solid">Admission<br/>Type</th>
            <th width = "8px" style="border: 1px solid">Case<br/>Type</th>

            <th width = "40px" style="border: 1px solid">Physician</th>

        </tr>';
        //  <th width = "40px" style="border: 1px solid">Diagnosis</th>
        //<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">DR. '.$patient->admtxt.'</td>
        foreach ($inpatients as $key => $patient)
        {
            $output .= '

    <tr>
<td align="right" style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;">'.($key+1).'.</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;">'.$patient->hpercode.'</td>
<td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.getpatientinfo($patient->hpercode).'<br/>
'.$patient->patsex.', '.number_format($patient->patage).' year(s) old</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.getFormattedDate($patient->admdate).'<br/>'.asDateTime($patient->admdate).'<br/>'.$patient->tsdesc.'</td>
<td align="center" style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->tacode.'</td>
<td align="center" style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->hsepriv.'</td>

<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">DR. '.getdoctorinfo($patient->licno).'</td>

    </tr>


            ';
        }

        $output .= '
        </table>
        <p><i><i></p>
        <p><em>Report generated by Webihomis</em></p>
        ';
        return $output;
    }





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coversheet_pdf($id){
        $enccode = str_replace("-","/",$id);
        $pdf=App::make('dompdf.wrapper');
        $pdf->setPaper('short', 'portrait');
       // $pdf->setPaper(array(0, 0, 612.00, 900.00),'landscape');
        $pdf->loadHTML($this->convert_erpatient_data_to_html($enccode));
        return $pdf->stream();

    }

    function convert_erpatient_data_to_html($id)
    {   $data = DB::table('hadmlog')
        ->join('hperson','hperson.hpercode','hadmlog.hpercode')
        ->join('hpatroom as A','A.enccode','hadmlog.enccode')
        ->join('hbed','A.bdintkey','hbed.bdintkey')
        ->join('hroom','hroom.rmintkey','A.rmintkey')
        ->join('hward','hward.wardcode','A.wardcode' )
        ->join('htypser','htypser.tscode','hadmlog.tscode')

        ->where('hadmlog.enccode',$id)
        ->first();

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
        if($data->dispcode == 'DISCH'){ $discharge = 'X';}else{$discharge = '&nbsp;&nbsp;';}
        if($data->dispcode == 'TRANS'){ $transfered = 'X';}else{$transfered = '&nbsp;&nbsp;';}
        if($data->dispcode == 'DAMA'){ $dama = 'X';}else{$dama = '&nbsp;&nbsp;';}
        if($data->dispcode == 'DIEDD'){ $diedd = 'X';}else{$diedd = '&nbsp;&nbsp;';}
        if($data->dispcode == 'ABSC'){ $absconded = 'X';}else{ $absconded = '&nbsp;&nbsp;';}
        if($data->dispcode == 'EXPIR'){ $expired = 'X';}else{ $expired = '&nbsp;&nbsp;';}

        if($data->disdate){
            $disc_date = getFormattedDate($data->disdate);
            $disc_time = asDateTime($data->disdate);
        }else{
            $disc_date ='';
            $disc_time = '';
        }

        if($data->condcode =='RECOV'){ $recovered = 'X'; }else{ $recovered = '&nbsp;&nbsp;';}
        if($data->condcode =='DIENA'){ $diedna = 'X'; }else{ $diedna = '&nbsp;&nbsp;'; }
        if($data->condcode =='IMPRO'){ $improved = 'X'; }else{ $improved = '&nbsp;&nbsp;'; }
        if($data->condcode =='UNIMP'){ $unimproved = 'X'; }else{ $unimproved = '&nbsp;&nbsp;'; }
        if($data->condcode =='DIEMI'){ $diemi = 'X'; }else{ $diemi = '&nbsp;&nbsp;'; }
        if($data->condcode =='DIENA'){ $diena = 'X'; }else{ $diena = '&nbsp;&nbsp;'; }
        if($data->condcode =='DIEPO'){ $diepo = 'X'; }else{ $diepo = '&nbsp;&nbsp;'; }
        if($data->condcode =='DPONA'){ $dpona = 'X'; }else{ $dpona = '&nbsp;&nbsp;'; }

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
            <td style="height: 18px; vertical-align: top; text-align: right; width: 70%;" colspan="15"><strong>HSP-01-NUR-1</strong></td>

        </tr>
        <tr style="height: 18px;">
            <td style="width: 60px; vertical-align: top; height: 18px;" colspan="7">NAME OF HOSPITAL:<strong>GOV. ROQUE B. ABLAN SR. MEMORIAL HOSPITAL</strong></td>
            <td style="width: 40%; vertical-align: top; height: 18px;" colspan="8">HOSP. CODE: <strong>'.$data->hfhudcode.'</strong></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 60px; vertical-align: top; height: 18px;" colspan="7">ADDRESS:&nbsp;<strong><p>P. GOMEZ ST. BRGY. 21 LAOAG CITY, ILOCOS NORTE, PHILIPPPINES,2900</p></strong></td>
            <td style="width: 40%; vertical-align: top; height: 18px;" colspan="8">HEALTH REC. NO. <strong>&nbsp;'.$data->hpercode.'</strong></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 30%; height: 10px; vertical-align: top;" colspan="2">SR. CITIZEN NO.&nbsp;<strong><p>'.$data->srcitizen.'</p></strong></td>
            <th  height: 5px; text-align: center; vertical-align: top;" colspan="5" scope="row">
                <h2 style="text-align: center;"><strong><span style="color: #ffcc00;">CLINICAL COVER SHEET</span></strong></h2>
            </th>
<td style="width: 30%; vertical-align: top;" colspan="8">OLD. HEALTH REC. NO. <strong><p></p></strong></td>
</tr>
<tr style="height: 18px;">
            <td style="width: 70px; vertical-align: top; height: 18px;" colspan="4">PATIENT NAME: (LASTNAME, FIRSTNAME, MIDDLENAME)<br/>
           <strong><p style="text-align:justify;">'.$data->patlast.', '.$data->patfirst.' '.$data->patmiddle.'.</p></strong></td>
            <td style="width: 30px; vertical-align: top; height: 18px;" colspan="11">WARD/ROOM/BED/SERVICE&nbsp;<br/>
            <strong><p>'.$data->wardname.'/ '.$data->rmname.'/ '.$data->bdname.'/ '.$data->tsdesc.'</p></strong></td>
        </tr>
        <tr style="height: 18px;">
        <td style="width: 80px; vertical-align: top; height: 18px;" colspan="5">PERMANENT ADDRESS<br/>
        <strong>'.getpatientaddress($data->hpercode).'</strong></td>
        <td style="width: 15%; vertical-align: top; height: 18px;" colspan="2">TEL NO.:<br/>
        <strong>'.$tel_no.'</strong></td>
        <td style="width: 15%; vertical-align: top; height: 18px;" colspan="3">SEX<br/>
        &nbsp;<strong>'.$data->patsex.'</strong>&nbsp;</td>
        <td style="width: 20%; vertical-align: top; height: 18px;" colspan="5">CIVIL STATUS<br/>
        <strong>'.getcivilstatusdesc($data->patcstat).'</strong>&nbsp;</td>
        </tr>
        <tr style="height: 18px;">
        <td style="width: 5px; vertical-align: top; height: 18px;" colspan="1">BIRTHDATE:&nbsp;<strong><br/>'.getformatteddate($data->patbdate).'</strong></td>
        <td style="width: 5px; vertical-align: top; height: 18px;" colspan="1">AGE<strong><br/>'.number_format($data->patage).' yr(s) old</strong></td>
        <td style="width: 15px; vertical-align: top; height: 18px;" colspan="1">BIRTH PLACE<strong><br/>'.$data->patbplace.'</strong></td>
        <td style="width: 15px; vertical-align: top; height: 18px;" colspan="2">NATIONALITY<strong><br/>'.getPatNationality($data->hpercode).'</strong></td>
        <td style="width: 15px; vertical-align: top; height: 18px;" colspan="4">RELIGION<strong><br/>'.getPatReligion($data->hpercode).'</strong></td>
        <td style="width: 15px; vertical-align: top; height: 18px;" colspan="6">OCCUPATION<strong><br/>'.convertOccupationType($data->patempstat).'</strong></td>
    </tr>

    <tr style="height: 18px;">
        <td style="width: 70px; vertical-align: top; height: 18px;" colspan="3">FATHERS NAME<br/><strong>'.getFatherinfo($data->hpercode).'</strong></td>
        <td style="vertical-align: top;" colspan="5">ADDRESS<br/><strong>'.$data->fataddr.'</strong></td>
        <td style="width: 30%; vertical-align: top; height: 18px;" colspan="7">TEL. NO.<BR/> <strong>'.$data->fattel.'</strong></td>
    </tr>

    <tr style="height: 18px;">
    <td style="width: 70px; vertical-align: top; height: 18px;" colspan="3">MOTHERS (MAIDEN) NAME<br/>
        <strong>'.getMotherinfo($data->hpercode).'</strong></td>
    <td style="width: 30%; vertical-align: top; height: 18px;" colspan="5">ADDRESS<br/>
        <strong>'.$data->motaddr.'</strong></td>
    <td style="width: 30%; vertical-align: top; height: 18px;" colspan="7">TEL. NO.:<br/><strong>'.$data->mottel.'</strong></td>
</tr>
<tr style="height: 18px;">
<td style="width: 70px; vertical-align: top; height: 18px;" colspan="3">SPOUSE NAME<br/>
    <strong>'.getSpouseinfo($data->hpercode).'</strong></td>
<td style="width: 30%; vertical-align: top; height: 18px;" colspan="5">ADDRESS<br/>
    <strong>'.$data->spaddr.'</strong></td>
<td style="width: 30%; vertical-align: top; height: 18px;" colspan="7">TEL. NO.:<br/><strong>00000043</strong></td>
</tr>\
<tr style="height: 18px;">
    <td style="vertical-align: top; height: 18px;" colspan="2">ADMISSION:<br/>
        DATE: <strong>'.getFormattedDate($data->admdate).'</strong>
        <br/>
        TIME: <strong>'.asDateTime($data->admdate).'</strong>
    </td>
    <td style="vertical-align: top; height: 18px;" colspan="1">DISCHARGE:<br/>
    DATE:<strong> '.$disc_date.'</strong>
    <br/>
    TIME:<strong> '.$disc_time.'</strong>
</td>
<td style="vertical-align: top; text-align: center; height: 18px; font-size: 10px; colspan="1">TOTAL NO.<br/> OF DAYS <br/>
     <strong><p style="font-size: 12px;">'.\Carbon\Carbon::parse($data->admdate)->diffInDays(\Carbon\Carbon::parse($data->disdate)).'</p></strong></td>
<td style="vertical-align: top;" colspan="11">ADMITTING PHYSICIAN:<br/><br/>
    <strong>'.getdoctorinfo($data->licno).'</strong></td>

</tr>
<tr style="height: 18px;">
<td style="width: 50px; vertical-align: top; height: 18px;" colspan="2">ADMITTING CLERK:<br/><br/>
    <strong>'.getemployeeinfo($data->entryby).'</strong>&nbsp;</td>
<td style="width: 30%; vertical-align: top; height: 18px;" colspan="2">TYPE OF ADMISSION:<br/><br/>
         ['.$new.'] New &nbsp;&nbsp; ['.$old.'] Old &nbsp;&nbsp; [&nbsp;&nbsp;] Former OPD</td>
<td style="width: 30%; vertical-align: top; height: 18px;" colspan="11">MSS CLASSIFICATION: '.getmssclassification($id).'<br/>
MSS No.:<strong>'.$data->mssno.'<br/>&nbsp;
     </strong></td>
</tr>
<tr style="height: 18px;">
    <td style="width: 70px; vertical-align: top; height: 18px;" colspan="2">ALLERGIC TO:<br/><br/>
        <strong>&nbsp;</strong>
    </td>
    <td style="width: 70px; vertical-align: top; height: 18px;" colspan="1">HOSPITALIZATION PLAN:<br/>
    <strong>&nbsp;<br/></strong>


</td>
<td style="width: 30%; vertical-align: top; height: 18px;" colspan="3">HEALTH INSURANCE NAME<br/>
     <strong></strong></td>
<td style="width: 30%; vertical-align: top; height: 18px;" colspan="9">PHIC<br/><br/>
    <strong>&nbsp;</strong></td>

</tr>
<tr style="height: 18px;">
<td style="vertical-align: top; height: 18px;" colspan="3">DATA FURNISHED BY: (Signature over Printed Name)<br/><br/>
    <strong><p style="text-align:center;">'.$informant->patinform.'</p></strong></td>
<td style="vertical-align: top; height: 18px;" colspan="6">ADDRESS OF INFORMANT<br/><br/>
    <strong><p>'.$informant->patinfadd.'</p></strong></td>
<td style="vertical-align: top; height: 18px;" colspan="6">CONTACT. NO.<br/><br/><p><strong>'.$informant->patinftel.'</p></strong></td>
</tr>
<tr style="height: 40px;">
<td style="vertical-align: top;"  colspan="9">ADMITTING DIAGNOSIS:
    <strong><p>'.$data->admtxt.'</p></strong></td>
<td style="vertical-align: top;" colspan="6">ICD CODE 10:<br/>
    &nbsp;<strong></strong></td>
</tr>
<tr style="height: 50px;">
<td style="width: 70px; vertical-align: top; height: 25px;" colspan="9">PRINCIPAL DIAGNOSIS:
    <strong><p>'.$finaldiagnosis.'</p></strong></td>
<td style="width: 30%; vertical-align: top; height: 25px;" colspan="6">ICD CODE 10:<br/>
    <strong><p>'.$final_icd.'</p></strong></td>
</tr>
<tr style="height: 50px;">
<td style="width: 70px; vertical-align: top; height: 25px;" colspan="15">OTHER DIAGNOSIS:<br/>
    &nbsp;<strong></strong>&nbsp;</td>
</tr>
<tr>
    <td style="width: 70px; vertical-align: top; height: 25px;" colspan="15">PRINCIPAL OPERATION/PROCEDURES:<br/><strong><p> '.$operation_done.'</p></strong></td>
</tr>
<tr>
    <td style="width: 70px; vertical-align: top; height: 25px;" colspan="15">OTHER OPERATION/PROCEDURES:<br/><strong><p> '.$operation_done.'</p></strong> </td>
</tr>
<tr>
<td style="width: 70px; vertical-align: top; height: 25px;" colspan="15">ACCIDENT/INJURIES/POISONING (ECODE):<br/><strong><p> </p></strong> </td>
</tr>

<tr style="height: 50px;">
<td style="width: 70px; vertical-align: top; height: 25px;" colspan="15">PLACE OF COCCURENCE:
    </td>
</tr>
<tr style="height: 50px;">
<td style="width: 100px; vertical-align: top; height: 25px;" colspan="3">DISPOSITION:<br/>&nbsp;&nbsp; ['.$discharge.'] DISCHARGE &nbsp;&nbsp;&nbsp;&nbsp; ['.$dama.'] DAMA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ['.$diedd.'] EXPIRED <br/><br/>
&nbsp;&nbsp; ['.$transfered.'] TRANSFERED &nbsp;&nbsp;&nbsp;&nbsp;['.$absconded.'] ABSCONDED<br/>
 <p/>
</td>
<td style="width: 100px; vertical-align: top; height: 25px;" colspan="12">CONDITION:<br/>
 ['.$recovered.'] RECOVERED &nbsp; ['.$diedna.'] DIED &nbsp; ['.$diedna.'] -48 HOURS &nbsp;&nbsp; ['.$diemi.'] AUTOPSY
<br/><br/>
['.$improved.'] IMPROVED &nbsp;['.$unimproved.'] UNIMPROVED  [&nbsp;&nbsp;] +48 HOURS &nbsp; [&nbsp;&nbsp;] AUTOPSY<br/>
</td>
</tr>
<tr style="height: 25px;">
<td style="width: 70px; vertical-align: top; height: 25px;" colspan="3">
    <p>Blood Pressure:<strong>'.$vitals->vsbp.'</strong>
    &nbsp;Temperature:<strong>'.$vitals->vstemp.'</strong> &nbsp;Pulse:<strong>'.$vitals->vspulse.'</strong><br/>
    Weight:<strong>'.$vitals->vstemp.'</strong>&nbsp;Respiration:<strong>'.$vitals->vsresp.'</strong>&nbsp;
    Blood Type:<strong>'.$data->bloodtype.'</strong></p>
</td>
<td style="width: 100px; vertical-align: top; height: 18px;" colspan="12"><p>New Born
&nbsp;Weight: <strong>'.$vitals->vstemp.'</strong> &nbsp;Kgs.
&nbsp;Height: <strong>'.$vitals->vstemp.'</strong> &nbsp;Cms. <p/></td>
</tr>
        </tbody>
        </table>
        <p style="font-size:9px"><em>Report generated by i-Homis WEB 1.0</em></p>
        ';

        return $output;
    }


/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getdischargeinfo($id){
        $enccode = str_replace("-","/",$id);
        $data = DB::table('hadmlog')
        ->where('enccode',$enccode)
        // ->select('hadmlog.enccode','hadmlog.hpercode','hadmlog.casenum','hadmlog.patage','hadmlog.patagemo','hadmlog.patagedy','hadmlog.hsepriv','hadmlog.patagehr','hadmlog.newold','hadmlog.tacode','hadmlog.tscode','hadmlog.licno','hadmlog.admpreg','hadmlog.admtxt'
       //)
        ->first();
        $reasonsfortrans = DB::table('herlog')->select('reftxt')->distinct('reftxt')->get();

        $isdiagexist = DB::table('hencdiag')->where('enccode',$enccode)
        ->where('tdcode','FINDX')->first();
            if($isdiagexist){
                $diagnosis = $isdiagexist->diagtext;
                $code = $isdiagexist->diagcode_ext;
            }else{
                $diagnosis = '';
                $code = '';
            }

            // $assignedroom= DB::table('hpatroom')
            //         ->select(' hpatroom.bdintkey','hpatroom.rmintkey','hpatroom.hprdate','hpatroom.hprtime')
            //         ->where('hpatroom.enccode',$enccode)
            //         // ->where('hpatroom.patrmstat','A')
            //         ->where('hpatroom.hprdate','=',DB::raw("(select max(p.hprdate) from hpatroom p where hpatroom.enccode = p.enccode and  p.patrmstat = 'A' )"))
            //         ->first();
            // $bed = $assignedroom->bedactual;
        return response()->json(
            [
                'enccode'        => $data->enccode,
                'hpercode'       => $data->hpercode,
                'patientname'    => getpatientinfo($data->hpercode),
                'licno'         => $data->licno,
                'dispositions'  =>$this->dispositions,
                'conditions'    =>$this->conditions,
                'reasonsfortrans' => $reasonsfortrans,
                'code'      => $code,
                'diagnosis'      => $diagnosis
                // 'bed' => $bed
            ]
        );
    }

    Public function store(Request $request,$id){
        try{
            $enccode  = str_replace("-","/",$id);
            $data = Inpatients::where('enccode','=',$enccode)
                ->first();

                    DB::table('hadmlog')
                        ->where('hadmlog.enccode','=',$enccode)
                        ->update([
                            'admdate' => Carbon::parse($request->input('admdate'))->format('Y-m-d H:i:s'),
                            'admtime' => Carbon::parse($request->input('admdate'))->format('Y-m-d H:i:s'),
                            'tacode' => $request->input('tacode'),
                            'tscode' => $request->input('tscode'),
                            'admnotes' => $request->input('admnotes'),
                            'admtxt' => $request->input('admtxt'),
                            'licno' => $request->input('licno'),
                            'hsepriv' => $request->input('hsepriv'),
                            'user_id' => auth::user()->id,
                            'updated_at' => carbon::now()
                        ]);

      //  //Insert transaction log
       $translogs = new Translogs();
       $translogs->user_name = auth::user()->name;
       $translogs->tbl_name ='hadmlog';
       $translogs->primary_keys = $enccode;
       $translogs->tran_date =  carbon::now();
       $translogs->ue_mode = 'U';
       $translogs->sys_desc='Admission Log - Update';
       $translogs->user_id = auth::user()->id;
       $translogs->save();
                        return response()->json(array("success"=>true));
            }catch(\Exception $excpetion){
                return redirect()->back()->with('An error occurred!');
            }
    }

    Public function update(Request $request,$id){
        try{
      $enccode  = str_replace("-","/",$id);
      $data = Inpatients::where('enccode','=',$enccode)
          ->first();

              DB::table('hadmlog')
                  ->where('hadmlog.enccode','=',$enccode)
                  ->update([
                      'admdate' => Carbon::parse($request->input('admdate'))->format('Y-m-d H:i:s'),
                      'admtime' => Carbon::parse($request->input('admdate'))->format('Y-m-d H:i:s'),
                      'tacode' => $request->input('tacode'),
                      'tscode' => $request->input('tscode'),
                      'admnotes' => $request->input('admnotes'),
                      'admtxt' => $request->input('admtxt'),
                      'licno' => $request->input('licno'),
                      'hsepriv' => $request->input('hsepriv'),
                      'user_id' => auth::user()->id,
                      'updated_at' => carbon::now()
                  ]);

//  //Insert transaction log
  //$translogs = new Translogs();
//  $translogs->user_name = auth::user()->name;
 //$translogs->tbl_name ='hadmlog';
 //$translogs->primary_keys = $enccode;
 //$translogs->tran_date =  carbon::now();
 //$translogs->ue_mode = 'U';
 //$translogs->sys_desc='Admission Log - Update';
 //$translogs->user_id = auth::user()->id;
 //$translogs->save();
                  return response()->json(array("success"=>true));
      }catch(\Exception $excpetion){
          return redirect()->back()->with('An error occurred!');
      }
  }//update



  Public function discharge(Request $request,$id){
    try{



        $enccode  = str_replace("-","/",$id);
  $data = Inpatients::where('enccode','=',$enccode)
      ->first();
          DB::table('hadmlog')
              ->where('hadmlog.enccode','=',$enccode)
              ->update([
                  'disdate' => Carbon::parse($request->input('disdate'))->format('Y-m-d H:i:s'),
                  'distime' => Carbon::parse($request->input('disdate'))->format('Y-m-d H:i:s'),
                  'dispcode' => $request->input('dispcode'),
                  'condcode' => $request->input('condcode'),
                 'dcspinst' => $request->input('dcspinst'),
               //   'hrefto' => $request->input('hrefto'),
                //  'reftxt' => $request->input('reftxt'),
                  'admstat' => 'I',
                //  'disnotes' => 'discharge notes',
                  //pexpireddate if patient expired
                  'user_id' => auth::user()->id,
                  'updated_at' => carbon::now()
              ]);



              DB::table('hpatchrg')
              ->where('enccode','=',$enccode)
              ->update([
                  'pcdisch' => 'Y',
                  'datemod' =>  carbon::now()
                  ]);
        $diagnosis = $request->input('diagtext');
        if($request->input('diagcode_ext')){
                   $codeext = $request->input('diagcode_ext');
                   $subcat =mb_substr($request->input('diagcode_ext'), 0, 3);
        }else
        {
            $codeext = '';
            $subcat ='';
        }
        if($diagnosis){
            $isdiagexist = DB::table('hencdiag')->where('enccode',$enccode)
            ->where('tdcode','FINDX')->first();
                if($isdiagexist){
                    DB::table('hencdiag')
                        ->where('hencdiag.enccode',$enccode)
                        ->where('hencdiag.tdcode','FINDX')
                        ->update([
                            'diagtext' => strtoupper($request->input('diagtext')),
                            'diagcode_ext' => $codeext,
                            'diagsubcat' => $subcat,
                            'user_id' => auth::user()->id,
                            'updated_at' => Carbon::parse($request->dtetake)->format('Y-m-d H:i:s')
                    ]);
                }else{//update final diagnosis
                    $hencdiag = new Hencdiag();
                    $hencdiag->enccode = $enccode;
                    $hencdiag->hpercode = $data->hpercode;
                    $hencdiag->licno = $data->licno;
                    $hencdiag->encdate =  carbon::now();
                    $hencdiag->enctime =  carbon::now();
                    $hencdiag->tdcode = 'FINDX';
                    $hencdiag->edstat = 'A';
                    $hencdiag->edlock = 'N';
                    $hencdiag->primediag = 'Y';
                    $hencdiag->diagtext =  $request->input('diagtext');
                    $hencdiag->diagsubcat = $subcat;
                    $hencdiag->diagcode_ext= $request->input('diagcode_ext');
                    $hencdiag->entryby = Auth::user()->employeeid;
                    $hencdiag->user_id = auth::user()->id;
                    $hencdiag->save();
               }
           }

// $assignedroom= DB::table('hpatroom')
//         ->select(' hpatroom.bdintkey','hpatroom.rmintkey','hpatroom.hprdate','hpatroom.hprtime')
//         ->where('hpatroom.enccode',$enccode)
//         // ->where('hpatroom.patrmstat','A')
//         ->where('hpatroom.hprdate','=',DB::raw("(select max(p.hprdate) from hpatroom p where hpatroom.enccode = p.enccode and  p.patrmstat = 'A' )"))
//         ->first();
//        console($assignedroom);
// if($assignedroom){
//             $is_wardcode = $assignedroom->wardcode;
//             $is_bdcode = $assignedroom->bdintkey;
//             $is_rmintkey =$assignedroom->rmintkey;

//             $bedactual = DB::table('hbed')
//             ->where('hbed.wardcode',$is_wardcode)
//             ->where('hbed.rmintkey',$is_rmintkey)
//             ->where('hbed.bdintkey',$is_bdcode)
//             ->select('hbed.bdactual')
//             ->first();

//             DB::table('hbed')
//             ->where('hbed.wardcode',$is_wardcode)
//             ->where('hbed.rmintkey',$is_rmintkey)
//             ->where('hbed.bdintkey',$is_bdcode)
//              ->update([
//                 'bdvacocc' => 'V',
//                 'updsw' => 'Y',
//                 'datemod' => Carbon::parse($request->dtetake)->format('Y-m-d H:i:s'),
//                 'bdactual' => 0
//             ]);
//             }


           DB::table('hpatroom')
           ->where('enccode','=',$enccode)
           ->update([
               'patrmstat' => 'I',
               'updsw' => 'Y',
               'datemod' => carbon::now()
           ]);
           DB::table('henctr')
           ->where('enccode','=',$enccode)
           ->update([
               'encstat' => 'I',
               'updsw' => 'Y',
               'datemod' => carbon::now()->format('Y-m-d H:i:s')
           ]);
          // $Syslogs = new Syslogs();
            // $Syslogs->prikey = $enccode;
            // $Syslogs->method = 'Update';
            // $Syslogs->description = 'tblhadmlog, discharge patient.';
            // $Syslogs->user_id = auth::user()->id;
            // $Syslogs->token = Hash::make('tblhadmlog, discharge patient.');
            // $Syslogs->datacrypted = Hash::make('tblhadmlog, discharge patient.');
            // $Syslogs->save();
        return response()->json(array("success"=>true));


  }catch(\Exception $excpetion){
      return redirect()->back()->with('An error occurred!');
  }
}//discharge admission

 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dailyadmissions($date='')
    {
    //    if($month==NULL || $year==NULL){
    //     $currentMonth = date('m');
    //     $currentYear = date('y');
    // }
    if($date==NULL){
        $date = Carbon::now()->format('Y-m-d');
    }
    $date1 = Carbon::parse($date. '00:00:00');
    $date2 = Carbon::parse($date. '23:59:59');
        $admissions = Inpatients
        ::where('hadmlog.admdate','>=',$date1)
        ->where('hadmlog.admdate','<=',$date2)
        //wheremonth('admdate',1)
        //->whereyear('admdate','2020')
        ->join('htypser','htypser.tscode','hadmlog.tscode')
        ->join('hperson','hperson.hpercode','hadmlog.hpercode')
         ->select('hadmlog.hpercode','hadmlog.patage','hadmlog.admdate','hadmlog.licno','htypser.tsdesc','hadmlog.entryby','hperson.patsex')
         ->groupby(['hadmlog.hpercode','hadmlog.patage','hadmlog.admdate','hadmlog.licno','htypser.tsdesc','hadmlog.entryby','hperson.patsex'])
        ->get();
        $count_patientsbyservice = $admissions->countBy(function ($item) {
            return $item['tsdesc'];
        });
        $ob = $count_patientsbyservice->get('OBSTETRICS');
        $grouped = $admissions->groupBy('tsdesc');
         $grouped->toArray();

        $count_pedia = 0;
        // $admissions->countBy(function ($item) {
        //     return $item['tsdesc'];
        // });
        $pedia = 0;
        // $count_pedia->get('PEDIATRICS');
        $surgery = 0;
        //$count_pedia->get('SURGERY');

        return view('transactions.admitting.dailyadmissions',compact('admissions','pedia','surgery','date'))
        // ->with('selectedFilter', $id)
        ->with('ob',$ob)
        ->with('grouped',$grouped)
        ->with('services',Servicetype::all());

    }
    public function patient_discharge(Request $request,$id){
        //
        $id  = str_replace("-","/",$request->input('enccode'));
        $data = DB::table('hadmlog')->where('enccode','=',$id)
            ->first();

        $this->validate(request(), [
            'dispcode' => 'required'
        ]);

        try{
            if ($data) {
                $doctype = $request->input('dispcode');
                DB::table('hadmlog')
                    ->where('enccode','=',$id)
                    ->update([
                        'disdate' => $request->input('disdate'),
                        'distime' => $request->input('disdate'),
                        'dispcode' => $request->input('dispcode'),
                        'condcode' => $request->input('condcode'),
                        'dcspinst' => $request->input('dcspinst'),
                        'admstat' => 'I',
                        'user_id' => auth::user()->id,
                        'updated_at' => carbon::now()
                    ]);

                    DB::table('henctr')
                  ->where('enccode','=',$id)
                  ->update([
                      'encstat' => 'I',
                      'updsw' => 'Y',
                      'datemod' => carbon::now()
                  ]);
                  DB::table('hpatroom')
                  ->where('enccode','=',$id)
                  ->update([
                      'patrmstat' => 'I',
                      'updsw' => 'Y',
                      'datemod' => carbon::now()
                  ]);
                  DB::table('hpatchrg')
                  ->where('enccode','=',$id)
                  ->update([
                      'pcdisch' => 'Y',
                      'datemod' =>  carbon::now()
                      ]);

                $assignedroom= DB::table('hpatroom')
                    ->select(' hpatroom.bdintkey','hpatroom.rmintkey','hpatroom.hprdate','hpatroom.hprtime')
                    ->where('hpatroom.enccode',$id)
                    ->where('hpatroom.patrmstat','A')
                    ->where('hpatroom.hprdate','=',DB::raw("(select max(p.hprdate) from hpatroom p where hpatroom.enccode = p.enccode and  p.patrmstat = 'A' )"))
                    ->first();

                    if($assignedroom){
                        $is_wardcode = $assignedroom->wardcode;
                        $is_bdcode = $assignedroom->bdintkey;
				        $is_rmintkey =$assignedroom->rmintkey;
				        $ldt_prdate=$assignedroom->hprdate;
                        $ldt_prtime=$assignedroom->hprtime;

                        $bedactual = DB::table('hbed')
                        ->where('hbed.wardcode',$is_wardcode)
                        ->where('hbed.rmintkey',$is_rmintkey)
                        ->where('hbed.bdintkey',$is_bdcode)
                        ->select('hbed.bdactual')
                        ->first();

                        DB::table('hbed')
                        ->where('hbed.wardcode',$is_wardcode)
                        ->where('hbed.rmintkey',$is_rmintkey)
                        ->where('hbed.bdintkey',$is_bdcode)
                         ->update([
                            'bdvacocc' => 'V',
                            'updsw' => 'Y',
                            'datemod' => Carbon::parse($request->dtetake)->format('Y-m-d H:i:s'),
                            'bdactual' => number_format($bedactual - 1)
                                                    ]);

                    }
                    //Insert transaction log
                    $translog = new Translogs();
                    $translog->user_name = auth::user()->name;
                    $translog->tbl_name ='hadmlog';
                    $translog->primary_keys = $id;
                    $translog->tran_date = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
                    $translog->ue_mode = 'U';
                    $translog->sys_desc='Ward - Discharge';
                    $translog->user_id = auth::user()->id;
                    $translog->save();

                $diagnosis = $request->input('diagtext');
                if($diagnosis){
                    $isdiagexist = DB::table('hencdiag')->where('enccode',$id)
                    ->where('tdcode','FINDX')->first();
                        if(!$isdiagexist){
                            $hencdiag = new Hencdiag();
                            $hencdiag->enccode = $id;
                            $hencdiag->hpercode = $request->hpercode;
                            $hencdiag->licno = $request->input('licno');
                            $hencdiag->encdate = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
                            $hencdiag->enctime = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
                            $hencdiag->tdcode = 'FINDX';
                            $hencdiag->edstat = 'A';
                            $hencdiag->edlock = 'N';
                            $hencdiag->primediag = 'Y';
                            $hencdiag->diagtext = strtoupper($request->input('diagtext'));
                            $hencdiag->diagsubcat = mb_substr($request->input('diagcodeext'), 0, 3);
                            $hencdiag->diagcode_ext= $request->input('diagcodeext');
                            $hencdiag->entryby = Auth::user()->employeeid;
                            $hencdiag->user_id = auth::user()->id;
                            $hencdiag->save();
                        }else{//update final diagnosis
                            DB::table('hencdiag')
                                ->where('hencdiag.enccode',$id)
                                ->where('hencdiag.tdcode','FINDX')
                                ->update([
                                    'diagtext' => strtoupper($request->input('diagtext')),
                                    'user_id' => auth::user()->id,
                                    'updated_at' => Carbon::parse($request->dtetake)->format('Y-m-d H:i:s')
                            ]);
                        }
                }
            return redirect()->back()
            ->with('type','success')
            ->with('msg','Patient Discharge Successfully.');
            }
        }catch(\Exception $excpetion){
            return redirect()->back()->with('An error occurred!');
        }
    }

    public function patient_undodischarge(Request $request,$id=''){
        //
        $id  = str_replace("-","/",$request->input('enccode'));
        $data = DB::table('hadmlog')->where('enccode','=',$id)
            ->first();

        $this->validate(request(), [
            'dispcode' => 'required'

        ]);

        try{
            if ($data) {
                $doctype = $request->input('dispcode');
                DB::table('hadmlog')
                    ->where('enccode','=',$id)
                    ->update([
                        'disdate' => NULL,
                        'distime' => NULL,
                        'dispcode' => NULL,
                        'condcode' => NULL,
                        'dcspinst' => $request->input('dcspinst'),
                        'admstat' => 'A',
                        'user_id' => auth::user()->id,
                        'updated_at' => carbon::now()
                    ]);

                    DB::table('henctr')
                  ->where('enccode','=',$id)
                  ->update([
                      'encstat' => 'A',
                      'updsw' => 'N',
                      'datemod' => carbon::now()
                  ]);
                  DB::table('hpatroom')
                  ->where('enccode','=',$id)
                  ->update([
                      'patrmstat' => 'A',
                      'updsw' => 'N',
                      'datemod' => carbon::now()
                  ]);
                  DB::table('hpatchrg')
                  ->where('enccode','=',$id)
                  ->update([
                      'pcdisch' => 'N',
                      'datemod' =>  carbon::now()
                      ]);

                $assignedroom= DB::table('hpatroom')
                    ->select(' hpatroom.bdintkey','hpatroom.rmintkey','hpatroom.hprdate','hpatroom.hprtime')
                    ->where('hpatroom.enccode',$id)
                    ->where('hpatroom.patrmstat','A')
                    ->where('hpatroom.hprdate','=',DB::raw("(select max(p.hprdate) from hpatroom p where hpatroom.enccode = p.enccode and  p.patrmstat = 'A' )"))
                    ->first();

                    if($assignedroom){
                        $is_wardcode = $assignedroom->wardcode;
                        $is_bdcode = $assignedroom->bdintkey;
				        $is_rmintkey =$assignedroom->rmintkey;
				        $ldt_prdate=$assignedroom->hprdate;
                        $ldt_prtime=$assignedroom->hprtime;

                        $bedactual = DB::table('hbed')
                        ->where('hbed.wardcode',$is_wardcode)
                        ->where('hbed.rmintkey',$is_rmintkey)
                        ->where('hbed.bdintkey',$is_bdcode)
                        ->select('hbed.bdactual')
                        ->first();

                        DB::table('hbed')
                        ->where('hbed.wardcode',$is_wardcode)
                        ->where('hbed.rmintkey',$is_rmintkey)
                        ->where('hbed.bdintkey',$is_bdcode)
                         ->update([
                            'bdvacocc' => 'V',
                            'updsw' => 'Y',
                            'datemod' => Carbon::parse($request->dtetake)->format('Y-m-d H:i:s'),
                            'bdactual' => $bedactual -1
                        ]);

                    }








                // $diagnosis = $request->input('diagtext');
                // if($diagnosis){
                //     $isdiagexist = DB::table('hencdiag')->where('enccode',$id)
                //     ->where('tdcode','FINDX')->first();
                //         if(!$isdiagexist){
                //             $hencdiag = new Hencdiag();
                //         $hencdiag->enccode = $id;
                //         $hencdiag->hpercode = $request->hpercode;
                //         $hencdiag->licno = $request->input('licno');
                //         $hencdiag->encdate = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
                //         $hencdiag->enctime = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
                //         $hencdiag->tdcode = 'FINDX';
                //         $hencdiag->edstat = 'A';
                //         $hencdiag->edlock = 'N';
                //         $hencdiag->primediag = 'Y';
                //         $hencdiag->diagtext = strtoupper($request->input('diagtext'));
                //         $hencdiag->diagsubcat = mb_substr($request->input('diagcodeext'), 0, 3);
                //         $hencdiag->diagcode_ext= $request->input('diagcodeext');
                //         $hencdiag->entryby = auth::user()->id;
                //         $hencdiag->user_id = auth::user()->id;
                //         $hencdiag->save();
                // }

            //}
            return redirect()->back()
            ->with('type','success')
            ->with('msg','Patient Discharge Successfully.');
            }
        }catch(\Exception $excpetion){
            return redirect()->back()->with('An error occurred!');
        }
    }


    public function daily_discharges($date='')
    {
        $doctors = Doctors::getActiveDoctors('RESID');
        $reasonsfortrans = DB::table('herlog')->select('reftxt')->distinct('reftxt')->get();
        $diagnosis = DB::table('hdiag')
        ->where('diagstat','A')
        ->get();
            if($date==NULL){
                $date = Carbon::now()->format('Y-m-d');
            }
             $discharges = Inpatients::DischargesList($date);
             $count_patientsbyservice = $discharges->countBy(function ($item) {
                 return $item['tsdesc'];
             });
             $ob = $count_patientsbyservice->get('OBSTETRICS');
             $grouped = $discharges->groupBy('tsdesc');
              $grouped->toArray();

             $count_pedia = 0;
             // $admissions->countBy(function ($item) {
             //     return $item['tsdesc'];
             // });
             $pedia = 0;
             // $count_pedia->get('PEDIATRICS');
             $surgery = 0;
             //$count_pedia->get('SURGERY');

             return view('transactions.wards.daily_discharges',compact('discharges','pedia','surgery'))
             ->with('date', $date)
             ->with('ob',$ob)
             ->with('grouped',$grouped)
             ->with('doctors',$doctors)
             ->with('dispositions', $this->dispositions)
             ->with('conditions', $this->conditions)
             ->with('reasonsfortrans',$reasonsfortrans)
             ->with('diagnosis',$diagnosis)
             ->with('admissiontypes',$this->admissiontypes)
            // ->with('servicecasetypes',$this->servicecasetypes())
             ->with('servicetypes',DB::table('htypser')->get());
             }



     public Function get_PatientRooms(Request $request){
         if($request->ajax()){
            $query = $request->get('query');
            $enccode  = str_replace("-","/",$query);
            $admissionrooms = Patientrooms::get_patientrooms($enccode);
            if($admissionrooms->count() <> 0){
                return Datatables::of($admissionrooms)
                ->with('wards',Wards::all())
                ->toJson();

            }

         }
     }


     public function Patient_rooms($id){
    //
        // public function Patient_rooms(Request $request){
        //     if($request->ajax())

        //     {
        //         $query = $request->get('query');
                $enccode  = str_replace("-","/",$id);
        $admissionrooms = Patientrooms::get_patientrooms($enccode);
        //$hpercode='';
       // $admdiagnosis = Inpatients::getAdmissionbyId($enccode);
      //  $hpercode = $admissionrooms->hpercode;

        // if($admissionrooms->count() <> 0){
        //    $enccode = $admissionrooms->enccode;
        //    $hpercode = $admissionrooms->hpercode;

        // }
       // return response()->json(

        return view('transactions.admitting.admission_rooms',compact('admissionrooms','enccode'))
        ->with('wards',Wards::all());
            }


/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clinicalabstract_pdf($id){
        $enccode = str_replace("-","/",$id);
        $pdf=App::make('dompdf.wrapper');
        $pdf->setPaper('short', 'portrait');
       // $pdf->setPaper(array(0, 0, 612.00, 900.00),'landscape');
        $pdf->loadHTML($this->convert_clinicalabstract_data_to_html($enccode));
        return $pdf->stream();
    }

    function convert_clinicalabstract_data_to_html($id)
    {   $data = DB::table('hadmlog')
        ->join('hperson','hperson.hpercode','hadmlog.hpercode')
        ->join('hpatroom as A','A.enccode','hadmlog.enccode')
        ->join('hbed','A.bdintkey','hbed.bdintkey')
        ->join('hroom','hroom.rmintkey','A.rmintkey')
        ->join('hward','hward.wardcode','A.wardcode' )
        ->join('htypser','htypser.tscode','hadmlog.tscode')

        ->where('hadmlog.enccode',$id)
        ->first();

        $hospitalinfo = DB::table('fhud_hospital')
            ->join('hprov','hprov.provcode','fhud_hospital.provcode')
            ->join('hcity','hcity.ctycode','fhud_hospital.ctycode')
            ->join('hbrgy','hbrgy.bgycode','fhud_hospital.brgy')
            ->where('hfhudcode',auth::user()->hosp_id)->first();
            $chiefcomplaint="";
            $history ="";
            $chiefcomplaint = DB::table('hmrhisto')->where('histype','COMPL')
            ->where('enccode',$id)
            ->first();
            if($chiefcomplaint){
                $chiefcomplaint = $chiefcomplaint->history;
            }

            $historyillness = DB::table('hmrhisto')->where('histype','PRHIS')
            ->where('enccode',$id)
            ->orderby('datemod','DESC')
            ->get();

            $courseward = DB::table('hcrsward')
            ->where('enccode',$id)
            ->orderby('dtetake','ASC')
            ->get();
            $course='';
            foreach($courseward as $key => $row){
                $course = $course . trim($row->crseward).'<br/>';
            }


            //dd($course);
            $history='';
            if($historyillness){

            foreach($historyillness as $key => $row){
                $history = $history. trim($row->history);
            }
            }else{
                $history="";
            }
            $operation_done='';
            $operations = DB::table('hproclog')->where('hproclog.enccode',$id)
            ->join('hproc','hproc.prikey','hproclog.prikey')
            ->join('hprocm','hprocm.proccode','hproc.proccode')
            ->first();
            if($operations){
                $operation_done = $operations->procdesc;
                $operation_anesth = $operations->anestype;
            }



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
        if($data->dispcode == 'DISCH'){ $discharge = 'X';}else{$discharge = '&nbsp;&nbsp;';}
        if($data->dispcode == 'TRANS'){ $transfered = 'X';}else{$transfered = '&nbsp;&nbsp;';}
        if($data->dispcode == 'DAMA'){ $dama = 'X';}else{$dama = '&nbsp;&nbsp;';}
        if($data->dispcode == 'DIEDD'){ $diedd = 'X';}else{$diedd = '&nbsp;&nbsp;';}
        if($data->dispcode == 'ABSC'){ $absconded = 'X';}else{ $absconded = '&nbsp;&nbsp;';}
        if($data->dispcode == 'EXPIR'){ $expired = 'X';}else{ $expired = '&nbsp;&nbsp;';}

        if($data->disdate){
            $disc_date = getFormattedDate($data->disdate);
            $disc_time = asDateTime($data->disdate);
        }else{
            $disc_date ='';
            $disc_time = '';
        }

        if($data->condcode =='RECOV'){ $recovered = 'X'; }else{ $recovered = '&nbsp;&nbsp;';}
        if($data->condcode =='DIENA'){ $diedna = 'X'; }else{ $diedna = '&nbsp;&nbsp;'; }
        if($data->condcode =='IMPRO'){ $improved = 'X'; }else{ $improved = '&nbsp;&nbsp;'; }
        if($data->condcode =='UNIMP'){ $unimproved = 'X'; }else{ $unimproved = '&nbsp;&nbsp;'; }
        if($data->condcode =='DIEMI'){ $diemi = 'X'; }else{ $diemi = '&nbsp;&nbsp;'; }
        if($data->condcode =='DIENA'){ $diena = 'X'; }else{ $diena = '&nbsp;&nbsp;'; }
        if($data->condcode =='DIEPO'){ $diepo = 'X'; }else{ $diepo = '&nbsp;&nbsp;'; }
        if($data->condcode =='DPONA'){ $dpona = 'X'; }else{ $dpona = '&nbsp;&nbsp;'; }

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

.noline td{
    border-bottom: 1px solid white;
    }
        .border-bottom{
    border-bottom: 1px solid black;
    }
    .noborder{
    border: 1px solid white;
    }
      .noborder-left{
    border-left: 1px solid white;
    }
          .noborder-right{
    border-right: 1px solid white;
    }
          .noborder-bottom{
    border-bottom: 1px solid white;
    }
</style>
        <table style="border-collapse: collapse; width: 100%; height: 100px;" border="1">
        <tbody style="font-size:12px">
        <tr style="height: 18px;">
            <td class="noline" style="vertical-align: top;  border-right: 1px solid white;border-top: 1px solid white;border-left: 1px solid white; " colspan="4"><p style="text-align: center;">Republic of the Philippines<br/>
                PROVINCE OF ILOCOS NORTE<br/>
                Laoag City<br/>
                <strong>'.$hospitalinfo->hfhudname.'</strong><br/>
                PHIC <em>Accredited Healthcare Provider<br/>
                '.$hospitalinfo->address.', '.$hospitalinfo->bgyname.', '.$hospitalinfo->ctyname.' Philippines, 2900<br/>
                Tel No. (677)600-2360; (677)770-4152 TO 54;</em></p></td>
        </tr>
        <tr style="height: 18px;">
        <th  height: 5px; text-align: center; vertical-align: top;" colspan="1" scope="row">
            <h2 style="text-align: center;"><strong><span style="color: #ffcc00;">CLINICAL ABSTRACT</span></strong></h2>
            </th>
            <td style="width: 40%; vertical-align: top; height: 18px;" colspan="3"><p>DATE RECEIVED:</p></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 60%; height: 10px; vertical-align: top;" colspan="1"><p>Name of Hospital/Ambulatory Clinic</p></td>
            <td style="width: 40%; vertical-align: top;" colspan="3"><p>Health Record No.: '.$data->hpercode.'</p></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 60%; height: 10px; vertical-align: top;" rowspan="2"><p style=" font-size:14px; text-align:center;"><strong>'.$hospitalinfo->hfhudname.'</strong></p></td>
            <td style="width: 40%; vertical-align: top;" colspan="3"><p>Admission Date: '.getformatteddate($data->admdate).'</p></td>
        </tr>
        <tr style="height: 54px;">

            <td style="width: 40%; vertical-align: top;" colspan="3"><p>Accreditation No.: '.$hospitalinfo->accreno.'</p></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 60%; height: 10px; vertical-align: top;" colspan="1"><p>Address of Hospital/Ambulatory Clinic: '.$hospitalinfo->address.'</p></td>
            <td style="width: 40%; vertical-align: top;" colspan="3"><p>Barangay: '.$hospitalinfo->bgyname.'</p></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 60%; vertical-align: top;"><p>Municipality/City:'.$hospitalinfo->ctyname.'</p></td>
            <td style="width: 25%; vertical-align: top;"><p style="text-align:left;">Province: '.$hospitalinfo->provname.'</p></td>
            <td style="width: 10%; vertical-align: top;" colspan="2"><p>Zip Code: '.$hospitalinfo->ctyzipcode.'</p></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 60%; height: 10px; vertical-align: top;" colspan="1"><p>Patient Name:</p></td>
            <td style="width: 20%; vertical-align: top;"><p>2. Age: '.number_format($data->patage).'</p></td>
            <td style="width: 20%; vertical-align: top;" colspan="2"><p>3. Sex: '.$data->patsex.'</p></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 60%; height: 10px; vertical-align: top;" colspan="1"><p>Last Name: '.$data->patlast.'</p></td>
            <td style="width: 20%; border-bottom: 1px solid white; vertical-align: top;" colspan="3"><p>4.</p></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 60%; height: 10px; vertical-align: top;" colspan="1"><p>First Name: '.$data->patfirst.'</p></td>
            <td style="width: 40%; border-top: 1px solid white; vertical-align: bottom" rowspan="2" colspan="3"><p style="text-align: center;">
                <strong><span style="text-decoration: underline;">&nbsp;&nbsp;&nbsp;'.getdoctorinfo($data->licno).'&nbsp;&nbsp;&nbsp;</span></strong>
            <br/> <em>Printed name and Signature of Physician</em></p></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 60%; height: 10px; vertical-align: top;" colspan="1"><p>Middle Name: '.$data->patmiddle.'</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="4"><p>5. <strong>Diagnosis:</strong> '.$finaldiagnosis.'</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="4"><p>6. <strong>Chief Complaint: </strong> '.$chiefcomplaint.'</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="4"><p>6. <strong>Brief History:</strong> '.$history.'</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="4"><p>6. <strong>Physical Examination:</strong><br/></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="4"><p>10. <strong>Course in the Ward:</strong> '.$course.'</p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="4"><p>11. <strong>Pertinent Laboratory and Diagnosic Findings (Urinalysis, CBC, XRay, Biopsy, Fecalysis, etc):</strong></br></p></td>
        </tr>
        <tr style="height: 18px;">
            <td style="vertical-align: top;" colspan="4"><p>12. <strong>Operation Performed:</strong></br>'.$operation_done.'</p></td>
        </tr>
        <tr style="height: 54px;">
            <td style="width: 40%; height: 10px; vertical-align: top;" colspan="1"><p>Date of Operation</p></td>
            <td style="width: 60%; vertical-align: top;" colspan="3"><p>Anesthesia: '.$data->hpercode.'</p></td>
        </tr>

        </tbody>
        </table>
        <p style="font-size:9px"><em>Report generated by i-Homis WEB 1.0</em></p>
        ';

        return $output;
    }

}
