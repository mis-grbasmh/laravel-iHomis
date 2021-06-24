<?php
use Illuminate\Support\Facades\DB;

    Function DispositionType(){
        $data = array(
            'DISCH'     =>  'Discharged',
            'TRANS'     =>  'Transferred',
            'DAMA'      =>  'Discharge Against Medical Advise',
            'ABSC'      =>  'Absconded',
            'EXPIR'     =>  'Expired'
        );
        return $data;
    }

    Function ClinicalDisposition($type){
        $data = array(
            'TRASH'     =>  'Treated and Sent Home',
            'ADMIT'     =>  'For Admission',
            'REFER'     =>  'Referred',
            'REFAD'     =>  'Refused Admission',
            'OWC'       => 'Out When Called',
            'ABSC'       => 'Absconded',
            'TRANS'     =>'Transferred',
            'EXPIR'     =>  'Expired',
            'HAMA'      => 'Home Against Medical Advice',
            ''          => 'None'
        );
        if($type){
            return $data[$type];
        }
        return $data;
    }

    Function AccomodationType(){
        $data = array(
            'ADPAY'     =>  'Pay',
            'SERVI'     =>  'Service',
            'MEDPY'     =>  'PHIC Pay',
            'MEDCH'     =>  'PHIC Charity',
            'HMOPY'     =>  'Health Maintenance Org.',
        );
        return $data;
    }

    Function ServicecaseType(){
        $data = array(
            'HP'    => 'House Private (HP)',
            'PW'    => 'Private Walkin (PW)',
            'VP'    => 'Visiting Private (VP)',
            'CP'    => 'Charity (CP)'
        );

        return $data;
    }

    Function ConditionType(){
        $data= array(
            'RECOV'     =>  'Recovered',
            'IMPRO'     =>  'Improved',
            'UNIMP'     =>  'Unimproved',
            'DIEMI'     =>  'Died < 48 hours Autopsied',
            'DIENA'     =>  'Died < 48 hours not autopsied',
            'DIEPO'     =>  'Died > 48 hours autopsied',
            'DPONA'     =>  'Died > 48 hours not autopsied'
        );
        return $data;
    }
    Function RelationshipType($type){
        $data = array(
            'SELF'     => 'aboved-name person',
            'HUSBA'     => 'Husband',
            'MOTH'     => 'Mother',
            'SPOU'     => 'Spouse',
            'WIFE'     => 'Wife',
            'SISTE'     => 'Sister',
            'GNDCH'     => 'Grand Child',
            'RELAT'     => 'Relative',
            'AUNT'     => 'Aunt',
            'DAUGH'     => 'Daugther',
            'UNCLE'     => 'Uncle',
            'SISTE'     => 'Sister'
        );
        if($type){
            return $data[$type];
        }else
        {
            return $data;
        }

    }
    Function ReasonforTransfer(){
       $data =  DB::table('herlog')->select('reftxt')->distinct('reftxt')->get();
       return $data;
    }
    Function DoctorClassification(){
        $data = array(
            'GENPR'     =>  'General Practitioner(Med)',
            'SPECI'     =>  'Specialist',
            'SURGE'     =>  'Surgeon',
            'ANEST'     =>  'Anesthesiologist',
            'PEDIA'     =>  'Pediatrician',
            'OPTHA'     =>  'Opthamologist',
            'ORTHO'     =>  'Orthopedics',
            'OBGYN'     =>  'Obstetrics & Gynecologist',
            'NEURO'     =>  'Neurosurgeon',
            'DENTI'     =>  'Dentist',
            'OPHTA'     =>  'Ophthamologist',
            'DERMA'     =>  'Dermatologist',
            'PATHO'     =>  'Pathologist',
            'RADIO'     =>  'Radiologist',
            'PULMO'     =>  'Pulmonologist',
            'CARDI'     =>  'Cardiogist',
        );
        return $data;
    }


    Function convertDoctorClassification($class){
        $data = array(
            'GENPR'     =>  'General Practitioner',
            'SPECI'     =>  'Specialist',
            'SURGE'     =>  'Surgeon',
            'ANEST'     =>  'Anesthesiologist',
            'PEDIA'     =>  'Pediatrician',
            'OPTHA'     =>  'Opthamologist',
            'ORTHO'     =>  'Orthopedics',
            'OBGYN'     =>  'Obstetrics & Gynecologist',
            'NEURO'     =>  'Neurosurgeon',
            'DENTI'     =>  'Dentist',
            'OPHTA'     =>  'Ophthamologist',
            'DERMA'     =>  'Dermatologist',
            'PATHO'     =>  'Pathologist',
            'RADIO'     =>  'Radiologist',
            'PULMO'     =>  'Pulmonologist',
            'CARDI'     =>  'Cardiogist',
        );
        if($class){
            return $data[$class];
        }else
        {
            return $data;
        }


    }
    // Function Nationalities(){
    //     $data = array(
    //         'FILIP'     =>  'Filipino',
    //         'AMERI'     =>  'American',
    //         'SPANI'     =>  'Spanish',
    //         'CHINE'     =>  'Chinese',
    //         'GERMN'     =>  'German',
    //         'BANGD'     =>  'Bangladesh',
    //         'BRITS'     =>  'British',
    //         'ENGLS'     =>  'English',
    //         'FRNCH'     =>  'French',
    //         'CANAD'     =>  'Canadian'
    //     );
    //     return $data;
    // }

    Function Nationalities($nationality){
        $data = array(
            'FILIP'     =>  'Filipino',
            'AMERI'     =>  'American',
            'SPANI'     =>  'Spanish',
            'CHINE'     =>  'Chinese',
            'GERMN'     =>  'German',
            'BANGD'     =>  'Bangladesh',
            'BRITS'     =>  'British',
            'ENGLS'     =>  'English',
            'FRNCH'     =>  'French',
            'CANAD'     =>  'Canadian'
        );
        if ($nationality) {
            return $data[$nationality];
        }
        return $data;
    }

    Function OccupationType(){
        $data = array(
            'EMPLO'     => 'Employed',
            'UNEMPLO'     => 'Unemployed',

        );
        return $data;
    }

    Function convertOccupationType($occupation){
        if($occupation){
        $data = array(
            'EMPLO'     => 'Employed',
            'UNEMP'     => 'Unemployed',

        );
        return $data[$occupation];
    }else{
        return 'N/A';
    }
    }
    Function getDoctorCategory($category){
        $data= array(
            'RESID'    => 'Resident Physician',
            'VISIP'    => 'Visiting Physician',
            'INTER'    => 'Intern',
            'FELLO'    => 'Fellow',
            'CONSU'    => 'Consultant',
            'PHN'       => 'Public Health Nurse',
        );
        return $data[$category];
    }

    Function Suffix(){
        $data = [
            "NULL"           => "N/A",
            'SR'         => 'Sr.',
            'JR'         => 'Jr.',
            'II'         => 'II',
            'III'        => 'III',
            'IV'         => 'IV',
            'V'          => 'V',
        ];
        return $data;
    }

    function getGender($gender){
        $data = [
            'M' => 'Male',
            'F' => 'Female',
        ];
        return $data[$gender];
    }

    function getNewOld($newold){
        $data = [
            'O' => 'Old Patient',
            'N' => 'New Patient',
        ];
        return $data[$newold];
    }

    function ConvertNewOld($newold){
        $data = [
            'Old Patient' => 'O',
            'New Patient' => 'N',
        ];
        return $data[$newold];
    }


    Function UomTypes(){
       return DB::table('huom')->where('uomstat','A')
            ->orderby('uomcode','ASC')->get();
    }
    Function gen_hospitalno(){
    //return DB::table('hperson')->select(convert(char(10),max(convert(numeric(10),right(rtrim(hpercode),10)))))->first();
    return DB::table('genhpercode')->select('newcode')->first();
}




    Function Transfertypes(){
        return DB::table('herlog')->select('reftxt')->distinct('reftxt')->get();
    }

    function format_money($money){
        if(!$money) {
            return "\$0.00";
        }
        $money = number_format($money, 2);
        if(strpos($money, '-') !== false) {
            $formatted = explode('-', $money);
            return "-\$$formatted[1]";
        }
        return "\$$money";
    }



    function genEmployeeid(){
        $year = date('Y');
        // $result = \App\Employee::
    }



