<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Inpatients;
use App\Patients;
use App\Wards;
use carbon\carbon;
use App\Doctororder;
use Illuminate\Support\Facades\DB;
use App\Doctors;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Validator;
class WardController extends Controller
{

    public function index($id=''){

        // $doctors = Doctors::getActiveDoctors('RESID');
        // $diagnosis = DB::table('hdiag')
        // ->where('diagstat','A')
        // ->get();
      //  $inpatients = Inpatients::InpatientlistforDiet($id);
      //  $inpatientbywards = Inpatients::Inpatientlist($id)->groupBy('wardname');
      $data = Inpatients::wherenull('disdate')->where('admstat','A')
      ->join('hperson','hperson.hpatcode','hadmlog.hpercode')
      ->join('hpatroom as A','A.enccode','hadmlog.enccode')
      ->join('hbed','A.bdintkey','hbed.bdintkey')
      ->join('hroom','hroom.rmintkey','A.rmintkey')
      ->join('hward','hward.wardcode','A.wardcode' )
      ->join('htypser','htypser.tscode','hadmlog.tscode')
      ->select('hadmlog.enccode','hadmlog.hpercode',
      DB::raw("(select top(1) dietcode from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as diet"),
      DB::raw("(select top(1) enccode from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DISCH' order by A.dodate DESC) as fordischarge "),
           DB::raw("(select top(1) dietlunch from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as lunch "),
           DB::raw("(select top(1) dietdinner from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as supper"),
           DB::raw("(select top(1) remarks from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as fordischarge"),
           DB::raw("(SELECT TOP (1) hvsothr.othrmeas FROM hvsothr WHERE (hvsothr.othrvs = 'HEIGH') and (hvsothr.enccode = A.enccode) ORDER BY hvsothr.othrdte DESC) as ht"),
           DB::raw("(SELECT TOP (1) hvsothr.othrmeas FROM hvsothr WHERE (hvsothr.othrvs = 'WEIGH') and (hvsothr.enccode = A.enccode) ORDER BY hvsothr.othrdte DESC) as wt"),
              'hadmlog.patage',
              'hadmlog.admdate',
              'hadmlog.admtxt',
              'hadmlog.licno',
              'hperson.patsex','htypser.tsdesc','hward.wardname','hroom.rmname','hbed.bdname')
          ->where('A.patrmstat','A')
          ->wherenull('disdate')
          ->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
          ->orderby('hperson.patlast','ASC');
          if($id){
          $inpatients=$data->where('hward.wardname',$id)->get();
          }else
          {
            $inpatients=$data->get();
          }
            if (request()->ajax()) {
                return Datatables::of($inpatients)
                ->addColumn('admission', function($inpatient) {
                    return ' <small>'.getFormattedDate($inpatient->admdate) .' at '. asDateTime($inpatient->admdate).'<br/><strong>'.$inpatient->wardname.'-'.$inpatient->rmname.'-'.$inpatient->bdname.'</strong><br/>
                   Length of Stay: '. \Carbon\Carbon::parse($inpatient->admdate)->diffInDays(\Carbon\Carbon::now()).'day(s)</small>';
                })
                ->addColumn('doctor', function($inpatient) {
                    return '<small>'.getdoctorinfo($inpatient->licno) .'<br/><strong>'. $inpatient->tsdesc.'</strong></small>';
                })
                ->addColumn('patient',function ($inpatient){
                    return '<small><strong>'.getpatientinfo($inpatient->hpercode).'</strong><br/> '. $inpatient->patsex.', '.number_format($inpatient->patage).' year(s) old <br/>
                    '.$inpatient->hpercode.'</small>';
                })
                ->addColumn('diagnosis',function ($inpatient){
                   return '<small><span class="ellipsis">'.$inpatient->admtxt.'</span></small>';
                   })
                ->addColumn('types',function ($inpatient){
                    if (!$inpatient->diet) return '<span class="badge badge-danger">No Diet</span>';
                    if($inpatient->fordischarge) return ' <span class="badge badge-warning">For Discharge</span>';
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
                                        <a class="dropdown-item"   href="#" onclick=patientcharges("'.$enccode.'") title="Click to do view Patient Charges">Patient Charges</a>
                                        <a class="dropdown-item"   href="#" onclick=doctorsorder("'.$enccode.'") title="Click to do view Doctors Order">Doctors Order</a>
                                        <a class="dropdown-item"   href="#" onclick=patientdoctors("'.$enccode.'") title="Click to do view Doctors">View Doctor</a>
                                        <a class="dropdown-item btnDischarge" data-toggle="tooltip" title="Click to discharge patient" data-placement="bottom" data-id="'.$enccode.'" data-discharge="/admission/discharge">Discharge</a>
                                    </div>
                            </div>';
                })
                ->rawColumns(['patient','admission','types','diagnosis','doctor','actions'])
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


        return view('transactions.wards.index',compact('id'))
            ->with('wards',Wards::getActiveWards());

    }

//* Display the specified resource.
//*
//* @param  int  $id
//* @return \Illuminate\Http\Response
//*/
    public function show($id = '')
     {

        // $diagnosis = DB::table('hdiag')
        // ->where('diagstat','A')
        // ->select('diagdesc','diagcode')
        // ->get();
        // $doctors = Doctors::getActiveDoctors('RESID');
      //  $inpatients = Inpatients::InpatientlistforDiet($type);
      $inpatients = Inpatients::wherenull('disdate')->where('admstat','A')
        ->join('hperson','hperson.hpatcode','hadmlog.hpercode')
        ->join('hpatroom as A','A.enccode','hadmlog.enccode')
        ->join('hbed','A.bdintkey','hbed.bdintkey')
        ->join('hroom','hroom.rmintkey','A.rmintkey')
        ->join('hward','hward.wardcode','A.wardcode' )
        ->join('htypser','htypser.tscode','hadmlog.tscode')
        ->select('hadmlog.enccode','hadmlog.hpercode',
        DB::raw("(select top(1) dietcode from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as diet"),
        DB::raw("(select top(1) enccode from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DISCH' order by A.dodate DESC) as fordischarge "),
             DB::raw("(select top(1) dietlunch from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as lunch "),
             DB::raw("(select top(1) dietdinner from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as supper"),
             DB::raw("(select top(1) remarks from hdocord as A where A.enccode = hadmlog.enccode and A.orcode='DIETT' order by A.dodate DESC) as fordischarge"),
             DB::raw("(SELECT TOP (1) hvsothr.othrmeas FROM hvsothr WHERE (hvsothr.othrvs = 'HEIGH') and (hvsothr.enccode = A.enccode) ORDER BY hvsothr.othrdte DESC) as ht"),
             DB::raw("(SELECT TOP (1) hvsothr.othrmeas FROM hvsothr WHERE (hvsothr.othrvs = 'WEIGH') and (hvsothr.enccode = A.enccode) ORDER BY hvsothr.othrdte DESC) as wt"),
                'hadmlog.patage',
                'hadmlog.admtxt',
                'hadmlog.admdate',
                'hadmlog.licno',
                'hperson.patsex','htypser.tsdesc','hward.wardname','hroom.rmname','hbed.bdname')
            ->where('A.patrmstat','A')
            ->wherenull('disdate')
            ->where('A.hprdate','=',DB::raw("(select max(hpatroom.hprdate) from hpatroom where hpatroom.enccode = A.enccode)"))
            ->orderby('hperson.patlast','ASC');
            if (request()->ajax()) {
                return Datatables::of($inpatients)
                ->addColumn('admission', function($inpatient) {
                    return getFormattedDate($inpatient->admdate) .' at '. asDateTime($inpatient->admdate).'<br/><strong>'.$inpatient->wardname.'-'.$inpatient->rmname.'-'.$inpatient->bdname.'</strong><br/>
                    <small>Length of Stay: '. \Carbon\Carbon::parse($inpatient->admdate)->diffInDays(\Carbon\Carbon::now()).'day(s)</small>';
                })
                ->addColumn('doctor', function($inpatient) {
                    return getdoctorinfo($inpatient->licno) .'<br/><small><strong>'. $inpatient->tsdesc.'</strong></small>';
                })
                ->addColumn('patient',function ($inpatient){
                    return '<strong>'.getpatientinfo($inpatient->hpercode).'</strong><br/> '. $inpatient->patsex.', '.number_format($inpatient->patage).' year(s) old <br/><small>
                    '.$inpatient->hpercode.'</small>';
                })

                ->addColumn('types',function ($inpatient){
                    if (!$inpatient->diet) return '<span class="badge badge-danger">No Diet</span>';
                    if($inpatient->fordischarge) return ' <span class="badge badge-warning">For Discharge</span>';
                })
                ->addColumn('diagnosis',function ($inpatient){
                  return '<small><span class="ellipsis">'.$inpatient->admtxt.'</span></small>';
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
                                        <a class="dropdown-item"
                                            data-toggle="tooltip" title="Click to do view Patient Charges" onclick="patientcharges('.$inpatient->enccode.');return false;" href="#">Patient Charges</a>
                                        <a data-toggle="modal" data-id="@book.Id" title="Add this item" class="open-AddBookDialog"></a>
                                        <a class="dropdown-item"   href="#" onclick=doctorsorder("'.$enccode.'") title="Click to do view Doctors Order">Doctors Order</a>
                                        <a class="dropdown-item"   href="#" onclick=patientdoctors("'.$enccode.'") title="Click to do view Doctors">View Doctor</a>
                                        <a class="dropdown-item btnDischarge" data-toggle="tooltip" title="Click to discharge patient" data-placement="bottom" data-id="'.$enccode.'" data-discharge="/admission/discharge">Discharge</a>
                                    </div>
                            </div>';
                })
                ->rawColumns(['patient','admission','types','doctor','actions','diagnosis'])
                ->make(true);


        //     $countall = count($data->get());
        //     if($id==''){
        //    $inpatients = $data->get();
        // }else{
        //    $inpatients = $data->where('hward.wardname',$id)->get();
        // }
        //     // ->where('wardname',$type)->paginate(25);
        //     $count = $inpatients->countBy(function ($item) {
        //         return $item['patsex'];
        //     });
        //     $count_patientsbyservice = $inpatients->countBy(function ($item) {
        //         return $item['tsdesc'];
        //     });
        //     $pedia = $count_patientsbyservice->get('PEDIATRICS');

        //     $males = $count->get('M');
        //     $females = $count->get('F');

        }
            return view('transactions.wards.index',compact('inpatients','males','females','pedia','count','countall','id'))
            ->with('wards',Wards::getActiveWards());
    }
}
