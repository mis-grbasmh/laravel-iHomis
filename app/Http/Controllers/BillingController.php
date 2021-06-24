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
use App\Hencdiag;
use App\Patientrooms;
use App\Outpatient;
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

class BillingController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $enccode ='';
        $hpercode ='';
        $admdiagnosis='';
        if($id){
            $enccode  = str_replace("-","/",$id);

        }else{
            $enccode ='';
            $hpercode ='';
            $admdiagnosis='';
        }
        return view('transactions.billing.index',compact('enccode','hpercode','admdiagnosis'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id){
            $enccode  = str_replace("-","/",$id);

        }else{
            $enccode ='';
            $hpercode ='';
            $admdiagnosis='';
        }
        return view('transactions.billing.index',compact('enccode','hpercode','admdiagnosis'));
    }

    public function get_patientfordischarge($id){
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
                return '<strong>'.getpatientinfo($inpatient->hpercode).'</strong><br/><small> '. $inpatient->patsex.', '.number_format($inpatient->patage).' year(s) old <br/>
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
                                    <a class="dropdown-item btnCoversheet" data-toggle="tooltip" title="Click to view clinical cover sheet " data-placement="bottom" data-id="'.$enccode.'" data-coversheet="/admission/coversheet">Cover Sheet</a>
                                    <a class="dropdown-item btnAdmissionSlip" data-toggle="tooltip" title="Click to view admission slip " data-placement="bottom" data-id="'.$enccode.'" data-admissionslip="/admission/admissionslip">Admission Slip</a>
                                    <a class="dropdown-item btnAdmissionDoctors" data-toggle="tooltip" title="Click to view admission slip " data-placement="bottom" data-id="'.$enccode.'" data-admissiondoctor="/admission/admissionslip">View Doctors</a>

                                    <a class="dropdown-item"   href="#" onclick=patient_rooms("'.$enccode.'") title="Click to do view Doctors Order">View Rooms</a>
                                    <a class="dropdown-item btnEdit" data-toggle="tooltip" data-placement="bottom" data-id="'.$enccode.'" data-edit="/admission/edit">Edit Admission</a>
                                    <a class="dropdown-item btnDischarge" data-toggle="tooltip" title="Click to discharge patient" data-placement="bottom" data-id="'.$enccode.'" data-discharge="/admission/discharge">Discharge</a>


                                </div>
                        </div>';
            })

            ->rawColumns(['patient','admission','doctor','msstype','clerk','actions'])
            ->make(true);
    }
}


public function soa($id=''){
    if($id){
        $enccode  = str_replace("-","/",$id);

        $encouter = DB::table('henctr')->select('toecode')->where('enccode',$enccode)->first();
        if($encouter->toecode =='ADM'){
            $admdiagnosis = Inpatients::getAdmissionbyId($enccode);

        }else{
            $admdiagnosis = outpatient::get_CliniclRecordbyId($enccode);
        }
        $hpercode = $admdiagnosis->hpercode;
        $patientname = getpatientinfo($admdiagnosis->hpercode). ', '.$admdiagnosis->patsex.', ' .number_format($admdiagnosis->patage).' year(s) old';
        $admdate = getFormattedDate($admdiagnosis->encdate);
        $admtime = ' @ '.asDateTime($admdiagnosis->encdate).' ('.$encouter->toecode.')';
        $accomptype = $admdiagnosis->tacode;

    }else{
        $enccode ='';
        $hpercode ='';
        $admdiagnosis='';
        $patientname='';
        $admdate = '';
        $accomptype= '';
        $admtime= '';
    }

    return view('transactions.billing.soa',compact('enccode','hpercode','admdiagnosis','patientname','admdate','admtime','accomptype'));
}//end function soa

