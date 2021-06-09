<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Inpatients;
use App\Doctors;
use Carbon\Carbon;
use App\Emergencyroom;
use App\Outpatient;
use App\Progressnotes;
use App\Doctororder;
use App\hdiet;
use App\Hrxo;

class DoctorsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public $progresstypes = array(
        'CONSU'    => 'Consultations',
        'COMPL'   => 'Complications',
        'CONSW'   => 'Conditions of surgical wounds',
        'DEVIN'   => 'Development of infection',
        'RMSUD'   => 'Removal of sutures and drains',
        'CSTSP'   => 'Use of casts or splints',
        'OTHER'   => 'Others',
    );
   public $doctortypes = array(
       'ADMIT'  => 'ADMITTING DOCTOR',
       'CONSU'  => 'CONSULTING DOCTOR',
       'ATTEN'  => 'ATTENDING DOCTOR',
       'FELLO'  =>  'FELLOW DOCTOR',
   );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enctype='')
    {
        //displays patients by doctor
        $doctor = Doctors::where('employeeid',Auth::user()->employeeid)->select('licno')->first();

        if($doctor){
            $licno = $doctor->licno;}else{
            $licno = 'NULL';
        }

        if($doctor){

            if($enctype == 1){
                $inpatients = Emergencyroom::where('herlog.licno',$doctor->licno)
                ->join('hperson','hperson.hpatcode','herlog.hpercode')
                ->join('hprovider','hprovider.licno','herlog.licno')
                ->join('hpersonal','hpersonal.employeeid','hprovider.employeeid')
                ->join('htypser','htypser.tscode','herlog.tscode')
                ->get();
        }elseif($enctype==2){
            $inpatients = Outpatient::where('hopdlog.licno',$doctor->licno)
            ->join('hperson','hperson.hpatcode','hopdlog.hpercode')
            ->join('hprovider','hprovider.licno','hopdlog.licno')
            ->join('hpersonal','hpersonal.employeeid','hprovider.employeeid')
            ->join('htypser','htypser.tscode','hopdlog.tscode')
            ->wherenull('opddtedis')
            ->where('opdstat','A')
            ->limit(5)
            ->get();
        }else{
            $inpatients = Inpatients::Inpatientbydoctor($doctor->licno)->get();

            // $inpatients = Inpatients::wherenull('disdate')->where('admstat','A')
            // ->join('hperson','hperson.hpatcode','hadmlog.hpercode')
            // ->join('hprovider','hprovider.licno','hadmlog.licno')
            // ->join('hpersonal','hpersonal.employeeid','hprovider.employeeid')
            // ->join('hpatroom as A','A.enccode','hadmlog.enccode')
            // ->join('hbed','A.bdintkey','hbed.bdintkey')
            // ->join('hroom','hroom.rmintkey','A.rmintkey')
            // ->join('hward','hward.wardcode','A.wardcode' )
            // ->join('htypser','htypser.tscode','hadmlog.tscode')
            // ->join('hadmcons','hadmcons.enccode','hadmlog.enccode')
            // ->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
            // ->where('hadmcons.licno',$doctor->licno)
            // ->get();

        }
        $count_sex = $inpatients->countBy(function ($item) {
            return $item['patsex'];
        });
        $gender_count=[];
        $male = $count_sex->get('M');
        $female = $count_sex->get('F');
        $gender_count = ['male'=>$male,'female'=>$female];

        return view('transactions.doctors.index',compact('inpatients','doctor','licno','male','female'))
        ->with('progresstypes',$this->progresstypes)
        ->with('doctortypes', $this->doctortypes);
    }else{
        return abort(403, 'Unauthorized action. Please contact your System Administrator');
    }

    }

Public function doctororders($id=''){
    if($id){

        $enccode = str_replace("-","/",$id);
        //$admdiagnosis = Inpatients::getAdmissionbyId($enccode);
        $doctors = DB::table('hadmcons')->where('enccode',$enccode)
           ->select('hadmcons.licno')
           ->get();
       // $hpercode=$admdiagnosis->hpatcode;
      //  $labexams = Doctororder::getExaminations($enccode);
       // $dietorders =  Doctororder::getDietorders($enccode);
        //$radexams =Doctororder::getRadiologyoders($enccode);

     //   $drugmeds = Hrxo::getdrugsmedsorders($enccode);
        return view('admin.doctors.doctorsorder',compact('enccode'))
        ->with('doctors',$doctors)
        ->with('diettypes',hdiet::where('dietstat','A')->select('dietcode','dietdesc')->orderby('dietdesc','ASC')->get());
    }
}

