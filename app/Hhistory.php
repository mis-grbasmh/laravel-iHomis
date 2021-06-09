<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hhistory extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hmrhisto';

    public static function getHistory($id,$type){
        $results = \App\Hhistory::where('enccode',$id)
        ->where('histype',$type)
        ->select('history')
        ->first();

        if($results==NULL){
            return 'No entry found...';
        }else{
            return $results->history;
        }

    }
}
