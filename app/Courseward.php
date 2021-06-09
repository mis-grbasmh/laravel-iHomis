<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courseward extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hcrsward';

    protected $fillable = ['key','value'];


    public static function getCourseWard($id){
       
        $results = \App\Courseward::where('enccode',$id)
        ->select('id','enccode','dtetake','tmetake','crseward','entryby','user_id','created_at')->orderby('tmetake','ASC')
        ->get();
        
        if($results==NULL){
            return 'No entry found...';
        }else{
            return $results;
        }

    }
}
