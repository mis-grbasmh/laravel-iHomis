<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hexamination extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hphyexam';

    public static function getExaminations($id){
       
        return \App\Hexamination::where('enccode',$id)
        ->first();
    }
}
