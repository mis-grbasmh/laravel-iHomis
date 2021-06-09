<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Inpatients;
use App\Patients;
use App\Wards;
use carbon\carbon;
use App\Doctororder;
use Illuminate\Support\Facades\DB;
use App\Doctors;
use App\Outpatient;
Use App\Emergencyroom;
Use App\Diagnosis;

class MedrecController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id='')
    {
        $transactions = DB::table('hcert')
         ->leftjoin('hpay','hcert.enccode','hpay.enccode')
        ->select('hcert.dateasof','hcert.enccode','hcert.hpercode','hcert.upicode','hcert.certno','hcert.certype','hcert.reqper'
        ,'hcert.relto','hcert.purpose','hcert.entryby','hcert.releaseby','hcert.releasedate','hpay.acctno',
         'hpay.orno','hpay.ordate','hpay.amt','hpay.curcode','hpay.paytype','hpay.paycode','hpay.bal','hpay.payrem','hpay.entryby AS cashier')

        // ->select('hcert.enccode','hcert.hpercode','hcert.dateasof','hcert.certno','hcert.certype','hcert.reqper','hcert.relto','hcert.purpose','hpay.orno')
        // ->join('hpay','hpay.orno','hcert.orno','left outer')
       ->wherenull('hcert.releasedate')

    //    ->orwhere('hcert.certno','<>','NULL')
            ->paginate(25);
            return view('transactions.medicalrecords.index',compact('transactions'));
    }

public function code_diagnosis(){
$date = carbon::now();
$diagnosis =Diagnosis::all();
    $er = emergencyroom::where('hencdiag.tdcode', '=', 'FINDX')
    ->join('htypser','herlog.tscode','htypser.tscode')
    ->join('hencdiag','hencdiag.enccode','herlog.enccode')
    ->select('herlog.enccode','herlog.patage','tsdesc as service','erdate as encdate','herlog.hpercode','erdtedis as dischargedate','herlog.licno as doctor',
    DB::raw("(select top(1) upper(diagtext+' ('+ diagcode_ext +')') from hencdiag where hencdiag.enccode = herlog.enccode and hencdiag.tdcode='FINDX' order by encdate DESC) as findx"),
    DB::raw("'ER' as type"));
$transactions = Outpatient::where('hencdiag.tdcode', '=', 'FINDX')
    ->join('htypser','hopdlog.tscode','htypser.tscode')
    ->join('hencdiag','hencdiag.enccode','hopdlog.enccode')
    ->select('hopdlog.enccode','hopdlog.patage','tsdesc as service','opddate as encdate','hopdlog.hpercode','opddtedis as dischargedate','hopdlog.licno as doctor','hencdiag.diagcode_ext',
    DB::raw("(select top(1) upper(diagtext+' ('+ diagcode_ext +')') from hencdiag where hencdiag.enccode = hopdlog.enccode and hencdiag.tdcode='FINDX' order by encdate DESC) as findx"),
    DB::raw("'OPD' as type"))
// $transactions    = Inpatients::where('hencdiag.tdcode', '=', 'FINDX')
//     ->join('htypser','hadmlog.tscode','htypser.tscode')
//     ->join('hencdiag','hencdiag.enccode','hadmlog.enccode')
//     ->select('hadmlog.enccode','hadmlog.patage','hadmlog.admtxt as diagnosis','tsdesc as service','admdate as encdate','hadmlog.hpercode','disdate as dischargedate','hadmlog.licno as doctor','hencdiag.diagcode_ext',
//     DB::raw("(select top(1) upper(diagtext+' ('+ diagcode_ext +')') from hencdiag where hencdiag.enccode = hadmlog.enccode and hencdiag.tdcode='FINDX' order by encdate DESC) as findx"),
//     DB::raw("'ADM' as type"))
  //\.  ->union($er)
//->union($opd)->where('hadmlog.enccode','000004300000000000001907/20/202013:29:00')

  ->wherenull('hencdiag.diagcode_ext')
->orderby('encdate','DESC')
->skip(5)
                ->take(5)
 ->paginate(10);

    return view('transactions.medicalrecords.codediagnosis',compact('transactions','date','diagnosis'));
}