//Function to get Diet Order Description
Function getDietDesc($id){
    if($id){
        $results =   \App\hdiet::where('dietcode',$id)->first();
    }else{
        return "No Diet Order";
    }

    if($results){
        return $results->dietdesc;
    }
}//End Function getDietDesc

Function getVP($id){
    $results = DB::table('hvitalsign')->select('vsbp')->orderby('datelog','DESC')->first();
    if($results){
        return $results->vsbp;
    }else{
        return '&nbsp;&nbsp;';
    }
}



function getPhicDiagosis($id){
    // $result = DB::table('hphicdischargediagnosis')->where('enccode',$id)->first();

    $result = DB::table('hphicdischargediagnosis')->where('enccode',$id)
            ->select('prelatedprocedure','prvscode','pdischargediagnosis','picdcode')
            ->first();
      if($result){
            if($result->prelatedprocedure){
            return $result->prelatedprocedure ." (". $result->prvscode .") ";
        }elseif(!$result->prelatedprocedure){
            return $result->pdischargediagnosis ." (". $result->picdcode .") ";
        }else{
            $result = DB::table('hpatcon1')->where('enccode',$id)->first();
            if($result->pdischargediag1){
                return $result->pdischargediag1 ." (". $result->picdcode1 .") ";
            }else{
                return '<span class="badge badge-info>No Data Found</span>';
            }
        }
    }else{
        return 'Non-Phic'   ;
    }
    }

