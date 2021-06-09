<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Miscellaneous;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\Facades\DataTables;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function miscellaneous()
    {
        $miscellaneous = Miscellaneous::
        join('huom','huom.uomcode','=','hmisc.uomcode')->get();
        return view('admin.item.miscelleneous',compact('miscellaneous'));
    }

    public static function uomselect(){
        return DB::table('huom')->where('uomstat','A')->get();
    }


    public static function get_Itemsmedication(Request $request){
        if($request->ajax()){
            $drugitems = DB::table('hdmhdr')
            ->join('hdmhdrsub','hdmhdr.dmdcomb','=','hdmhdrsub.dmdcomb')
            ->join('hroute', 'hroute.rtecode', '=', 'hdmhdr.rtecode', 'left outer')
            ->join('hdruggrp','hdruggrp.grpcode','hdmhdr.grpcode','left outer')
            ->join('hgen','hgen.gencode','=','hdruggrp.gencode','left outer')
            ->join('hform','hdmhdr.formcode','=','hform.formcode','left outer')
            ->where('hdmhdr.dmdstat','A')->get();


            return Datatables::of($drugitems)

            ->addcolumn('itemcode',function($drugitem){
                return $drugitem->dmdcomb.$drugitem->dmdctr;
            })
            ->editColumn('dmdcomb', function($drugitem) {
                return $drugitem->dmdctr;
                })
                ->addcolumn('details',function($drugitem) {
                    // return $drugitem->gendesc.' '.$drugitem->dmdnost.' '.$drugitem->stredesc.' '.$drugitem->formdesc.' '.$drugitem->rtedesc;
                    $itemdetails =$drugitem->gendesc.' '.$drugitem->dmdnost.' '.$drugitem->formdesc;
                    return $itemdetails;
                    // <td><strong>{{$drugmed->gendesc}} {{$drugmed->dmdnost}} {{$drugmed->stredesc}} {{$drugmed->formdesc}} {{$drugmed->rtedesc }}</strong></td>

                })
                ->editcolumn('stockbal',function($drugitem) {
                    return number_format($drugitem->stockbal);
                })
                ->addColumn('action',function($drugitem){
                    return
                    '<a class="btn btn-info btn-sm btnSelect" data-toggle="tooltip" data-placement="bottom" data-details="'.$drugitem->gendesc.' '.$drugitem->dmdnost.' '.$drugitem->formdesc.'" data-id="'.$drugitem->dmdcomb.'" data-dmdctr="'.$drugitem->dmdctr.'"data-select="/drugitem/select">Select</a>';

                })
            ->make(true);
        }
    }

    public function getcharges_items(Request $request){
        if($request->ajax())
        {
            $output = '';
            $query = $request->get('query');
            if($query != '')
            {
                if($query =='LABOR' || $query=='LABTF' ){
                    $data = DB::table('hproc')
                        ->join('hprocm','hprocm.proccode','hproc.proccode')
                        ->select('procdesc')
                        ->where('hproc.prikey',$query)
                        ->first();
                    return $data->procdesc;
            }elseif($query =='NNDRR'){
                $data = DB::table('hclass2')
                ->where('cl2comb',$query)
                ->select('cl2desc')
                ->first();
            return $data->cl2desc;


            }elseif($query=='MISCE'){
                $data = DB::table('hmisc')
                ->where('hmstat','A')
                ->select('hmcode as code','hmdesc as item','hmamt','uomcode')
                ->get();
            }
            }
            $total_row = $data->count();
            if($total_row > 0)
            {
                foreach($data as $row)
                {
                    $output .= '
                        <option value>"' .$row->code.'">
                          '.$row->item.'</option>';
                }
            }else{
                $output = '
                    <tr>
                    <td align="center" colspan="6">No Data Found</td>
                   </tr>
                    ';
            }//else
        $data = array(
              'table_data'  => $output,
               'total_data'  => $total_row
            );

            echo json_encode($data);
        }
    }
}