//Diet order
public function dietorder(Request $request,$id=''){

    //
    $this->validate($request , [
        'enccode' => 'required',
        'dietcode' => 'required',
    ]);

      $id  = str_replace("-","/",$request->input('enccode'));

              $dietorder = new Doctororder();
              $docointkey = $id.'-'.Carbon::parse($request->dodate)->format('m/d/y-H:i').'-'.$request->licno.'-DIETT';
              $dietorder->docointkey = $docointkey;
              $dietorder->enccode = $id;
              $dietorder->dodate = Carbon::parse($request->dodate)->format('Y-m-d H:i:s');
              $dietorder->dotime =Carbon::parse($request->dodate)->format('Y-m-d H:i:s');
              $dietorder->licno = $request->licno;
              $dietorder->ordcon = 'NEWOR';
              $dietorder->orcode = 'DIETT';
              $dietorder->hpercode = $request->hpercode;
              $dietorder->dopriority = 'ROUTIN';
              $dietorder->dodtepost =Carbon::parse($request->dodtepost)->format('Y-m-d H:i:s');
              $dietorder->dotmepost =Carbon::parse($request->dodtepost)->format('Y-m-d H:i:s');
              $dietorder->dostat='A';
              $dietorder->dolock ='N';
              $dietorder->confdl ='N';
              $dietorder->doctype ='ATTEN';
              $dietorder->entby = Auth::user()->employeeid;
              $dietorder->statdate =Carbon::parse($request->dodtepost)->format('Y-m-d H:i:s');
              $dietorder->stattime =Carbon::parse($request->dodtepost)->format('Y-m-d H:i:s');
              $dietorder->dietcode =$request->dietcode;
              $dietorder->dietlunch=$request->dietlunch;
              $dietorder->dietdinner=$request->dietdinner;
              $dietorder->remarks=$request->remarks;
              $dietorder->user_id=Auth::user()->id;
              $dietorder->created_at = carbon::now();
        try{
            $dietorder->save();
            return redirect()->back()
             ->with('type','success')
             ->with('msg','Diet Order successfully saved.');
      }catch(\Exception $exception){
          return redirect()->back()
          ->with('type','warning')
          ->with('msg','error.'.$exception);
  }
}//Function diet order

public function update_dietorder(Request $request){
    $this->validate($request , [
        'id' => 'required',
    ]);

    $id = $request->input('id');
    $dietorder = DB::table('hdocord')->where('id','=',$id)
    ->first();
    if($dietorder)
    {
    try{
        DB::table('hdocord')
        ->where('hdocord.id',$id)
        ->update([
            'dodate' => Carbon::parse($request->dodate)->format('Y-m-d H:i:s'),
            'dotime' =>Carbon::parse($request->dodate)->format('Y-m-d H:i:s'),
            // 'licno' => $request->licno,
            'dodtepost' =>Carbon::parse($request->dodtepost)->format('Y-m-d H:i:s'),
            'dotmepost' =>Carbon::parse($request->dodtepost)->format('Y-m-d H:i:s'),
            'statdate' =>Carbon::parse($request->dodtepost)->format('Y-m-d H:i:s'),
            'stattime' =>Carbon::parse($request->dodtepost)->format('Y-m-d H:i:s'),
            'dietcode' => $request->dietcode,
            'dietlunch'=>$request->dietlunch,
            'dietdinner'=>$request->dietdinner,
            'remarks'=>$request->remarks,
            'updated_at' => carbon::now()
        ]);
    }catch(\Exception $excpetion){
        return redirect()->back()
        ->with('type','warning')
        ->with('msg','error.');
    }
}
return redirect()->back()
->with('type','success')
->with('msg','Course in the ward updated Successfully.');
}


public function show($id=''){
    try{
        if($id){
            $enccode = str_replace("-","/",$id);
             $doctornotes = DB::table('hprognte')
            ->where('enccode',$enccode)
            ->where('progstat','A')
            ->orderby('progdte','ASC')
            ->get();
            $doctor = Doctors::where('employeeid',Auth::user()->employeeid)->select('licno')->first();
            if($doctor){
                $licno = $doctor->licno;}else{
                $licno = 'NULL';
            }
                 $patient =Inpatients::getAdmissionbyId($enccode);

        }
        return view('admin.doctors.progressnotes',compact('doctornotes','patient','enccode','licno'))
        ->with('progresstypes',$this->progresstypes);

    }catch(\Exception $excpetion){
        return redirect()->back()
        ->with('type','warning')
        ->with('msg','error.');
    }
}//Function show

