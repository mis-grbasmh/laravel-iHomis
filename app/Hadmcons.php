<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Hadmcons extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hadmcons';
 /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    public $timestamps = true;

  //  protected $guarded = [];
    //use SoftDeletes;
   // protected $fillable = ['key','value'];
    protected $fillable = ['enccode', 'licno','acostat','acolock','acodatemod','acoconfdl','hpercode','doctype','entryby','user_id'];
  //  protected $fillable = ['enccode', 'licno','hpercode','doctype','created_at'];

   public static function get_patientdoctors($id){
        return  hadmcons::where('enccode',$id)
        ->join('hprovider','hprovider.licno','hadmcons.licno')
        ->where('hprovider.empstat','A')
        ->orderby('acocodatemod','ASC')
        ->get();
    }

    public function user()
{
  return $this->belongsTo('App\User');
}

}
