<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hdept';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    protected $fillable = [
      'deptcode', 'deptname','deptstat',
  ];

}
