<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Emergencyroom;
use App\Patients;
use PDF;
use App\Employees;
use App\Doctors;
use App\Hencdiag;
use Yajra\DataTables\Facades\DataTables;

use Validator;

class ErpatientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public $natureofincidents = array(
        'V/A'    => 'Vehicular Accident',
        'MAU'   => 'Mauling',
        'HAC'   => 'Hacking',
        'LIQ'   => 'Liqour Test',
        'STO'   => 'Stonning',
        'STA'   => 'Stabbing',
        'FAL'   => 'Fall',
        'GUN'   => 'Gunshot',
        'INJ'   => 'Injury',
        'RAP'   => 'Rape',
        'BUR'   => 'Burn',
        'BLA'   => 'Blasting Injury',
    );

    public $broughtby = array(
        'SELF'    => 'Self',
        'POLIC'   => 'Police',
        'RELAT'   => 'Relative',
        'FAMEM'   => 'Family Member',
        'AMBUL'   => 'Ambulance',
        'UNKNO'   => 'Unknown',
        'FREND'   => 'Friend',
        'OTHRS'   => 'Others',
        'NULL'   => '',
    );

    public $conditions = array(
        'GOOD'    => 'Good',
        'FAIR'   => 'Fair',
        'POOR'   => 'Poor',
        'SHOCK'   => 'Shock',
        'HEMOR'   => 'Hemorrhagic',
        'DOA'   => 'Dead On Arrival',
        'COMAT'   => 'Comatose',
        'NULL'   => '',
    );
    public $conditiondischarges = array(
        'STABL'    => 'Stable',
        'CRITI'    => 'Critical ',
        'EXPIR'    => 'Expired',
        'NULL'   => '',
    );

    Public $accomodations = array(
        'ADPAY'     =>  'Pay',
        'SERVI'     =>  'Service',
        'MEDPY'     =>  'PHIC Pay',
        'MEDCH'     =>  'PHIC Charity',
        'HMOPY'     =>  'Health Maintenance Org.',
    );


    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id='')
    {
        $servicetypes = DB::table('htypser')->get();
        $dispositions = DB::table('herdisp')->get();
        $reasonsfortrans = DB::table('herlog')->select('reftxt')->distinct('reftxt')->get();
       $erpatients = Emergencyroom::getERLogs()
       ->get();

        $male = Emergencyroom::getERLogs()->where('hperson.patsex','M')->count();
        $female =Emergencyroom::getERLogs()->where('hperson.patsex','=','F')->count();

        // $doctors =  Doctors::where('hprovider.empstat','A')->where('hprovider.catcode','RESID')
        //             ->join('hpersonal','hpersonal.employeeid','=','hprovider.employeeid')
        //             ->join('hproviderclass','hproviderclass.code','=','hprovider.clscode')
        //             ->where('hpersonal.empprefix','DR')->where('hpersonal.empstat','A')
        //             ->where('hprovider.clscode','!=','ANEST')
        //             ->orderby('hpersonal.lastname','ASC')->get();

                    if (request()->ajax()) {
                        return Datatables::of($erpatients)
                        ->addColumn('patient',function ($erpatient){
                            return '<strong>'.getpatientinfo($erpatient->hpercode).'</strong><br/> '. $erpatient->patsex.', '.number_format($erpatient->patage).' year(s) old <br/><small>
                            '.$erpatient->hpercode.'</small>';
                        })
                        ->addColumn('admission', function($erpatient) {
                            return getFormattedDate($erpatient->erdate) .' at '. asDateTime($erpatient->erdate);
                        })
                        ->addColumn('complaint', function($erpatient) {
                            return '<small>'. $erpatient->complaint.'</small>';
                        })
                        ->addColumn('doctor', function($erpatient) {
                            return getdoctorinfo($erpatient->licno) .'<br/><small><strong>'. $erpatient->tsdesc.'</strong></small>';
                        })
                        ->addColumn('ercase',function ($erpatient){
                            return '<span class="badge badge-info">'. $erpatient->ercase.'</span>'
                          ;
                        })
                        ->addColumn('clerk',function ($erpatient){
                            return '<strong>'.getemployeeinfo($erpatient->entryby).'</strong>'
                          ;
                        })
                        ->addColumn('actions',function ($erpatient){
                            $enccode = str_replace("-","/",$erpatient->enccode);
                            return '
                                   <div class="dropdown">
                                       <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                                           <i class="tim-icons icon-settings-gear-63"></i>
                                       </button>
                                       <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                                           <h6 class="dropdown-header">Select Action</h6>
                                                <a class="dropdown-item btnPatientProfile" data-toggle="tooltip" title="Click to view patient profile " data-placement="bottom" data-id="'.$enccode.'" data-patientprofile="/admission/patientprofile">Patient Profile</a>
                                                <a class="dropdown-item"
                                                data-toggle="tooltip" title="Click to do view Patient Charges" onclick="patientcharges('.$erpatient->enccode.');return false;" href="#">Patient Charges</a>
                                                <a data-toggle="modal" data-id="@book.Id" title="Add this item" class="open-AddBookDialog"></a>
                                                <a class="dropdown-item"   href="#" onclick=doctorsorder("'.$enccode.'") title="Click to do view Doctors Order">Doctors Order</a>
                                                <a class="dropdown-item"   href="#" onclick=patientdoctors("'.$enccode.'") title="Click to do view Doctors">View Doctor</a>
                                                <a class="dropdown-item discharge"
                                                            data-toggle="modal" data-toggle="tooltip" title="Click to discharge patient" data-placement="right" data-target="#discharge" data-keyboard="false" data-backdrop="static"
                                                            data-id="{{ $erpatient->enccode}}"
                                                            data-hpercode="{{ $erpatient->hpercode}}"
                                                            data-licno="{{ $erpatient->licno}}"
                                                            data-patient="'.getpatientinfo($erpatient->hpercode).'"
                                                            href="#">Discharge</i>
                                                </a>
                                            </div>
                                    </div>';
                        })

                        ->rawColumns(['patient','admission','ercase','complaint','doctor','clerk','actions'])
                        ->make(true);
                    }
                        return view('transactions.emergencyroom.index',compact('male','female'))
                        ->with('dispositions',$dispositions)
         ->with('reasonsfortrans',$reasonsfortrans)
         ->with('conditions',$this->conditions)
         ->with('accomodations',$this->accomodations)
         ->with('conditiondischarges', $this->conditiondischarges)
         ->with('natureofincidents',$this->natureofincidents)
        ;

    }


