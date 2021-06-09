<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Outpatient;
use App\Servicetype;
use App\Doctors;

class OutpatientController extends Controller
{
    Public $accomodations = array(
        'ADPAY'     =>  'Pay',
        'SERVI'     =>  'Service',
        'MEDPY'     =>  'PHIC Pay',
        'MEDCH'     =>  'PHIC Charity',
        'HMOPY'     =>  'Health Maintenance Org.',
    );
    public function index()
    {
        $doctors =  Doctors::where('hprovider.empstat','A')->where('hprovider.catcode','RESID')
        ->join('hpersonal','hpersonal.employeeid','=','hprovider.employeeid')
        ->join('hproviderclass','hproviderclass.code','=','hprovider.clscode')
        ->where('hpersonal.empprefix','DR')->where('hpersonal.empstat','A')
        ->where('hprovider.clscode','!=','ANEST')
        ->orderby('hpersonal.lastname','ASC')->get();
    $month_date = date('m');
    $year_date = date('Y');
    $datestart = date('Y-m-d');
    $dateend = date('Y-m-d 23:59:59');
    $services = Servicetype::all();
    $outpatients = Outpatient
    ::whereMonth('opddate',$month_date)
    ->whereyear('opddate',$year_date)
    ->wherenull('opddtedis')
    ->join('hperson','hperson.hpatcode','=','hopdlog.hpercode')
    ->join('htypser','htypser.tscode','=','hopdlog.tscode')
    ->orderby('opddate','DESC')
    ->select('hperson.patsex','hopdlog.enccode','hopdlog.hpercode','htypser.tsdesc','hopdlog.patage','hopdlog.opddate','hopdlog.licno','hopdlog.entryby')
    ->inRandomOrder()
    ->limit(20)
    ->get();


    return view('admin.patient.outpatient',compact('outpatients','doctors'))
    ->with('services')
    ->with('accomodations',$this->accomodations);

    }

    public function recentlyvisited()
    {
        $appointments = Outpatient::orderBy('opddate','desc')->paginate(10);
        return view('opd/recently-visited',compact('appointments'));
    }

}
