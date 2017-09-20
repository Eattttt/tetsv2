<?php

namespace App\Http\Controllers\Auth;

use App\Model\Permission;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //用户权限切换
    public function user_switch_start( $new_user )
    {
        session(['role'=>$new_user]);
        return Redirect::action('IndexController@index');
    }

    /**
     * 用户权限列表
     * getpermission从roles表中取出当前用的所有权限
     * @return mixed
     */

    public function GetStatus(Request $request)
    {
        $tid = $request->id;
        $year = $request->year;


        $user = User::where('id',$tid)->with(['roles'=>function($query) use ($year){
            return $query->where( 'supervise_time' ,'=' ,$year ) ;
        }])->get();

       if(empty($user[0]->roles[0])){//must be a teacher
           $user = User::where('id',$tid)->with(['roles'=>function($query) use ($year){
               return $query->where( 'role_id' ,'=' ,6 ) ;
           }])->get();
       }

        if(session('role') == null)
        {
            $role = $user[0]->roles[0]->name;
            session(['role'=>$role]);
        }

        $data = $user[0]->roles;
        return $data;
    }

    //咨询系统中当前登录的教师信息
    public function TeacherUserManage()
    {

        $user_info = DB::table('users')->where('user_id','=',Auth::user()->user_id)->get();
        $data=[
            'name'=>Auth::user()->name,
            'user_id'=>$user_info[0]->user_id,
            'phone'=>$user_info[0]->phone,
            'email'=>$user_info[0]->email,
        ];
        return view('acsystem.UserManage.TeacherUserManage',compact('data'));
    }

    public function getTeacherUserInfo(Request $request)
    {
        $all=$request->all();
        DB::table('users')->where('user_id', $all['user_id'])->update( ['email' => $all['email']]);
        DB::table('users')->where('user_id', $all['user_id'])->update( ['phone' => $all['phone']]);
//        Auth::user()->where('email',$request->get('email'))->update($all['phone']);
        return Redirect::to('/TeacherUserManage')->withCookie('mess','个人信息修改成功！');
    }



    //当前登录的督导用户信息
    public function userManage()
    {

        $user_info = DB::table('users')->where('user_id','=',Auth::user()->user_id)->get();
        $data=[
            'name'=>Auth::user()->name,
            'user_id'=>$user_info[0]->user_id,
            'phone'=>$user_info[0]->phone,
            'email'=>$user_info[0]->email,
        ];
        return view('userManage',compact('data'));
    }
    public function getUserInfo(Request $request)
    {
        $all=$request->all();
        DB::table('users')->where('user_id', $all['user_id'])->update( ['email' => $all['email']]);
        DB::table('users')->where('user_id', $all['user_id'])->update( ['phone' => $all['phone']]);
//        Auth::user()->where('email',$request->get('email'))->update($all['phone']);
        return Redirect::to('/userManage')->withCookie('mess','个人信息修改成功！');
    }

    public function ChangePass()
    {
        return view('changePass');
    }

    public function SubmitPass(Request $request)
    {
        $rules = array(
            'cpt' => 'required|captcha',
        );
        $messages = array(
            'cpt.required' => '请输入验证码',
            'cpt.captcha' => '验证码错误，请重试',
        );

        $input = array('cpt'=>Input::get('cpt'));
        $validator = Validator::make($input, $rules, $messages);
        $newPassword=$request->get('password_new');
        $newPassword_confirmation=$request->get('newPassword_confirmation');

        if($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else if(Hash::check($request->get('password_now'),Auth::user()->password)){
            if($newPassword==$newPassword_confirmation){
                Auth::user()->password=Hash::make($newPassword);
                Auth::user()->save();
                return Redirect::to('/changePass')->withCookie('message','密码修改成功！');
            }else{
                return  Redirect::back()->withErrors('两次密码不一致，请重新输入!');
            }
        }else{
            return  Redirect::back()->withErrors('原密码输入有误，重新输入!');
        }
    }



    public function TeacherChangePass()
    {
        return view('acsystem.UserManage.TeacherChangePass');
    }

    public function SubmitTeacherPass(Request $request)
    {
        $rules = array(
            'cpt' => 'required|captcha',
        );
        $messages = array(
            'cpt.required' => '请输入验证码',
            'cpt.captcha' => '验证码错误，请重试',
        );

        $input = array('cpt'=>Input::get('cpt'));
        $validator = Validator::make($input, $rules, $messages);
        $newPassword=$request->get('password_new');
        $newPassword_confirmation=$request->get('newPassword_confirmation');

        if($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else if(Hash::check($request->get('password_now'),Auth::user()->password)){
            if($newPassword==$newPassword_confirmation){
                Auth::user()->password=Hash::make($newPassword);
                Auth::user()->save();
                return Redirect::to('/TeacherChangePass')->withCookie('message','密码修改成功！');
            }else{
                return  Redirect::back()->withErrors('两次密码不一致，请重新输入!');
            }
        }else{
            return  Redirect::back()->withErrors('原密码输入有误，重新输入!');
        }
    }
}
