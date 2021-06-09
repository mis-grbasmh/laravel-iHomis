<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\Hadmcons;
class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','employeeid','username','role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $dates = ['deleted_at'];

    public function user()
    {
      return $this->belongsTo('App\User','id','user_id');
    }
    public function Roles(){
        return $this->belongsToMany('App\Role');
    }

    public function hospitals(){
        return $this->belongsToMany('\App\Hospital');
    }

    public function doctors(){
        return $this->belongsToMany('\App\Doctors');
    }

    public function deletePrevRole($role_id,$user_id){
        return DB::delete("delete from role_user where role_id = $role_id and user_id= $user_id");
    }

    public function hasAnyRoles($roles){
        return null !== $this->roles()->whereIn('name',$roles)->first();
    }
    public function hasRole($role){
        return null !== $this->roles()->where('name',$role)->first();
    }

    public function hasHospital($hospid){
        return null !== $this->hospitals()->where('hfhudcode',$hospid)->first();
    }
    public function Hadmcons()
    {
      return $this->hasMany('App\Hadmcons');
    }

}
