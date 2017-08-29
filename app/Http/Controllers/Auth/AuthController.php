<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserLoginRequest;
use App\Model\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Validator;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Help\CptcodeController;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $username = 'user_id';
    protected $redirectAfterLogout = '/login';


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    public function getLogin(){
        return view('auth.login');
    }

    public function getLogout(){
        Auth::guard($this->getGuard())->logout();
//        $this->auth->logout();
        Session::flush();
        return redirect('/login');
    }
    /**
     * 处理登录认证
     *
     * @return Response
     */
    public function postLogin(UserLoginRequest $request)
    {
        /**
         * 判断是否为微信浏览器登陆
         *
         * @return true or false
         */
        function is_weixin(){
            if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
                return true;
            }
            return false;
        }
        $cpt_validator = $this->validat_cpt();
//        dd($cpt_validator);
        if($cpt_validator == false) {

            return Redirect::to('/login')->withErrors('验证码错误，请重试');
        }
        else {

            $user_id = $request->input('user_id');
            $password = $request->input('password');
            if (empty($remember)) {  //remember表示是否记住密码
                $remember = 0;
            } else {
                $remember = $request->input('remember');
            }
            //如果要使用记住密码的话，需要在数据表里有remember_token字段
            if (\Auth::attempt(['user_id' => $user_id, 'password' => $password, 'status' => '活跃'], $remember)) {
//            dd($request->all());
                if(is_weixin() == true)
                {
                    return redirect()->intended('/weixinTheoryEvaluationTableView');
                }
                return redirect()->intended('/index');
            }
            return Redirect::back()->withErrors('用户名/密码错误');
        }
    }

    protected function validat_cpt(){

        $code = new CptcodeController;

        $input = Input::get('cpt');
        if( strtoupper($input)==$code->get())
            return true;
        else
            return false;
//            $validator = Validator::make($input, $rules, $messages);
//            //validate make 方法会接收 HTTP 传入的请求以及验证的规则。如果验证通过，你的代码就可以正常的运行。
//            return $validator;
    }

    /**
         * 登录
     */
    public function login(){

        if(Auth::check()){
            return Redirect::to('/index');
        }else{
            return view('auth.login');
        }
    }

}