function getPhicProcedure($id){
if($id){
    $result = DB::table('hpatcon1')->where('enccode',$id)->first();
    if($result){
        return  $result->prelatedprocedure1 ." (". $result->prvscode1 .") ";
        }
    }
    }//function getPhicProcedure


    function getPatientname($id=''){
        if($id){
            $result = \App\Patients::where('hpatcode',$id)->first();
        }

        if($result){
            if(null===$result->patsuffix){
                return $result->patfirst ." ". $result->patmiddle." ".$result->patlast;
            }else{

                return $result->patfirst ." ". $result->patmiddle." ".$result->patlast." ".$result->patsuffix;

            }
        }else{
            return "No patient found";
        }
    }//function getPatientinfo


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
    function getFatherinfo($id=''){
        if($id){
            $result = \App\Patients::where('hpatcode',$id)->first();
        }

        if($result){
            if(null===$result->fatsuffix){
                return $result->fatlast .", ". $result->fatfirst ." ". $result->fatmid;
            }else{

                return $result->fatlast." ".$result->fatsuffix."., ". $result->fatfirst ." ". $result->fatmid;

            }
        }else{
            return "N/A";
        }
    }
    function getMotherinfo($id=''){
        if($id){
            $result = \App\Patients::where('hpatcode',$id)->first();
        }

        if($result){
                return $result->motlast .", ". $result->motfirst ." ". $result->motmid;
        }
    }

    function getSpouseinfo($id=''){
        if($id){
            $result = \App\Patients::where('hpatcode',$id)->first();
        }

        if($result->splast<>'' || $result->splast <> 'NONE'){
                return $result->splast .", ". $result->spfirst ." ". $result->spmid;
        }else{
            return 'None';
        }
    }

    Function getPatNationality($id){
        if($id){
            $result = \App\Patients::where('hpatcode',$id)->first();
        }
        if($result->natcode <> NULL){
            return Nationalities($result->natcode);
            // if($result->natcode == 'FILIP'){
            //     return 'FILIPNO';
            // }
        }else{
                return 'N/A';
        }

    }

    Function getPatReligion($id){
        if($id){
            $result = \App\Patients::where('hpatcode',$id)
            ->join('hreligion','hreligion.relcode','hperson.relcode')
            ->select('reldesc')
            ->first();
            if($result){
            if($result->reldesc <> NULL){
            return $result->reldesc;
            }else
            {
                return 'N/A';
            }
        }
        }
    }


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