public function show($id){
    $enccode = str_replace("-","/",$id);
    $pdf=App::make('dompdf.wrapper');
//   $pdf->setPaper('A3', 'portrait');
  //$pdf->setPaper(array(0, 0, 297.00, 420.00),'portratit');
  $pdf->setPaper('short', 'portrait');
    $pdf->loadHTML($this->convert_medicalrecord_to_html($enccode));
    return $pdf->stream();
}
function convert_medicalrecord_to_html($id){
    {

        $result = DB::table('henctr')->where('henctr.enccode',$id)->first();

        $operations = getOperationproc($id);
        if($operations){
                $operation_done = $operations->procdesc;
        }else{
            $operation_done='None';
        }

        $case = $result->toecode;
        switch($case){
            case   'OPD'    :
                $type = 'CLINICAL DIAGNOSIS';
                $data = Outpatient::where('hopdlog.enccode',$id)
                        ->join('hencdiag','hencdiag.enccode','hopdlog.enccode')
                        ->select('hopdlog.enccode','opddate as encdate','hopdlog.hpercode','hopdlog.patage','opddtedis as dischargedate','hopdlog.licno as doctor',DB::raw("'OPD' as type"))
                        ->first();
                break;
            case   'ER'    :
                $type = 'CLINICAL DIAGNOSIS';
                $data = Emergencyroom::where('herlog.enccode',$id)
                ->join('hencdiag','hencdiag.enccode','herlog.enccode')
                ->select('herlog.enccode','erdate as encdate','herlog.hpercode','herlog.patage','erdtedis as dischargedate','herlog.licno as doctor', DB::raw("'ER' as type"))
                ->first();
                break;
            default:
            $type = 'ADMITTING DIAGNOSIS';
            $data = Inpatients::where('hadmlog.enccode',$id)
            ->join('hencdiag','hencdiag.enccode','hadmlog.enccode')
            ->join('hcert','hcert.enccode','hadmlog.enccode')
            ->select('hadmlog.enccode','admdate as encdate','hadmlog.hpercode','hadmlog.patage','disdate as dischargedate','hadmlog.licno as doctor', DB::raw("'ER' as type"),
            'hencdiag.diagtext','hcert.reqper','hcert.purpose')
            ->where('hencdiag.tdcode','ADMDX')
            ->first();
              break;
           }




        // $data = DB::table('hadmlog')
        // ->join('hperson','hperson.hpercode','hadmlog.hpercode')
        // ->join('hpatroom as A','A.enccode','hadmlog.enccode')
        // ->join('hbed','A.bdintkey','hbed.bdintkey')
        // ->join('hroom','hroom.rmintkey','A.rmintkey')
        // ->join('hward','hward.wardcode','A.wardcode' )
        // ->join('htypser','htypser.tscode','hadmlog.tscode')
        // ->where('hadmlog.enccode',$id)
        // ->first();

    $output ='
    <style>
    body {font-family: sans-serif; margin: 0; text-align: justify; font-size: 1.0em;}
    p {text-align: justify; margin-left: 5px;margin-right: 5px; margin-top: 5px; margin-bottom: 5px; padding-left: 0px;}
    span {
        font-family: sans-serif;
        font-size: 1.2em;
    }
    @page { margin:20px;
    }
     @page { margin-top:160px;
        margin-left:100px;
        margin-right:100px
    }

 </style>

    <p style="text-align: center;text-decoration: underline;font-size: 14pt;"><span style="text-decoration: underline;"><strong>MEDICAL CERTIFICATE</strong></p>
    <br/><br/>
    <p style="text-align: justify;">TO WHOM IT MAY CONCERN:</p>
    <br/>
    <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>VENANCIO R. ADRIANO</strong>, <strong>'.number_format($data->patage).'</strong> years old, <strong>male</strong>, <strong>married</strong>, and a resident of
    '.getpatientaddress($data->hpercode).', is currently confined in this hospital from '.getLongDateFormat($data->encdate).' at '.asDateTime($data->encdate).' to present due to:</p>
    <br/>

    <p style="text-align: justify;"><em><strong>'.$type.' </strong></em></p>
    <p style="text-align: justify;"><strong><em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$data->diagtext.'</em></strong></p>
    ';
    if($operations){
    $output .='<br/>
    <p style="text-align: justify;"><strong><em>Operation Done:</em></strong></p>
    <p style="text-align: justify; padding-left: 40px;"><strong><em>'.$operation_done.'</em></strong></p>';
    }
    $output .='<br/>
    <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above-named mention '.$data->purpose.'.</p>
    <br/>
    <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this '.\Carbon\Carbon::now()->format('l, jS \\of F Y').', Gov. Roque B. Ablan Sr. Memorial Hospital, Laoag City, Ilocos Norte, Philippines.</p>
    <br/><br/>
    <p style="text-align: right;"><strong> DR. '.Getdoctorinfo($data->doctor).'</strong></p>

    <p style="text-align: right;"><em>Attending Physician</em></p>
    <p style="text-align: right;">License No. <em>'.$data->doctor.'</em></p>



    ';
    return $output;
    }
}
    public function newborn($type = '')
    {
       $doctors = Doctors::getActiveDoctors('RESID');
        $inpatients = Inpatients::NewbornInpatient($type);

        $count = $inpatients->countBy(function ($item) {
            return $item['patsex'];
        });
        $count_patientsbyservice = $inpatients->countBy(function ($item) {
            return $item['tsdesc'];
        });
        $pedia = $count_patientsbyservice->get('PEDIATRICS');

        $males = $count->get('M');
        $females = $count->get('F');
        return view('admin.medrec.newborn',compact('inpatients','males','females','pedia'))
        ->with('wards',Wards::all())
        ->with('doctors',$doctors)
       ;
    }
}
