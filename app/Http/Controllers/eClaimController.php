<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
class eClaimController extends Controller
{
    public function statusperclaim($id=''){

           $status = DB::table('hphicclaimmap')
           ->where('enccode',$id)
           ->select('pStatus')
           ->first();

    }
}
