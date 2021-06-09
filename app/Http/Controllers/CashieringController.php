<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Hadmcons;
use App\Doctors;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Datatables;


class CashieringController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function summaryreport()
    //Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
    {

    //     $transdate1 = Carbon::now()->format('Y-m-d');


    // $date1 = Carbon::parse($transdate1. '00:00:00');
    // $date2 = Carbon::parse($transdate1. '23:59:59');
    //     $payments = DB::table('hpay')
    //     ->join('hcharge','hpay.chrgcode','=','hcharge.chrgcode')
    //     ->where('ordate','>=',$date1)
    //     ->where('ordate','<=',$date2)
    //     ->get();
        return view('transactions.billing.index');

    }
}