function Getdoctorinfo($id){

    $result = DB::table('hpersonal')
    ->join('hprovider','hprovider.employeeid','=','hpersonal.employeeid')
    ->where('hprovider.licno',$id)
    ->select('hpersonal.lastname','hpersonal.firstname','hpersonal.middlename','hpersonal.empsuffix')
    ->first();
    if($result){
        if(null===$result->empsuffix){
            return "DR. ". $result->lastname .", ". $result->firstname;
        }else{
            return "DR. ". $result->lastname ." ". $result->empsuffix.", ". $result->firstname;
        }
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

function GetemployeeinfobyID($id){
    $result = DB::table('users')->where('id',$id)->first();
    if ($result) {
        return $result->name;
    }else{
        return 'User Not Found';
    }
}

function getLeaveType($leave_id)
{


    // $result = \App\LeaveType::where('id', $leave_id)->first();

    // return $result->leave_type;
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
    }elseif($chargecode=='DRUME'){
        //remove last digit from the itemcode
        $itemcode = substr($itemcode,0,12);
        $result = DB::table('hdmhdr')
            ->where('dmdcomb',$itemcode)
            ->join('hdruggrp','hdruggrp.grpcode','hdmhdr.grpcode')
            ->join('hgen','hgen.gencode','hdruggrp.gencode')
            ->join('hform','hdmhdr.formcode','=','hform.formcode','left outer')
            ->join('hstre','hstre.strecode','hdmhdr.strecode','left outer')
            ->join('hroute', 'hroute.rtecode', '=', 'hdmhdr.rtecode', 'left outer')
            ->select('hgen.gendesc','hform.formdesc','hstre.stredesc','hdmhdr.dmdnost','hroute.rtedesc')
            ->first();
         return $result->gendesc.' '.$result->formdesc.' '.number_format($result->dmdnost).' '.$result->stredesc.' ('.$result->rtedesc.')';
    }
}

    Function get_MeddrugsPrice($item,$date){
        $result = DB::table('hdmhdrprice')
            ->select('hdmhdrprice.dmselprice')
            ->where('hdmhdrprice.dmdcomb',$item)
            ->where('hdmhdrprice.dmdprdte',$date)
            ->limit(1)
            ->orderby('hdmhdrprice.dmdprdte','DESC')->first();
        return $result->dmselprice;
    }



// function getYearBudget(){
//     $result = \App\AcctDetails::where('Year','2020')->first();
//     return $result;
// }
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

Function getOperationproc($id){
    $results = DB::table('hproclog')->where('hproclog.enccode',$id)
    ->join('hproc','hproc.prikey','hproclog.prikey')
    ->join('hprocm','hprocm.proccode','hproc.proccode')
    ->first();
    return $results;
}


function  getLongDateFormat($date)
{
    $date = strtotime($date);
    return date('F d, Y ',$date);
}
function asDateTime($value) {
    if(empty($value)) {
        return null;
    }
    return date("h:i A", strtotime($value));
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

// function select($query, $bindings = array())
// {
//     return $this->run($query, $bindings, function ($me, $query, $bindings) {
//         if ($me->pretending()) return array();

//         // For select statements, we'll simply execute the query and return an array
//         // of the database result set. Each element in the array will be a single
//         // row from the database table, and will either be an array or objects.
//         $statement = $me->getPdo()->prepare($query);

//         $statement->execute($me->prepareBindings($bindings));

//         return $statement->fetchAll($me->getFetchMode());
//     });
// }

function getmssclassification($id){
     $result = DB::table('hpatmss')
    ->join('hmssclass','hmssclass.mssikey','hpatmss.mssikey')
    ->join('hmssmemtype','hmssmemtype.msstypecode','hpatmss.mssphictype')
    ->select('hmssclass.mssdesc','hmssmemtype.msstypedesc','hmssmemtype.msstypecode')
    ->where('hpatmss.enccode',$id)->first();
    if( $result){
        return $result->mssdesc.'-'.$result->msstypecode;
    }else{
        return '<span class="badge badge-warning">No Data Found!</span>';
    }
}


Function getcivilstatusdesc($id){
    switch($id){

       case 'S': return     'Single';       break;
       case 'M': return     'Married';      break;
       case 'W': return     'Widowed';      break;
       case 'C': return     'Child';        break;
       case 'S': return     'Separated';    break;
       case 'D': return     'Divorsed';     break;
     default:
        return 'N/A';
          break;
    }
}

Function get_reltomember($id){
    switch($id){

       case '1': return     'Legitimate spouse not NHIP member';       break;
       case '2': return     'Unmarried unemployed, legitimated, acknowledged and illegitimate children or legally adopted/stepchildren, below 21 years old';      break;
       case '3': return     'Unmarried children below 21 yrs old with physical/mental disability, congenital and/or acquired before reaching 21 yrs old and wholly dependent on member for support';      break;
       case '4': return     'Parent who is 60 years old and above, not NHIP member and wholly dependent on member for support';        break;
     default:
        return 'Self';
          break;
    }

}

function get_mmhr($date_start,$date_end){
    $results = DB::table('hpatcon1')
    ->join('hadmlog','hpatcon1.enccode','hadmlog.enccode')
        ->where('hadmlog.admdate','<',$date_start)
        ->where('hadmlog.disdate','>',$date_end)
        ->get();
     return  $results->count();
}


Function get_firstcasePF($id){

    $result = DB::table('phic_soa')
    ->select([
          'enccode',
          DB::raw("SUM(firstcase) as fc"),
          DB::raw("SUM(secondcase) as sc")
      ])
    ->groupBy('enccode')

    ->where('enccode',$id)
    ->where('pf',1)
    ->first();
    if($result){
return number_format($result->fc + $result->sc);
}else{ return 'No Data Found';
}
}

Function get_ActualPF($id){

    $result = DB::table('phic_soa')
    ->select([
          'enccode',
          DB::raw("SUM(balance) as actualpf")

      ])
    ->groupBy('enccode')

    ->where('enccode',$id)
    ->where('pf',1)
    ->first();
    if($result){
return number_format($result->actualpf);
}else{ return 'No Data Found';
}
}



Function get_firstcaseHCI($id){

    $result = DB::table('phic_soa')
    ->select([
          'enccode',
          DB::raw("SUM(firstcase) as fc"),
          DB::raw("SUM(secondcase) as sc")
      ])
    ->groupBy('enccode')

    ->where('enccode',$id)
    ->where('pf',0)
    ->first();
    if($result){
        return number_format($result->fc + $result->sc);
    }else{
        return 'No Data Found';
    }
}

Function get_ActualHCI($id){

    $result = DB::table('phic_soa')
    ->select([
          'enccode',
          DB::raw("SUM(balance) as actualhci")

      ])
    ->groupBy('enccode')
    ->where('enccode',$id)
    ->where('pf',0)
    ->first();
    if($result){
        return number_format($result->actualhci);
    }else{
        return 'No Data Found';
    }
}

function get_philhealthamount($id){
    $data= DB::table('hphicclaimmap')->where('hphicclaimmap.enccode',$id)
        ->select('pClaimSeriesLhio')->first();
    if($data){
        $result = DB::table('hphicclaimpayee')
        ->select([
              'pClaimSeriesLhio',
              DB::raw("SUM(pclaimamount) as phicamt")

          ])
        ->groupBy('pClaimSeriesLhio')
        ->where('pClaimSeriesLhio',$data->pClaimSeriesLhio)

        ->first();
        if($result){
            return number_format($result->phicamt);
        }else{
            return number_format('0.00');
        }
    }



    //$result = DB::table('hphicclaimpayee')
   // ->where('pClaimSeriesLhio',$id)->first();
}
// get_PhilhealthClaimPaid($id){
//     //select * from hphicclaimpayee where pClaimSeriesLhio='210225050204405';
// }



Function get_eclaimstatus($id){
    $result = DB::table('hphicclaimmap')->where('hphicclaimmap.enccode',$id)->first();
    if($result){
        if($result->pStatus <> NULL){
        return $result->pStatus;
        }else{
            return 'No process found';
        }
    }else{
        return 'N/A';
    }
}
Function PhicMembershiptype($id){
     switch($id){

        case '01': return 'Employed - Private Sector'; break;
        case '02': return 'Employed - Govt Sector'; break;
        case '03': return'Indigent'; break;
        case '04': return 'Individually Paying - Self Employed'; break;
        case '05': return 'Individually Paying - OFW'; break;
        case '06': return  'Individually Paying - Others'; break;
        case '07': return  'Individually Paying - OWWA'; break;
        case '08': return  'Retiree/Pensioner - SSS'; break;
        case '09': return  'Retiree/Pensioner - GSIS'; break;
        case'10':  return   'Retiree/Pensioner - Military'; break;
        case '11': return  'Retiree/Pensioner - GSIS'; break;
        case '12': return  'Senior Citizen'; break;
     }

}

Function getservicetype($id){
    switch($id){
     case   'HP'    :    return 'House Private'; break;
     case   'PW'    :    return 'Private Walkin'; break;
     case   'VP'    :    return 'Visiting Private'; break;
     case   'CP'    :    return 'Charity'; break;
     default:
     return 'N/A';
       break;
    }
}

Function getdoctortype($id){
    switch($id){
        case 'CONSU' : return 'Consultant'; break;
        case 'ADMIT' : return 'Admitting Physician'; break;
        case 'RESID' : return 'Resident Physician'; break;
        default:
        return 'Attending Physician';
            break;
    }
}

Function getprefix($id){
    switch($id){
        case 'MD' : return 'Doctor of Medicine'; break;
        case 'RN' : return 'Registered Nurse'; break;
        case 'RM' : return 'Registered Midwife'; break;
        case 'MT' : return 'Medical Technologist'; break;
        case 'ENGR' : return 'Engineer'; break;
        default:
        return 'Not Defined';
          break;
    }
}

function getUserIpAddr(){
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
 }


 function GetPaymentDetails($id){
     $results = DB::table('hpay')->where('enccode',$id)->first();
     if( $results){
        return $results;
    }
 }

 Function get_drugmedsprice($item,$date){
    return DB::table('hdmhdrprice')
        ->where('hdmhdrprice.dmdprdte',$date)
        ->where('hdmhdrprice.dmdcomb',$item)
        ->select('hdmhdrprice.dmduprice')
        ->first();
}
