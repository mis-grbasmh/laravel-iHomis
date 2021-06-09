<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Providers extends Model
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


    public static $doctortypes = array(
            'ADMIT'  => 'ADMITTING DOCTOR',
            'CONSU'  => 'CONSULTING DOCTOR',
            'ATTEN'  => 'ATTENDING DOCTOR',
            'FELLO'  =>  'FELLOW DOCTOR',
        );





}
