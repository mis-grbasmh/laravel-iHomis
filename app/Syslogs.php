<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Syslogs extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'syslogs';


protected $fillable = [
        'prikey', 'method', 'description'
    ];

}
