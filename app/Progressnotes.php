<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Progressnotes extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hprognte';

    protected $fillable = ['key','value'];
}
