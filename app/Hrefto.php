<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hrefto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrefto';
    
    protected $fillable = [
        'docointkey', 'hpercode','srfcode','datemod','confdl','licno','hfhudcode','entryby','prreason',
    ];

}
