<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wards extends Model
{
       /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hward';


    

    Public Static Function getActiveWards(){
        return Wards::where('wardstat','A')->get();
    }

}
