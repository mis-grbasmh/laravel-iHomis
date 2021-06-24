<?php

$conditions = array(
    'RECOV'     =>  'Recovered',
    'IMPRO'     =>  'Improved',
    'UNIMP'     =>  'Unimproved',
    'DIEMI'     =>  'Died < 48 hours Autopsied',
    'DIENA'     =>  'Died < 48 hours not autopsied',
    'DIEPO'     =>  'Died > 48 hours autopsied',
    'DPONA'     =>  'Died > 48 hours not autopsied'
);

$accomodations = array(
    'ADPAY'     =>  'Pay',
    'SERVI'     =>  'Service',
    'MEDPY'     =>  'PHIC Pay',
    'MEDCH'     =>  'PHIC Charity',
    'HMOPY'     =>  'Health Maintenance Org.',
);
    $dispositions = array(
        'DISCH'     =>  'Discharged',
        'TRANS'     =>  'Transferred',
        'DAMA'      =>  'Discharge Against Medical Advise',
        'ABSC'      =>  'Absconded',
        'EXPIR'     =>  'Expired'
    );

   


function getNewEmployeeNo(){
    $year = date('Y');
   // $result = \App\Employee::
}

Function getDietDesc($id){
   if($id){
        $results =   \App\hdiet::where('dietcode',$id)->first();
   }else{
        return "No Diet Order";
   }
   if($results){
    return $results->dietdesc;
   }
}

  function getPatientinfo($id=''){

  if($id){
    $result = \App\Patients::where('hpatcode',$id)->first();
  }

    if($result){
        if(null===$result->patsuffix){
            return $result->patlast .", ". $result->patfirst ." ". $result->patmiddle;
        }else{

            return $result->patlast." ".$result->patsuffix."., ". $result->patfirst ." ". $result->patmiddle;

    }
}else{
    return "No patient found";
}

}//function getPatientinfo

function getPatientAddress($id=''){
    $result = DB::table('haddr')->where('hpercode',$id)
    ->join('hbrgy','hbrgy.bgycode  ','=','haddr.brg')
    ->join('hcity','hcity.ctycode','=','haddr.ctycode')
    ->join('hprov','hprov.provcode','=','hcity.ctyprovcod')->first();
    if($result){
    if($result->patstr){
    return $result->patstr. " ".$result->bgyname.", ". $result->ctyname.", ". $result->provname;}else{
        return $result->bgyname.", ". $result->ctyname.", ". $result->provname;
    }
}else {return "NO ADDRESS FOUND";}
}

// Function getPhicMember($id){
//     if($id){
//          $result = DB::table('hpatcon')
//             ->join('hphiclog','hphiclog.phicnum','hpatcon.memphicnum')
//             ->where('hpatcon.enccode',$id)
//          ->first();
//          return $result->memlast .", ". $result->memfirst ." ". $result->memmid;
//     }else{
//             return "No Member Recorded";
//     }
// }

Function GetMSSType($id){
    $result = DB::table('hpatmss')
    ->join('hmssclass','hmssclass.mssikey','hpatmss.mssikey')
    ->join('hmssmemtype','hmssmemtype.msstypecode','hpatmss.mssphictype')
    ->where('hpatmss.enccode',$id)->first();
    if($result){
        //hmssclass.mssdesc','hmssmemtype.msstypedesc','hmssmemtype.msstypecode'
        return $result->mssdesc .", ". $result->msstypedesc ." ".$result->msstypecode;
    }else{
        return 'No Data';
    }
}


function getPhicMember($id=''){

    $result = DB::table('hpatcon')
    ->join('hphiclog','hphiclog.phicnum','hpatcon.memphicnum')
    ->where('hpatcon.enccode',$id)
    ->select('hphiclog.memlast','hphiclog.memfirst','hphiclog.memmid')
    ->first();
    if($result){

    return $result->memlast .", ". $result->memfirst ." ".$result->memmid;
    }else{
        return '';
    }
    //return $licno;
 }//inpatientlist

function Getdoctorinfo($id=''){

    $result = DB::table('hpersonal')
    ->join('hprovider','hprovider.employeeid','=','hpersonal.employeeid')
    ->where('hprovider.licno',$id)
    ->select('hpersonal.lastname','hpersonal.firstname')
    ->first();
    if($result){

    return $result->lastname .", ". $result->firstname;
    }else{
        return '';
    }
    //return $licno;
 }//inpatientlist

function Getemployeeinfo($id=''){
    $result = \App\Employees::where('employeeid',$id)->first();
        if ($result) {
            return $result->lastname .", ". $result->firstname;
        }else{
            return $id;
        }
}

function getLeaveType($leave_id)
    {


        $result = \App\LeaveType::where('id', $leave_id)->first();

        return $result->leave_type;
    }