public function statement(){

    return view('transactions.billing.statement');
}

    function get_tentativebill($id){

    }//get_tentativebill

    function get_finalbill($id){

    }//get_finalbill

    function get_roomcharges(Request $request){
        if($request->ajax())
        {
            $query = $request->get('query');
            if($query != ''){
                $roomcharges =  PatientRooms::get_patientrooms(str_replace("-","/",$query));
            }//if query
            return Datatables::of($roomcharges)
        ->editColumn('hprdate', function($roomcharge) {
        return getFormattedDate($roomcharge->hprdate);
        })
        ->editcolumn('rmrate', function($roomcharge) {
            return number_format($roomcharge->rmrate,2);
        })

        ->addColumn('action',function($roomcharge){
            if($roomcharge->patrmstat == "A" || auth()->user()->roles->first()->name == "Admin")
            return
            '<button type="button" class="btn btn-info btn-sm btnEdit" data-toggle="tooltip" data-placement="bottom" data-edit="/dietetics/'.$roomcharge->enccode.'/edit"><i class="tim-icons icon-pencil"></i></button>
             <button type="submit" class="btn btn-warning btn-sm btnDelete" data-remove="/dietetics/'.$roomcharge->enccode.'/delete"><i class="tim-icons icon-trash-simple"></i></button>
             ';
              else
              return '';



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
        // get_patientrooms
    }//get_roomcharges


    function get_itemscharges(Request $request){
        if($request->ajax())
        {
            $query = $request->get('query');
            if($query != ''){
                $itemscharges = DB::table('hpatchrg')
                ->join('hcharge','hcharge.chrgcode','hpatchrg.chargcode')
                ->select('hpatchrg.enccode','hpatchrg.acctno','hpatchrg.chargcode','hpatchrg.itemcode','hpatchrg.pcchrgdte','hpatchrg.pcchrgcod','hpatchrg.pchrgqty as qty','hpatchrg.uomcode as uom','hpatchrg.pchrgup','hcharge.chrgdesc as chargetype','hpatchrg.pcchrgamt as amt','hpatchrg.entryby','hpatchrg.pcdisch')
                ->where('hpatchrg.enccode',str_replace("-","/",$query))
                ->orderby('hpatchrg.pcchrgdte','DESC')
                ->get();
            }//if query
            // $total=0;
            // foreach($itemscharges as $key => $itemscharge){
            //     $total +=  $itemscharge->qty * $itemscharge->pchrgup;
            // }
            $total=0;

            return Datatables::of($itemscharges)
        ->editColumn('pcchrgdte', function($itemscharge) {
        return getFormattedDate($itemscharge->pcchrgdte) .' at '.asDateTime($itemscharge->pcchrgdte);
        })
        ->editcolumn('itemcode', function($itemscharge) {
            return getItem_desc($itemscharge->itemcode,$itemscharge->chargcode);
        })
        ->editcolumn('qty', function($itemscharge) {
            return number_format($itemscharge->qty);
        })
        ->editcolumn('entryby', function($itemscharge) {
            return getemployeeinfo($itemscharge->entryby);
        })
        ->addColumn('total',function ($itemscharge){
            return number_format($itemscharge->qty * $itemscharge->pchrgup,2);
        })
        // ->addrow('rowtotal',function ($itemscharge){
        //     return 'asdsa';
        // })

        ->addColumn('action',function($itemscharge){
             if($itemscharge->pcdisch == "N" || auth()->user()->roles->first()->name == "Admin")
            return
            '<button type="button" class="btn btn-info btn-sm btnEdit" data-toggle="tooltip" data-placement="bottom" data-edit="/dietetics/'.$itemscharge->enccode.'/edit"><i class="tim-icons icon-pencil"></i></button>
             <button type="submit" class="btn btn-warning btn-sm btnDelete" data-remove="/dietetics/'.$itemscharge->enccode.'/delete"><i class="tim-icons icon-trash-simple"></i></button>
             ';
              else
              return '';


            // '<a  href="javascript:editdiet('.$selected->id.')" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Edit Diet">
            //   <i class="tim-icons icon-pencil"></i>
            //          </a>
            //          <input type="hidden" name="_token" value=""><input type="hidden" name="_method" value="delete">
            //           <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Delete Product">
            //           <i class="tim-icons icon-simple-remove"></i>
            //           </button>';

        })
        ->make(true);
         return response()->json(
             [
                 'sum'        => $total,
             ] );
             return view('transactions.billing.soa',compact('total'));
            }//if request

        // get_patientrooms
    }//get_roomcharges

    function get_profservcharges(Request $request){
        if($request->ajax())
        {
            $query = $request->get('query');
            if($query != ''){
               $hprofservs = DB::table('hprofserv')
               ->where('hprofserv.enccode',str_replace("-","/",$query))
               ->where('hprofserv.pfstat','A' )->get();
            }//if query


            return Datatables::of($hprofservs)
        ->editColumn('pfdtefrom', function($hprofserv) {
        return getFormattedDate($hprofserv->pfdtefrom) .' at '.asDateTime($hprofserv->pfdtefrom);
        })
        ->editColumn('pfdteto', function($hprofserv) {
            return getFormattedDate($hprofserv->pfdteto) .' at '.asDateTime($hprofserv->pfdteto);
            })

        ->addcolumn('doctor', function($hprofserv) {
            return 'DR. '.getdoctorinfo($hprofserv->licno).'<br/><span class="badge badge-info">
            '.$hprofserv->doctype.'</span>';
        })
        ->editcolumn('profrate', function($hprofserv) {
            return number_format($hprofserv->profrate,2);
        })
        ->editcolumn('pfamt', function($hprofserv) {
            return number_format($hprofserv->pfamt,2);
        })
        ->editcolumn('pftotamt', function($hprofserv) {
            return number_format($hprofserv->pftotamt,2);
        })
        ->editcolumn('numvisit', function($hprofserv) {
            return number_format($hprofserv->numvisit);
        })
        // ->addColumn('total',function ($hprofserv){
        //     return number_format($hprofserv->profrate * $hprofserv->numvisit,2);
        // })
        // ->addrow('rowtotal',function ($itemscharge){
        //     return 'asdsa';
        // })


        ->addColumn('action',function($hprofserv){
            return
            '<button type="button" class="btn btn-info btn-sm btnEdit" data-toggle="tooltip" data-placement="bottom" data-edit="/dietetics/'.$hprofserv->enccode.'/edit"><i class="tim-icons icon-pencil"></i></button>
             <button type="submit" class="btn btn-warning btn-sm btnDelete" data-remove="/dietetics/'.$hprofserv->enccode.'/delete"><i class="tim-icons icon-trash-simple"></i></button>
             ';

            // '<a  href="javascript:editdiet('.$selected->id.')" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Edit Diet">
            //   <i class="tim-icons icon-pencil"></i>
            //          </a>
            //          <input type="hidden" name="_token" value=""><input type="hidden" name="_method" value="delete">
            //           <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Delete Product">
            //           <i class="tim-icons icon-simple-remove"></i>
            //           </button>';

        })

        ->rawColumns(['doctor','action'])
        ->make(true);
        }//if request
    }

    function get_drugmedscharges(Request $request){
        $total = 0;
        if($request->ajax())
        {
            $query = $request->get('query');
            if($query != ''){
               $drugmeds = DB::table('hrxo')
               ->join('hdmhdrprice', function($join)
               {
                   $join->on('hrxo.dmdcomb','=','hdmhdrprice.dmdcomb');
                   $join->on('hrxo.dmdprdte','=','hdmhdrprice.dmdprdte');
               })
               ->select('hrxo.enccode','hrxo.dmdcomb','hrxo.qtyissued','hrxo.pchrgqty','hrxo.dodtepost','hrxo.entryby','hrxo.pcchrgcod','hrxo.dmdprdte','hdmhdrprice.dmduprice',
               DB::raw("qtyissued*dmduprice as amount"))
               ->where('hrxo.enccode',str_replace("-","/",$query))
               ->where('hrxo.rxostatus','A' )
               ->orderby('hrxo.dmdcomb', 'ASC')
               ->get();
            }//if query

            if($drugmeds){
                foreach($drugmeds as $row){
                    $total += $row->amount;
                }
                $totalamt = array('totalamt' =>  number_format($total,2));
            }

            return Datatables::of($drugmeds)
        ->editColumn('dodtepost', function($drugmed) {
        return getFormattedDate($drugmed->dodtepost) .' at '.asDateTime($drugmed->dodtepost);
        })
        ->editcolumn('qtyissued', function($drugmed) {
            return number_format($drugmed->qtyissued);
        })
        ->editcolumn('pchrgqty', function($drugmed) {
            return number_format($drugmed->pchrgqty);
        })
        ->editcolumn('entryby', function($drugmed) {
            return getemployeeinfo($drugmed->entryby);
        })
        ->editcolumn('dmdcomb', function($drugmed) {
            return getItem_desc($drugmed->dmdcomb,'DRUME');
        })
        ->editColumn('dmduprice',function ($drugmed){
            return number_format($drugmed->dmduprice,2);
        })
        ->editColumn('amount',function ($drugmed){
            return number_format($drugmed->amount,2);
        })
        ->addColumn('action',function($drugmed){
            return
            '<button type="button" class="btn btn-info btn-sm btnEdit" data-toggle="tooltip" data-placement="bottom" data-edit="/dietetics/'.$drugmed->enccode.'/edit"><i class="tim-icons icon-pencil"></i></button>
             <button type="submit" class="btn btn-warning btn-sm btnDelete" data-remove="/dietetics/'.$drugmed->enccode.'/delete"><i class="tim-icons icon-trash-simple"></i></button>
             ';
        })
        ->rawColumns(['doctor','action'])
        ->with($totalamt)
        ->toJson();
        }//if request
    }//End get_drugmedscharges


    function get_drugmedsreturn(Request $request){
        $total = 0;
        if($request->ajax())
        {
            $query = $request->get('query');
            if($query != ''){
               $drugmeds = DB::table('hrxoreturn')
               ->join('hdmhdrprice', function($join)
               {
                   $join->on('hrxoreturn.dmdcomb','=','hdmhdrprice.dmdcomb');
                   $join->on('hrxoreturn.dmdprdte','=','hdmhdrprice.dmdprdte');
               })
               ->select('hrxoreturn.enccode','hrxoreturn.dmdcomb','hrxoreturn.qty','hrxoreturn.returndate','hrxoreturn.returnby','hrxoreturn.dmdprdte','hrxoreturn.remarks','hdmhdrprice.dmduprice',
               DB::raw("qty*dmduprice as amount"))
               ->where('hrxoreturn.enccode',str_replace("-","/",$query))
               ->where('hrxoreturn.status','A' )
               ->get();
            }//if query

            if($drugmeds){
                foreach($drugmeds as $row){
                    $total += $row->amount;
                }
                $totalamt = array('totalamt' =>  number_format($total,2));
            }
            return Datatables::of($drugmeds)
        ->editColumn('returndate', function($drugmed) {
        return getFormattedDate($drugmed->returndate) .' at '.asDateTime($drugmed->returndate);
        })
        ->editcolumn('qty', function($drugmed) {
            return number_format($drugmed->qty);
        })
        ->editcolumn('returnby', function($drugmed) {
            return getemployeeinfo($drugmed->returnby);
        })
        ->editcolumn('dmdcomb', function($drugmed) {
            return getItem_desc($drugmed->dmdcomb,'DRUME');
        })
        ->editColumn('dmduprice',function ($drugmed){
            return number_format($drugmed->dmduprice,2);
        })
        ->editColumn('amount',function ($drugmed){
            return number_format($drugmed->amount,2);
        })
        ->addColumn('action',function($drugmed){
            return
            '<button type="button" class="btn btn-info btn-sm btnEdit" data-toggle="tooltip" data-placement="bottom" data-edit="/dietetics/'.$drugmed->enccode.'/edit"><i class="tim-icons icon-pencil"></i></button>
             <button type="submit" class="btn btn-warning btn-sm btnDelete" data-remove="/dietetics/'.$drugmed->enccode.'/delete"><i class="tim-icons icon-trash-simple"></i></button>
             ';
        })
        ->rawColumns(['doctor','action'])
        ->with($totalamt)
        ->toJson();
        }//if request
    }//End get_drugmedsreturn

    function get_discounts(Request $request){
        if($request->ajax()){
            $query = $request->get('query');
            if($query != ''){
                $discounts = DB::table('hpatdisc')
                    ->join('hdiscnt','hdiscnt.discikey','hpatdisc.discikey')
                    ->select('hpatdisc.enccode',
                        'hpatdisc.hpercode' ,
                        'hpatdisc.pdrefno' ,
                        'hpatdisc.pddte' ,
                        'hpatdisc.discikey' ,
                        'hpatdisc.pdtyp' ,
                        'hpatdisc.pdamt' ,
                        'hdiscnt.discdte')
                    ->where('enccode',$query)
                    ->where('hpatdisc.pdstat','A')
                    ->get();
                    return Datatables::of($discounts)
                    ->make(true);
            }//end if query
        }//end if request

    }//end function get_discounts()

}//BillingController
