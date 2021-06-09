<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hencdiag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hencdiag';

    public static function getFinalDiagnosis($id){

        $results = Hencdiag::where('enccode',$id)
        ->where('tdcode','FINDX')
        ->first();
        if($results==NULL){
            return 'No entry found...';
        }else{
            return $results->diagtext;
        }

    }

    public static function getPatientDiagnosis($id,$type){
        $results = Hencdiag::where('enccode',$id)
        ->where('tdcode',$type)
        ->orderby('encdate','DESC')
        ->first();
        return $results;
    }


    public static function getClinicalDiagnosis($id){
        $results = Hencdiag::where('enccode',$id)
        ->where('tdcode','CLIDI')
        ->first();
        if($results==NULL){
            return 'No entry found...';
        }else{
            return $results->diagtext;
        }
    }

    public static function getDiagnosisbytype($type){
        $results = Hencdiag::where('enccode',$type)
        ->where('tdcode',$type)
        ->first();
        if($results==NULL){
            return 'No entry found...';
        }else{
            return $results->diagtext;
        }
    }//end function getDiagnosisbytype
}
