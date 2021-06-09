<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hdiag';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    protected $fillable = [
      'diagcat', 'diagscat','diagcode','diagdesc',
  ];


  

}
