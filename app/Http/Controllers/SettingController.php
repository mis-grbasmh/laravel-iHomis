<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\Role;
use DB;
class SettingController extends Controller
{
    //
    public function index(){
        $setting = Setting::find(1);
        $roles = Role::all();
        $brand = DB::table('fhud_hospital')->where('hfhudcode','0000043')
        ->join('hcity','hcity.ctycode','fhud_hospital.ctycode')
        ->join('hprov','hprov.provcode','fhud_hospital.provcode')
        ->first(); 


        return view('admin.settings',compact('setting','roles','brand'));
    }



    public function store(Request $request){
        $setting = Setting::find(1);
        if ($request->hasFile('logo')) {
            $logo = time().'.'.request()->logo->getClientOriginalExtension();
            request()->logo->move(public_path('images'), $logo);
            $setting->logo = $logo;
        }
        $setting->title = $request->title;
        $setting->theme = $request->theme;
        $setting->save();

        $request->session()->flash('type','success');
        $request->session()->flash('msg','Record Added successfully.');

        return redirect('settings');
    }

    public function addRole(Request $request){
        $role = new Role();
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();
        return back()->with('type','success')->with('msg','Role Added Successfully');
    }
}
