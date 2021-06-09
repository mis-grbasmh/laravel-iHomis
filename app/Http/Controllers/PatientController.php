<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Inpatients;
use App\Wards;
use App\Emergencyroom;
use App\Outpatient;
use App\Patients;
use App\Doctororder;
use App\Doctors;
use App\Hrxo;
use App\Hdiet;
use App\Miscellaneous;
use App\Hhistory;
use App\Hencdiag;
use App\Courseward;
use App\Hadmcons;
use App\Hsignsymptoms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;


use PDF;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class PatientController extends Controller
{

    // Public $nationalities = array(
    //     'FILIP'     =>  'Filipino',
    //     'AMERI'     =>  'American',
    //     'SPANI'     =>  'Spanish',
    //     'CHINE'     =>  'Chinese',
    //     'GERMN'     =>  'German',
    //     'BANGD'     =>  'Bangladesh',
    //     'BRITS'     =>  'British',
    //     'ENGLS'     =>  'English',
    //     'FRNCH'     =>  'French',
    //     'CANAD'     =>  'Canadian'
    // );

   Public Function get_transfer_types()
    {
        $categories = array_slice(Emergencyroom::all('reftxt')->toArray(), 0, 4);
        return array_column($categories, 'reftxt');

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $column = '';
        $hpercode = gen_hospitalno();
        $providers = Miscellaneous::all();
        return view('transactions.patients.create',compact('providers','hpercode','column'))
        ->with('religions', DB::table('hreligion')->where('relstat','A')->get())
      //  ->with('nationalities', $this->nationalities)
        ->with('citytowns', DB::table('hcity')->where('ctystat','A')
            ->join('hprov','hprov.provcode','hcity.ctyprovcod')
            ->orderby('ctynsocod','asc')->get());
    }

//select * from hcity inner join hprov on hprov.provcode = hcity.ctyprovcod
  Public Function edit(Patients $patient){

  }


    Public function getradiologyresultbyid(Request $request){
    if($request->ajax())
    {
        $output = '';
        $query = $request->get('query');
        if($query != '')
        {
            $data    = DB::table('hdocord')
             ->join('hradresult','hradresult.docointkey','hdocord.docointkey')
            ->join('hprocm','hprocm.proccode','hdocord.proccode')

            ->take(1)
           ->where('id','=',$query)->first();
        }
        $total_row = $data->count();
        if($total_row <> 0){
            $data = array(
                'dodate'  => $data->dodate,
                'total_data'  => $total_row,
                'notes'  => $data->notes
              );
              echo json_encode($data);
        }
    }
}

public function patient_summaryofcharges($id){
    try{
        if($id){

            $drugsmeds = DB::table('hrxo')
                ->join('hcharge','hcharge.chrgcode','hrxo.chrgcode')
                ->select('hcharge.chrgdesc',DB::Raw('sum(hrxo.pcchrgamt) as amt'))
                ->where ('hrxo.enccode',$id);
            $misc = DB::table('hpatchrg')
                ->join('hcharge','hcharge.chrgcode','hpatchrg.chrgcode')
                ->select('hcharge.chrgdesc',DB::Raw('sum(hpatchrg.pcchrgamt) as amt'))
                ->where ('hpatchrg.enccode',$id)
                ->union($drugsmeds)
            ->get();

        }
    }catch(\Exception $exception){
        return redirect()->back()
        ->with('type','warning')
        ->with('msg','An error occurred!'.$exception);
    }
}

public function patientcharges($id=''){
    try{
        if($id){
         $enccode = str_replace("-","/",$id);
         $admdiagnosis = Inpatients::getAdmissionbyId($enccode);
         $hpercode = $admdiagnosis->hpercode;
         $labcharges = DB::table('hproc')
             ->join('hprocm','hprocm.proccode','hproc.proccode')
             ->select('prikey','procdesc');

        //get nondrugs items
             $nondrugs = DB::table('hclass2')
                 ->select('cl2comb','cl2desc')
                 ->union($labcharges);
       $patientcharges = DB::table('hpatchrg as A')
                     ->join('hcharge','hcharge.chrgcode','A.chargcode')
                     ->select('hpatchrg.pcchrgdte','hpatchrg.pcchrgcod','hpatchrg.pchrgqty as qty','hpatchrg.uomcode as uom','hcharge.chrgdesc as chargetype','hpatchrg.pcchrgamt as amt')

                    //  ->join('hdmhdr','hdmhdr.dmdcomb','=',DB::Raw('substr(itemcode,0,12)as item'))


                     //000000002079 ->1 tanggalin ung right
                     ->where('A.enccode',$enccode)
                     ->orderby('A.pcchrgdte','ASC')
                     ->paginate(10);
         $accountno = getPatientAccountNo($enccode);

        }else{
         $enccode='';
         $admdiagnosis='';
         $accountno='';
         $patientcharges='';
         $hpercode='';
     }


         return view('transactions.patients.patientcharges',compact('patientcharges','enccode','hpercode','admdiagnosis','accountno'))
         ->with('costcenters',DB::table('hcostre')->where('crestat','A')->get())
         ->with('chargetypes',DB::table('hcharge')->where('chrgstat','A')->orderby('chrgdesc','ASC')->get())
         ->with('uomtypes',ItemController::uomselect());

     }catch(\Exception $exception){
         return redirect()->back()
         ->with('type','warning')
         ->with('msg','An error occurred!'.$exception);
     }
 }//function patientcharges

public function patientdoctors($id=''){
    try{
        $enccode = str_replace("-","/",$id);
        $admdiagnosis = Inpatients::getAdmissionbyId($enccode);
        $hpercode = $admdiagnosis->hpercode;
        $doctors = Hadmcons::get_patientdoctors($enccode);
        $activedoctors = Doctors::getActiveDoctors('RESID');
        return view('transactions.wards.patient_doctors',compact('enccode','hpercode','admdiagnosis','doctors','activedoctors'));

    }catch(\Exception $exception){
        return redirect()->back()
        ->with('type','warning')
        ->with('msg','An error occurred!'.$exception);
    }
}//function patientdoctors

public function patientdoctorsorder($id=''){
    try{

        if($id){

            $enccode = str_replace("-","/",$id);
            $admdiagnosis = Inpatients::getAdmissionbyId($enccode);
            $doctors = DB::table('hadmcons')->where('enccode',$enccode)
                ->select('hadmcons.licno')
                ->get();
            $hpercode=$admdiagnosis->hpatcode;
            $labexams = Doctororder::getExaminations($enccode,'LABOR');
           // $dietorders =  Doctororder::getDietorders($enccode);
           // $radexams =Doctororder::getRadiologyoders($enccode);

            //$drugmeds = Hrxo::getdrugsmedsorders($enccode);
            return view('transactions.wards.patient_doctorsorder',compact('admdiagnosis','enccode','hpercode','labexams'))
            ->with('doctors',$doctors)
            ->with('diettypes',hdiet::where('dietstat','A')->select('dietcode','dietdesc')->orderby('dietdesc','ASC')->get());
        }
    }catch(\Exception $exception){
        return redirect()->back()
        ->with('type','warning')
        ->with('msg','An error occurred!'.$exception);
    }
}//function patientdoctors


    //get radiology result by patient
    public function radiologyresult($id=''){
        try{
            $results = DB::table('hdocord')
            ->join('hradresult','hradresult.docointkey','hdocord.docointkey')
            ->join('hprocm','hprocm.proccode','hdocord.proccode')
            ->where('orcode','RADIO')
            ->orderby('dodate','DESC');
        if($id){
            if(is_integer($id)){
                $enccode = $id;
                $results = $results
                ->where('id',$enccode)
                ->first();
           }else{
               $enccode = str_replace("-","/",$id);
               $results = $results
               ->where('enccode',$enccode)
               ->first();
           }
            $otherexams = DB::table('hdocord')
            ->join('hradresult','hradresult.docointkey','hdocord.docointkey')
            ->join('hprocm','hprocm.proccode','hdocord.proccode')
            ->join('henctr','henctr.enccode','hdocord.enccode')
            ->where('hdocord.hpercode',$id)
            ->where('orcode','RADIO')
            ->orderby('dodate','DESC')
            ->get();
        }
        return view('admin.patient.ragiology_result',compact('results','enccode','otherexams'));
        }catch(\Exception $excpetion){
        return redirect()->back()
        ->with('type','warning')
        ->with('msg','error.'.$excpetion);
    }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id='')
    {
        return view('admin.patient.profile',compact('profile'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clinicalabstract($id='')
    {
        if($id){
            $enccode = str_replace("-","/",$id);
            $complaint = Hhistory::getHistory($enccode,'COMPL');
            $pasthistory = Hhistory::getHistory($enccode,'PAHIS');
            $history = Hhistory::getHistory($enccode,'PRHIS');
            $admdiagnosis = Inpatients::getAdmissionbyId($enccode);
            $hpercode=$admdiagnosis->hpatcode;
            $symptoms  = Hsignsymptoms::getSignssymptoms($enccode);
            $finaldiagnosis = Hencdiag::getFinalDiagnosis($enccode);
            $coursewards =  Courseward::getCourseWard($enccode);
        }else{
            $hpercode=null;
            $enccode="";
           $admdiagnosis="";
           $coursewards="";
           $complaint="";
           $drugmeds="";
           $finaldiagnosis="";
           $admission="";
           $pasthistory="";
           $history="";
           $hphyexam="";
           $symptoms="";
           $vitalsigns="";
           $prenatals="";
           $estatus="";
        }
        return view('admin.doctors.clinicalabstract',compact('complaint','admdiagnosis','enccode','hpercode','historycomplete','forms','prenatals','estatus'));
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


    function clinicalabstractpdf(Request $request){

            $pdf=app::make('dompdf.wrapper');
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


   function examination_results(){
       $result = DB::table('hdocord')->get();
   }

function getPatient_radiologyresult(Request $request){
    if($request->ajax())
        {
            $output = '';
            $query = $request->get('query');
            if($query != 0)
            {
            $data = Doctororder::getRadiologyoderbyid($query);
            }
                    $output .= '
                    <p>CASE NUMBER: <strong><u>'. $data->caseno.'</u></strong></p>
                    <p>DATE ORDERED: <strong><u>'. getFormattedDate($data->dodate).'</u></strong></p>
                    <p>EXAMINATION(S) DESIRED: <strong><u>'.$data->procdesc.'</u></strong></p>
                    <p>REQUESTED PHYSICIAN: <strong><u>DR. '.getdoctorinfo($data->licno).'</strong></u></p>
                    <hr/>
                    <textarea readonly style="background-color:white;" rows="25" id="notes" name="notes" required="" aria-required="true" aria-invalid="false" class="form-control no-resize" placeholder="Please type course in the ward..." required>
                    '.$data->notes.'</textarea>
                    ';
        $data = array(
              'table_data'  => $output
            );
            echo json_encode($data);
        }
}

function get_dietorder(Request $request){
    if($request->ajax())
    {
        $output = '';
        $query = $request->get('query');
        if($query != '')
        {
            $dietorders =  Doctororder::getDietorders(str_replace("-","/",$query));
        }
        return Datatables::of($dietorders)
        ->editColumn('dietcode', function($dietorder) {
        return getDietDesc($dietorder->dietcode);
        })
        ->editColumn('dietlunch', function($dietorder) {
            return getDietDesc($dietorder->dietlunch);
        })
        ->editColumn('dietdinner', function($dietorder) {
            return getDietDesc($dietorder->dietdinner);
        })
        ->editColumn('dodate', function($dietorder) {
        return getFormattedDate($dietorder->dodate);
        })
        ->editColumn('statdate', function($dietorder) {
            return getFormattedDate($dietorder->statdate);
            })
        ->editColumn('licno', function($dietorder) {
        return getdoctorinfo($dietorder->licno);
        })
        ->addColumn('action',function($dietorder){
            return
            '<button type="button" class="btn btn-info btn-sm btnEdit" data-toggle="tooltip" data-placement="bottom" data-edit="/dietetics/'.$dietorder->id.'/edit">Edit</button>
             <button type="submit" class="btn btn-warning btn-sm btnDelete" data-remove="/dietetics/'.$dietorder->id.'">Delete</button>';
            // '<a  href="javascript:editdiet('.$selected->id.')" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Edit Diet">
            //   <i class="tim-icons icon-pencil"></i>
            //          </a>
            //          <input type="hidden" name="_token" value=""><input type="hidden" name="_method" value="delete">
            //           <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Delete Product">
            //           <i class="tim-icons icon-simple-remove"></i>
            //           </button>';

        })
        ->make(true);



    //     $total_row = $data->count();
    //     if($total_row > 0)
    //     {
    //         foreach($data as $key => $row)
    //         {
    //             $key = $key+1;
    //             $output .= '
    //                  <tr>

    //                   <td>'.$key.'</td>
    //                   <td>'.getFormattedDate($row->dodate).'</td>
    //                   <td>'.getFormattedDate($row->statdate).'</td>
    //                   <td>'.getDietDesc($row->dietcode).'</td>
    //                   <td>'.getDietDesc($row->dietlunch).'</td>
    //                   <td>'.getDietDesc($row->dietdinner).'</td>
    //                   <td>'.$row->remarks.'</td>
    //                   <td>'.getdoctorinfo($row->licno).'</td>
    //                   <td class="td-actions text-right">
    //                   <a href="http://laravel-inventory-master.test:8080/inventory/products/2" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="More Details">
    //                   <i class="tim-icons icon-zoom-split"></i>
    //                 </a>
    //                 <a  href="javascript:editdiet('.$row->id.')" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Edit Diet">
    //                 <i class="tim-icons icon-pencil"></i>
    //                 </a>
    //                 <form action="http://laravel-inventory-master.test:8080/inventory/products/2" method="post" class="d-inline">
    //                     <input type="hidden" name="_token" value=""><input type="hidden" name="_method" value="delete">
    //                       <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Delete Product">
    //                         <i class="tim-icons icon-simple-remove"></i>
    //                     </button>
    //                 </form>


    //                 </tr>
    //             ';
    //         }
    //     }else{
    //         $output = '
    //             <tr>
    //             <td align="center" colspan="6">No Data Found</td>
    //            </tr>
    //             ';
    //     }//else
    // $data = array(
    //       'table_data'  => $output,
    //        'total_data'  => $total_row
    //     );

    //     echo json_encode($data);
     }
}

//fetch medication order records
function get_laboratoryorder(Request $request){
    if (request()->ajax()) {
        $query = $request->get('query');
        $labexams = Doctororder::getExaminations(str_replace("-","/",$query),'LABOR');
        return Datatables::of($labexams)
            // ->editColumn('entryby', function($labexam) {
            // return getemployeeinfo($labexam->entryby);
            // })
            ->editColumn('dodate', function($labexam) {
            return getFormattedDate($labexam->dodate);
            })
            ->editColumn('procdesc', function($labexam) {
            return $labexam->procdesc;
            })
           ->editcolumn('entby',function($labexam){
               return getemployeeinfo($labexam->entby);
           })
            ->editColumn('licno', function($labexam) {
            return getdoctorinfo($labexam->licno);
            })
            ->addColumn('action',function($selected){
                return
                '<button type="button" class="btn btn-info btn-sm btnEdit" data-edit="/crud/'.$selected->id.'/edit">Edit</button>
                <button type="submit" class="btn btn-warning btn-sm btnDelete" data-remove="/crud/'.$selected->id.'">Delete</button>';
            })
        ->make(true);
    }//end if


}//end function

//fetch medication order records
function get_radiologyorder(Request $request){
    if (request()->ajax()) {
        $query = $request->get('query');
        $labexams = Doctororder::getRadiologyorder(str_replace("-","/",$query));
        return Datatables::of($labexams)
            // ->editColumn('entryby', function($labexam) {
            // return getemployeeinfo($labexam->entryby);
            // })
            ->editColumn('dodate', function($labexam) {
            return getFormattedDate($labexam->dodate);
            })
            ->editColumn('procdesc', function($labexam) {
            return $labexam->procdesc;
            })
           ->editcolumn('entby',function($labexam){
               return getemployeeinfo($labexam->entby);
           })
            ->editColumn('licno', function($labexam) {
            return getdoctorinfo($labexam->licno);
            })
            ->addColumn('action',function($selected){
                return
                // <td class="text-right">

                //
                //                         <a href="javascript:void(0)" class="btn btn-link btn-danger btn-icon btn-sm remove"><i class="tim-icons icon-simple-remove"></i></a>
                //                       </td>
                '<a href="javascript:void(0)" class="btn btn-link btn-warning btn-icon btn-sm edit"><i class="tim-icons icon-pencil"></i></a>
                <button type="button" class="btn btn-link btn-warning btn-icon btn-sm edit" data-edit="/crud/'.$selected->id.'/edit"><i class="tim-icons icon-pencil"></i></button>
                <button type="submit" class="btn btn-warning btn-sm btnDelete" data-remove="/crud/'.$selected->id.'">Delete</button>';
            })
        ->make(true);
    }//end if
}//end function

//fetch medication order records
    function get_medicationorder(Request $request){
        if (request()->ajax()) {
            $query = $request->get('query');
            $medications = DB::Table('hrxo')
            ->select(['hrxo.qtyissued',
            'hrxo.qtybal',
            'hrxo.dodate',
            'hrxo.entryby',
            'hrxo.pcchrgcod',
            'hgen.gendesc',
            'hdmhdr.brandname',
            'hstre.stredesc',
            'hrxo.estatus'
            ])
            ->join('hdmhdr','hdmhdr.dmdcomb','hrxo.dmdcomb')
            ->join('hdruggrp','hdruggrp.grpcode','hdmhdr.grpcode')
            ->join('hgen','hgen.gencode','hdruggrp.gencode')
            ->join('hroute','hdmhdr.rtecode','hroute.rtecode','outer')
            ->join('hstre','hdmhdr.strecode','hstre.strecode','outer')
            ->join('hform','hdmhdr.formcode','hform.formcode','outer')
            ->where('hrxo.enccode',str_replace("-","/",$query))
            ->orderby('hrxo.dodate','ASC')
            ->get();
            // if($medications->estatus =='S'){
            //     $status = 'Served';
            // }

            return Datatables::of($medications)

                ->editColumn('entryby', function($medication) {
                return getemployeeinfo($medication->entryby);
                })
                ->editcolumn('qtyissued',function($medication) {
                    return number_format($medication->qtyissued);
                })
                ->editcolumn('qtybal',function($medication){
                    return number_format($medication->qtybal);
                })
                ->editColumn('dodate', function($medication) {
                return date('d-m-Y', strtotime($medication->dodate));
                })
                ->editColumn('gendesc', function($medication) {
                return $medication->gendesc. ' '.$medication->brandname;
                })

            ->make(true);
        }//end if
    }//end function

function getPatient_nursesnotes(Request $request){
    if($request->ajax())
    {
        $output = '';
        $query = $request->get('query');
        if($query != '')
        {

            $data = DB::table('hrxo')->where('enccode', '=', $query)
            ->join('hdmhdr', function($join)
            {
                $join->on('hdmhdr.dmdcomb','=','hrxo.dmdcomb');

            })
            ->join('hroute', 'hroute.rtecode', '=', 'hdmhdr.rtecode', 'left outer')
            ->join('hstre','hstre.strecode','hdmhdr.strecode','left outer')
            ->join('hdruggrp','hdruggrp.grpcode','hdmhdr.grpcode','left outer')
            ->join('hgen','hgen.gencode','=','hdruggrp.gencode','left outer')
            ->join('hform','hdmhdr.formcode','=','hform.formcode','left outer')
            ->join('hdmhdr_edpms','hdmhdr.hprodid','=','hdmhdr_edpms.pDrugCode ','left outer')
             ->get();

         //   echo $data->hpercode;
        }
        $total_row = $data->count();
        if($total_row > 0)
        {
            foreach($data as $row)
            {
                $output .= '
                    <tr>
                      <td style="display:none;">'.$row->dmdcomb.'</td>
                      <td>'.$row->dodate.'</td>
                      <td>'.$row->gendesc.' '.$row->dmdnost.' '.$row->stredesc.' '.$row->formdesc.' '.$row->rtedesc.'</td>
                      <td>'.$row->qtyissued.'</td>
                      <td>'.$row->qtybal.'</td>

                    </tr>
                ';
            }
        }else{
            $output = '
                <tr>
                <td align="center" colspan="6">No Data Found</td>
               </tr>
                ';
        }//else
    $data = array(
          'table_data'  => $output,
           'total_data'  => $total_row
        );

        echo json_encode($data);
    }
}
        function getPatient_medication(Request $request){
            if($request->ajax())
        {
            $output = '';
            $query = $request->get('query');
            if($query != '')
            {

                $data = DB::table('hrxo')->where('enccode', '=', $query)
                ->join('hdmhdr', function($join)
                {
                    $join->on('hdmhdr.dmdcomb','=','hrxo.dmdcomb');

                })
                ->join('hroute', 'hroute.rtecode', '=', 'hdmhdr.rtecode', 'left outer')
                ->join('hstre','hstre.strecode','hdmhdr.strecode','left outer')
                ->join('hdruggrp','hdruggrp.grpcode','hdmhdr.grpcode','left outer')
                ->join('hgen','hgen.gencode','=','hdruggrp.gencode','left outer')
                ->join('hform','hdmhdr.formcode','=','hform.formcode','left outer')
                ->join('hdmhdr_edpms','hdmhdr.hprodid','=','hdmhdr_edpms.pDrugCode ','left outer')
                 ->get();

             //   echo $data->hpercode;
            }
            $total_row = $data->count();
            if($total_row > 0)
            {
                foreach($data as $row)
                {
                    $output .= '
                        <tr>
                          <td style="display:none;">'.$row->dmdcomb.'</td>
                          <td>'.$row->dodate.'</td>
                          <td>'.$row->gendesc.' '.$row->dmdnost.' '.$row->stredesc.' '.$row->formdesc.' '.$row->rtedesc.'</td>
                          <td>'.$row->qtyissued.'</td>
                          <td>'.$row->qtybal.'</td>

                        </tr>
                    ';
                }
            }else{
                $output = '
                    <tr>
                    <td align="center" colspan="6">No Data Found</td>
                   </tr>
                    ';
            }//else
        $data = array(
              'table_data'  => $output,
               'total_data'  => $total_row
            );

            echo json_encode($data);
        }
            }



            function getPatient_examination(Request $request){
                if($request->ajax())
            {
                $output = '';
                $query = $request->get('query');
                if($query != '')
                {
                    $data = DB::table('hdocord')->where('enccode', '=', $query)
                    ->join('hprocm','hprocm.proccode','hdocord.proccode')
                    ->select('hdocord.docointkey','hdocord.dodate','hdocord.url','hprocm.procdesc')
                    ->where('orderupd','ACTIV')
                    ->where('orcode','LABOR')
                     ->get();
                }
                $total_row = $data->count();
                if($total_row > 0)
                {
                    foreach($data as $row)
                    {
                        $output .= '
                            <tr>
                              <td style="display:none;">'.$row->docointkey.'</td>
                              <td>'.$row->dodate.'</td>
                              <td>'.$row->procdesc.'</td>
                              <td>'.$row->docointkey.'</td>
                              <td><a  target="blank" href="../'. $row->url .'">view</a></td>';
                              if($row->url != NULL){
                                 '<td><a  target="blank" href="../'. $row->url .'">view</a></td>';
                            }else{
                                '<td>sadsad</td>';
                            '</tr>
                        ';
                    }
                    }
                }else{
                    $output = '
                        <tr>
                        <td align="center" colspan="6">No Data Found</td>
                       </tr>
                        ';
                }//else
            $data = array(
                  'table_data'  => $output,
                   'total_data'  => $total_row
                );

                echo json_encode($data);
            }
        }



    function action(Request $request)
    {
        if($request->ajax())
        {
            $output = '';
            $filter = $request->get('filter');
            $query = $request->get('query');

            if($query != '')
            {
                   if($filter == 1){
                      $keywords = $query;
                      $data = Patients::
                      select('hpercode','patlast','patmiddle','patfirst','patsuffix','patsex','patbdate')
                      ->where('hpatcode', 'like', '%'.trim($keywords).'')
                      ->take(1)
                      ->orderby('patfirst','ASC')
                      ->get();
                   }elseif($filter == 2){

                    $keywords = explode(',', $query);

                    // $keywords = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/", $query);
                   // $keywords = preg_split("/[\s,]+/", $query);
                    $data = Patients::
                    select('hpercode','patlast','patmiddle','patfirst','patsuffix','patsex','patbdate')
                    ->where('patlast', '=', ''.$keywords[0].'')
                    ->where('patfirst', 'like', '%'.trim($keywords[1]).'%')
                    ->take(5)
                    ->orderby('patfirst','ASC')
                    ->get();

                   }//if
                else{
                    $keywords = $query;
                    $data = Patients::
                    select('hpercode','patlast','patmiddle','patfirst','patsuffix','patsex','patbdate','hcity.ctyname')
                    ->join('haddr','haddr.hpercode','hperson.hpercode')
                    ->join('hcity','hcity.ctycode','haddr.ctycode')
                    ->where('hcity.ctyname', 'like', '%'.trim($keywords).'')
                    ->take(5)
                    ->orderby('patfirst','ASC')
                    ->get();
                }
                }//if
            $total_row = $data->count();
            if($total_row > 0)
            {
                foreach($data as $row)
                {
                    $age = \Carbon\Carbon::parse($row->patbdate)->diffInYears(\Carbon\Carbon::now());
                    $output .= '
                    <tr>
                    <td style="display:none;">'.$row->hpercode.'</td>
                    <td>'.$row->patlast.', '.$row->patfirst.' '.$row->patmiddle.'<br/><small>'.$row->hpercode.'</small></td>
                    <td>'.$row->patsex.'/'.$age.'</td>
                    <td>'.getPatientAddress($row->hpercode).'</td>
                    <td>'.getFormattedDate($row->patbdate).'</td>
                    </tr>
                    ';
                }
            }else{
                $output = '
                    <tr>
                    <td align="center" colspan="5">No Data Found</td>
                   </tr>
                    ';
            }//else
        $data = array(
              'table_data'  => $output,
               'total_data'  => $total_row,
               'data' =>$row

            );

            echo json_encode($data);
        }
    }

    function getPatient_history(Request $request){
        if($request->ajax())
        {
            $output = '';
            $query = $request->get('query');
            if($query != '')
            {
               $er = emergencyroom::where('herlog.hpercode', '=', ''.trim($query).'')
                    ->join('htypser','herlog.tscode','htypser.tscode')
                    ->select('enccode','tsdesc as service','erdate as encdate','hpercode','erdtedis as dischargedate','licno as doctor',
                    DB::raw("'ER' as type"));
                $opd = Outpatient::where('hopdlog.hpercode', '=', ''.trim($query).'')
                    ->join('htypser','hopdlog.tscode','htypser.tscode')
                    ->select('enccode','tsdesc as service','opddate as encdate','hpercode','opddtedis as dischargedate','licno as doctor',
                    DB::raw("'OPD' as type"));
                $data    = Inpatients::where('hpercode', '=', ''.trim($query).'')
                    ->join('htypser','hadmlog.tscode','htypser.tscode')
                    ->select('enccode','tsdesc as service','admdate as encdate','hpercode','disdate as dischargedate','licno as doctor',
                    DB::raw("'ADM' as type"))
                    ->union($er)
		            ->union($opd)
                ->orderby('encdate','DESC')
                ->get();
            }
            $total_row = $data->count();
            if($total_row > 0){
                foreach($data as $row)
                {
                    if($row->doctor){
                        $doctor = getdoctorinfo($row->doctor);
                    }else{
                        $doctor = 'None Specified';
                    }
                    $output .= '
                        <tr>
                          <td style="display:none;">'.$row->enccode.'</td>
                          <td>'.$row->type.'</td>
                          <td>'.$row->service.'</td>
                          <td>'.getFormattedDate($row->encdate).'<br/><small> '.asDateTime($row->encdate).'</small></td>
                          <td>'.$doctor.'</td>
                          <td>'.getFormattedDate($row->dischargedate).'<br/><small> '.asDateTime($row->dischargedate).'</small></td>

                        </tr>
                    ';
                }
            }else{
                $output = '
                    <tr>
                    <td align="center" colspan="6">No Data Found</td>
                   </tr>
                    ';
            }//else
        $data = array(
              'table_data'  => $output,
               'total_data'  => $total_row
            );

            echo json_encode($data);
        }
        }//end function

        //
        Public function get_provincebycitycode(Request $request){
            if($request->ajax())
            {
                $query = $request->get('query');
                if($query != '')
                $barangays = DB::table('hbrgy')
                    ->where('bgymuncod',$query)
                    ->orderby('bgyname','ASC')
                    ->get();

                    if($barangays->count() <> 0){
                        return response()->json($barangays);
                    }
                   // return response()->json($barangays);
            }//end if request ajax

        }//end function get_provincebycitycode

}