/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProgressnotes(Request $request)
    {
        $this->validate($request , [
            'enccode' => 'required'
        ]);
        $progressnotes = new Progressnotes();
        $enccode = str_replace("-","/",$request->enccode);
        $progressnotes->enccode = $enccode;
        $progressnotes->hpercode = $request->hpercode;
        $progressnotes->progdte = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
        $progressnotes->progtme = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
        $progressnotes->datemod = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
        $progressnotes->progrem = $request->progrem;
        $progressnotes->progtype = $request->progtype;
        $progressnotes->progstat = 'A';
        $progressnotes->confdl = 'N';
        $progressnotes->licno = $request->licno;
        $progressnotes->entryby = Auth::user()->employeeid;
        $progressnotes->user_id = Auth::user()->id;
        $progressnotes->created_at = carbon::now();
        try{
            $progressnotes->Save();
            return redirect()->back()
            ->with('type','success')
            ->with('msg','Progress notes in the ward created Successfully.');

            //return view('admin.CF4.edit',['success' => 'Entry added succesfully']);
        }catch(\Exception $excpetion){
            //try to categorize the error using the exception.
            return redirect()->back()
            ->with('type','warning')
            ->with('msg',''.$excpetion); //'An error occurred!'
            //return view('admin.CF4.edit',['error' => 'An error occurred!']);
        }
    }

    function get_progressnotes_data($id)
    {
        if($id){
            $enccode = str_replace("-","/",$id);
            $progressnotes = DB::table('hprognte')
           ->where('enccode',$enccode)
           ->where('progstat','A')
           ->orderby('progdte','ASC')
           ->get();
           $doctor = Doctors::where('employeeid',Auth::user()->employeeid)->select('licno')->first();
           if($doctor){
               $licno = $doctor->licno;}else{
               $licno = 'NULL';
           }
                $patient =Inpatients::getAdmissionbyId($enccode);
                return $progressnotes;
        }
    }

    function progressnotes_pdf($id)
    {
        try{

            $progressnotes=$this->get_progressnotes_data($id);
                return view('admin.doctors.progressnotes_pdf')->with('progressnotes',$progressnotes);
         }catch(\Exception $excpetion){
        return redirect()->back()
        ->with('type','warning')
        ->with('msg','error.'.$excpetion);
        }
    }

    function pdf(){
        $pdf=App::make('dompdf.wrapper');
        $pdf->setPaper('Legal', 'landscape');
       // $pdf->setPaper(array(0, 0, 612.00, 900.00),'landscape');
        $pdf->loadHTML($this->convert_progressnotes_data_to_html());
        return $pdf->stream();
    }

    function convert_progressnotes_data_to_html($id='')
    {
        $progressnotes_data= $this->get_progressnotes_data($id);

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

        foreach ($progressnotes_data as $key => $patient)
        {
            $output .= '

    <tr>
<td align="right" style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;">'.($key+1).'.</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;">'.$patient->hpercode.'</td>
<td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.getpatientinfo($patient->hpercode).'</td>
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

    function get_doctorsbytype(Request $request){
    try{
        if($request->ajax())
        {
                $query = $request->get('query');
                $query2 = $request->get('query2');
//->where('clscode',$query2)
            $doctors = Doctors::where('clscode',$query2)->where('hprovider.empstat','A')
                 ->where('catcode','RESID')
                 ->orwhere('catcode','VISIP')
                 ->orwhere('catcode','CONSU')
                 ->join('hpersonal','hpersonal.employeeid','hprovider.employeeid')
                ->select('hprovider.licno',DB::raw("'DR. '+LASTNAME+', '+FIRSTNAME as name"))
                ->orderby('hpersonal.lastname','ASC')
                // ->select('hprovider.licno')
                // DB::raw("LASTNAME+', '+FIRSTNAME as name"
                ->get();

            if($doctors->count() <> 0){
                return response()->json($doctors);
                // echo json_encode($results);
            }
        }


         }catch(\Exception $excpetion){
            return redirect()->back()
            ->with('type','warning')
            ->with('msg','error.'.$excpetion);
        }
    }
    function get_doctorsbyservicetype(Request $request){
        try{
            if($request->ajax())
            {
                $query = $request->get('query');
                switch($query){

                    case '001': $clscode='GENPR';       break;  //medical
                    case '002': $clscode='OBGYN';       break;  //ob
                    case '003': $clscode='OBGYN';       break;  //gyne
                    case '003': $clscode='GENPR';       break;  //animal bite
                    case '004': $clscode='PEDIA';       break;
                    case '005': $clscode='SURGE';       break;
                    case '007': $clscode='DENTI';       break;
                    case '009': $clscode='ORTHO';       break;
                    case '011': $clscode='OPTHA';       break;
                    case '012': $clscode='GENPR';       break;
                    case '014': $clscode='OPTHA';       break;
                  default:
                     return 'Self';
                       break;
                 }


                $doctors = Doctors::where('clscode',$clscode)->where('hprovider.empstat','A')
                    ->where('hpersonal.empstat','A')
                    ->where('catcode','<>','PHN')
                    ->where('catcode','<>','NOTAP')
                    ->where('catcode','<>','MIDWF')
                     ->join('hpersonal','hpersonal.employeeid','hprovider.employeeid')
                    ->select('hprovider.catcode','hprovider.catcode','hprovider.licno',DB::raw("+LASTNAME+', '+FIRSTNAME as name"))
                    ->orderby('hpersonal.lastname','ASC')
                    // ->select('hprovider.licno')
                    // DB::raw("LASTNAME+', '+FIRSTNAME as name"
                    ->get();
                if($doctors->count() <> 0){
                    return response()->json($doctors);
                    // echo json_encode($results);
                }
            }


             }catch(\Exception $excpetion){
                return redirect()->back()
                ->with('type','warning')
                ->with('msg','error.'.$excpetion);
            }
        }

}



