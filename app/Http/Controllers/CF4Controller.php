<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Courseward;
use App\Patients;
use App\Hsignsymptoms;
use App\Hexamination;
use App\Hcomplaints;
use App\Hhistory;
use app\Hrxo;
use App\Inpatients;
use App\Hencdiag;

class CF4Controller extends Controller
{
    public $units = array(
        'HOU'    => 'Hour/s',
        'DAY'   => 'Day/s',
        'WEK'   => 'Week/s',
        'MON'   => 'Months/s',
        'YER'   => 'Year/s',
        'UNKNO'   => 'Minute/s',
        'FREND'   => 'Friend',
        'OTHRS'   => 'Others',
        'NULL'   => '',
    );
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id='')
    {
      $forms=DB::table('hform')->WHERE('hform.formstat','a')
      ->orwhere('hform.formstat','A')
      ->select('formcode','formdesc')
      ->orderby('formdesc','ASC')
      ->get();

      if($id){
        $enccode = str_replace("-","/",$id);
        $estatus = DB::table('hphicclaimmap')
        ->where('enccode',$enccode)
        ->first();
        $complaint = Hhistory::getHistory($enccode,'COMPL');
        $pasthistory = Hhistory::getHistory($enccode,'PAHIS');
        $history = Hhistory::getHistory($enccode,'PRHIS');
        $admdiagnosis = Inpatients::getAdmissionbyId($enccode);
        $duedaysleft=\Carbon\Carbon::parse($admdiagnosis->admdate)->diffInDays(\Carbon\Carbon::now());
        $hpercode=$admdiagnosis->hpatcode;
        $symptoms  = Hsignsymptoms::getSignssymptoms($enccode);
        $finaldiagnosis = Hencdiag::getFinalDiagnosis($enccode);
        $coursewards =  Courseward::getCourseWard($enccode);
        $hphyexam =Hexamination::getExaminations($enccode);
        $prenatals = DB::table('hprenatal')
             ->where('enccode',$enccode)
             ->first();
        $inc_complete =0;
        if($complaint <> NULL || trim($complaint) <>''){
            $inc_complete += 10;
        }
        if($history <> NULL || trim($history) <>''){
            $inc_complete += 20;
        }
        if($hphyexam <> NULL){
            $inc_complete += 20;
        }

        if($coursewards->count() > 0){
            $inc_complete += 20;
        }
        if($finaldiagnosis <> NULL || trim($finaldiagnosis)<>''){
            $inc_complete += 20;
        }




         $drugmeds = DB::Table('hrxoissue')
         ->join('hrxo','hrxo.docointkey','hrxoissue.docointkey')
         // ->join('hpatchrg','hpatchrg.enccode','hrxoissue.enccode','left outer')
         ->join('hdmhdrsub', function($join)
         {
             $join->on('hdmhdrsub.dmdcomb', '=','hrxoissue.dmdcomb');
             $join->on('hdmhdrsub.dmdctr','=','hrxoissue.dmdctr');
             $join->on('hdmhdrsub.dmhdrsub','=','hrxoissue.chrgcode');
         })
         // ->join('hdmhdrprice', function($join)
         // {
         //     $join->on('hdmhdrsub.dmdcomb','=','hdmhdrprice.dmdcomb');
         //     $join->on('hdmhdrsub.dmdctr','=','hdmhdrprice.dmdctr');
         //     $join->on('hdmhdrprice.dmhdrsub','=','hdmhdrsub.dmhdrsub');
         // })
         ->join('hdmhdr', function($join)
         {
             $join->on('hdmhdr.dmdcomb','=','hrxoissue.dmdcomb');
             $join->on('hdmhdr.dmdcomb','=','hdmhdrsub.dmdcomb');
             $join->on('hdmhdr.dmdctr','=','hdmhdrsub.dmdctr');
         })
         ->join('hroute', 'hroute.rtecode', '=', 'hdmhdr.rtecode', 'left outer')
         ->join('hstre','hstre.strecode','hdmhdr.strecode','left outer')
         ->join('hdruggrp','hdruggrp.grpcode','hdmhdr.grpcode','left outer')
         ->join('hgen','hgen.gencode','=','hdruggrp.gencode','left outer')
         ->join('hform','hdmhdr.formcode','=','hform.formcode','left outer')
         ->join('hdmhdr_edpms','hdmhdr.hprodid','=','hdmhdr_edpms.pDrugCode ','left outer')


         // ->join('hpackage','hpackage.packcode','hdmhdr.packcode' ,'left outer')
         // ->join('huom','huom.uomcode','hdmhdrprice.unitcode','left outer')
     //    ->where('hrxoissue.dmdprdte', "=",  getDateFormat('hdmhdrprice.dmdprdte'))
      //   ->where('hrxoissue.dmdprdte', "=",  date('d-m-Y', strtotime('hdmhdrprice.dmdprdte')))

     //    ->where(DB::raw('convert(hrxoissue.dmdprdte,"H:i A")'), '=', 'hdmhdrprice.dmdprdte')
        ->where('hrxoissue.enccode',$enccode)
         ->get();
         $selectedtab="admission";
         $vitalsigns = DB::table('hvitalsign')->where('enccode',$enccode)->first();

      }else{
        $inc_complete ="";
        $duedaysleft="";
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

    return view('transactions.phic.cf4',compact('complaint','enccode','hpercode','historycomplete','forms','prenatals','estatus'))
            ->with('coursewards',$coursewards)
            ->with('drugmeds',$drugmeds)
            ->with('admdiagnosis',$admdiagnosis)
            ->with('finaldiagnosis',$finaldiagnosis)
            ->with('pasthistory',$pasthistory)
            ->with('symptoms',$symptoms)
            ->with('hphyexam',$hphyexam)
            ->with('history',$history)
            ->with('vitalsigns',$vitalsigns)
            ->with('estatus',$estatus)
            ->with('units',$this->units)
            ->with('duedaysleft',$duedaysleft)
            ->with('inc_complete',$inc_complete);
}

 public function patientcf4status($id=''){

 }

 Public function InsertSignsymptoms(Request $request, $id=''){
    $this->validate($request , [
        'enccode' => 'required'
    ]);
    $courseward = new Courseward();
    $courseward->enccode = $request->enccode;
    $courseward->hpercode = "12121";
    $courseward->dtetake = $request->dtetake;
    $courseward->tmetake = $request->dtetake;
    $courseward->crseward = $request->courseward;
    $courseward->entryby = Auth::user()->employeeid;
    $courseward->user_id = Auth::user()->id;
    $courseward->created_at = carbon::now();
    try{
        $courseward->saveOrFail();
        return redirect()->back()
        ->with('type','success')
        ->with('msg','Course in the ward created Successfully.');

        //return view('admin.CF4.edit',['success' => 'Entry added succesfully']);
    }catch(\Exception $excpetion){
        //try to categorize the error using the exception.
        return redirect()->back()
        ->with('type','warning')
        ->with('msg','error.');
        //return view('admin.CF4.edit',['error' => 'An error occurred!']);
    }
 }

 Public function saveHistoryIllness(Request $request){
    $id = $request->enccode;
    $result = DB::table('hmrhisto')
        ->where('enccode',$id)
        ->where('histype','PAHIS')
        ->first();
 }

    public function savePreHistory(Request $request){
        $this->validate($request , [
            'enccode' => 'required',
            ''
        ]);
    }

    public function saveDrugsMeds(Request $request){
        $this->validate($request , [
            'enccode' => 'required'
        ]);

        $id = $request->enccode;

         $result = Hsignsymptoms::where('enccode',$id)->first();

         if ( $request->painsite == true ){
             $others = $request->others;
         }else{
             $others =null;
         }
         if ($result) {
             //if row exist in hsignsymptoms
             try{
                 DB::table('hsignsymptoms')
                 ->where('enccode','=',$id)
                 ->update([
             'alter_mental_sensorium' => '0',
             'abdominal_cramp_pain' => $request->abdominal,
             'anorexia' =>$request->anorexia,
             'bleeding_gums' => $request->bleeding_gums,
             'body_weakness' => $request->body_weakness,
             'blurring_vision' => $request->blurring_vision,
             'chest_pain_discomfort' => $request->chest_pain_discomfort,
             'constipation' => $request->constipation,
             'cough' => $request->cough,
             'diarrhea' => $request->diarrhea,
             'dizziness' => $request->dizziness,
             'dysphagia' => $request->dysphagia,
             'dysuria' => $request->dysuria,
             'epistaxis' => $request->epistaxis,
             'fever' => $request->fever,
             'frequent_urination' => $request->frequent_urination,
             'headache' => $request->headache,
             'hematemesis' => $request->hematemesis,
             'hematuria' => $request->hematuria,
             'hemoptysis' => $request->hemoptysis,
             'hematemesis' => $request->hematemesis,
             'irritability' => $request->irritability,
             'jaundice' => $request->jaundice,
             'lower_extremity_edema' => $request->lower_extremity_edema,
             'myalgia' => $request->myalgia,
             'orthopnea' => $request->orthopnea,
             'painsite' => $request->painsite,
             'palpitations' => $request->palpitations,
             'seizures' => $request->seizures,
             'skin_rashes' => $request->skin_rashes,
             'sbbtm' => $request->sbbtm,
             'sweating' => $request->sweating,
             'urgency' => $request->urgency,
             'vomiting' => $request->vomiting,
             'weight_loss' => $request->weight_loss,
             'others' => $others,
             'dyspnea' => $request->dyspnea,
             'user_id' => Auth::user()->id,
             'updated_at' => carbon::now()
             ]);
             return redirect()->back()
                 ->with('type','success')
                 ->with('msg','Signs and Symptoms updated Successfully.');
             }catch(\Exception $excpetion){
                  return redirect()->back()->with('An error occurred!');
              }
         }else{
             $hsignsymptoms = new Hsignsymptoms();
             $hsignsymptoms->enccode = $id;
             $hsignsymptoms->datelog = carbon::now();
             $hsignsymptoms->timelog = carbon::now();
             $hsignsymptoms->datemod = NULL;
             $hsignsymptoms->entryby = Auth::user()->employeeid;
             $hsignsymptoms->user_id = Auth::user()->id;
             $hsignsymptoms->created_at = carbon::now();
             try{
                 $hsignsymptoms->saveOrFail();
                 return redirect()->back()
                 ->with('type','success')
                 ->with('msg','Course in the ward created Successfully.');

                 //return view('admin.CF4.edit',['success' => 'Entry added succesfully']);
             }catch(\Exception $excpetion){
                 //try to categorize the error using the exception.
                 return redirect()->back()
                 ->with('type','warning')
                 ->with('msg','error.');
                 //return view('admin.CF4.edit',['error' => 'An error occurred!']);
             }
         }
    }

    public function saveSignsSymptoms(Request $request,$id=''){
       $this->validate($request , [
           'enccode' => 'required'
       ]);

       $id = $request->enccode;

        $result = Hsignsymptoms::where('enccode',$id)->first();

        if ( $request->painsite == true ){
            $others = $request->others;
        }else{
            $others =null;
        }
        if ($result) {
            //if row exist in hsignsymptoms
            try{
                DB::table('hsignsymptoms')
                ->where('enccode','=',$id)
                ->update([
            'alter_mental_sensorium' => $request->sensorium,
            'abdominal_cramp_pain' => $request->abdominal,
            'anorexia' =>$request->anorexia,
            'bleeding_gums' => $request->bleeding_gums,
            'body_weakness' => $request->body_weakness,
            'blurring_vision' => $request->blurring_vision,
            'chest_pain_discomfort' => $request->chest_pain_discomfort,
            'constipation' => $request->constipation,
            'cough' => $request->cough,
            'diarrhea' => $request->diarrhea,
            'dizziness' => $request->dizziness,
            'dysphagia' => $request->dysphagia,
            'dysuria' => $request->dysuria,
            'epistaxis' => $request->epistaxis,
            'fever' => $request->fever,
            'frequent_urination' => $request->frequent_urination,
            'headache' => $request->headache,
            'hematemesis' => $request->hematemesis,
            'hematuria' => $request->hematuria,
            'hemoptysis' => $request->hemoptysis,
            'hematemesis' => $request->hematemesis,
            'irritability' => $request->irritability,
            'jaundice' => $request->jaundice,
            'lower_extremity_edema' => $request->lower_extremity_edema,
            'myalgia' => $request->myalgia,
            'orthopnea' => $request->orthopnea,
            'painsite' => $request->painsite,
            'palpitations' => $request->palpitations,
            'seizures' => $request->seizures,
            'skin_rashes' => $request->skin_rashes,
            'sbbtm' => $request->sbbtm,
            'sweating' => $request->sweating,
            'urgency' => $request->urgency,
            'vomiting' => $request->vomiting,
            'weight_loss' => $request->weight_loss,
            'others' => $others,
            'dyspnea' => $request->dyspnea,
            'user_id' => Auth::user()->id,
            'updated_at' => carbon::now()
            ]);
            return redirect()->back()
                ->with('type','success')
                ->with('msg','Signs and Symptoms updated Successfully.');
            }catch(\Exception $excpetion){
                 return redirect()->back()->with('An error occurred!');
             }
        }else{
            $hsignsymptoms = new Hsignsymptoms();
            $hsignsymptoms->enccode = $id;
            $hsignsymptoms->datelog = carbon::now();
            $hsignsymptoms->timelog = carbon::now();
            $hsignsymptoms->datemod = NULL;
            $hsignsymptoms->entryby = Auth::user()->employeeid;
            $hsignsymptoms->user_id = Auth::user()->id;
            $hsignsymptoms->created_at = carbon::now();
            try{
                $hsignsymptoms->saveOrFail();
                return redirect()->back()
                ->with('type','success')
                ->with('msg','Course in the ward created Successfully.');

                //return view('admin.CF4.edit',['success' => 'Entry added succesfully']);
            }catch(\Exception $excpetion){
                //try to categorize the error using the exception.
                return redirect()->back()
                ->with('type','warning')
                ->with('msg','error.');
                //return view('admin.CF4.edit',['error' => 'An error occurred!']);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveAdmissionComplaint(Request $request, $id=''){
        $datetoday =  Carbon::today()->toDateString();
        $this->validate(request(), [
            'enccode' => 'required'
        ]);

        $id = $request->enccode;

        //save admitting diagnosis

            DB::table('hadmlog')
            ->where('enccode','=',$id)
            ->update([
            'admtxt' => $request->admtxt
            ]);

            DB::table('hencdiag')
            ->where('enccode','=',$id)
            ->where('tdcode','FINDX')
            ->update([
            'diagtext' => $request->finaldiagnosis
            ]);

            $historyillness = DB::table('hmrhisto')->where('histype','PRHIS')
            ->where('enccode',$id)
            ->first();
            if($historyillness){
                DB::table('hmrhisto')
                ->where('enccode','=',$id)
                ->where('histype','PRHIS')
                ->update([
                'history' => strtoupper($request->presentillness)
                ]);
            }else{

                $historyillness = new Hcomplaints();
                $historyillness->hpercode =$request->hpercode;
                $historyillness->enccode = $id;
                $historyillness->datelog = carbon::now();
                $historyillness->histype = 'PRHIS';
                $historyillness->history = $request->presentillness;
                $historyillness->timelog = carbon::now();
                $historyillness->confdl = 'N';
                $historyillness->hisstat = 'A';
                $historyillness->entryby = Auth::user()->employeeid;
                $historyillness->user_id = Auth::user()->id;
                $historyillness->save();
            }
           $complaint = DB::table('hmrhisto')->where('enccode','=',$id)
                ->where('histype','COMPL')->first();
            if($complaint)
            {
                DB::table('hmrhisto')
                ->where('enccode','=',$id)
                ->where('histype','COMPL')
                ->update([
                'history' => $request->complaint
                ]);
            }
            else
            {
            $hcomplaints = new Hcomplaints();
            $hcomplaints->hpercode =$request->hpercode;
            $hcomplaints->enccode = $id;
            $hcomplaints->datelog = carbon::now();
            $hcomplaints->histype = 'COMPL';
            $hcomplaints->history = $request->complaint;
            $hcomplaints->timelog = carbon::now();
            $hcomplaints->confdl = 'N';
            $historyillness->hisstat = 'A';
            $hcomplaints->entryby = Auth::user()->employeeid;
            $hcomplaints->user_id = Auth::user()->id;
            try{
                $hcomplaints->saveOrFail();

                return redirect()->back()
                ->with('type','success')
                ->with('msg','Course in the ward created Successfully.');

                //return view('admin.CF4.edit',['success' => 'Entry added succesfully']);
            }catch(\Exception $exception){
                //try to categorize the error using the exception.
                return redirect()->back()
                ->with('type','warning')
                ->with('msg',$exception.'');
                //return view('admin.CF4.edit',['error' => 'An error occurred!']);
            }
        }





    return redirect()->back()
            ->with('type','success')
            ->with('msg','Admission/Complaint/History Successfully saved.');




    // }


    }//function

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function saveExamination(Request $request, $id='')
    {
        $this->validate(request(), [
            'enccode' => 'required',
            'hpercode' => 'required'
        ]);
        $id = $request->enccode;
        $examination = Hexamination::where('enccode',$id)->first();
        if ($examination) {
            DB::table('hphyexam')
            ->where('enccode','=',$id)
            ->update([
            'awakealert' => $request->awakealert,
            'alteredsensorium' => $request->alteredsensorium,
            'heent_essentiallynormal' => $request->heent_essentiallynormal,
            'heent_abnopupireact' => $request->heent_abnopupireact,
            'heent_cervlympadeno' => $request->heent_cervlympadeno ,
            'heent_drymucousmembrane' => $request->heent_drymucousmembrane,
            'heent_ictericsclerae' => $request->heent_ictericsclerae,
            'heent_paleconjunctivae' => $request->heent_paleconjunctivae,
            'heent_sunkeneyeballs' => $request->heent_sunkeneyeballs,
            'heent_sunkenfontanelle' => $request->heent_sunkenfontanelle,
            'heent_others' => $request->heent_others,
            'cl_essentiallynormal' => $request->cl_essentiallynormal,
            'cl_asymchestexpansion' => $request->cl_asymchestexpansion,
            'cl_decbreathsounds' => $request->cl_decbreathsounds,
            'cl_wheezes' => $request->cl_wheezes,
            'cl_lumpoverbreast' => $request->cl_lumpoverbreast,
            'cl_ralescracklesrhonchi' => $request->cl_ralescracklesrhonchi,
            'cl_interribclaretract' => $request->cl_interribclaretract,
            'cl_others' => $request->cl_others,
            'cvs_essentiallynormal' => $request->cvs_essentiallynormal,
            'cvs_disapexbeat' => $request->cvs_disapexbeat,
            'cvs_heavesthrills' => $request->cvs_heavesthrills,
            'cvs_pericarbulge' => $request->cvs_pericarbulge,
            'cvs_irregularrhythm' => $request->cvs_irregularrhythm,
            'cvs_muffledheartsounds' => $request->cvs_muffledheartsounds,
            'cvs_murmur' => $request->cvs_murmur,
            'cvs_others' => $request->cvs_others,
            'abd_essentiallynormal' => $request->abd_essentiallynormal,
            'abd_abdrigidity' => $request->abd_abdrigidity,
            'abd_abdtenderness' => $request->abd_abdtenderness,
            'abd_hyperbowelsounds' => $request->abd_hyperbowelsounds,
            'abd_palpablemass' => $request->abd_palpablemass,
            'abd_tympdullabdomen' => $request->abd_tympdullabdomen,
            'abd_uterinecontraction' => $request->abd_uterinecontraction,
            'abd_others' => $request->abd_others,
            'guie_essentiallynormal' => $request->guie_essentiallynormal,
            'guie_bldstainedinexamfinger' => $request->guie_bldstainedinexamfinger,
            'guie_cervicaldilatation' => $request->guie_cervicaldilatation,
            'guie_presenceabnodis' => $request->guie_presenceabnodis,
            'guie_others' => $request->guie_others,
            'skinex_essentiallynormal' => $request->skinex_essentiallynormal,
            'skinex_clubbing' => $request->skinex_clubbing,
            'skinex_coldclammyskin' => $request->skinex_coldclammyskin,
            'skinex_cyanosismottledskin' => $request->skinex_cyanosismottledskin,
            'skinex_edemaswelling' => $request->skinex_edemaswelling,
            'skinex_decmobility' => $request->skinex_decmobility,
            'skinex_palenailbeds' => $request->skinex_palenailbeds,
            'skinex_poorskinturgor' => $request->skinex_poorskinturgor,
            'skinex_rashespetechiae' => $request->skinex_rashespetechiae,
            'skinex_weakpulses' => $request->skinex_weakpulses,
            'skinex_others' => $request->skinex_others,
            'neuro_essentiallynormal' => $request->neuro_essentiallynormal,
            'neuro_abnormalgait' => $request->neuro_abnormalgait,
            'neuro_abnopositionsense' => $request->neuro_abnopositionsense,
            'neuro_abnodecsensation' => $request->neuro_abnodecsensation,
            'neuro_abnoreflexes'=> $request->neuro_abnoreflexes,
            'neuro_pooralteredmemory' => $request->neuro_pooralteredmemory,
            'neuro_poormusctonestren' => $request -> neuro_poormusctonestren,
            'neuro_poorcoordination' => $request->neuro_poorcoordination,
            'neuro_others' => $request -> neuro_others
            ]);
                try{
                    return redirect()->back()
                    ->with('type','success')
                    ->with('msg','Physical Examination updated Successfully.');
                }catch(\Exception $excpetion){
                    return redirect()->back()->with('An error occurred!');
                }
        }else{
            $examination = new Hexamination();
            $enccode = str_replace("-","/",$request->enccode);
            $examination->enccode = $enccode;
            $examination->hpercode = $request->hpercode;
            $examination->pedtelog = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
            $examination->petmelog = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
            $examination->datemod = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
            $examination->petype = "PEADM";
            $examination->pestat = 'A';
            $examination->pelock = 'N';
            $examination->awakealert = $request->awakealert;
            $examination->alteredsensorium = $request->alteredsensorium;
            $examination->heent_essentiallynormal = $request->heent_essentiallynormal;
            $examination->heent_abnopupireact = $request->heent_abnopupireact;
            $examination->heent_cervlympadeno = $request->heent_cervlympadeno;
            $examination->heent_drymucousmembrane = $request->heent_drymucousmembrane;
            $examination->heent_ictericsclerae = $request->heent_ictericsclerae;
            $examination->heent_paleconjunctivae = $request->heent_paleconjunctivae;
            $examination->heent_sunkeneyeballs = $request->heent_sunkeneyeballs;
            $examination->heent_sunkenfontanelle = $request->heent_sunkenfontanelle;
            $examination->heent_others = $request->heent_others;
            $examination->cl_essentiallynormal = $request->cl_essentiallynormal;
            $examination->cl_asymchestexpansion = $request->cl_asymchestexpansion;
            $examination->cl_decbreathsounds = $request->cl_decbreathsounds;
            $examination->cl_wheezes = $request->cl_wheezes;
            $examination->cl_lumpoverbreast = $request->cl_lumpoverbreast;
            $examination->cl_ralescracklesrhonchi = $request->cl_ralescracklesrhonchi;
            $examination->cl_interribclaretract = $request->cl_interribclaretract;
            $examination->cl_others = $request->cl_others;
            $examination->cvs_essentiallynormal = $request->cvs_essentiallynormal;
            $examination->cvs_disapexbeat = $request->cvs_disapexbeat;
            $examination->cvs_heavesthrills = $request->cvs_heavesthrills;
            $examination->cvs_pericarbulge = $request->cvs_pericarbulge;
            $examination->cvs_irregularrhythm = $request->cvs_irregularrhythm;
            $examination->cvs_muffledheartsounds = $request->cvs_muffledheartsounds;
            $examination->cvs_murmur = $request->cvs_murmur;
            $examination->cvs_others = $request->cvs_others;
            $examination->abd_essentiallynormal = $request->abd_essentiallynormal;
            $examination->abd_abdrigidity = $request->abd_abdrigidity;
            $examination->abd_abdtenderness = $request->abd_abdtenderness;
            $examination->abd_hyperbowelsounds = $request->abd_hyperbowelsounds;
            $examination->abd_palpablemass = $request->abd_palpablemass;
            $examination->abd_tympdullabdomen = $request->abd_tympdullabdomen;
            $examination->abd_uterinecontraction = $request->abd_uterinecontraction;
            $examination->abd_others = $request->abd_others;
            $examination->guie_essentiallynormal = $request->guie_essentiallynormal;
            $examination->guie_bldstainedinexamfinger = $request->guie_bldstainedinexamfinger;
            $examination->guie_cervicaldilatation = $request->guie_cervicaldilatation;
            $examination->guie_presenceabnodis = $request->guie_presenceabnodis;
            $examination->guie_others = $request->guie_others;
            $examination->skinex_essentiallynormal = $request->skinex_essentiallynormal;
            $examination->skinex_clubbing = $request->skinex_clubbing;
            $examination->skinex_coldclammyskin = $request->skinex_coldclammyskin;
            $examination->skinex_cyanosismottledskin = $request->skinex_cyanosismottledskin;
            $examination->skinex_edemaswelling = $request->skinex_edemaswelling;
            $examination->skinex_decmobility = $request->skinex_decmobility;
            $examination->skinex_palenailbeds = $request->skinex_palenailbeds;
            $examination->skinex_poorskinturgor = $request->skinex_poorskinturgor;
            $examination->skinex_rashespetechiae = $request->skinex_rashespetechiae;
            $examination->skinex_weakpulses = $request->skinex_weakpulses;
            $examination->skinex_others = $request->skinex_others;
            $examination->neuro_essentiallynormal = $request->neuro_essentiallynormal;
            $examination->neuro_abnormalgait = $request->neuro_abnormalgait;
            $examination->neuro_abnopositionsense = $request->neuro_abnopositionsense;
            $examination->neuro_abnodecsensation = $request->neuro_abnodecsensation;
            $examination->neuro_abnoreflexes= $request->neuro_abnoreflexes;
            $examination->neuro_pooralteredmemory = $request->neuro_pooralteredmemory;
            $examination->neuro_poormusctonestren = $request -> neuro_poormusctonestren;
            $examination->neuro_poorcoordination = $request->neuro_poorcoordination;
            $examination->neuro_others = $request -> neuro_others;
            $examination->entryby = Auth::user()->employeeid;
            $examination->user_id = Auth::user()->id;
            $examination->created_at = carbon::now();
            try{
                $examination->Save();
                return redirect()->back()
                ->with('type','success')
                ->with('msg','Physical Examination Created Successfully');

                //return view('admin.CF4.edit',['success' => 'Entry added succesfully']);
            }catch(\Exception $excpetion){
                //try to categorize the error using the exception.
                return redirect()->back()
                ->with('type','warning')
                ->with('msg','An error occurred!'.$excpetion);
                //return view('admin.CF4.edit',['error' => 'An error occurred!']);
            }


    }

}
public function cf4destroy(Request $request,$id='')
{
  try{
    //DB::table('users')->where('id', $id)->delete();

    $cf4 = DB::table('hcrsward')
    ->where('enccode',$id)
    ->where('tmetake',$request->tmetake);
    $cf4->delete();
        return redirect()->back()
            ->with('type','success')
            ->with('msg','Physical Examination updated Successfully.');

    }catch(\Exception $excpetion){
    return redirect()->back()->with('An error occurred!');

  }
}

}