/**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function foradmission($id=''){
            // $foradmission = Emergencyroom::foradmision();
            $foradmissions = Emergencyroom::eradmission();
            if (request()->ajax()) {
                return Datatables::of($foradmissions)
                ->addColumn('patient',function ($foradmission){
                    return '<strong>'.getpatientinfo($foradmission->hpercode).'</strong><br/> '. $foradmission->patsex.', '.number_format($foradmission->patage).' year(s) old <br/><small>
                    '.$foradmission->hpercode.'</small>';
                })
                ->addColumn('admission', function($foradmission) {
                    return getFormattedDate($foradmission->erdate) .' at '. asDateTime($foradmission->erdate);
                })
                ->addColumn('complaint', function($foradmission) {
                    return '<small>'. $foradmission->complaint.'</small>';
                })
                ->addColumn('doctor', function($foradmission) {
                    return getdoctorinfo($foradmission->licno) .'<br/><small><strong>'. $foradmission->tsdesc.'</strong></small>';
                })
                ->addColumn('clerk',function ($foradmission){
                    return '<strong>'.getemployeeinfo($foradmission->entryby).'</strong>'
                  ;
                })
                ->addColumn('actions',function ($foradmission){
                    $enccode = str_replace("-","/",$foradmission->enccode);
                    return '
                           <div class="dropdown">
                               <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                                   <i class="tim-icons icon-settings-gear-63"></i>
                               </button>
                               <div class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                                   <h6 class="dropdown-header">Select Action</h6>
                                        <a class="dropdown-item"
                                        data-toggle="tooltip" title="Click to do view Patient Charges" onclick="patientcharges('.$foradmission->enccode.');return false;" href="#">Patient Charges</a>
                                        <a data-toggle="modal" data-id="@book.Id" title="Add this item" class="open-AddBookDialog"></a>
                                        <a class="dropdown-item"   href="#" onclick=doctorsorder("'.$enccode.'") title="Click to do view Doctors Order">Doctors Order</a>
                                        <a class="dropdown-item"   href="#" onclick=patientdoctors("'.$enccode.'") title="Click to do view Doctors">View Doctor</a>
                                        <a class="dropdown-item discharge"
                                                    data-toggle="modal" data-toggle="tooltip" title="Click to discharge patient" data-placement="right" data-target="#discharge" data-keyboard="false" data-backdrop="static"
                                                    data-id="{{ $foradmission->enccode}}"
                                                    data-hpercode="{{ $foradmission->hpercode}}"
                                                    data-licno="{{ $foradmission->licno}}"
                                                    data-patient="'.getpatientinfo($foradmission->hpercode).'"
                                                    href="#">Discharge</i>
                                        </a>
                                    </div>
                            </div>';
                })

                ->rawColumns(['patient','admission','complaint','doctor','clerk','actions'])
                ->make(true);
    }
    return view('transactions.emergencyroom.er_foradmission');
    }

/**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id ='000000000179787';
        $encounter = DB::Table('henctr')->where('hpercode',$id)->count();
        $doctors =  Doctors::where('hprovider.empstat','A')->where('hprovider.catcode','RESID')
                    ->join('hpersonal','hpersonal.employeeid','=','hprovider.employeeid')
                    ->join('hproviderclass','hproviderclass.code','=','hprovider.clscode')
                    ->where('hpersonal.empprefix','DR')->where('hpersonal.empstat','A')
                    ->orderby('hpersonal.lastname','ASC')->get();

        $servicetypes = DB::table('htypser')->get();

        $patientinfo =Patients::where('hpatcode',$id)->first();
        return view('admin.patient.erpatient_create',compact('patientinfo','servicetypes','doctors','encounter'))
        ->with('broughtby',$this->broughtby)
        ->with('conditions',$this->conditions)
        ->with('accomodations',$this->accomodations);
    }

 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $erpatients = Emergencyroom::where('enccode','=',trim($id))
        ->where('erstat','A')->get();
        return view('admin.patient.edit_erpatient',compact('erpatients'));
    }

    Public Function er_deaths($date1='',$date2=''){
        if($date1==NULL || $date2==NULL) {
            $date1 = Carbon::now()->format('Y-m-d');
            $date2 = Carbon::now()->format('Y-m-d');
        }
        $datestart = Carbon::parse($date1. '00:00:00');
        $dateend = Carbon::parse($date2. '23:59:59');
        $erdeaths = Emergencyroom::where('dispcode','EXPIR')
            //->wheremonth('erdtedis',$month)
            //->whereyear('erdtedis',$year)
            ->where('erdtedis','>=',$datestart)
            ->where('erdtedis','<=',$dateend)
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

        if (request()->ajax()) {
        return Datatables::of($erdeaths)
        ->addColumn('patient',function ($erdeath){
            return '<strong>'.getpatientinfo($erdeath->hpercode).'</strong><br/><small>
            '.$erdeath->hpercode.'</small>';
        })
        ->addColumn('admission', function($erdeath) {
            return
                    '<strong>'.getdoctorinfo($erdeath->licno) .'</strong><br/><small>'. $erdeath->tsdesc.'</strong><br/>
                    <small>'.getFormattedDate($erdeath->erdate) .' at '. asDateTime($erdeath->erdate).'<br/>'.
                   getFormattedDate($erdeath->erdtedis) .' at '. asDateTime($erdeath->erdtedis).'</small>';
        })
        ->addColumn('address', function($erdeath) {
            return '<small>'.getPatientAddress($erdeath->hpercode).'</small>';
        })


        ->addColumn('diagnosis',function ($erdeath){
            return  '<small>'.$erdeath->diagnosis.'</small>'
            ;
        })
        ->addColumn('actions',function ($erdeath){
            $enccode = str_replace("-","/",$erdeath->enccode);
            return '
                   <div class="dropdown">
                       <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                           <i class="tim-icons icon-settings-gear-63"></i>
                       </button>
                       <div class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                           <h6 class="dropdown-header">Select Action</h6>
                                <a class="dropdown-item"
                                    data-toggle="tooltip" title="Click to do view Patient Charges" onclick="patientcharges('.$erdeath->enccode.');return false;" href="#">Patient Charges</a>
                                <a data-toggle="modal" data-id="@book.Id" title="Add this item" class="open-AddBookDialog"></a>
                                <a class="dropdown-item"   href="#" onclick=doctorsorder("'.$enccode.'") title="Click to do view Doctors Order">Doctors Order</a>
                                <a class="dropdown-item discharge"
                                            data-toggle="modal" data-toggle="tooltip" title="Click to discharge patient" data-placement="right" data-target="#discharge" data-keyboard="false" data-backdrop="static"
                                            data-id="{{ $inpatient->enccode}}"
                                            data-hpercode="{{ $inpatient->hpercode}}"
                                            data-licno="{{ $inpatient->licno}}"
                                            data-patient="'.getpatientinfo($erdeath->hpercode).'"
                                            href="#">Discharge</i>
                                </a>
                            </div>
                    </div>';
       })
        ->rawColumns(['patient','address','admission','diagnosis','actions'])
        ->make(true); }
        return view('transactions.emergencyroom.er_deaths')
        ->with('date1',$date1)
        ->with('date2',$date2);

    }
    public function discharge(Request $request,$id=''){
       //     $id = $request->input('enccode');
            $id  = str_replace("-","/",$request->input('enccode'));
            $er = DB::table('herlog')->where('enccode','=',$id)
            ->first();

            $this->validate(request(), [
                'dispcode' => 'required'

            ]);

            try{
            if ($er) {
                $doctype = $request->input('dispcode');
                 DB::table('herlog')
                 ->where('enccode','=',$id)
                 ->update([
                     'erdtedis' => $request->input('erdtedis'),
                     'ertmedis' => $request->input('erdtedis'),
                     'dispcode' => $request->input('dispcode'),
                     'condcode' => $request->input('condcode'),
                     'resadmit' => $request->input('resadmit'),
                     'refto'=> $request->input('refto'),
                     'reftxt'=> $request->input('reftxt'),
                     'erstat' => 'I',
                     'user_id' => auth::user()->id,
                     'updated_at' => carbon::now()
                 ]);
              $isdiagexist = DB::table('hencdiag')->where('enccode',$id)->first();
              if(!$isdiagexist){
                $hencdiag = new Hencdiag();
                $hencdiag->enccode = $id;
                $hencdiag->hpercode = $request->hpercode;
                $hencdiag->licno = $request->input('licno');
                $hencdiag->encdate = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
                $hencdiag->enctime = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
                $hencdiag->tdcode = 'CLIDI';
                   if($doctype == 'ADMIT'){
                        $hencdiag->doctype = $doctype;
                   }
                    $hencdiag->edstat = 'A';
                    $hencdiag->edlock = 'N';
                    $hencdiag->primediag = 'Y';
                    $hencdiag->diagtext = $request->input('diagtext');
                    $hencdiag->diagsubcat = mb_substr($request->input('diagcodeext'), 0, 3);
                    $hencdiag->diagcode_ext= $request->input('diagcodeext');
                    $hencdiag->entryby = auth::user()->id;
                    $hencdiag->user_id = auth::user()->id;
                    $hencdiag->save();
                    }
                return redirect()->back()
                ->with('type','success')
                ->with('msg','ER Patient Log, Discharge Successfully.');
                }
        }catch(\Exception $excpetion){
                    return redirect()->back()->with('An error occurred!');
        }
    }

/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $enccode = str_replace("-","/",$id);

        // $erpatients = Emergencyroom::where('herlog.enccode','=',$enccode)
        //     ->join('hperson','hperson.hpatcode','=','herlog.hpercode')
        //     ->join('htypser','htypser.tscode','=','herlog.tscode')
        //     ->join('hecase','hecase.enccode','=','herlog.enccode')
        //     ->select('herlog.enccode','herlog.patage','hperson.patsex','herlog.hpercode','herlog.newold','herlog.ercase','herlog.medicolegal','herlog.ernotify','herlog.erbrouby',
        //     'herlog.erdate','herlog.entryby','herlog.licno','herlog.ernotes','htypser.tsdesc','hecase.injdte','hecase.ijntme','hecase.injrem','hecase.injadd')
        //     ->first();

        // $pataddr ='';// getPatientAddress($erpatients->hpercode);
        // //$erclerk = Employees::getEmployeeName($erpatients->entryby);
        // $erclerk =  Getemployeeinfo($erpatients->entryby);
        // $patientname = getPatientinfo($erpatients->hpercode);
        // $erhistories = Emergencyroom::where('herlog.hpercode','=',$erpatients->hpercode)
        // ->join('htypser','htypser.tscode','=','herlog.tscode')
        // ->leftjoin('hencdiag','hencdiag.enccode','=', 'herlog.enccode')
        // ->join('herdisp','herdisp.herdispcode','herlog.dispcode')
        // ->select('herlog.enccode','herlog.hpercode','htypser.tsdesc','herlog.patage','herlog.erdate','herlog.licno','herlog.entryby','herdisp.herdispdesc','herlog.erdtedis',
        // 'herlog.condcode','hencdiag.diagtext','hencdiag.diagcode_ext')
        // ->get();

        // $vitalsigns = DB::table('hvitalsign')
        //     ->where('enccode', $enccode)
        //     ->orderby('datelog','DESC')
        //     ->get();

         return view('transaction.emergencyroom.erdeaths', compact('erpatients','enccode','erhistories','erclerk','patientname','pataddr','vitalsigns'));
        // ->with('broughtby', $this->broughtby);


    }

    function erpatient_pdf()
    {
        $erpatient_data=$this->get_erpatient_data();
        return view('admin.patient.erpatient_pdf')->with('erpatient_data',$erpatient_data);
    }

    function get_erpatient_data()
    {
        $erpatient_data = Emergencyroom::wherenull('erdtedis')
        ->where('erstat','A')
        ->join('hperson','hperson.hpatcode','=','herlog.hpercode')
        ->join('hprovider','hprovider.licno','=','herlog.licno')
        ->join('hpersonal','hpersonal.employeeid','=','hprovider.employeeid')
        ->join('htypser','htypser.tscode','=','herlog.tscode')
        ->limit(30)
        ->get();
        return $erpatient_data;
    }


    function pdf(){
        $pdf=App::make('dompdf.wrapper');
        $pdf->setPaper('Legal', 'landscape');
       // $pdf->setPaper(array(0, 0, 612.00, 900.00),'landscape');
        $pdf->loadHTML($this->convert_erpatient_data_to_html());
        return $pdf->stream();
    }

    function convert_erpatient_data_to_html()
    {
        $erpatient_data= $this->get_erpatient_data();

        $output='
        <img src="images/main/mainlayout/logo_dark_long.png" alt="">
        <hr>

        <h2 align="center" style="color:#201D1E">ER Registered Patients</h2>
       <table style="border-collapse:collapse;border=0px;">

        <tr align="center">
        <th width = "40px" style="border: 1px solid">#</th>
            <th width = "100px" style="border: 1px solid">Health Rec. No.</th>
            <th  width = "100px" style="border: 1px solid">Patient Name</th>
            <th width = "100px" style="border: 1px solid">Age</th>
            <th width = "100px" style="border: 1px solid">ER Date & Time</th>
            <th width = "100px" style="border: 1px solid">ER Case</th>
            <th width = "100px" style="border: 1px solid">Diagnosis</th>
            <th width = "100px" style="border: 1px solid">Physician</th>


        </tr>
        ';

        foreach ($erpatient_data as $key => $patient)
        {
            $output .= '

    <tr>
<td align="right" style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;">'.($key+1).'.</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;">'.$patient->hpercode.'</td>
<td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->patlast.', '.$patient->patfirst.' '. $patient->patmiddle.'</td>
<td align="center" style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->patage.'</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.getFormattedDate($patient->erdate).'<br/>'.asDateTime($patient->erdate).'</td>
<td align="center" style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->ercase.'</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">DR. '.$patient->lastname.'</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">DR. '.$patient->lastname.'</td>

    </tr>


            ';
        }

        $output .= '
        </table>
        <hr>
        <p><i><i></p>
        <p><em>Report generated by ihomis</em></p>
        ';
        return $output;
    }
}//Class ER
