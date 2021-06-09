<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignsymptomsController extends Controller
{
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'enccode' => 'required'
        ]);

        $hsignsymptoms = Hsignsymptoms::where('enccode',$id);
     //   $hsignsymptoms->enccode = $request->enccode;
     //   $hsignsymptoms->datelog = $request->datelog;
     //   $hsignsymptoms->timelog = $request->timelog;
      //  $hsignsymptoms->datemod = $request->datemod;
        $hsignsymptoms->alter_mental_sensorium = $request->enccode;
        $hsignsymptoms->abdominal_cramp_pain = $request->enccode;
        $hsignsymptoms->anorexia = $request->anorexia;
        $hsignsymptoms->bleeding_gums = $request->bleeding_gums;
        $hsignsymptoms->body_weakness = $request->body_weakness;
        $hsignsymptoms->blurring_vision = $request->blurring_vision;
        $hsignsymptoms->chest_pain_discomfort = $request->chest_pain_discomfort;
        $hsignsymptoms->constipation = $request->constipation;
        $hsignsymptoms->cough = $request->cough;
        $hsignsymptoms->diarrhea = $request->diarrhea;
        $hsignsymptoms->dizziness = $request->dizziness;
        $hsignsymptoms->dysphagia = $request->dysphagia;
        $hsignsymptoms->dysuria = $request->dysuria;
        $hsignsymptoms->epistaxis = $request->epistaxis;
        $hsignsymptoms->fever = $request->fever;
        $hsignsymptoms->frequent_urination = $request->frequent_urination;
        $hsignsymptoms->headache = $request->headache;
        $hsignsymptoms->hematemesis = $request->hematemesis;
        $hsignsymptoms->hematuria = $request->hematuria;
        $hsignsymptoms->hemoptysis = $request->hemoptysis;
        $hsignsymptoms->hematemesis = $request->hematemesis;
        $hsignsymptoms->irritability = $request->irritability;
        $hsignsymptoms->jaundice = $request->jaundice;
        $hsignsymptoms->lower_extremity_edema = $request->lower_extremity_edema;
        $hsignsymptoms->myalgia = $request->myalgia;
        $hsignsymptoms->orthopnea = $request->orthopnea;
        $hsignsymptoms->painsite = $request->painsite;
        $hsignsymptoms->palpitations = $request->palpitations;
        $hsignsymptoms->seizures = $request->seizures;
        $hsignsymptoms->skin_rashes = $request->skin_rashes;
        $hsignsymptoms->sbbtm = $request->sbbtm;
        $hsignsymptoms->sweating = $request->sweating;
        $hsignsymptoms->urgency = $request->urgency;
        $hsignsymptoms->vomiting = $request->vomiting;
        $hsignsymptoms->weight_loss = $request->weight_loss;
        $hsignsymptoms->others = $request->others;
        $hsignsymptoms->dyspnea = $request->dyspnea;
        
        $hsignsymptoms->entryby = Auth::user()->employeeid;
        $hsignsymptoms->user_id = Auth::user()->id; 
        try{
            $hsignsymptoms->saveOrFail();
            return redirect()->back();
            //return view('admin.CF4.edit',['success' => 'Entry added succesfully']);
        }catch(\Exception $excpetion){
            //try to categorize the error using the exception. 
            return view('admin.CF4.edit',['error' => 'An error occurred!']);
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
        //
    }


}
