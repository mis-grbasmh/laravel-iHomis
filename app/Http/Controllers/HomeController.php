<?php

namespace App\Http\Controllers;
use App\Sale;
use Carbon\Carbon;
use App\SoldProduct;
use App\Transaction;
use App\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Inpatients;


class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
     $year = '2021';

     $jan = Inpatients::wheremonth('hadmlog.admdate',1)
            ->whereyear('hadmlog.admdate',$year)->count();
    // $ = Inpatients::wheremonth('hadmlog.admdate',1)
    //         ->whereyear('hadmlog.admdate',$year)->count();
    //  wheremonth('hadmlog.disdate',$month)
     //     $monthlyBalanceByMethod = $this->getMethodBalance()->get('monthlyBalanceByMethod');
       // $monthlyBalance = $this->getMethodBalance()->get('monthlyBalance');

        //$anualsales = $this->getAnnualSales();
        //$anualclients = $this->getAnnualClients();
        //$anualproducts = $this->getAnnualProducts()
        
//        return view('dashboard', [
//            'monthlybalance'            => $monthlyBalance,
//            'monthlybalancebymethod'    => $monthlyBalanceByMethod,
//            'lasttransactions'          => Transaction::latest()->limit(20)->get(),
//            'unfinishedsales'           => Sale::where('finalized_at', null)->get(),
//            'anualsales'                => $anualsales,
//            'anualclients'              => $anualclients,
//            'anualproducts'             => $anualproducts,
//            'lastmonths'                => array_reverse($this->getMonthlyTransactions()->get('lastmonths')),
//            'lastincomes'               => $this->getMonthlyTransactions()->get('lastincomes'),
//            'lastexpenses'              => $this->getMonthlyTransactions()->get('lastexpenses'),
//            'semesterexpenses'          => $this->getMonthlyTransactions()->get('semesterexpenses'),
//            'semesterincomes'           => $this->getMonthlyTransactions()->get('semesterincomes')
//        ]);
        return view('dashboard',[
        'Jan'            =>  1212,
        'Feb'            => 230]);
    }


    public function getmorbidity_discharge(){
       $servicetypes = DB::table('htypser')->all();
       foreach ($servicetypes as $servicetype) {
           $morbidity = DB::table('hadmlog')
            ->select('hsubcateg.subcatdesc','hsubcateg.diagsubcat',   
                DB::raw("(select sum(hadmlog.condcode = 'RECOV' then 1 else 0 end)) as r"),
                DB::raw("(select sum(hadmlog.condcode = 'IMPRO' then 1 else 0 end)) as i"),
                DB::raw("(select sum(hadmlog.condcode = 'UNIMP' then 1 else 0 end)) as u"),
                DB::raw("(select sum(hadmlog.condcode = 'TRANS' then 1 else 0 end)) as t"),
                DB::raw("(select sum(hadmlog.condcode = 'DAMA' then 1 else 0 end)) as h"),
                DB::raw("(select sum(hadmlog.condcode = 'ABSC' then 1 else 0 end)) as a"),
                DB::raw("(select sum(hadmlog.condcode = 'DIEMI' or hadmlog.condcode = 'DIENA' then 1 else 0 end)) as died1"),
                DB::raw("(select sum(hadmlog.condcode = 'DIEPO' or hadmlog.condcode = 'DPONA' then 1 else 0 end)) as died2")
                

            )
            ->join('hdiag','hdiag.diagcode','hencdiag.diagcode')
            ->join('hencdiag','hencdiag.enccode','hadmlog.enccode')

           ->where('hadmlog')
           ->get();
       }//end foreach
       
//         SELECT hsubcateg.subcatdesc,hsubcateg.diagsubcat,   
// 			died_1 = sum(case when hadmlog.condcode = 'DIEMI'  or hadmlog.condcode = 'DIENA'	then 1 else 0 end),
// 			died_2 = sum(case when hadmlog.condcode = 'DIEPO'  or hadmlog.condcode = 'DPONA'	then 1 else 0 end),
// 			total = count(hsubcateg.subcatdesc),'                        '
//  FROM hadmlog,hdiag,hencdiag,hsubcateg,htypser
//  WHERE ( hadmlog.enccode = hencdiag.enccode ) and
// 	    ( hencdiag.diagcode = hdiag.diagcode ) and  
//        ( hdiag.diagsubcat = hsubcateg.diagsubcat ) and
// 		 ( hadmlog.tscode = htypser.tscode) and
//        ( htypser.tstype = 'MEDIC' )  and
//          ( hencdiag.primediag = 'Y' ) and
// 			( hencdiag.tdcode = 'FINDX') and
//        ( hadmlog.disdate between :fr_date and dateadd(dd,1, :to_date))
//  GROUP BY hsubcateg.subcatdesc,hsubcateg.diagsubcat
    }


    public function getMethodBalance()
    {
        $methods = PaymentMethod::all();
        $monthlyBalanceByMethod = [];
        $monthlyBalance = 0;

        foreach ($methods as $method) {
            $balance = Transaction::findByPaymentMethodId($method->id)->thisMonth()->sum('amount');
            $monthlyBalance += (float) $balance;
            $monthlyBalanceByMethod[$method->name] = $balance;
        }
        return collect(compact('monthlyBalanceByMethod', 'monthlyBalance'));
    }

    public function getAnnualSales()
    {
        $sales = [];
        foreach(range(1, 12) as $i) {
            $monthlySalesCount = Sale::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', $i)->count();

            array_push($sales, $monthlySalesCount);
        }
        return "[" . implode(',', $sales) . "]";
    }

    public function getAnnualClients()
    {
        $clients = [];
        foreach(range(1, 12) as $i) {
            $monthclients = Sale::selectRaw('count(distinct client_id) as total')
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $i)
                ->first();

            array_push($clients, $monthclients->total);
        }
        return "[" . implode(',', $clients) . "]";
    }

    public function getAnnualProducts()
    {
        $products = [];
        foreach(range(1, 12) as $i) { 
            $monthproducts = SoldProduct::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', $i)->sum('qty');

            array_push($products, $monthproducts);
        }        
        return "[" . implode(',', $products) . "]";
    }

    public function getMonthlyTransactions()
    {
        $actualmonth = Carbon::now();

        $lastmonths = [];
        $lastincomes = '';
        $lastexpenses = '';
        $semesterincomes = 0;
        $semesterexpenses = 0;

        foreach (range(1, 6) as $i) {
            array_push($lastmonths, $actualmonth->shortMonthName);

            $incomes = Transaction::where('type', 'income')
                ->whereYear('created_at', $actualmonth->year)
                ->WhereMonth('created_at', $actualmonth->month)
                ->sum('amount');

            $semesterincomes += $incomes;
            $lastincomes = round($incomes).','.$lastincomes;

            $expenses = abs(Transaction::whereIn('type', ['expense', 'payment'])
                ->whereYear('created_at', $actualmonth->year)
                ->WhereMonth('created_at', $actualmonth->month)
                ->sum('amount'));

            $semesterexpenses += $expenses;
            $lastexpenses = round($expenses).','.$lastexpenses;

            $actualmonth->subMonth(1);
        }

        $lastincomes = '['.$lastincomes.']';
        $lastexpenses = '['.$lastexpenses.']';

        return collect(compact('lastmonths', 'lastincomes', 'lastexpenses', 'semesterincomes', 'semesterexpenses'));
    }
}
