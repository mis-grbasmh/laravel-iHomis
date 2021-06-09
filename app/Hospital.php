<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hospital extends Model
{
/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fhud_hospital';

     public function users(){
        return $this->belongsToMany('\App\User');
    }
}
