<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hecase extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hecase';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    Public Static function get_er_case($id=''){
        $results = hecase::where('enccode',$id)->first();
        return $results;
     }//inpatientlist

}
