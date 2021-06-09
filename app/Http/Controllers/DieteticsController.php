<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wards;
use App\Inpatients;
use App\Doctororder;
use App\hdiet;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
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
            //'breakfast' => getdietdesc($data->dietcode),
            //'lunch'     => getdietdesc($data->dietlunch),
            //'dinner'    => getdietdesc($data->dietdinner),
            'dietcode'      => $data->dietcode,
            'dietlunch'     => $data->dietlunch,
            'dietdinner'    => $data->dietdinner,
            'remarks'       => $data->remarks,
            'dodate'        => date('Y-m-d\TH:i', strtotime($data->dodate)),
            'statdate'      => date('Y-m-d\TH:i', strtotime($data->statdate)),
            'dodtepost'     => date('Y-m-d\TH:i', strtotime($data->dodtepost)),
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
