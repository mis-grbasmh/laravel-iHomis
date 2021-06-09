<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class phicmembers extends Model
{
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hphiclog';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];


    public function getnewcode(){
       
    }
}
