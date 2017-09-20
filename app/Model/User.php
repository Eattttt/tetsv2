<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';  //定义用户表名称
    protected $primaryKey = "id";    //定义用户表主键
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','sex','name','starttime',
        'endtime','email','phone','state',
        'unit','status','group','workstate',
        'prorank','skill','password'
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
     * Get all of the roles for the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Model\Role')->withPivot('supervise_time');
    }

    /**
     * Get all of the activities for the user.
     */
    public function activities()
    {
        return $this->belongsToMany('App\Model\Activities')->withPivot('state');
    }

    /**
     * is this user has some role?
     */
    public function hasRole($role)
    {
        //
        if (is_string($role)){

            return $this->roles->contains('name',$role);
        }
//        dd($this->roles);
        return !! $role->intersect($this->roles)->count();//判断role中和this->roles中是否存在相同的个数，加反号之后
    }
}
