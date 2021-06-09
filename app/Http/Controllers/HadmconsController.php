<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Hadmcons;
use App\Doctors;
use App\Inpatients;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Datatables;

class HadmconsController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $doctors = Hadmcons::where('enccode',$id)
        ->get();
        return view('Transactions.patients.doctors', compact('doctors'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $activedoctors = Doctors::all();
        // return view('sales.create', compact('clients'));
    }

/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function store(Request $request){
        public function get_patientdoctor(Request $request){
            if($request->ajax()){
               $id = $request->get('enccode');
               $results = Hadmcons::get_patientdoctors($id);
               if($results->count() <> 0){
                    return response()->json($results);
                    // echo json_encode($results);
                }
            }//end ajax
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function store(Request $request){
    public function save_doctor(Request $request){
            if($request->ajax()){
                 $existent = Hadmcons::where('enccode', $request->get('enccode'))->where('licno', $request->licno)->get();
                 if($existent->count()) {
                    return response()->json(array("success"=>false));
                 }
                $hadmcons = new Hadmcons();
                $hadmcons->enccode =$request->get('enccode');
                $hadmcons->licno =$request->get('licno');
                $hadmcons->acostat =  'A';
                $hadmcons->acolock =  'N';
                $hadmcons->acoconfdl =  'N';
                $hadmcons->hpercode = $request->get('hpercode');
                $hadmcons->doctype = $request->get('doctype');
                $hadmcons->acodatemod = carbon::now();
                $hadmcons->entryby =  auth::user()->id;
                $hadmcons->user_id =  auth::user()->id;
                $hadmcons->save();
                return response()->json(array("success"=>true));
                }

                //return json_encode(array('statusCode'=>200));
              //  return response()->json(array("success"=>true));


    }//End function Store

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $enccode = str_replace("-","/",$id);
            $admdiagnosis = Inpatients::getAdmissionbyId($enccode);
            $hpercode = $admdiagnosis->hpercode;
          //  $doctors = Hadmcons::get_patientdoctors($enccode);
          //  $activedoctors = Doctors::getActiveDoctors('RESID');
            return view('transactions.wards.patient_doctors',compact('enccode','hpercode','admdiagnosis'));

        }catch(\Exception $exception){
            return redirect()->back()
            ->with('type','warning')
            ->with('msg','An error occurred!'.$exception);
        }

       // return view('sales.show', ['sale' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hadmcons $model)
    {
        $model->delete();

        return redirect()
            ->route('sales.index')
            ->withStatus('Patients Doctor has been successfully removed.');
    }




}
