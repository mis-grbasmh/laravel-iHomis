<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
class Patientrooms extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hpatroom';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    protected $fillable = [
      'enccode', 'hpercode','hprdate','hprtime',
  ];

  Public Static Function get_patientrooms($id){
   $results =  Patientrooms::where('enccode',$id)
    ->join('hbed','hpatroom.bdintkey','hbed.bdintkey')
    ->join('hroom','hroom.rmintkey','hpatroom.rmintkey')
    ->join('hward','hward.wardcode','hpatroom.wardcode')
    ->join('hrmacc','hbed.rmaccikey','hrmacc.rmaccikey')
    ->select('hpatroom.enccode as itemcode',
  'hprdate',
//   'hsetup.sbasrm' ,
//   'hsetup.scuttime' ,
  'hrmacc.rmrate',
  'hpatroom.hprdate as transdate',
  'hpatroom.entryby',
  'hrmacc.rmrate',
  'hroom.rmname',
  'hward.wardname',
  'hbed.bdname',
  'hrmacc.rmaccdesc as itemdesc')
  ->where('patrmlock','N')
  ->where('rmvcode','<>','REVOK')
//   ->wherenotnull('civpopu')
    ->get();
    return $results;

  }


//   from hpatroom,hbed,
//   hrmacc, hroom,
//   henctr,    hhospmas  , hsetup
//   where hpatroom. bdintkey= hbed.bdintkey
//   and hbed.rmaccikey = hrmacc.rmaccikey
//   and hpatroom.rmintkey=hroom.rmintkey
//   and hpatroom.enccode= henctr.enccode
//   and hpatroom.enccode= :is_enccode
//   and patrmlock = 'N'
//   and rmvcode <> 'REVOK'
//   and civpopu is not null
//   order by  trandate

}
