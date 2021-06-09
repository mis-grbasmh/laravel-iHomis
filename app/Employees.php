<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Employees extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hpersonal';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];


    Public Static function Getemployeeinfo($id=''){
      $results = DB::table('hpersonal')->where('employeeid',$id)->first();
      return $results;
   }//inpatientlist

   public static function getEmployeeCount($status)
   {
             return Employees::where('empstat','=',$status)->count();
   }

public static function getEmployeeName($id)
{
   return Employees::where('employeeid',$id)->first()->select(DB::raw("CONCAT(lname,', ',fname,' ',mname')as Fullname"));
   //return Employees::where('employeeid',$id)->select(DB::raw("CONCAT(lastname,', ',firtname,' ',middlename')as fullname"))->first();
}

   


}
