<?php

namespace App\Http\Controllers\Activity;

use App\Model\Activities;
use App\Model\activities_user;
use Illuminate\Http\Request;
use App\Http\Controllers\Help\HelpController;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Monolog\Formatter\LogglyFormatter;

class ActivityController extends Controller
{

    protected $fields = [
        'name' => '',
        'teacher' => '',
        'start_time' => '',
        'place' => '',
        'end_time' => '',
        'term' => '',
        'all_num' => '',
        'information' => '',
        'apply_start_time' => '',
        'apply_end_time' => '',
        'apply_state' => '',
    ];
    public function index()
    {
        $help = new HelpController;
        $Term = $help->GetYearSemester(date("Y-m-d"));
        $titleterm = $Term['YearSemester'];
        return view('acsystem.Activity.index', compact('titleterm'));
    }

    public function index1()
    {
        $help = new HelpController;
        $Term = $help->GetYearSemester(date("Y-m-d"));
        $titleterm = $Term['YearSemester'];
        return view('acsystem1.Activity.index', compact('titleterm'));
    }

    /**
     * @param $flag
     * flag is user's id
     * this function is to get the activities of flag teacher attended
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function ShowAttendActivities($flag){
        $activity = DB::table('activities_user')
            ->leftjoin('activities','activities_user.activities_id','=','activities.id')
            ->where('activities_user.user_id',$flag)
            ->where('activities_user.fin_state','!=','报名未参加')
            ->orderBy('term','desc')
            ->get();

        return $activity;
    }

    /**
     * @param $flag
     * flag is user's id
     * this function is to get the activities of flag teacher could attend
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function ShowTeacherActivities($userId){
        $help = new HelpController;
        $Term = $help->GetYearSemester(date("Y-m-d"));

        $activity = Activities::where('term',$Term['YearSemester'])->with(['users'=>function($query) use($userId){
            return $query->select('users.id','users.user_id')->where('users.id','=',$userId);
        }])->get();

        return $activity;

    }

    /**
     * @param Request $request
     * @return string
     */
    public function EnrollActivities(Request $request){
        $activityId = $request->get('activityId');
        $userId = $request->get('userId');
        $attendNum = $request->get('attendNum');
        $remainderNum = $request->get('remainderNum');
        $newAttendNum = intval($attendNum)+1;
        $newRemainderNum =intval($remainderNum)-1;

//        activities_user::updateOrCreate(
//            [
//                'activities_id' =>  $activityId,
//                'user_id' =>  $userId,
//            ],
//            [
//                'state' => '已报名',
//                'fin_state' => '报名未参加'
//            ]
//        );
        activities_user::insert(
            [
                'activities_id' =>  $activityId,
                'user_id' =>  $userId,
                'state' => '已报名',
                'fin_state' => '报名未参加'
            ]
        );

        //attend_num ++ and remainder_num --
        Activities::where('id',$activityId)->update([
            'attend_num' =>  $newAttendNum,
            'remainder_num' =>  $newRemainderNum
        ]);
        return '报名成功';
    }


    /**
     * @param Request $request
     * @return string
     */
    public function CancelActivities(Request $request){
        $activityId = $request->get('activityId');
        $userId = $request->get('userId');
        $attendNum = $request->get('attendNum');
        $remainderNum = $request->get('remainderNum');
        $newAttendNum = intval($attendNum)-1;
        $newRemainderNum =intval($remainderNum)+1;

        activities_user::where(
            [
                'activities_id' =>  $activityId,
                'user_id' =>  $userId,
                'state' => '已报名'
            ])->delete();

        //attend_num -- and remainder_num ++
        Activities::where('id',$activityId)->update([
            'attend_num' =>  $newAttendNum,
            'remainder_num' =>  $newRemainderNum
        ]);
        return '取消成功';
    }





    // 替换 create() 方法如下
    /**
     * Show form for creating new tag
     */
    public function modify()
    {
        $help = new HelpController;
        $Term = $help->GetYearSemester(date("Y-m-d"));
        $this->fields['term'] = $Term['YearSemester'];
        $titleterm = $Term['YearSemester'];
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        return view('acsystem.Activity.modify',$data, compact('titleterm'));
    }

    public function show($timeflag)
    {
        $help = new HelpController;
        $Term = $help->GetYearSemester(date("Y-m-d"));
        $this->fields['term'] = $Term['YearSemester'];
        $titleterm = $timeflag;
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        return view('acsystem.Activity.modify',$data, compact('titleterm'));
    }

