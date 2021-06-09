<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Hrxo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrxo';


    //hrxoissue
    Public Static Function getdrugsmedsorders($id=''){
    
    
        $results = DB::Table('hrxo')
        ->select('hrxo.qtyissued','hrxo.qtybal','hrxo.dodate','hrxo.entryby','hrxo.pcchrgcod', 'hgen.gendesc','hdmhdr.brandname','hstre.stredesc')
        ->join('hdmhdr','hdmhdr.dmdcomb','hrxo.dmdcomb') 
       
        ->join('hdruggrp','hdruggrp.grpcode','hdmhdr.grpcode')
        ->join('hgen','hgen.gencode','hdruggrp.gencode')
        ->join('hroute','hdmhdr.rtecode','hroute.rtecode','outer')               
        ->join('hstre','hdmhdr.strecode','hstre.strecode','outer') 
        ->join('hform','hdmhdr.formcode','hform.formcode','outer')   
        ->where('hrxo.enccode',$id)
        ->orderby('hrxo.dodate','DESC')->get()
         ; 
        return $results;
       
    
    }

    Public Static Function getdrugsmeditem($id=''){
        $results = DB::table('hdmhdr');

    }
    
}
