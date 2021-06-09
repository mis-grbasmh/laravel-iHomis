<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outpatient extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hopdlog';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enccode', 'hpercode', 'patage','tacode','tscode','opddate','opdtime','licno','opdrem','casenum','patagemo','patagedy','patagehr',
    ];

    Public Static Function get_CliniclRecordbyId($id){
        $results =Outpatient::where('enccode','=',$id)
        ->join('hperson','hperson.hpatcode','hopdlog.hpercode')
        ->select('opdtxt as admitdiag','hopdlog.hpercode','hopdlog.tscode','hopdlog.tacode','hopdlog.licno','hperson.hpatcode','hperson.patbdate','hperson.patsex','hopdlog.opddate as encdate','hopdlog.opddtedis as disdate','hopdlog.patage','hopdlog.opddisp')
        ->limit(1)
        ->first();

        return $results;
     }

}






