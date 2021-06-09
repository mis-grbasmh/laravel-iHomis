<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Courseward;
class CoursewardController extends Controller
{
    
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'enccode' => 'required'
        ]);
        $courseward = new Courseward();
        $enccode = str_replace("-","/",$request->enccode);
        $courseward->enccode = $enccode;
        $courseward->hpercode = $request->hpercode;
        $courseward->dtetake = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
        $courseward->tmetake = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
        $courseward->datemod = Carbon::parse($request->dtetake)->format('Y-m-d H:i:s');
        $courseward->crseward = strtoupper($request->courseward);
        $courseward->crsestat = 'A';
        $courseward->crselock = 'N';
        $courseward->entryby = Auth::user()->employeeid;
        $courseward->user_id = Auth::user()->id;
        $courseward->created_at = carbon::now();
        try{
            $courseward->Save();
            return redirect()->back()
            ->with('type','success')
            ->with('msg','Course in the ward created Successfully.');
           
            //return view('admin.CF4.edit',['success' => 'Entry added succesfully']);
        }catch(\Exception $excpetion){
            //try to categorize the error using the exception. 
            return redirect()->back()
            ->with('type','warning')
            ->with('msg','An error occurred!');
            //return view('admin.CF4.edit',['error' => 'An error occurred!']);
        }
    }

    public function update(Request $request)
    {
        $id = $request->input('idward');
    
        $updatecrsward = DB::table('hcrsward')->where('id','=',$id)
        ->first();
    if($updatecrsward)
    {    
      try{
        DB::table('hcrsward')
        ->where('id',$id)
        ->update([
        'crseward' => strtoupper($request->input('courseward')),
        'dtetake' => $request->input('dtetake'),
        'tmetake' => $request->input('dtetake')
        ]);
    }catch(\Exception $excpetion){
        return redirect()->back()
        ->with('type','warning')
        ->with('msg','error.'.$excpetion);
      }
    }
        return redirect()->back()
        ->with('type','success')
        ->with('msg','Course in the ward updated Successfully.');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('idward');
        $updatecrsward = DB::table('hcrsward')->where('id','=',$id)
        ->first();
    if($updatecrsward)
    {    
        DB::table('hcrsward')
        ->where('id',$id)
        ->update([
        'crseward' => $request->input('courseward')
        ]);
    }
        return redirect()->back()
            ->with('type','success')
            ->with('msg','Course in the ward updated Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

}
