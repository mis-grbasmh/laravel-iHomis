<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hcomplaints extends Model
{
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hmrhisto';
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
      'hpercode','enccode', 'datelog', 'histype','history','entryby',
  ];


  

}
