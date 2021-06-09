<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hrefrom extends Model
{
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrefrom';

    protected $fillable = [
        'enccode', 'hpercode','rfnotes','datemod','confdl','entryby',
    ];


    

}