function getPatientAccountNo($id){
    $result = DB::table('hpatacct')
        ->where('enccode',$id)
        ->select('paacctno')->first();
        if( $result){
            return $result->paacctno;
        }else{
            return '';
        }


}

function getItem_desc($itemcode,$chargecode){
    if($chargecode =='LABOR' || $chargecode=='LABTF' ){
            $result = DB::table('hproc')
                ->join('hprocm','hprocm.proccode','hproc.proccode')
                ->select('procdesc')
                ->where('hproc.prikey',$itemcode)
                ->first();
            return $result->procdesc;
    }elseif($chargecode =='NNDRR'){
        $result = DB::table('hclass2')
        ->where('cl2comb',$itemcode)
        ->select('cl2desc')
        ->first();
    return $result->cl2desc;


    }elseif($chargecode=='MISCE'){
        $result = DB::table('hmisc')
        ->where('hmcode',$itemcode)
        ->select('hmdesc')
        ->first();
        return $result->hmdesc;
    }
}



    function getYearBudget(){
        $result = \App\AcctDetails::where('Year','2020')->first();
        return $result;
    }
    function covertDateToDay($date)
    {
        $day = strtotime($date);
        $day = date("l", $day);

        return strtoupper($day);
    }

    /*
    function getFormattedDate($date)
    {
        $date = new DateTime($date);
        return date_format($date, 'l jS \\of F Y');
    }*/

    function getPassword($input='')
    {
        $str = '';
        $input='&6A2CÖ';
        // for($i=0;$i<strlen($input);$i+=2) $str .= chr(hexdec(substr($input,$i,2)));
        $str = mb_convert_encoding($input, "EUC-JP", "auto");

    $raw1 = Trim($input);
    $key1 = Trim('advise');
    $key2 = $key1;
    $s_enc = '';
    $kc = '';
    $llr = strlen($raw1);
    $i = 1;
    $b=0;
    $c=0;
    $k =0;
    $decode = 0;
    Do {
        $key2 = $key2 ." ".$key1;
    }while (strLen($key2) < $llr);

    $key1 = substr($key2, 1, $llr);
 for($i = 1; $i<$llr; $i++){
    $b = ord(substr($raw1, $i, 1));
         $c = ord(substr($key1, $i, 1));
       //  $k = ($b - $c) + 124;
         if($k >= 255){
            $k = $k - 256;
        }

      $kc = Chr($k);
     $decode = $decode.$k."=".$kc." ";
     $s_enc = $s_enc . $kc;
    }

     return substr($raw1, $i, 1);
    //  return $k;
    }

    function getFormattedDate($date)
    {
        $date = strtotime($date);

        return date('M-d-Y', $date);
    }




    function  getDateFormat($date)
    {
        return $date->format('Y-m-d H:i:s.v');
    }
    function asDateTime($value) {
        if(empty($value)) {
            return null;
        }
        return date("H:i A", strtotime($value));
    }

    function count_admission($id=''){
        return DB::table('hadmlog')
            ->where('hpercode',$id)->count();
    }
    function count_er($id=''){
        return DB::table('herlog')
            ->where('hpercode',$id)->count();
    }
    function count_opd($id=''){
        return DB::table('hopdlog')
            ->where('hpercode',$id)->count();
    }
    function select($query, $bindings = array())
    {
    return $this->run($query, $bindings, function($me, $query, $bindings)
    {
        if ($me->pretending()) return array();

        // For select statements, we'll simply execute the query and return an array
        // of the database result set. Each element in the array will be a single
        // row from the database table, and will either be an array or objects.
        $statement = $me->getPdo()->prepare($query);

        $statement->execute($me->prepareBindings($bindings));

        return $statement->fetchAll($me->getFetchMode());
    });


function passDecryption($input=''){
//    $raw1 = Trim($input);
//     $key1 = Trim('advise');
//     $key2 = $key1;
//     $s_enc = '';
// $kc = '';
//     $llr = strlen($raw1);
//     $i = 1;
//     $b=0;
//     $c=0;
//     $k =0;
//     $decode = 0;

//     Do {
//         $key2 = $key2 + " " + $key1;
//     }while (strLen($key2) < $llr);

//     $key1 = Mid($key2, 1, $llr);

    // for($i = 1; $i<$llr; $i++){
    //     $b = Microsoft.VisualBasic.Asc(Mid($raw1, $i, 1));
    //     $c = Microsoft.VisualBasic.Asc(Mid($key1, $i, 1));
    //     $k = ($b - $c) + 124;
    //     if($k >= 255){
    //         $k = $k - 256;
    //     }

    //     $kc = Convert.ToChar($k);
    //     $decode = $decode.$k."=".$kc." ";
        $s_enc = $s_enc + $kc;
    }
  return   $s_enc;
}
