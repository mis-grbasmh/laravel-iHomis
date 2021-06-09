<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientDiagnosis extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hencdiag';
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
