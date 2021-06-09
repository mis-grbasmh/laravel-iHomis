<?php

namespace App\Http\Controllers;
use App\Hrefrom;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Export;
use App\DataTables\ExportDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Validator;

class HrefromController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(Request $request){   
     // if($request->ajax()){              
      $patients = DB::table('hrefrom')
      ->select('id','enccode','hpercode','rfnotes',
      'rfdate',   
      'rftime',   
      'srfcode',   
      'tdcode',   
      'tdcode',   
      'rearefcod',   
      'rflicno',   
      'rfstat',   
      'rflock',   
      'datemod',   
      'updsw',   
      'confdl',   
      'rfcontrol', 
      'refnmtyp')
->wherenull('hrefrom.rfstat')
->get();
//die($request);
    
     //   die('me here');
          return Datatables::of($patients)
          ->addIndexColumn()
                    ->addColumn('action', function($row){
     
                           $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        
    //  }
      return view('transactions.referrals.referral_from');
  }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('transactions.referrals.referralfrom_create');
    }

}
