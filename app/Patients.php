<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class Patients extends Model
{
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hperson';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

public Static function GenHealthno(){
    $data = "";
}

    Public Static function getAge($bdate=''){
        $day1 =0;
        $day2=0;
    return     $data = [
        'months'=> Carbon::parse($bdate)->diffInMonths(Carbon::now()),
        'day1'   => Carbon::parse(now())->format('d'),
        'day'  => Carbon::parse($bdate)->format('d'),
        'days'   => $day1 - $day2,
    ];

    }

    public function getnewcode(){
        $data = db::table('hperson')->DB::raw('SELECT');
    }

public function scopeSearchByKeyword($query, $keyword)
    {
        if ($keyword!='') {
            $query->where(function ($query) use ($keyword) {
                $query->where("hpercode", "LIKE","%$keyword%")
                    ->orWhere("patlast", "LIKE", "%$keyword%")
                    ->orWhere("patfirst", "LIKE", "%$keyword%");
            });
        }
        return $query;
    }

    Public static function getPatientInfo($id){
        $result = Patients::where('hperson.hpercode',$id)
            ->join('haddr','haddr.hpercode','hperson.hpercode')
            ->join('hreligion','hreligion.relcode','hperson.relcode')
            ->first();
            return $result;
    }
}