    public function create(Requests\ActivityCreateRequest $request)
    {
//        dd($request->all());
        $input = $request->all();
        $input['remainder_num']=$input['all_num'];
        Activities::create($input);
        return redirect('/activity/modify')->withSuccess('添加成功！');
    }

    public function change(Requests\ActivityChangeRequest $request)
    {
        $input = $request->all();
        $flag = DB::table('activities')->where('name',$input['nameChange'])
            ->update([
                "teacher" => $input['teacherChange'],
                "start_time" => $input['start_timeChange'],
                "place" => $input['placeChange'],
                "end_time" => $input['end_timeChange'],
                "term" => $input['termChange'],
                "all_num" => $input['all_numChange'],
                "information" =>$input['informationChange'],
                //"state" => $input['stateChange'],
                "apply_start_time" => $input['apply_start_timeChange'],
                "apply_end_time" => $input['apply_end_timeChange'],
                "apply_state" => $input['apply_stateChange']
            ]);
        $flag = DB::update('update activities set remainder_num = all_num - attend_num');
        return redirect('/activity/modify')->withSuccess('修改成功！');
    }

    public function addteacher(Requests\TeacherAddRequest $request)
    {
        $input = $request->all();
        $arr = explode(',',$input['add-teacherid']);
        if(count($arr)>$input['remind_num'])
            return redirect('/activity/modify')->withErrors('超出剩余名额！');
        for($index=0;$index<count($arr);$index++) {
            $results = DB::select('select id from users where user_id = '.$arr[$index]);
            if(!$results)
                return redirect('/activity/modify')->withErrors($arr[$index].' 不存在的教师ID！');
            $sql1 = 'insert into activities_user select id,';
            $sql2 = ' as activities_id,\'已报名\' as state,\'报名未参加\' as fin_state from users where user_id = ';
            $sql = $sql1 . $input['act-ID'] . $sql2 . $arr[$index];

            $results = DB::select('select * from users a,activities_user b where a.user_id='.$arr[$index].' and a.id=b.user_id and b.activities_id =' . $input['act-ID']);
            if($results)
                return redirect('/activity/modify')->withErrors($arr[$index].' 参与教师ID已存在！');
            $flag = DB::insert($sql);

            DB::update('update activities set attend_num = attend_num + 1,remainder_num = all_num - attend_num where id ='.$input['act-ID']);

        }
       // Log::info();
        return redirect('/activity/modify')->withSuccess('添加成功！');
    }

    public function ShowActivities($timeflag){

        if($timeflag)
        {
            if($timeflag != 'all')
            {

                $activity = Activities::where('term','like',$timeflag.'%')->get();
            }
            else
                $activity = Activities::all();
        }
        else{//没有传时间标记的话，默认显示当前学期的
            $time = date("Y-m-d");
            $help = new HelpController;
            $Term = $help->GetYearSemester($time);
            $activity = Activities::where('term','=',$Term['YearSemester'])->get();
        }
        return $activity;
    }





    public function attendTeacher(Request $request){
        $activityId = $request->get('id');
        $attendPeople = DB::table('activities_user')
            ->select('users.unit','users.name')
            ->leftjoin('users','users.id', '=', 'activities_user.user_id')
            ->where('activities_id','=',$activityId)->get();

        return $attendPeople;
    }

    public function showTeachers(Request $request){
        $activityId = $request->get('id');
        $teachers = DB::table('activities_user')
            ->select('users.id','users.user_id','users.unit','users.name','activities_user.fin_state')
            ->leftjoin('users','users.id', '=', 'activities_user.user_id')
            ->where('activities_id','=',$activityId)->get();
        //log::info($teachers);
        return $teachers;
    }

    public function showTeachersExcel($activityId){
        $teachers = DB::table('activities_user')
            ->select('users.id','users.user_id','users.unit','users.name','activities_user.fin_state')
            ->leftjoin('users','users.id', '=', 'activities_user.user_id')
            ->where('activities_id','=',$activityId)->get();
        return $teachers;
    }

    public function deleteActivity(Request $request){
        $input = $request->all();
        DB::table('activities')->whereIn('id',$input['dataArr'])->delete();
        return ('删除成功！');
    }

    public function TeachersStateChange(Request $request){
        $input = $request->all();
        //log::info($input);
        $flag=DB::table('activities_user')
            ->where('activities_id',$input['activity_id'])
            ->whereIn('user_id',$input['dataArr'])
            ->update(['fin_state' => $input['newState']]);
        return ('修改成功！');
    }
}
