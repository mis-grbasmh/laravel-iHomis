<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wards;
use App\Inpatients;
use App\Doctororder;
use App\hdiet;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Validator;
class DieteticsController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * Dietetics Index
     */
    public function index($id=''){
      $inpatients = Inpatients::InpatientlistforDiet($id);
            if (request()->ajax()) {
                return Datatables::of($inpatients)
                ->addColumn('admission', function($inpatient) {
                    return getFormattedDate($inpatient->admdate) .' at '. asDateTime($inpatient->admdate).'<br/><strong>'.$inpatient->wardname.'-'.$inpatient->rmname.'-'.$inpatient->bdname.'</strong>';
                })
                ->addColumn('doctor', function($inpatient) {
                    return getdoctorinfo($inpatient->doctor) .'<br/><small><strong>'. $inpatient->tsdesc.'</strong></small>';
                })
                ->addColumn('patient',function ($inpatient){
                    return '<strong>'.getpatientinfo($inpatient->hpercode).'</strong><br/> '. $inpatient->patsex.', '.number_format($inpatient->patage).' year(s) old <br/><small>
                    '.$inpatient->hpercode.'</small>';
                })
                ->addColumn('religion',function ($inpatient){
                    return getPatReligion($inpatient->hpercode)
                    ;
                })
                ->addColumn('dietorders',function ($inpatient){
                    return '<small> BF: '.getDietDesc($inpatient->breakfast).'<br/>Lunch: '. getDietDesc($inpatient->lunch).'<br/>Supper: '.getDietDesc($inpatient->supper).'<br/>'.$inpatient->dietremarks.'
                    <small>'
                    ;
                })
                ->addColumn('dietnotes',function ($inpatient){
                    return '<small>'.$inpatient->dietremarks.'</small>'
                    ;
                })
                ->addColumn('bmi',function ($inpatient){
                    return '<small>'.$inpatient->vsbmi.'</small><br/>'.$inpatient->vsbmicat
                    ;
                })
            ->rawColumns(['patient','admission','doctor','religion','dietorders','dietnotes','bmi'])
                ->make(true);
             }
        return view('transactions.dietetics.index')
        ->with('wards',Wards::all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Crud  $crud
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DB::table('hdocord')
        ->whereid($id)
        ->select('hdocord.id','hdocord.dodate','hdocord.statdate','hdocord.dodtepost','hdocord.licno','hdocord.dietcode','hdocord.dietlunch','hdocord.dietdinner','hdocord.donotes','hdocord.remarks','hdocord.donotes',
        DB::raw("(select dietdesc from hdiet as A where A.dietcode = hdocord.dietcode) as diet")
       )
        ->first();
        return response()->json([
            'id'        => $data->id,
            'licno'    => $data->licno,
            'breakfast' => getdietdesc($data->dietcode),
            'lunch'     => getdietdesc($data->dietlunch),
            'dinner'    => getdietdesc($data->dietdinner),
            'dietcode'      => $data->dietcode,
            'dietlunch'     => $data->dietlunch,
            'dietdinner'    => $data->dietdinner,
            'remarks'       => $data->remarks,
            'dodate'        => date('Y-m-d\TH:i', strtotime($data->dodate)),
            'statdate'      => date('Y-m-d\TH:i', strtotime($data->statdate)),
            'dodtepost'     => date('Y-m-d\TH:i', strtotime($data->dodtepost))
        ]);
    }

    public function update(Request $request,$id){
        try{
            $this->validate(request(), [
                'dietcode'=>'required',
                'dietlunch'=>'required',
                'dietdinner'=>'required',
            ]);
        $data = Doctororder::where('id','=',$id)
            ->first();
                DB::table('hdocord')
                    ->where('hdocord.id','=',$id)
                    ->update([
                        'dodate' => Carbon::parse($request->input('dodate'))->format('Y-m-d H:i:s'),
                        'dotime' => Carbon::parse($request->input('dodate'))->format('Y-m-d H:i:s'),
                        'licno' => $request->input('licno'),
                        'dodtepost' => Carbon::parse($request->input('dodate'))->format('Y-m-d H:i:s'),
                        'dotmepost' => Carbon::parse($request->input('dodate'))->format('Y-m-d H:i:s'),
                        'statdate' => Carbon::parse($request->input('dodate'))->format('Y-m-d H:i:s'),
                        'stattime' => Carbon::parse($request->input('dodate'))->format('Y-m-d H:i:s'),
                        'dietcode' => $request->input('dietcode'),
                        'dietlunch' => $request->input('dietlunch'),
                        'dietdinner' => $request->input('dietdinner'),
                        'remarks' => $request->input('remarks'),
                        'donotes' => $request->input('donotes'),
                        'updated_at' => carbon::now()
                    ]);
            return response()->json(array("success"=>true));
        }catch(\Exception $excpetion){
            return redirect()->back()->with('An error occurred!');
        }
    }

//$subQuery = DB::query()->from('t1')->where('t1.col1', 'val1');
//$query = DB::query()->fromSub($subQuery, 'subquery');
//$query->join('t2', function(JoinClause $join) {
 //   $join->on('subquery.col1', 't2.col2');
  //  $join->where('t2.col3', 'val3');
//})->where('t2.col4', 'val4');

// DB::select('select * from members where id = ?', [1]);

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
        ->addColumn('dietlunch', function($dietorder) {
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
            '<button type="button" class="btn btn-info btn-sm btnEdit" data-toggle="tooltip" data-placement="bottom" data-edit="/dietetics/'.$dietorder->id.'/edit"><i class="tim-icons icon-pencil"></i></button>
             <button type="submit" class="btn btn-warning btn-sm btnDelete" data-remove="/dietetics/'.$dietorder->id.'/delete"><i class="tim-icons icon-trash-simple"></i></button>
             ';

            // '<a  href="javascript:editdiet('.$selected->id.')" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Edit Diet">
            //   <i class="tim-icons icon-pencil"></i>
            //          </a>
            //          <input type="hidden" name="_token" value=""><input type="hidden" name="_method" value="delete">
            //           <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Delete Product">
            //           <i class="tim-icons icon-simple-remove"></i>
            //           </button>';

        })
        ->make(true);
     }//if request
    }//function get_dietorder

 /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Crud  $crud
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Display a diet list report all admitted patients.
     *
     * @return \Illuminate\Http\Response
     * Dietetics Dietlist Report pdf
     */
    Public Function rptDietlist($id){
        try{
            $pdf=App::make('dompdf.wrapper');
            // $pdf->setPaper('long', 'landscape');
             $pdf->setPaper(array(0, 0, 612.00, 936.00),'landscape');
             $pdf->loadHTML($this->convert_dietlist_to_html($id));
             return $pdf->stream();

        }catch(\Exception $excpetion){
        } //end try
    }//end function rptDietlist


    function convert_dietlist_to_html($id)
    {
        //$inpatients =  Inpatients::Inpatientlist('');
        $data = Inpatients::wherenull('disdate')->where('admstat','A')
        ->join('hperson','hperson.hpatcode','hadmlog.hpercode')
        ->join('hpatroom as A','A.enccode','hadmlog.enccode')
        ->join('hbed','A.bdintkey','hbed.bdintkey')
        ->join('hroom','hroom.rmintkey','A.rmintkey')
        ->join('hward','hward.wardcode','A.wardcode' )
        ->join('hpatmss','hpatmss.enccode','hadmlog.enccode')
        ->join('hmssclass','hmssclass.mssikey','hpatmss.mssikey')
        ->join('hmssmemtype','hmssmemtype.msstypecode','hpatmss.mssphictype')
        ->join('hreligion','hreligion.relcode','hperson.relcode')
        ->select('hadmlog.enccode','hadmlog.hpercode','hadmlog.admtxt','hperson.patlast',
             DB::raw("(select top(1) dietcode from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as breakfast"),
             DB::raw("(select top(1) dietlunch from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as lunch "),
             DB::raw("(select top(1) dietdinner from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as supper"),
            //  DB::raw("(select top(1) licno from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as doctor"),
             DB::raw("(select top(1) remarks from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as dietremarks"),
        //     DB::raw("(SELECT TOP (1) hvsothr.vsbmi FROM hvsothr WHERE  (hvsothr.enccode = A.enccode) ORDER BY hvsothr.othrdte DESC) as bmi"),
         //    DB::raw("(SELECT TOP (1) hvsothr.vsbmicat FROM hvsothr WHERE  (hvsothr.enccode = A.enccode) ORDER BY hvsothr.othrdte DESC) as bmicat"),
                 'hreligion.reldesc',
                'hadmlog.patage',
                'hperson.patsex','hward.wardname','hroom.rmname','hbed.bdname','hmssclass.mssdesc','hmssmemtype.msstypedesc')
          ->where('A.patrmstat','A')
    // ->groupby(['hadmlog.enccode','hadmlog.hpercode','hadmlog.admtxt','hdocord.dietcode','hdocord.dietlunch','hdocord.dietdinner','hdocord.remarks','hvsothr.vsbmi','hvsothr.vsbmicat','hreligion.reldesc','hadmlog.patage','hperson.patsex','hward.wardname','hroom.rmname','hbed.bdname','hmssclass.mssdesc','hmssmemtype.msstypedesc'])
         ->groupby(['hadmlog.enccode','hadmlog.hpercode','hadmlog.admtxt','hperson.patlast','hreligion.reldesc','hadmlog.patage','hperson.patsex','hward.wardname','hroom.rmname','hbed.bdname','hmssclass.mssdesc','hmssmemtype.msstypedesc'])
         ->orderby('hperson.patlast','ASC')
          ->get();
      //  die($data);
          $grouped = $data->groupBy('wardname');
          $grouped->toArray();
        //   <img src="assets/img/logo.png" alt="" sizes="(min-width: 36em) 33.3vw, 100vw">
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


        <h5 align="center" style="color:#201D1E">NUTRITION AND DIETETICS SERVICE</h5>
        <h5 align="center" style="color:#201D1E">DIET LIST</h5>';

        foreach ($grouped as $row){

            $output .='
            <table style="border-collapse:collapse;border=1px;">
            <tr align="left">
            <th width = "2px" style="border: 1px solid" colspan="11"><p>WARD NAME: '.$row[0]->wardname.'</p></th>
            </tr>
            <tr align="center">
            <th width = "2px" style="border: 1px solid" rowspan="2">#</th>
            <th style="border: 1px solid" rowspan="2">Room/Bed</th>
            <th style="border: 1px solid" rowspan="2">Category<br/>of Patient</th>
            <th style="border: 1px solid" rowspan="2">Patient Details</th>
            <th style="border: 1px solid" rowspan="2">Religion</th>
            <th style="border: 1px solid" rowspan="2">Admitting<br/>Diagnosis</th>
            <th style="border: 1px solid" rowspan="2">BMI<br/>Details</th>
            <th style="border: 1px solid" colspan="3">Prescribed Diet</th>
            <th style="border: 1px solid" rowspan="2">Remarks<br/>Details</th>


        </tr><tr><th style="border: 1px solid">BREAKFAST</th>
        <th style="border: 1px solid">LUNCH</th>
        <th style="border: 1px solid">DINNER</th></tr>';
        $i = 1;
        foreach ($data as $key => $patient){
            if($patient->wardname == $row[0]->wardname ){

                $output .='<tr>

                <td align="right" style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;">'.$i.'.</td>
                <td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;">'.$patient->wardname.'/'.$patient->bdname.'</td>
                <td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;">'.$patient->mssdesc.'</td>
                <td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.getpatientinfo($patient->hpercode).'<br/>
                '.$patient->patsex.', '.number_format($patient->patage).' year(s) old</td>
                <td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->reldesc.'</td>
                <td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->admtxt.'</td>
                <td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">Height:<br/>Weight:<br/>BMI:'.$patient->bmi.'</td>
                <td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.getdietdesc($patient->breakfast).'</td>
                <td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.getdietdesc($patient->lunch).'</td>
                <td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.getdietdesc($patient->supper).'</td>
                <td  style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->dietremarks.'</td>
                </tr>
                '
                ;
                $i=$i+1;}

            }
         $output .='</table>
         <p><i><i></p>
         <p><em>Report generated by Webihomis</em></p>
         ';
        }
        return $output;
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Doctororder  $docorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctororder $docorder, $id)
    {
        try{
            $docorder->find($id)->delete();
            return json_encode(array('statusCode'=>200));
        }catch(\Exception $excpetion){
            return redirect()->back()->with('An error occurred!');

        }
    }

}//end class dietetics controller
