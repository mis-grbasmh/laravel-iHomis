<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hsupplier';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'suppcode', 'suppname', 'suppowne','suppaddr',
    ];

    protected $dates = ['datemod'];

}
