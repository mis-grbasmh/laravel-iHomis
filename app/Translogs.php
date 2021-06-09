<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translogs extends Model
{
    /* The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trans_log';

    protected $fillable = [
        'user_name', 'tbl_name', 'primary_keys','tran_date','ue_mode','sys_desc'
    ];
}
/**
    