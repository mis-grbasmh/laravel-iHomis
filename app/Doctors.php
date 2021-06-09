<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Doctors extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hprovider';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    public function users(){
      return $this->belongsToMany('\App\User');
  }

    public static function getActiveDoctors($doctype){
      $data =Doctors::where('hprovider.empstat','A')->where('hprovider.catcode',$doctype)
      // ->where('hprovider.clscode','<>','ANEST')
      ->orwhere('hprovider.catcode','=','VISIP')
      ->orwhere('hprovider.catcode','=','RESID')
      ->orwhere('hprovider.catcode','=','CONSU')
      ->join('hproviderclass','hproviderclass.code','hprovider.clscode')
      ->select('hprovider.licno','hprovider.docpma','hproviderclass.name')
      ->get();
    return $data;
}
    public static function getPatientsDoctors($id){
      $data = DB::table('hprofserv')
      ->where('enccode',$id)
      ->select('hprofserv.licno')
      ->get();
    return($data);
}

}
