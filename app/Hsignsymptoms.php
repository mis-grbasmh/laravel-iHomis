<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hsignsymptoms extends Model
{
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hsignsymptoms';
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
      'enccode', 'email', 'password',
  ];

  public static function getSignssymptoms($id){
       
    return \App\Hsignsymptoms::where('enccode',$id)
    ->first();
}

}
