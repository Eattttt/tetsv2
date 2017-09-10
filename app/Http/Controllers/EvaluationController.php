<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Help\HelpController;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Redirect;

class EvaluationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',['only',['NecessaryTask','Evaluation']]);
    }


    /**
     * 必听任务数据获取

     */
    public function GetNecessaryTask(Request $request)
    {
        $help = new HelpController;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $unit = $request->group;



        $year = $year1."-".$year2;
        //        $year='2016-2017';
        //        $semester=array(0=>'1');
        //        $unit='第四组';
        if($unit==null)
        {
            $ids = array('"第一组"', '"第二组"', '"第三组"', '"第四组"');
            $ids_ordered = implode(',', $ids);

            $DataArr = DB::table("lessons")
                ->where('lesson_level','=','关注课程')
                ->where('lesson_year','=',$year)
                ->where('lesson_semester','=',$semester[0])
                ->orderByRaw(DB::raw("FIELD(assign_group, $ids_ordered)"))
                ->orderBy('lesson_teacher_name','asc')
                ->get();
        }
        else
        {
            /*
             *             将同一组督导所关注的学院字段全部提取出来，通过for循环设置SQL查询语句
             *             排序要求：
             *              1、小组内督导对应的学院
             *              2、按小组顺序
             *              3、教师名字升序
             * */

            $unit = $help->GetUnitByGroup($unit);
//            Log::write('info',$unit);
            $rule='ORDER by ';
            for($k=0;$k<count($unit);$k++)
            {
                $rule .= "lesson_teacher_unit='".$unit[$k]->unit."'  desc ,";
            }
//            $rule=trim($rule,',');
            $DataArr = DB::select('select * from lessons
                where lesson_level=\'关注课程\'
                and lesson_year="'.$year.'"
                and lesson_semester="'.$semester[0].'" '
                .$rule.
                ' FIELD(assign_group ,\'第一组\', \'第二组\', \'第三组\', \'第四组\'),
                 lesson_teacher_name asc');
        }
        if ($DataArr == null )
        {
            return -1;
        }
        else
        {
            for ($i=0;$i<count($DataArr);$i++)
            {
                switch ($DataArr[$i]->lesson_time){
                    case '0102':$DataArr[$i]->lesson_time = '上午1-2节';break;
                    case '0304':$DataArr[$i]->lesson_time = '上午3-4节';break;
                    case '0506':$DataArr[$i]->lesson_time = '下午1-2节';break;
                    case '0708':$DataArr[$i]->lesson_time = '下午3-4节';break;
                    case '0910':$DataArr[$i]->lesson_time = '晚上1-2节';break;
                    case '01020304':$DataArr[$i]->lesson_time = '上午4节';break;
                    case '05060708':$DataArr[$i]->lesson_time = '下午4节';break;
                    case '091011':$DataArr[$i]->lesson_time = '晚上1-3节';break;
                }
            }
            return json_encode($DataArr);
        }


    }
    /**
     * 删除必听任务
     * 应该根据years删除
     *
     */
    public function DeleteNecessaryTask1()
    {
        return Redirect::to('/index');
    }

    public function DeleteNecessaryTask(Request $request)
    {
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $year = $year1."-".$year2;
        $dataArr = $request->dataArr;
//        Log::write('info',($dataArr));
//        在lesson表中将lesson_level更新为自主听课
        $data=null;
        for ($i=0;$i<count($dataArr);$i++)
        {
            $data = DB::table('lessons')
                ->where("lesson_name",'=',$dataArr[$i]['lesson_name'])
                ->where("lesson_teacher_name",'=',$dataArr[$i]['lesson_teacher_name'])
                ->where("lesson_teacher_unit",'=',$dataArr[$i]['lesson_teacher_unit'])
                ->where("lesson_year",'=',$year)
                ->where("lesson_semester",'=',$semester)
                ->update([
                    'lesson_type'=>null,
                    'assign_group'=>'',
                    'lesson_level'=>'自主听课',
                    'lesson_grade'=>null
                ]);
        }

        return "删除成功！";


    }

    /**
     * 增加必听任务课程
     */
    public function AddNecessaryTask(Request $request)
    {
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $year = $year1."-".$year2;
        $teacher = $request->teacher;
        $lesson = $request->lesson;
        $group = $request->group;
        $reason = $request->reason;

        $data = DB::table('lessons')
            ->where("lesson_name",'=',$lesson)
            ->where("lesson_teacher_name",'=',$teacher)
            ->where("lesson_year",'=',$year)
            ->where("lesson_semester",'=',$semester)
            ->update([
                'lesson_level'=>'关注课程',
                'assign_group'=>$group,
                'lesson_attention_reason'=>$reason
            ]);
        if ($data!=null)
        {
            return "添加成功！";
        }
        else{
            return "添加失败！";

        }
    }
    /**
     * 必听任务视图
     */
    public function NecessaryTask()
    {

        return view('NecessaryTask');
    }


    /**
     * 关注课程完成情况视图
     */
    public function Evaluation()
    {
        $frontdata=$this->GetFrontValueTable();
        for($i=0;$i<count($frontdata[1]);$i++)
            if($frontdata[1][$i]->text=='理论课评价表')break;
        $front =array(
            '1'=>array_key_exists($i,$frontdata[2])?$frontdata[2][$i]:array(),//一级菜单项
            '2'=>array_key_exists($i,$frontdata[3])?$frontdata[3][$i]:array(),//二级菜单项
            '3'=>array_key_exists($i,$frontdata[4])?$frontdata[4][$i]:array()//三级菜单项
        );
        $backdata=$this->GetBackValueTable();
        for($i=0;$i<count($backdata[1]);$i++)
            if($backdata[1][$i]->text=='理论课评价表')break;
        $back =array(
            '1'=>array_key_exists($i,$backdata[2])?$backdata[2][$i]:array(),//一级菜单项
            '2'=>array_key_exists($i,$backdata[3])?$backdata[3][$i]:array(),//二级菜单项
            '3'=>array_key_exists($i,$backdata[4])?$backdata[4][$i]:array()//三级菜单项
        );
        return view('Evaluation',compact('front','back'));
    }

    public function GetFrontValueTable()
    {
        $mytime=new HelpController;
        $Time=$mytime->GetYearSemester(date('Y-m'));//将2016-8变为2016-2017-1的学年学期格式
        //通过GetCurrentTableName函数将2016-2017-1格式得到当前使用评价体系使用版本的后缀名
        $TableName=$mytime->GetCurrentTableName($Time['YearSemester']);

        $DataTable=array();
        $DataFirst=array();
        $DataSecond=array();
        $DataThird=array();

        $TableType=DB::table('front_contents'.$TableName)->where('fid','=',0)->get();

        for($iType=0;$iType<count($TableType);$iType++)
        {
            $DataTable[$iType]=$TableType[$iType];
            $DataFirst[$iType]=array();
            $DataSecond[$iType]=array();
            $DataThird[$iType]=array();
            $IndexFirst=DB::table('front_contents'.$TableName)->where('fid','=',$TableType[$iType]->id)->get();
            for($iF=0;$iF<count($IndexFirst);$iF++)
            {
                $DataFirst[$iType][$iF]=$IndexFirst[$iF];
                $DataSecond[$iType][$iF]=array();
                $DataThird[$iType][$iF]=array();
                $IndexSecond=DB::table('front_contents'.$TableName)->where('fid','=',$IndexFirst[$iF]->id)->get();
                for($iS=0;$iS<count($IndexSecond);$iS++)
                {
                    $DataSecond[$iType][$iF][$iS]=$IndexSecond[$iS];
                    $DataThird[$iType][$iF][$iS]=array();
                    $IndexThird=DB::table('front_contents'.$TableName)->where('fid','=',$IndexSecond[$iS]->id)->get();
                    for($iT=0;$iT<count($IndexThird);$iT++)
                        $DataThird[$iType][$iF][$iS][$iT]=$IndexThird[$iT];
                }
            }
        }
        $data = Array(
            '1'=>$DataTable,
            '2'=>$DataFirst,
            '3'=>$DataSecond,
            '4'=>$DataThird,
        );
        return $data;
    }
    public function GetBackValueTable()
    {
        $mytime=new HelpController;
        $Time=$mytime->GetYearSemester(date('Y-m'));//将2016-8变为2016-2017-1的学年学期格式
        //通过GetCurrentTableName函数将2016-2017-1格式  得到  当前使用评价体系使用版本的后缀名
        $TableName=$mytime->GetCurrentTableName($Time['YearSemester']);

        $DataTable=array();
        $DataFirst=array();
        $DataSecond=array();
        $DataThird=array();

        $TableType=DB::table('back_contents'.$TableName)->where('fid','=',0)->get();
        for($iType=0;$iType<count($TableType);$iType++)
        {
            $DataTable[$iType]=$TableType[$iType];
            $DataFirst[$iType]=array();
            $DataSecond[$iType]=array();
            $DataThird[$iType]=array();
            $IndexFirst=DB::table('back_contents'.$TableName)->where('fid','=',$TableType[$iType]->id)->get();
            for($iF=0;$iF<count($IndexFirst);$iF++)
            {
                $DataFirst[$iType][$iF]=$IndexFirst[$iF];
                $DataSecond[$iType][$iF]=array();
                $DataThird[$iType][$iF]=array();
                $IndexSecond=DB::table('back_contents'.$TableName)->where('fid','=',$IndexFirst[$iF]->id)->get();
                for($iS=0;$iS<count($IndexSecond);$iS++)
                {
                    $DataSecond[$iType][$iF][$iS]=$IndexSecond[$iS];
                    $DataThird[$iType][$iF][$iS]=array();
                    $IndexThird=DB::table('back_contents'.$TableName)->where('fid','=',$IndexSecond[$iS]->id)->get();
                    for($iT=0;$iT<count($IndexThird);$iT++)
                        $DataThird[$iType][$iF][$iS][$iT]=$IndexThird[$iT];
                }
            }
        }
        $data = Array(
            '1'=>$DataTable,
            '2'=>$DataFirst,
            '3'=>$DataSecond,
            '4'=>$DataThird
        );
        return $data;
    }

    //校级+大组长
    public function UnEvaluated(Request $request)//所有督导未完成的必听任务
    {
        $ids = array('"第一组"', '"第二组"', '"第三组"', '"第四组"');
        $ids_ordered = implode(',', $ids);

        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $year = $year1."-".$year2;
        $DataArr = DB::table("lessons")
            ->where('lesson_level','=','关注课程')
            ->where('lesson_year','=',$year)
            ->where('lesson_semester','=',$semester[0])
            ->where('lesson_state','=','未完成')
            ->orderByRaw(DB::raw("FIELD(assign_group, $ids_ordered)"))
            ->get();

        for ($i=0;$i<count($DataArr);$i++)
        {
            switch ($DataArr[$i]->lesson_time){
                case '0102':$DataArr[$i]->lesson_time = '上午1-2节';break;
                case '0304':$DataArr[$i]->lesson_time = '上午3-4节';break;
                case '0506':$DataArr[$i]->lesson_time = '下午1-2节';break;
                case '0708':$DataArr[$i]->lesson_time = '下午3-4节';break;
                case '0910':$DataArr[$i]->lesson_time = '晚上1-2节';break;
                case '01020304':$DataArr[$i]->lesson_time = '上午4节';break;
                case '05060708':$DataArr[$i]->lesson_time = '下午4节';break;
                case '091011':$DataArr[$i]->lesson_time = '晚上1-3节';break;
            }
        }
        return json_encode($DataArr);
    }
    /*
     * 对于全校的已完成必听课程简介：
     * 1、先从课程表中选出所有的必听课程
     * 2、依次遍历每个必听课程属于哪个评价表
     * 3、上课班级的顺序有所不同，注意在评价表和课程表中班级要做一个核对
     * */
    public function Evaluated(Request $request)//已完成的必听任务简介
    {
        $mytime = new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $year = $year1."-".$year2;

//        $year='2016-2017';
//        $semester=array(0=>'1');

        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        $LessonList = $this->GetFinishedNecessaryLesson($year,$semester[0]);//本学年本学期的已完成的关注课程列表
        //学年学期 得到 该学年学期所属的时间段
//        2016-2017-1 => 2016-09 ~ 2017-03
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);

        $data = array();
        for ($k=0;$k<count($LessonList);$k++)
        {
            $table = $mytime->GetCurrentTableName1($LessonList[$k][1],$LessonList[$k][2],$TableFlag);

            //确定属于那张表
            //table[0]表的正面  table[1]表的背面				'.$table[0].'.`授课总体评价`,

            $DataArr = DB::select('SELECT distinct
                '.$table[0].'.valueID,
                '.$table[0].'.课程名称,
                '.$table[0].'.任课教师,
                '.$table[0].'.课程属性,
				'.$table[0].'.上课班级,
		    	'.$table[0].'.督导id,
				'.$table[0].'.`督导姓名`,
				'.$table[0].'.`听课时间`,
				'.$table[0].'.`听课节次`,
				'.$table[0].'.`填表时间`,
				'.$table[0].'.`听课节次`,
				T2.assign_group
            FROM '.$table[0].'
             LEFT JOIN(select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table[0].'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table[0].'.任课教师
            and T2.lesson_class= '.$table[0].'.`上课班级`
            WHERE
            '.$table[0].'.课程名称 = "'.$LessonList[$k][1].'"
            and '.$table[0].'.任课教师 = "'.$LessonList[$k][2].'"
            and '.$table[0].'.`评价状态` = "已完成"
            and '.$table[0].'.`听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"
            order by FIELD(assign_group ,"第一组", "第二组", "第三组", "第四组") desc ');
            for ($l=0;$l<count($DataArr);$l++)
                array_push($data,$DataArr[$l]);

        }
        return json_encode($data);
    }
    public function Saved(Request $request)//待提交的必听任务简介
    {
        $mytime = new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $year = $year1."-".$year2;

//        $year='2016-2017';
//        $semester=array(0=>'1');

        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        $LessonList = $this->GetSavedNecessaryLesson($year,$semester[0]);//本学年本学期的待提交的关注课程列表
        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);

        $data = array();
        for ($k=0;$k<count($LessonList);$k++)
        {
            $table = $mytime->GetCurrentTableName1($LessonList[$k][1],$LessonList[$k][2],$TableFlag);
//            dd($table);

            //确定属于那张表
            //table[0]表的正面  table[1]表的背面
            $DataArr = DB::select('SELECT distinct '.$table[0].'.课程名称, '.$table[0].'.任课教师, '.$table[0].'.课程属性,
				'.$table[0].'.上课班级,
				'.$table[0].'.督导id,
				'.$table[0].'.`督导姓名`,
				'.$table[0].'.`听课时间`,
				'.$table[0].'.`填表时间`,
				'.$table[0].'.`听课节次`,
				T2.assign_group
            FROM '.$table[0].'
            LEFT JOIN(select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table[0].'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table[0].'.任课教师
            and T2.lesson_class= '.$table[0].'.`上课班级`
            WHERE
                '.$table[0].'.课程名称 = "'.$LessonList[$k][1].'" and '.$table[0].'.任课教师 = "'.$LessonList[$k][2].'"
            and '.$table[0].'.`评价状态` like "待提交%"
            and 	'.$table[0].'.`听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"
             order by FIELD(assign_group ,"第一组", "第二组", "第三组", "第四组") desc');
            for ($l=0;$l<count($DataArr);$l++)
                array_push($data,$DataArr[$l]);

        }
//        file_put_contents("data/EvaluatedNecessaryTask.json",json_encode($data));
        return json_encode($data);
    }

    protected function GetFinishedNecessaryLesson($year,$semester)//获取当前已经完成的必听任务列表
    {
        $DataArr = DB::table("lessons")->select('lesson_name','lesson_teacher_name')
            ->where('lesson_level','=','关注课程')
            ->where('lesson_year','=',$year)
            ->where('lesson_semester','=',$semester)
            ->where('lesson_state','=','已完成')->distinct()
            ->get();

        $data = [];
        for ($i=0;$i<count($DataArr);$i++)
        {
            $data[$i] =[
                '1'=> $DataArr[$i]->lesson_name,
                '2'=> $DataArr[$i]->lesson_teacher_name
            ];
        }
        return $data;
    }
    protected function GetSavedNecessaryLesson($year,$semester)    //获取当前已经保存的必听任务列表

    {
        $DataArr = DB::table("lessons")->select('lesson_name','lesson_teacher_name')
            ->where('lesson_level','=','关注课程')
            ->where('lesson_year','=',$year)
            ->where('lesson_semester','=',$semester)
            ->where('lesson_state','like','待提交%')->distinct()
            ->get();

        $data = [];
        for ($i=0;$i<count($DataArr);$i++)
        {
            $data[$i] =[
                '1'=> $DataArr[$i]->lesson_name,
                '2'=> $DataArr[$i]->lesson_teacher_name
            ];
        }
        return $data;
    }


    //小组长
    public function GroupUnEvaluated(Request $request)//小组内未完成的必听任务
    {
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $group = $request->group;
        $year = $year1."-".$year2;
        $DataArr = DB::table("lessons")
            ->where('lesson_level','=','关注课程')
            ->where('lesson_year','=',$year)
            ->where('lesson_semester','=',$semester[0])
            ->where('assign_group','=',$group)
            ->where('lesson_state','=','未完成')
            ->get();
        return json_encode($DataArr);
    }
    public function GroupEvaluated(Request $request)//小组内已完成的必听任务简介
    {
        $mytime = new HelpController;

        $group = $request->group;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $year = $year1."-".$year2;


//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $group = 'B组';

        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        $LessonList = $this->GetGroupFinishedNecessaryLesson($year,$semester[0],$group);//本学年本学期的已完成的关注课程列表

        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);

        $data = array();
        for ($k=0;$k<count($LessonList);$k++)
        {
            $table = $mytime->GetCurrentTableName1($LessonList[$k][1],$LessonList[$k][2],$TableFlag);
//            dd($table);

            //确定属于那张表
            //table[0]表的正面  table[1]表的背面
            $DataArr = DB::select('SELECT distinct '.$table[0].'.课程名称, '.$table[0].'.任课教师, '.$table[0].'.课程属性,
				'.$table[0].'.上课班级,
				'.$table[0].'.督导id,
				'.$table[0].'.`督导姓名`,
				'.$table[0].'.`听课时间`,
				'.$table[0].'.`填表时间`,
				'.$table[0].'.`听课节次`,
				T2.assign_group
            FROM '.$table[0].'
             LEFT JOIN(select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table[0].'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table[0].'.任课教师
            and T2.lesson_class= '.$table[0].'.`上课班级`
            WHERE
                '.$table[0].'.课程名称 = "'.$LessonList[$k][1].'" and '.$table[0].'.任课教师 = "'.$LessonList[$k][2].'"
            and 	'.$table[0].'.`听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
            for ($l=0;$l<count($DataArr);$l++)
                array_push($data,$DataArr[$l]);

        }
        return json_encode($data);
    }
    public function GroupSaved(Request $request)//小组内已完成的必听任务简介
    {
        $mytime = new HelpController;

        $group = $request->group;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $year = $year1."-".$year2;


//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $group = 'B组';

        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        $LessonList = $this->GetGroupSavedNecessaryLesson($year,$semester[0],$group);//本学年本学期的已完成的关注课程列表

        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);
        $data = array();
        for ($k=0;$k<count($LessonList);$k++)
        {

            $table = $mytime->GetCurrentTableName1($LessonList[$k][1],$LessonList[$k][2],$TableFlag);
//            dd($table);

            //确定属于那张表
            //table[0]表的正面  table[1]表的背面
            $DataArr = DB::select('SELECT distinct '.$table[0].'.课程名称, '.$table[0].'.任课教师, '.$table[0].'.课程属性,
				'.$table[0].'.上课班级,
				'.$table[0].'.督导id,
				'.$table[0].'.`督导姓名`,
				'.$table[0].'.`听课时间`,
				'.$table[0].'.`填表时间`,
				'.$table[0].'.`听课节次`,
            T2.assign_group
            FROM '.$table[0].'
             LEFT JOIN(select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table[0].'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table[0].'.任课教师
            and T2.lesson_class= '.$table[0].'.`上课班级`
            WHERE
                '.$table[0].'.课程名称 = "'.$LessonList[$k][1].'"
                and '.$table[0].'.任课教师 = "'.$LessonList[$k][2].'"
                and '.$table[0].'.`评价状态` like "待提交%"
                and '.$table[0].'.`听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
            for ($l=0;$l<count($DataArr);$l++)
                array_push($data,$DataArr[$l]);

        }
        return json_encode($data);
    }

//获取当前已经完成的小组必听任务列表
    protected function GetGroupFinishedNecessaryLesson($year,$semester,$group)
    {
        $DataArr = DB::table("lessons")
            ->select('lesson_name','lesson_teacher_name')
            ->where('lesson_level','=','关注课程')
            ->where('lesson_year','=',$year)
            ->where('assign_group','=',$group)
            ->where('lesson_semester','=',$semester)
            ->where('lesson_state','=','已完成')->distinct()
            ->get();

        $data = [];
        for ($i=0;$i<count($DataArr);$i++)
        {
            $data[$i] =[
                '1'=> $DataArr[$i]->lesson_name,
                '2'=> $DataArr[$i]->lesson_teacher_name
            ];
        }
        return $data;
    }
    protected function GetGroupSavedNecessaryLesson($year,$semester,$group)    //获取当前已经保存的必听任务列表
    {
        $DataArr = DB::table("lessons")->select('lesson_name','lesson_teacher_name')
            ->where('assign_group','=',$group)
            ->where('lesson_level','=','关注课程')
            ->where('lesson_year','=',$year)
            ->where('lesson_semester','=',$semester)
            ->where('lesson_state','like','待提交%')->distinct()
            ->get();

        $data = [];
        for ($i=0;$i<count($DataArr);$i++)
        {
            $data[$i] =[
                '1'=> $DataArr[$i]->lesson_name,
                '2'=> $DataArr[$i]->lesson_teacher_name
            ];
        }
        return $data;
    }

    //院级
    public function UnitUnEvaluated(Request $request)//小组内所有督导未完成的必听任务
    {
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $unit = $request->unit;

        $year = $year1."-".$year2;
        $DataArr = DB::table("lessons")
            ->where('lesson_level','=','关注课程')
            ->where('lesson_year','=',$year)
            ->where('lesson_semester','=',$semester[0])
            ->where('lesson_teacher_unit','=',$unit)
            ->where('lesson_state','=','未完成')
            ->get();
        return json_encode($DataArr);
    }
    public function UnitEvaluated(Request $request)//小组内已完成的必听任务简介
    {
        $mytime = new HelpController;
        $unit = $request->unit;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $year = $year1."-".$year2;


//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $unit = '信息学院';
        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        $LessonList = $this->GetUnitFinishedNecessaryLesson($year,$semester[0],$unit);//本学年本学期的已完成的关注课程列表
        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);

        $data = array();
        for ($k=0;$k<count($LessonList);$k++)
        {
            $table = $mytime->GetCurrentTableName1($LessonList[$k][1],$LessonList[$k][2],$TableFlag);
//            dd($table);

            //确定属于那张表
            //table[0]表的正面  table[1]表的背面
            $DataArr = DB::select('SELECT distinct '.$table[0].'.课程名称, '.$table[0].'.任课教师, '.$table[0].'.课程属性,
				'.$table[0].'.上课班级,
				'.$table[0].'.督导id,
				'.$table[0].'.`督导姓名`,
				'.$table[0].'.`听课时间`,
				'.$table[0].'.`填表时间`,
				'.$table[0].'.`听课节次`,
            T2.assign_group
            FROM '.$table[0].'
             LEFT JOIN(select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table[0].'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table[0].'.任课教师
            and T2.lesson_class= '.$table[0].'.`上课班级`
            WHERE
                '.$table[0].'.课程名称 = "'.$LessonList[$k][1].'" and '.$table[0].'.任课教师 = "'.$LessonList[$k][2].'"
            and 	'.$table[0].'.`听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
            for ($l=0;$l<count($DataArr);$l++)
                array_push($data,$DataArr[$l]);

        }
        return json_encode($data);
    }
    public function UnitSaved(Request $request)//小组内已完成的必听任务简介
    {
        $mytime = new HelpController;

        $unit = $request->unit;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        $year = $year1."-".$year2;


//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $group = 'B组';

        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        $LessonList = $this->GetUnitSavedNecessaryLesson($year,$semester[0],$unit);//本学年本学期的已完成的关注课程列表

        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);

        $data = array();
        for ($k=0;$k<count($LessonList);$k++)
        {
            $table = $mytime->GetCurrentTableName1($LessonList[$k][1],$LessonList[$k][2],$TableFlag);
//            dd($table);

            //确定属于那张表
            //table[0]表的正面  table[1]表的背面
            $DataArr = DB::select('SELECT distinct '.$table[0].'.课程名称, '.$table[0].'.任课教师, '.$table[0].'.课程属性,
				'.$table[0].'.上课班级,
				'.$table[0].'.督导id,
				'.$table[0].'.`督导姓名`,
				'.$table[0].'.`听课时间`,
				'.$table[0].'.`填表时间`,
				'.$table[0].'.`听课节次`,
				T2.assign_group
            FROM '.$table[0].'
             LEFT JOIN(select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table[0].'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table[0].'.任课教师
            and T2.lesson_class= '.$table[0].'.`上课班级`
            WHERE
                '.$table[0].'.课程名称 = "'.$LessonList[$k][1].'" and '.$table[0].'.任课教师 = "'.$LessonList[$k][2].'"
                  and '.$table[0].'.`评价状态` like "待提交%"
            and 	'.$table[0].'.`听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
            for ($l=0;$l<count($DataArr);$l++)
                array_push($data,$DataArr[$l]);

        }
        return json_encode($data);
    }

    //获取当前已经完成的院级必听任务列表
    protected function GetUnitFinishedNecessaryLesson($year,$semester,$unit)
    {
        $DataArr = DB::table("lessons")->select('lesson_name','lesson_teacher_name')
            ->where('lesson_level','=','关注课程')
            ->where('lesson_teacher_unit','=',$unit)
            ->where('lesson_year','=',$year)
            ->where('lesson_semester','=',$semester)
            ->where('lesson_state','=','已完成')->distinct()
            ->get();

        $data = [];
        for ($i=0;$i<count($DataArr);$i++)
        {
            $data[$i] =[
                '1'=> $DataArr[$i]->lesson_name,
                '2'=> $DataArr[$i]->lesson_teacher_name
            ];
        }
        return $data;
    }
    //获取当前已经保存的院级必听任务列表
    protected function GetUnitSavedNecessaryLesson($year,$semester,$unit)
    {
        $DataArr = DB::table("lessons")->select('lesson_name','lesson_teacher_name')
            ->where('lesson_teacher_unit','=',$unit)
            ->where('lesson_level','=','关注课程')
            ->where('lesson_year','=',$year)
            ->where('lesson_semester','=',$semester)
            ->where('lesson_state','like','待提交%')->distinct()
            ->get();

        $data = [];
        for ($i=0;$i<count($DataArr);$i++)
        {
            $data[$i] =[
                '1'=> $DataArr[$i]->lesson_name,
                '2'=> $DataArr[$i]->lesson_teacher_name
            ];
        }
        return $data;
    }





    /**
     * 管理员/普通督导 在web端填写评价信息表
     * 三种表中的valueID在各自的类型中为唯一标识，方便于修改待提交内容
     *参数说明：
     * 1、headdata：评价表头部内容
     * 2、frontdata：评价表正面内容
     * 3、backdata1：评价表背面单选/复选框内容
     * 4、backdata2：评价表背面文字输入内容
     *
     * 理论表和实践表有区别：
     * 理论表拥有课程时间（上课周次，课程节次，上课地点）
     * 实践表无课程时间和上课地址
     */
    public function CreateTheoryEvalutionFrontTable(Request $request)
    {
        $Version=new HelpController;
        $Current='2017-2018-1';
        $TableName=$Version->GetCurrentTableName($Current);

        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."front_theory_evalution$TableName"."'"))==1)
            mysqli_query($con,"drop table front_theory_evalution$TableName;");
        $str='valueID int primary key not null auto_increment,
        任课教师 varchar(255)';
//        $str=$str.'任课教师 varchar(255),';
//        $str=$str.'上课班级 varchar(255),';
//        $str=$str.'上课地点 varchar(255),';
//        $str=$str.'上课班级 varchar(255),';
//        $str=$str.'填表时间 varchar(255),';
//        $str=$str.'听课节次 varchar(255),';
//        $str=$str.'课程名称 varchar(255),';
//        $str=$str.'督导姓名 varchar(255),';
//        $str=$str.'督导id varchar(255),';
//        $str=$str.'课程属性 varchar(255),';
//        $str=$str.'评价状态 varchar(255)';
        $result=DB::table("front_theory_contents$TableName")->where('text','=','理论课评价表')->get();
        mysqli_query($con,"create table front_theory_evalution".$TableName." ($str);");
    }


    public function TheoryFrontEvaluationTableToDB(Request $request)
    {
        $Version=new HelpController;
        $Current='2017-2018-1';
        $TableName=$Version->GetCurrentTableName($Current);

        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
//        $TableName='_2017_09';
        //理论表正面
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."front_theory_contents$TableName"."'"))==1)
            mysqli_query($con, "drop table front_theory_contents$TableName;");
        $str='id int primary key not null auto_increment,fid int,level int,text varchar(255),cssstyle int';
        mysqli_query($con,"create table front_theory_contents".$TableName." ($str);");

        DB::table("front_theory_contents$TableName")->insert(
            ['fid' => 0, 'text' => '理论课评价表', 'cssstyle' => 1, 'level' => 0]
        );
        $frontdata = $request->frontdata;
        for($i=0;$i<count($frontdata);$i++)
        {
            DB::table("front_theory_contents$TableName")->insert(
                ['fid' => $frontdata[$i]['fid'], 'text' => trim($frontdata[$i]['text']), 'cssstyle' => $frontdata[$i]['cssstyle'], 'level' => $frontdata[$i]['level']]
            );
        }
        $queue=array();
        $head=-1;$tail=0;
        array_push($queue,DB::table("front_contents$TableName")->where('text','=','理论课评价表')->get()[0]->id);
        while($head<$tail)
        {
            $head++;
            $x=$queue[$head];
            $result=DB::table("front_contents$TableName")->where('fid','=',$x)->get();
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,$result[$i]->id);
                $tail++;
            }
        }
        for($i=0;$i<count($queue);$i++)
        {
            $id=$queue[$i];
            DB::table("front_contents$TableName")->where('id','=',$id)->delete();
        }
        $queue=array();
        $head=-1;$tail=0;
        $result=DB::table("front_theory_contents$TableName")->where('text','=','理论课评价表')->get();
        array_push($queue,
            [
                'result'=>$result[0],
                'fid'=>0
            ]
        );
        while($head<$tail)
        {
            $head++;
            $result=$queue[$head]['result'];
            $fid=$queue[$head]['fid'];
            $nowid=DB::table("front_contents$TableName")->insertGetId(
                [
                    'fid' => $fid,
                    'text' => $result->text,
                    'cssstyle' => $result->cssstyle,
                    'level' => $result->level
                ]
            );
            $id=$result->id;
            $result=DB::table("front_theory_contents$TableName")->where('fid','=',$id)->get();
            $fid=$nowid;
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,
                    [
                        'result'=>$result[$i],
                        'fid'=>$fid
                    ]
                );
                $tail++;
            }
        }
        return;
    }
    public function TheoryBackEvaluationTableToDB(Request $request)
    {
        $Version=new HelpController;
        $Current='2017-2018-1';
        $TableName=$Version->GetCurrentTableName($Current);

        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
//        $TableName='_2017_09';
        //理论表背面
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."back_theory_contents$TableName"."'"))==1)
        {
            mysqli_query($con, "drop table back_theory_contents$TableName;");
        }
        $str='id int primary key not null auto_increment,fid int,level int,text varchar(255),cssstyle int';
        mysqli_query($con,"create table back_theory_contents".$TableName." ($str);");
        DB::table("back_theory_contents$TableName")->insert(
            ['fid' => 0, 'text' => '理论课评价表', 'cssstyle' => 1, 'level' => 0]
        );
        $backdata = $request->backdata;
        for($i=0;$i<count($backdata);$i++)
        {
            DB::table("back_theory_contents$TableName")->insert(
                ['fid' => $backdata[$i]['fid'], 'text' => trim($backdata[$i]['text']), 'cssstyle' => $backdata[$i]['cssstyle'], 'level' => $backdata[$i]['level']]
            );
        }
        $queue=array();
        $head=-1;$tail=0;
        array_push($queue,DB::table("back_contents$TableName")->where('text','=','理论课评价表')->get()[0]->id);
        while($head<$tail)
        {
            $head++;
            $x=$queue[$head];
            $result=DB::table("back_contents$TableName")->where('fid','=',$x)->get();
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,$result[$i]->id);
                $tail++;
            }
        }
        for($i=0;$i<count($queue);$i++)
        {
            $id=$queue[$i];
            DB::table("back_contents$TableName")->where('id','=',$id)->delete();
        }
        $queue=array();
        $head=-1;$tail=0;
        $result=DB::table("back_theory_contents$TableName")->where('text','=','理论课评价表')->get();
        array_push($queue,
            [
                'result'=>$result[0],
                'fid'=>0
            ]
        );
        while($head<$tail)
        {
            $head++;
            $result=$queue[$head]['result'];
            $fid=$queue[$head]['fid'];
            $nowid=DB::table("back_contents$TableName")->insertGetId(
                [
                    'fid' => $fid,
                    'text' => $result->text,
                    'cssstyle' => $result->cssstyle,
                    'level' => $result->level
                ]
            );
            $id=$result->id;
            $result=DB::table("back_theory_contents$TableName")->where('fid','=',$id)->get();
            $fid=$nowid;
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,
                    [
                        'result'=>$result[$i],
                        'fid'=>$fid
                    ]
                );
                $tail++;
            }
        }
        return;
    }

    public function PracticeFrontEvaluationTableToDB(Request $request)
    {
        $Version=new HelpController;
        $Current='2017-2018-1';
        $TableName=$Version->GetCurrentTableName($Current);
        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
//        $TableName='_2017_09';
        //实践表正面
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."front_practice_contents$TableName"."'"))==1)
            mysqli_query($con, "drop table front_practice_contents$TableName;");
        $str='id int primary key not null auto_increment,fid int,level int,text varchar(255),cssstyle int';
        mysqli_query($con,"create table front_practice_contents".$TableName." ($str);");

        DB::table("front_practice_contents$TableName")->insert(
            ['fid' => 0, 'text' => '实践课评价表', 'cssstyle' => 1, 'level' => 0]
        );
        $frontdata = $request->frontdata;
        for($i=0;$i<count($frontdata);$i++)
        {
            DB::table("front_practice_contents$TableName")->insert(
                ['fid' => $frontdata[$i]['fid'], 'text' => trim($frontdata[$i]['text']), 'cssstyle' => $frontdata[$i]['cssstyle'], 'level' => $frontdata[$i]['level']]
            );
        }
        $queue=array();
        $head=-1;$tail=0;
        array_push($queue,DB::table("front_contents$TableName")->where('text','=','实践课评价表')->get()[0]->id);
        while($head<$tail)
        {
            $head++;
            $x=$queue[$head];
            $result=DB::table("front_contents$TableName")->where('fid','=',$x)->get();
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,$result[$i]->id);
                $tail++;
            }
        }
        for($i=0;$i<count($queue);$i++)
        {
            $id=$queue[$i];
            DB::table("front_contents$TableName")->where('id','=',$id)->delete();
        }
        $queue=array();
        $head=-1;$tail=0;
        $result=DB::table("front_practice_contents$TableName")->where('text','=','实践课评价表')->get();
        array_push($queue,
            [
                'result'=>$result[0],
                'fid'=>0
            ]
        );
        while($head<$tail)
        {
            $head++;
            $result=$queue[$head]['result'];
            $fid=$queue[$head]['fid'];
            $nowid=DB::table("front_contents$TableName")->insertGetId(
                [
                    'fid' => $fid,
                    'text' => $result->text,
                    'cssstyle' => $result->cssstyle,
                    'level' => $result->level
                ]
            );
            $id=$result->id;
            $result=DB::table("front_practice_contents$TableName")->where('fid','=',$id)->get();
            $fid=$nowid;
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,
                    [
                        'result'=>$result[$i],
                        'fid'=>$fid
                    ]
                );
                $tail++;
            }
        }
        return;
    }
    public function PracticeBackEvaluationTableToDB(Request $request)
    {
        $Version=new HelpController;
        $Current='2017-2018-1';
        $TableName=$Version->GetCurrentTableName($Current);

        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
//        $TableName='_2017_09';
        //理论表背面
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."back_practice_contents$TableName"."'"))==1)
        {
            mysqli_query($con, "drop table back_practice_contents$TableName;");
        }
        $str='id int primary key not null auto_increment,fid int,level int,text varchar(255),cssstyle int';
        mysqli_query($con,"create table back_practice_contents".$TableName." ($str);");
        DB::table("back_practice_contents$TableName")->insert(
            ['fid' => 0, 'text' => '实践课评价表', 'cssstyle' => 1, 'level' => 0]
        );
        $backdata = $request->backdata;
        for($i=0;$i<count($backdata);$i++)
        {
            DB::table("back_practice_contents$TableName")->insert(
                ['fid' => $backdata[$i]['fid'], 'text' => trim($backdata[$i]['text']), 'cssstyle' => $backdata[$i]['cssstyle'], 'level' => $backdata[$i]['level']]
            );
        }
        $queue=array();
        $head=-1;$tail=0;
        array_push($queue,DB::table("back_contents$TableName")->where('text','=','实践课评价表')->get()[0]->id);
        while($head<$tail)
        {
            $head++;
            $x=$queue[$head];
            $result=DB::table("back_contents$TableName")->where('fid','=',$x)->get();
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,$result[$i]->id);
                $tail++;
            }
        }
        for($i=0;$i<count($queue);$i++)
        {
            $id=$queue[$i];
            DB::table("back_contents$TableName")->where('id','=',$id)->delete();
        }
        $queue=array();
        $head=-1;$tail=0;
        $result=DB::table("back_practice_contents$TableName")->where('text','=','实践课评价表')->get();
        array_push($queue,
            [
                'result'=>$result[0],
                'fid'=>0
            ]
        );
        while($head<$tail)
        {
            $head++;
            $result=$queue[$head]['result'];
            $fid=$queue[$head]['fid'];
            $nowid=DB::table("back_contents$TableName")->insertGetId(
                [
                    'fid' => $fid,
                    'text' => $result->text,
                    'cssstyle' => $result->cssstyle,
                    'level' => $result->level
                ]
            );
            $id=$result->id;
            $result=DB::table("back_practice_contents$TableName")->where('fid','=',$id)->get();
            $fid=$nowid;
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,
                    [
                        'result'=>$result[$i],
                        'fid'=>$fid
                    ]
                );
                $tail++;
            }
        }
        return;
    }

    public function PhysicalFrontEvaluationTableToDB(Request $request)
    {
        $Version=new HelpController;
        $Current='2017-2018-1';
        $TableName=$Version->GetCurrentTableName($Current);

        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
//        $TableName='_2017_09';
        //理论表正面
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."front_physical_contents$TableName"."'"))==1)
            mysqli_query($con, "drop table front_physical_contents$TableName;");
        $str='id int primary key not null auto_increment,fid int,level int,text varchar(255),cssstyle int';
        mysqli_query($con,"create table front_physical_contents".$TableName." ($str);");

        DB::table("front_physical_contents$TableName")->insert(
            ['fid' => 0, 'text' => '体育课评价表', 'cssstyle' => 1, 'level' => 0]
        );
        $frontdata = $request->frontdata;
        for($i=0;$i<count($frontdata);$i++)
        {
            DB::table("front_physical_contents$TableName")->insert(
                ['fid' => $frontdata[$i]['fid'], 'text' => trim($frontdata[$i]['text']), 'cssstyle' => $frontdata[$i]['cssstyle'], 'level' => $frontdata[$i]['level']]
            );
        }
        $queue=array();
        $head=-1;$tail=0;
        array_push($queue,DB::table("front_contents$TableName")->where('text','=','体育课评价表')->get()[0]->id);
        while($head<$tail)
        {
            $head++;
            $x=$queue[$head];
            $result=DB::table("front_contents$TableName")->where('fid','=',$x)->get();
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,$result[$i]->id);
                $tail++;
            }
        }
        for($i=0;$i<count($queue);$i++)
        {
            $id=$queue[$i];
            DB::table("front_contents$TableName")->where('id','=',$id)->delete();
        }
        $queue=array();
        $head=-1;$tail=0;
        $result=DB::table("front_physical_contents$TableName")->where('text','=','体育课评价表')->get();
        array_push($queue,
            [
                'result'=>$result[0],
                'fid'=>0
            ]
        );
        while($head<$tail)
        {
            $head++;
            $result=$queue[$head]['result'];
            $fid=$queue[$head]['fid'];
            $nowid=DB::table("front_contents$TableName")->insertGetId(
                [
                    'fid' => $fid,
                    'text' => $result->text,
                    'cssstyle' => $result->cssstyle,
                    'level' => $result->level
                ]
            );
            $id=$result->id;
            $result=DB::table("front_physical_contents$TableName")->where('fid','=',$id)->get();
            $fid=$nowid;
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,
                    [
                        'result'=>$result[$i],
                        'fid'=>$fid
                    ]
                );
                $tail++;
            }
        }
        return;
    }
    public function PhysicalBackEvaluationTableToDB(Request $request)
    {
        $Version=new HelpController;
        $Current='2017-2018-1';
        $TableName=$Version->GetCurrentTableName($Current);

        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
//        $TableName='_2017_09';
        //理论表背面
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."back_physical_contents$TableName"."'"))==1)
        {
            mysqli_query($con, "drop table back_physical_contents$TableName;");
        }
        $str='id int primary key not null auto_increment,fid int,level int,text varchar(255),cssstyle int';
        mysqli_query($con,"create table back_physical_contents".$TableName." ($str);");
        DB::table("back_physical_contents$TableName")->insert(
            ['fid' => 0, 'text' => '体育课评价表', 'cssstyle' => 1, 'level' => 0]
        );
        $backdata = $request->backdata;
        for($i=0;$i<count($backdata);$i++)
        {
            DB::table("back_physical_contents$TableName")->insert(
                ['fid' => $backdata[$i]['fid'], 'text' => trim($backdata[$i]['text']), 'cssstyle' => $backdata[$i]['cssstyle'], 'level' => $backdata[$i]['level']]
            );
        }
        $queue=array();
        $head=-1;$tail=0;
        array_push($queue,DB::table("back_contents$TableName")->where('text','=','体育课评价表')->get()[0]->id);
        while($head<$tail)
        {
            $head++;
            $x=$queue[$head];
            $result=DB::table("back_contents$TableName")->where('fid','=',$x)->get();
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,$result[$i]->id);
                $tail++;
            }
        }
        for($i=0;$i<count($queue);$i++)
        {
            $id=$queue[$i];
            DB::table("back_contents$TableName")->where('id','=',$id)->delete();
        }
        $queue=array();
        $head=-1;$tail=0;
        $result=DB::table("back_physical_contents$TableName")->where('text','=','体育课评价表')->get();
        array_push($queue,
            [
                'result'=>$result[0],
                'fid'=>0
            ]
        );
        while($head<$tail)
        {
            $head++;
            $result=$queue[$head]['result'];
            $fid=$queue[$head]['fid'];
            $nowid=DB::table("back_contents$TableName")->insertGetId(
                [
                    'fid' => $fid,
                    'text' => $result->text,
                    'cssstyle' => $result->cssstyle,
                    'level' => $result->level
                ]
            );
            $id=$result->id;
            $result=DB::table("back_physical_contents$TableName")->where('fid','=',$id)->get();
            $fid=$nowid;
            for($i=0;$i<count($result);$i++)
            {
                array_push($queue,
                    [
                        'result'=>$result[$i],
                        'fid'=>$fid
                    ]
                );
                $tail++;
            }
        }
        return;
    }


    public function DBTheoryEvaluationTable(Request $request)
    {
        $YearSemester=new HelpController;
        $valueID = $request->valueID;
        $headdata = $request->headdata;
        $frontdata = $request->frontdata;
        $backdata = $request->backdata;
        $lesson_state = $request->LessonState;
        /*
         * $headdata：
         * 0：章节目录 1：课程名称 2：任课教师 3：上课班级: 4：上课地点
         * 5：听课时间 6：督导姓名 7：课程属性 8：督导id 9：听课节次     ->value1,将听课节次拆开后的结果
         * 10:授课教师ID
         * */
        $version=$YearSemester->GetYearSemester($headdata[5]['value']);
        $TableNamePostfix=DB::table('evaluation_migration')
            ->select('Table_Name')
            ->where('Create_Year','=',$version['YearSemester'])
            ->get();

        $TableName1 = 'front_theory_evaluation'.$TableNamePostfix[0]->Table_Name;
        $TableName2 = 'back_theory_evaluation'.$TableNamePostfix[0]->Table_Name;

        $TableName3 = "front_practice_evaluation".$TableNamePostfix[0]->Table_Name;
        $TableName4 = "front_physical_evaluation".$TableNamePostfix[0]->Table_Name;
//
//        设置lessons 表中的课程状态字段
        if ($lesson_state=='待提交'|| $lesson_state=='待提交1')
        {
            //查询lesson表是否提交了已经完成的记录
            $flag = DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_state','=','已完成')
                ->count();

            if ($flag == 0)//表中没有已完成的该条记录或者该记录处于待提交状态
            {
                DB::table('lessons')
                    ->where('lesson_name','=',$headdata[1]['value'])
                    ->where('lesson_teacher_name','=',$headdata[2]['value'])
                    ->where('lesson_year','=',$version['Year'])
                    ->where('lesson_semester','=',$version['Semester'])
                    ->update([
                        'lesson_state'=>$lesson_state,
                        'lesson_type'=>$headdata[7]['value'],
                    ]);
            }
            //该课程为已评价，说明别的督导已经提交评价，则不修改lesson表
        }
        if ($lesson_state=='已完成')//相当于填完评价表直接提交
        {
            DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->update([
                    'lesson_state'=>'已完成',
                    'lesson_type'=>$headdata[7]['value']
                ]);
        }
        if ($valueID==NULL|| $valueID==0)//创建的新评价表，此时监测评价头部是否相同
        {
            //查询评价表中是否有该课程的记录：防止督导通过
//        1、添加新的评价表，来覆盖之前提交的
//        2、修改待提交的，覆盖之前已经提交的。
//        检测标准：1、同一老师，同一门课程，同一组班级，同一时间(听课日期+听课节次)，
            $SubmitF = null;
            $SubmitF3 = null;
            $SubmitF4 = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {

                $SubmitF = DB::table($TableName1)->select('评价状态')//检验本表(理论课评价表)中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                    ->get();
                if ($SubmitF!=null)
                {
                    if ($SubmitF[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        DB::table($TableName1)
                            ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                            ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                            ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                            ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                            ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                            ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                            ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                            ->update(
                                [$headdata[0]['key'] => $headdata[0]['value']],
                                [$headdata[4]['key'] => $headdata[4]['value']],
                                [$headdata[6]['key'] => $headdata[6]['value']],
                                [$headdata[7]['key'] => $headdata[7]['value']]
                            );
                        DB::table($TableName2)
                            ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                            ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                            ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                            ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                            ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                            ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                            ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                            ->update(
                                [$headdata[0]['key'] => $headdata[0]['value']],
                                [$headdata[4]['key'] => $headdata[4]['value']],
                                [$headdata[6]['key'] => $headdata[6]['value']],
                                [$headdata[7]['key'] => $headdata[7]['value']]
                            );
                        for ($i=0;$i<count($frontdata);$i++)
                        {
                            DB::table($TableName1)
                                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                                ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                                ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                                ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                                ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                                ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                                ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                                ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
                        }

                        for($i=0;$i<count($backdata);$i++)
                        {
//                Log::info("yesyes");
                            DB::table($TableName2)
                                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                                ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                                ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                                ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                                ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                                ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                                ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                                ->update([$backdata[$i]['key']=> $backdata[$i]['value']]);
                        }
                        return '已保存更新该评论表数据！';
//                        return '您已保存过该时间段内的评论表！';
                    }
                }

                $SubmitF3 = DB::table($TableName3)->select('评价状态')//检验（实践课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
//                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//数据库中未加第 节 时
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF3!=null)
                {
                    if ($SubmitF3[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在实践课评价表中提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF3[0]->评价状态 == '待提交'|| $SubmitF3[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在实践课评价表中保存过该时间段内的评论内容！';
                    }
                }
                $SubmitF4 = DB::table($TableName4)->select('评价状态')//检验（体育课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
//                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//数据库中未加第 节 时
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF4!=null)
                {
                    if ($SubmitF4[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF4[0]->评价状态 == '待提交'|| $SubmitF4[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表保存过该时间段内的评论内容！';
                    }
                }
            }

            $flag1 = DB::table($TableName1)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//督导id
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        $headdata[10]['key']=> $headdata[10]['value'],//授课教师ID
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );

            //内容插入
            //评价表正面内容插入
            for ($i=0;$i<count($frontdata);$i++)
            {
                DB::table($TableName1)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
//                        ->where($headdata[9]['key'],'=',$headdata[9]['value'])//听课节次未加第几节
                    ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
            }

            //评价表背面内容插入

            $flag2=DB::table($TableName2)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//课程属性
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        $headdata[10]['key']=> $headdata[10]['value'],//授课教师ID
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );

            for($i=0;$i<count($backdata);$i++)
            {
//                Log::info("yesyes");
                DB::table($TableName2)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
//                        ->where($headdata[9]['key'],'=',$headdata[9]['value'])//听课节次未加第几节
                    ->update([$backdata[$i]['key']=> $backdata[$i]['value']]);
            }



            //从theory评价表中取出三个评述
            $textarea = DB::select('SELECT text FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where text LIKE \'如果以上各方面不能准确表达您的意见%\'
                                     AND fid = ANY(SELECT id FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where fid = 1)');

            if ($lesson_state == '已完成')
                return '提交成功';
            else
                return '保存成功';
//            }
        }
        else{//想要修改评价表
            //说明该课程在该课程节次已经在理论评价表中被该老师评价过了，
            //此时valueID 不可能为 0 或者null
            //查询评价表中是否有该课程的记录：防止督导通过
//        1、更新之前保存的。
//        2、更新数据并提交。
//        检测标准：valueID，
            $SubmitF = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {
                $SubmitF = $tableF = DB::table($TableName1)
                    ->where('valueID','=',$valueID)
                    ->get();
                if ($SubmitF!=null)
                {
                    //说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    if ($SubmitF[0]->评价状态 == '已完成' )
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                            break;//此时就是该评价表本身修改
                    }
                }
            }
            DB::table($TableName1)
                ->where('valueID','=',$valueID)
                ->update(
                    [$headdata[0]['key'] => $headdata[0]['value']],
                    [$headdata[4]['key'] => $headdata[4]['value']],
                    [$headdata[6]['key'] => $headdata[6]['value']],
                    [$headdata[7]['key'] => $headdata[7]['value']]
                );
            DB::table($TableName1)
                ->where('valueID','=',$valueID)
                ->update(
                        ['评价状态' => $lesson_state]
                );
            DB::table($TableName2)
                ->where('valueID','=',$valueID)
                ->update(
                    ['评价状态' => $lesson_state]
                );
            DB::table($TableName2)
                ->where('valueID','=',$valueID)
                ->update(
                    [$headdata[0]['key'] => $headdata[0]['value']],
                    [$headdata[4]['key'] => $headdata[4]['value']],
                    [$headdata[6]['key'] => $headdata[6]['value']],
                    [$headdata[7]['key'] => $headdata[7]['value']]
                );
            for ($i=0;$i<count($frontdata);$i++)
            {
                DB::table($TableName1)
                    ->where('valueID','=',$valueID)
                    ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
            }
            for($i=0;$i<count($backdata);$i++)
            {
//                Log::info("yesyes");
                DB::table($TableName2)
                    ->where('valueID','=',$valueID)
                    ->update([$backdata[$i]['key']=> $backdata[$i]['value']]);
            }
            if ($lesson_state == '已完成')
                return '提交成功';
            else
                return '保存成功';
        }
    }

    public function DBPracticeEvaluationTable(Request $request)
    {
        $YearSemester=new HelpController;
        $valueID = $request->valueID;
        $headdata = $request->headdata;
        $frontdata = $request->frontdata;
        $backdata = $request->backdata;
        $lesson_state = $request->LessonState;
        /*
         * $headdata：
         * 0：章节目录 1：课程名称 2：任课教师 3：上课班级: 4：上课地点
         * 5：听课时间 6：督导姓名 7：课程属性 8：督导id 9：听课节次     ->value1,将听课节次拆开后的结果
         * 10: 授课教师ID
         * */
        $version=$YearSemester->GetYearSemester($headdata[5]['value']);
        log::info( $version);
        $TableNamePostfix=DB::table('evaluation_migration')
            ->select('Table_Name')
            ->where('Create_Year','=',$version['YearSemester'])
            ->get();

        $TableName1 = 'front_practice_evaluation'.$TableNamePostfix[0]->Table_Name;
        $TableName2 = 'back_practice_evaluation'.$TableNamePostfix[0]->Table_Name;

        $TableName3 = "front_theory_evaluation".$TableNamePostfix[0]->Table_Name;
        $TableName4 = "front_physical_evaluation".$TableNamePostfix[0]->Table_Name;
//
//        设置lessons 表中的课程状态字段
        if ($lesson_state=='待提交'|| $lesson_state=='待提交1')
        {
            //查询lesson表是否提交了已经完成的记录
            $flag = DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_state','=','已完成')
                ->count();

            if ($flag == 0)//表中没有已完成的该条记录或者该记录处于待提交状态
            {
                DB::table('lessons')
                    ->where('lesson_name','=',$headdata[1]['value'])
                    ->where('lesson_teacher_name','=',$headdata[2]['value'])
                    ->where('lesson_year','=',$version['Year'])
                    ->where('lesson_semester','=',$version['Semester'])
                    ->update([
                        'lesson_state'=>$lesson_state,
                        'lesson_type'=>$headdata[7]['value'],
                    ]);
            }
            //该课程为已评价，说明别的督导已经提交评价，则不修改lesson表

        }
        if ($lesson_state=='已完成')//相当于填完评价表直接提交
        {
            DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->update([
                    'lesson_state'=>'已完成',
                    'lesson_type'=>$headdata[7]['value']
                ]);
        }
        if ($valueID==NULL|| $valueID==0)//创建的新评价表，此时监测评价头部是否相同
        {
            //查询评价表中是否有该课程的记录：防止督导通过
//        1、添加新的评价表，来覆盖之前提交的
//        2、修改待提交的，覆盖之前已经提交的。
//        检测标准：1、同一老师，同一门课程，同一组班级，同一时间(听课日期+听课节次)，
            $SubmitF = null;
            $SubmitF3 = null;
            $SubmitF4 = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {

                $SubmitF = DB::table($TableName1)->select('评价状态')//检验本表(理论课评价表)中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                    ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                    ->get();
                if ($SubmitF!=null)
                {
                    if ($SubmitF[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        DB::table($TableName1)
                            ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                            ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                            ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                            ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                            ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                            ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                            ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                            ->update(
                                [$headdata[0]['key'] => $headdata[0]['value']],
                                [$headdata[4]['key'] => $headdata[4]['value']],
                                [$headdata[6]['key'] => $headdata[6]['value']],
                                [$headdata[7]['key'] => $headdata[7]['value']]
                            );
                        DB::table($TableName2)
                            ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                            ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                            ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                            ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                            ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                            ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                            ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                            ->update(
                                [$headdata[0]['key'] => $headdata[0]['value']],
                                [$headdata[4]['key'] => $headdata[4]['value']],
                                [$headdata[6]['key'] => $headdata[6]['value']],
                                [$headdata[7]['key'] => $headdata[7]['value']]
                            );
                        for ($i=0;$i<count($frontdata);$i++)
                        {
                            DB::table($TableName1)
                                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                                ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                                ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                                ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                                ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                                ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                                ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                                ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
                        }

                        for($i=0;$i<count($backdata);$i++)
                        {
//                Log::info("yesyes");
                            DB::table($TableName2)
                                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                                ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                                ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                                ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                                ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                                ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                                ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                                ->update([$backdata[$i]['key']=> $backdata[$i]['value']]);
                        }
                        return '已保存更新该评论表数据！';
                    }
                }

                $SubmitF3 = DB::table($TableName3)->select('评价状态')//检验（实践课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
//                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//数据库中未加第 节 时
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF3!=null)
                {
                    if ($SubmitF3[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在实践课评价表中提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF3[0]->评价状态 == '待提交'|| $SubmitF3[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在实践课评价表中保存过该时间段内的评论内容！';
                    }
                }
                $SubmitF4 = DB::table($TableName4)->select('评价状态')//检验（体育课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
//                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//数据库中未加第 节 时
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF4!=null)
                {
                    if ($SubmitF4[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF4[0]->评价状态 == '待提交'|| $SubmitF4[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表保存过该时间段内的评论内容！';
                    }
                }
            }

            $flag1 = DB::table($TableName1)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//督导id
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        $headdata[10]['key']=> $headdata[10]['value'],//教师ID
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );

            //内容插入
            //评价表正面内容插入
            for ($i=0;$i<count($frontdata);$i++)
            {
                DB::table($TableName1)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
//                        ->where($headdata[9]['key'],'=',$headdata[9]['value'])//听课节次未加第几节
                    ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
            }

            //评价表背面内容插入

            $flag2=DB::table($TableName2)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//课程属性
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        $headdata[10]['key']=> $headdata[10]['value'],//教师ID
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );

            for($i=0;$i<count($backdata);$i++)
            {
//                Log::info("yesyes");
                DB::table($TableName2)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
//                        ->where($headdata[9]['key'],'=',$headdata[9]['value'])//听课节次未加第几节
                    ->update([$backdata[$i]['key']=> $backdata[$i]['value']]);
            }



            //从theory评价表中取出三个评述
            $textarea = DB::select('SELECT text FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where text LIKE \'如果以上各方面不能准确表达您的意见%\'
                                     AND fid = ANY(SELECT id FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where fid = 1)');

            if ($lesson_state == '已完成')
                return '提交成功';
            else
                return '保存成功';
//            }
        }
        else{
            $SubmitF = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {
                $SubmitF = $tableF = DB::table($TableName1)
                    ->where('valueID','=',$valueID)
                    ->get();
                if ($SubmitF!=null)
                {
                    //说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    if ($SubmitF[0]->评价状态 == '已完成' )
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        break;//此时就是该评价表本身修改
                    }
                }
            }
            DB::table($TableName1)
                ->where('valueID','=',$valueID)
                ->update(
                    [$headdata[0]['key'] => $headdata[0]['value']],
                    [$headdata[4]['key'] => $headdata[4]['value']],
                    [$headdata[6]['key'] => $headdata[6]['value']],
                    [$headdata[7]['key'] => $headdata[7]['value']]
                );
            DB::table($TableName1)
                ->where('valueID','=',$valueID)
                ->update(
                    ['评价状态' => $lesson_state]
                );
            DB::table($TableName2)
                ->where('valueID','=',$valueID)
                ->update(
                    ['评价状态' => $lesson_state]
                );
            DB::table($TableName2)
                ->where('valueID','=',$valueID)
                ->update(
                    [$headdata[0]['key'] => $headdata[0]['value']],
                    [$headdata[4]['key'] => $headdata[4]['value']],
                    [$headdata[6]['key'] => $headdata[6]['value']],
                    [$headdata[7]['key'] => $headdata[7]['value']]
                );
            for ($i=0;$i<count($frontdata);$i++)
            {
                DB::table($TableName1)
                    ->where('valueID','=',$valueID)
                    ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
            }
            for($i=0;$i<count($backdata);$i++)
            {
                DB::table($TableName2)
                    ->where('valueID','=',$valueID)
                    ->update([$backdata[$i]['key']=> $backdata[$i]['value']]);
            }
            if ($lesson_state == '已完成')
                return '提交成功';
            else
                return '保存成功';
        }
    }
    public function DBPhysicalEvaluationTable(Request $request)
    {
        $YearSemester=new HelpController;
        $valueID = $request->valueID;
        $headdata = $request->headdata;
        $frontdata = $request->frontdata;
        $backdata = $request->backdata;
        $lesson_state = $request->LessonState;
        /*
         * $headdata：
         * 0：章节目录 1：课程名称 2：任课教师 3：上课班级: 4：上课地点
         * 5：听课时间 6：督导姓名 7：课程属性 8：督导id 9：听课节次     ->value1,将听课节次拆开后的结果
         * 10：教师ID
         * */
        $version=$YearSemester->GetYearSemester($headdata[5]['value']);
        $TableNamePostfix=DB::table('evaluation_migration')
            ->select('Table_Name')
            ->where('Create_Year','=',$version['YearSemester'])
            ->get();

        $TableName1 = 'front_physical_evaluation'.$TableNamePostfix[0]->Table_Name;
        $TableName2 = 'back_physical_evaluation'.$TableNamePostfix[0]->Table_Name;

        $TableName3 = "front_theory_evaluation".$TableNamePostfix[0]->Table_Name;
        $TableName4 = "front_practice_evaluation".$TableNamePostfix[0]->Table_Name;

//        设置lessons 表中的课程状态字段
        if ($lesson_state=='待提交'|| $lesson_state=='待提交1')
        {
            //查询lesson表是否提交了已经完成的记录
            $flag = DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_state','=','已完成')
                ->count();

            if ($flag == 0)//表中没有已完成的该条记录或者该记录处于待提交状态
            {
                DB::table('lessons')
                    ->where('lesson_name','=',$headdata[1]['value'])
                    ->where('lesson_teacher_name','=',$headdata[2]['value'])
                    ->where('lesson_year','=',$version['Year'])
                    ->where('lesson_semester','=',$version['Semester'])
                    ->update([
                        'lesson_state'=>$lesson_state,
                        'lesson_type'=>$headdata[7]['value'],
                    ]);
            }
            //该课程为已评价，说明别的督导已经提交评价，则不修改lesson表

        }
        if ($lesson_state=='已完成')//相当于填完评价表直接提交
        {
            DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->update([
                    'lesson_state'=>'已完成',
                    'lesson_type'=>$headdata[7]['value']
                ]);
        }
        if ($valueID==NULL|| $valueID==0)//创建的新评价表，此时监测评价头部是否相同
        {
            //查询评价表中是否有该课程的记录：防止督导通过
//        1、添加新的评价表，来覆盖之前提交的
//        2、修改待提交的，覆盖之前已经提交的。
//        检测标准：1、同一老师，同一门课程，同一组班级，同一时间(听课日期+听课节次)，
            $SubmitF = null;
            $SubmitF3 = null;
            $SubmitF4 = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {

                $SubmitF = DB::table($TableName1)->select('评价状态')//检验本表(理论课评价表)中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                    ->get();
                if ($SubmitF!=null)
                {
                    if ($SubmitF[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        DB::table($TableName1)
                            ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                            ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                            ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                            ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                            ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                            ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                            ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                            ->update(
                                [$headdata[0]['key'] => $headdata[0]['value']],
                                [$headdata[4]['key'] => $headdata[4]['value']],
                                [$headdata[6]['key'] => $headdata[6]['value']],
                                [$headdata[7]['key'] => $headdata[7]['value']]
                            );
                        DB::table($TableName2)
                            ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                            ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                            ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                            ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                            ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                            ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                            ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                            ->update(
                                [$headdata[0]['key'] => $headdata[0]['value']],
                                [$headdata[4]['key'] => $headdata[4]['value']],
                                [$headdata[6]['key'] => $headdata[6]['value']],
                                [$headdata[7]['key'] => $headdata[7]['value']]
                            );
                        for ($i=0;$i<count($frontdata);$i++)
                        {
                            DB::table($TableName1)
                                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                                ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                                ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                                ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                                ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                                ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                                ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                                ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
                        }

                        for($i=0;$i<count($backdata);$i++)
                        {
//                Log::info("yesyes");
                            DB::table($TableName2)
                                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                                ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                                ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                                ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                                ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                                ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                                ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                                ->update([$backdata[$i]['key']=> $backdata[$i]['value']]);
                        }
                        return '已保存更新该评论表数据！';
                    }
                }

                $SubmitF3 = DB::table($TableName3)->select('评价状态')//检验（实践课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF3!=null)
                {
                    if ($SubmitF3[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在实践课评价表中提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF3[0]->评价状态 == '待提交'|| $SubmitF3[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在实践课评价表中保存过该时间段内的评论内容！';
                    }
                }
                $SubmitF4 = DB::table($TableName4)->select('评价状态')//检验（体育课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
//                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//数据库中未加第 节 时
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF4!=null)
                {
                    if ($SubmitF4[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF4[0]->评价状态 == '待提交'|| $SubmitF4[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表保存过该时间段内的评论内容！';
                    }
                }
            }

            $flag1 = DB::table($TableName1)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//督导id
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        $headdata[10]['key']=> $headdata[10]['value'],//教师ID
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );

            //内容插入
            //评价表正面内容插入
            for ($i=0;$i<count($frontdata);$i++)
            {
                DB::table($TableName1)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
//                        ->where($headdata[9]['key'],'=',$headdata[9]['value'])//听课节次未加第几节
                    ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
            }

            //评价表背面内容插入

            $flag2=DB::table($TableName2)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//课程属性
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        $headdata[10]['key']=> $headdata[10]['value'],//教师ID
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );

            for($i=0;$i<count($backdata);$i++)
            {
//                Log::info("yesyes");
                DB::table($TableName2)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')
                    ->where($headdata[10]['key'],'=',$headdata[10]['value'])
                    ->update([$backdata[$i]['key']=> $backdata[$i]['value']]);
            }



            //从theory评价表中取出三个评述
            $textarea = DB::select('SELECT text FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where text LIKE \'如果以上各方面不能准确表达您的意见%\'
                                     AND fid = ANY(SELECT id FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where fid = 1)');

            if ($lesson_state == '已完成')
                return '提交成功';
            else
                return '保存成功';
//            }
        }
        else{//想要修改评价表
            //说明该课程在该课程节次已经在理论评价表中被该老师评价过了，
            //此时valueID 不可能为 0 或者null
            //查询评价表中是否有该课程的记录：防止督导通过
//        1、添加新的评价表，来覆盖之前提交的
//        2、修改待提交的，覆盖之前已经提交的。
//        检测标准：valueID，
            $SubmitF = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {
                $SubmitF = $tableF = DB::table($TableName1)
                    ->where('valueID','=',$valueID)
                    ->get();
                if ($SubmitF!=null)
                {
                    //说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    if ($SubmitF[0]->评价状态 == '已完成' )
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        break;//此时就是该评价表本身修改
                    }
                }
            }
            DB::table($TableName1)
                ->where('valueID','=',$valueID)
                ->update(
                    [$headdata[0]['key'] => $headdata[0]['value']],
                    [$headdata[4]['key'] => $headdata[4]['value']],
                    [$headdata[6]['key'] => $headdata[6]['value']],
                    [$headdata[7]['key'] => $headdata[7]['value']]
                );
            DB::table($TableName1)
                ->where('valueID','=',$valueID)
                ->update(
                    ['评价状态' => $lesson_state]
                );
            DB::table($TableName2)
                ->where('valueID','=',$valueID)
                ->update(
                    ['评价状态' => $lesson_state]
                );
            DB::table($TableName2)
                ->where('valueID','=',$valueID)
                ->update(
                    [$headdata[0]['key'] => $headdata[0]['value']],
                    [$headdata[4]['key'] => $headdata[4]['value']],
                    [$headdata[6]['key'] => $headdata[6]['value']],
                    [$headdata[7]['key'] => $headdata[7]['value']]
                );
            for ($i=0;$i<count($frontdata);$i++)
            {
                DB::table($TableName1)
                    ->where('valueID','=',$valueID)
                    ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
            }
            for($i=0;$i<count($backdata);$i++)
            {
//                Log::info("yesyes");
                DB::table($TableName2)
                    ->where('valueID','=',$valueID)
                    ->update([$backdata[$i]['key']=> $backdata[$i]['value']]);
            }
            if ($lesson_state == '已完成')
                return '提交成功';
            else
                return '保存成功';
        }

    }


    public function DBPracticeFrontEvaluationTable(Request $request)
    {
        $YearSemester=new HelpController;
        $valueID = $request->valueID;
        $headdata =  $request->headdata;
        $frontdata = $request->frontdata;
        $backdata1 = $request->backdata1;
        $backdata2 = $request->backdata2;
        $lesson_state = $request->LessonState;

        $version = $YearSemester->GetYearSemester($headdata[5]['value']);
        $TableNamePostfix = DB::table('evaluation_migration')
            ->select('Table_Name')
            ->where('Create_Year','=',$version['YearSemester'])
            ->get();

        $TableName1 = 'front_practice_evaluation'.$TableNamePostfix[0]->Table_Name;
        $TableName2 = 'back_practice_evaluation'.$TableNamePostfix[0]->Table_Name;
        $TableName3 = "front_theory_evaluation".$TableNamePostfix[0]->Table_Name;
        $TableName4 = "front_physical_evaluation".$TableNamePostfix[0]->Table_Name;
//        Log::write('info',$lesson_state);


        /*
         * $headdata：
         * 0：章节目录 1：课程名称 2：任课教师 3：上课班级: 4：上课地点
         * 5：听课时间 6：督导姓名 7：课程属性 8：督导id 9：听课节次->value1,将听课节次拆开后的结果
         * */
//        设置lessons 表中的课程状态字段
        if ($lesson_state=='待提交'|| $lesson_state=='待提交1')
        {
            $flag = DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_state','=','已完成')
                ->count();

            if ($flag == 0)
            {
                DB::table('lessons')
                    ->where('lesson_name','=',$headdata[1]['value'])
                    ->where('lesson_teacher_name','=',$headdata[2]['value'])
                    ->where('lesson_year','=',$version['Year'])
                    ->where('lesson_semester','=',$version['Semester'])
                    ->update([
                        'lesson_state'=>'待提交',
                        'lesson_type'=>$headdata[7]['value'],
                    ]);
            }

        }
        if ($lesson_state=='已完成')
        {
            DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->update([
                    'lesson_state'=>'已完成',
                    'lesson_type'=>$headdata[7]['value']
                ]);
        }





        if ($valueID==NULL || $valueID==0)//创建的新评价表，此时监测评价头部是否相同
        {

            $SubmitF = null;
            $SubmitF3 = null;
            $SubmitF4 = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {
                $SubmitF = DB::table($TableName1)->select('评价状态')
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                    ->get();

                if ($SubmitF!=null)
                {
                    if ($SubmitF[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已保存过该时间段内的评论表！';
                    }
                }

                $SubmitF3 = DB::table($TableName3)->select('评价状态')//检验（实践课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
//                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//数据库中未加第 节 时
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF3!=null)
                {
                    if ($SubmitF3[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在理论课评价表中提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF3[0]->评价状态 == '待提交'|| $SubmitF3[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在理论课评价表中保存过该时间段内的评论内容！';
                    }
                }
                $SubmitF4 = DB::table($TableName4)->select('评价状态')//检验（体育课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
//                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//数据库中未加第 节 时
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF4!=null)
                {
                    if ($SubmitF4[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF4[0]->评价状态 == '待提交'|| $SubmitF4[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表保存过该时间段内的评论内容！';
                    }
                }
            }
            //头部插入
            $flag1 = DB::table($TableName1)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//督导id
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );
            //内容插入
            //评价表正面内容插入
            for ($i=0;$i<count($frontdata);$i++)
            {
                DB::table($TableName1)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
                    ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
            }

            //评价表背面内容插入
            $flag2 = DB::table($TableName2)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//课程属性
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );

            for ($i=0;$i<count($backdata1);$i++)
            {
                DB::table($TableName2)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
                    ->update([$backdata1[$i]=>'1']);
            }


            //从theory评价表中取出三个评述
            $textarea = DB::select('SELECT text FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where text LIKE \'如果以上各方面不能准确表达您的意见%\'
                                     AND fid = ANY(SELECT id FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where fid = 2)');

            //将三个评论插入数据库
            for ($a = 0;$a<count($textarea);$a++)
            {
                DB::table($TableName2)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
                    ->update([$textarea[$a]->text=>$backdata2[$a]['value']]);
            }
            if ($lesson_state == '已完成')
                return '提交成功';
            else
                return '保存成功';

        }
        else{//
            //此时valueID 不可能为 0 或者null
//            Log::write('info',$valueID);
            //查询评价表中是否有该课程的记录：防止督导通过
//        1、添加新的评价表，来覆盖之前提交的
//        2、修改待提交的，覆盖之前已经提交的。
//        检测标准：1、同一老师，同一门课程，同一组班级，同一时间(听课日期+听课节次)，
            $SubmitF = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {
                $SubmitF = DB::table($TableName1)->select('评价状态','valueID')
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                    ->get();

                if ($SubmitF!=null)
                {
                    //说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    if ($SubmitF[0]->评价状态 == '已完成' )
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
//                   当有valueID时，同样在
                        if($SubmitF[0]->valueID == $valueID)
                            break;//此时就是该评价表本身修改
                        else
                            return '您已保存过该时间段内的评论表！';
                    }
                }

            }

            $F = DB::table($TableName1)
                ->where('valueID','=',$valueID)
                ->get();
//Log::write('info',$F);
            if($F[0]->评价状态 == '已完成')
            {
                return '您已提交该课程评价，请勿重复提交。';
            }
            //此时的状态为可提交可保存
            if($F[0]->评价状态 == '待提交' || $F[0]->评价状态 == '待提交1')
            {
                //头部更新
//                DB::table($TableName1)
//                    ->where('valueID','=',$valueID)
//                    ->update([
//                        '评价状态'=>$lesson_state
//                    ]);

                //更新评价表头部
//                for($i=0;$i<count($headdata);$i++)
//                {
//                    DB::table($TableName1)
//                        ->where('valueID','=',$valueID)
//                        ->update([$headdata[$i]['key']=> $headdata[$i]['value']]);
//                }
                DB::table($TableName1)
                    ->where('valueID','=',$valueID)
                    ->delete();
                $flag0 = DB::table($TableName1)
                    ->insert(
                        [
                            'valueID'=>$valueID,
                            $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                            $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                            $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                            $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                            $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                            $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                            $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                            $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                            $headdata[8]['key']=> $headdata[8]['value'],//督导id
                            $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                            '评价状态'=>$lesson_state,
                            '填表时间'=>date("Y-m-d")
                        ]
                    );
                //评价表正面内容更新
                for ($i=0;$i<count($frontdata);$i++)
                {
                    DB::table($TableName1)
                        ->where('valueID','=',$valueID)
                        ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
                }


                //评价表背面内容更新
                DB::table($TableName2)
                    ->where('valueID','=',$valueID)
                    ->delete();

                //将表背面数据项制空
                $flag1 = DB::table($TableName2)
                    ->insert(
                        [
                            'valueID'=>$valueID,
                            $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                            $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                            $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                            $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                            $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                            $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                            $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                            $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                            $headdata[8]['key']=> $headdata[8]['value'],//督导id
                            $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                            '评价状态'=>$lesson_state,
                            '填表时间'=>date("Y-m-d")
                        ]
                    );

                for ($i=0;$i<count($backdata1);$i++)
                {
                    DB::table($TableName2)
                        ->where('valueID','=',$valueID)
                        ->update([$backdata1[$i]=>'1']);
                }
                //从theory评价表中取出三个评述
                $textarea = DB::select('SELECT text FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where text LIKE \'如果以上各方面不能准确表达您的意见%\'
                                     AND fid = ANY(SELECT id FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where fid = 2)');

                //将三个评论插入数据库
                for ($a = 0;$a<count($textarea);$a++)
                {

                    DB::table($TableName2)
                        ->where('valueID','=',$valueID)
                        ->update([$textarea[$a]->text=>$backdata2[$a]['value']]);
                }
                if ($lesson_state == '已完成')
                    return '提交成功';
                else
                    return '保存成功';
            }
        }

        return '未知错误，请联系开发人员';

    }

    public function DBPhysicalFrontEvaluationTable(Request $request)
    {
        $YearSemester=new HelpController;
        $valueID = $request->valueID;
        $headdata = ($request->headdata);
        $frontdata = ($request->frontdata);
        $backdata1 = $request->backdata1;
        $backdata2 = $request->backdata2;
        $lesson_state = ($request->LessonState);
        /*
         * $headdata：
         * 0：章节目录 1：课程名称 2：任课教师 3：上课班级: 4：上课地点
         * 5：听课时间 6：督导姓名 7：课程属性 8：督导id 9：听课节次     ->value1,将听课节次拆开后的结果
         * */
        $version = $YearSemester->GetYearSemester($headdata[5]['value']);
        $TableNamePostfix = DB::table('evaluation_migration')
            ->select('Table_Name')
            ->where('Create_Year','=',$version['YearSemester'])
            ->get();

        $TableName1 = 'front_physical_evaluation'.$TableNamePostfix[0]->Table_Name;
        $TableName2 = 'back_physical_evaluation'.$TableNamePostfix[0]->Table_Name;
        $TableName3 = "front_theory_evaluation".$TableNamePostfix[0]->Table_Name;
        $TableName4 = "front_practice_evaluation".$TableNamePostfix[0]->Table_Name;


//        设置lessons 表中的课程状态字段
        if ($lesson_state=='待提交'|| $lesson_state=='待提交1')
        {
            $flag = DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_state','=','已完成')
                ->count();

            if ($flag == 0)
            {
                DB::table('lessons')
                    ->where('lesson_name','=',$headdata[1]['value'])
                    ->where('lesson_teacher_name','=',$headdata[2]['value'])
                    ->where('lesson_year','=',$version['Year'])
                    ->where('lesson_semester','=',$version['Semester'])
                    ->update([
                        'lesson_state'=>'待提交',
                        'lesson_type'=>$headdata[7]['value'],
                    ]);
            }

        }
        if ($lesson_state=='已完成')
        {
            DB::table('lessons')
                ->where('lesson_name','=',$headdata[1]['value'])
                ->where('lesson_teacher_name','=',$headdata[2]['value'])
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->update([
                    'lesson_state'=>'已完成',
                    'lesson_type'=>$headdata[7]['value']
                ]);
        }



        if ($valueID==NULL|| $valueID==0)//创建的新评价表，此时监测评价头部是否相同
        {
            //查询评价表中是否有该课程的记录：防止督导通过
//        1、添加新的评价表，来覆盖之前提交的
//        2、修改待提交的，覆盖之前已经提交的。
//        检测标准：1、同一老师，同一门课程，同一组班级，同一时间(听课日期+听课节次)，
            $SubmitF = null;
            $SubmitF3 = null;
            $SubmitF4 = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {
                $SubmitF = DB::table($TableName1)->select('评价状态')
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                    ->get();

                if ($SubmitF!=null)
                {
                    if ($SubmitF[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已保存过该时间段内的评论表！';
                    }
                }

                $SubmitF3 = DB::table($TableName3)->select('评价状态')//检验（实践课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
//                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//数据库中未加第 节 时
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF3!=null)
                {
                    if ($SubmitF3[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在理论课评价表中提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF3[0]->评价状态 == '待提交'|| $SubmitF3[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在理论课评价表中保存过该时间段内的评论内容！';
                    }
                }
                $SubmitF4 = DB::table($TableName4)->select('评价状态')//检验（体育课评价表）中是否有重复记录
                ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
//                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//数据库中未加第 节 时
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')//
                    ->get();
                if ($SubmitF4!=null)
                {
                    if ($SubmitF4[0]->评价状态 == '已完成' )//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表提交过该时间段内的评论内容！';
                    }
                    if ($SubmitF4[0]->评价状态 == '待提交'|| $SubmitF4[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
                        return '您已在体育课评价表保存过该时间段内的评论内容！';
                    }
                }
            }
            //头部插入
            $flag1 = DB::table($TableName1)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//督导id
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );
            //内容插入
            //评价表正面内容插入
            for ($i=0;$i<count($frontdata);$i++)
            {
                DB::table($TableName1)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
                    ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
            }


            //评价表背面内容插入
            $flag2 = DB::table($TableName2)
                ->insert(
                    [
                        $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                        $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                        $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                        $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                        $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                        $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                        $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                        $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                        $headdata[8]['key']=> $headdata[8]['value'],//课程属性
                        $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                        '评价状态'=>$lesson_state,
                        '填表时间'=>date("Y-m-d")
                    ]
                );

            for ($i=0;$i<count($backdata1);$i++)
            {
                DB::table($TableName2)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
                    ->update([$backdata1[$i]=>'1']);
            }

            //从theory评价表中取出三个评述
            $textarea = DB::select('SELECT text FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where text LIKE \'如果以上各方面不能准确表达您的意见%\'
                                     AND fid = ANY(SELECT id FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where fid = 3)');

            //将三个评论插入数据库
            for ($a = 0;$a<count($textarea);$a++)
            {
                DB::table($TableName2)
                    ->where($headdata[0]['key'],'=',$headdata[0]['value'])
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[4]['key'],'=',$headdata[4]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[6]['key'],'=',$headdata[6]['value'])
                    ->where($headdata[7]['key'],'=',$headdata[7]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value'].'%')//
                    ->update([$textarea[$a]->text=>$backdata2[$a]['value']]);
            }
            if ($lesson_state == '已完成')
                return '提交成功';
            else
                return '保存成功';
        }
        else{//

            //想要修改评价表
            //说明该课程在该课程节次已经在理论评价表中被该老师评价过了，
            //此时valueID 不可能为 0 或者null
//            Log::write('info',$valueID);
            //查询评价表中是否有该课程的记录：防止督导通过
//        1、添加新的评价表，来覆盖之前提交的
//        2、修改待提交的，覆盖之前已经提交的。
//        检测标准：1、同一老师，同一门课程，同一组班级，同一时间(听课日期+听课节次)，
            $SubmitF = null;
            for ($m=0;$m<count($headdata[9]['value1']);$m++)
            {
                $SubmitF = DB::table($TableName1)->select('评价状态','valueID')
                    ->where($headdata[1]['key'],'=',$headdata[1]['value'])
                    ->where($headdata[2]['key'],'=',$headdata[2]['value'])
                    ->where($headdata[3]['key'],'=',$headdata[3]['value'])
                    ->where($headdata[5]['key'],'=',$headdata[5]['value'])
                    ->where($headdata[8]['key'],'=',$headdata[8]['value'])
                    ->where($headdata[9]['key'],'like','%'.$headdata[9]['value1'][$m].'%')
                    ->get();

                if ($SubmitF!=null)
                {
                    //说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    if ($SubmitF[0]->评价状态 == '已完成' )
                    {
                        return '您已提交过该时间段内的评论表！';
                    }
                    if ($SubmitF[0]->评价状态 == '待提交'|| $SubmitF[0]->评价状态 == '待提交1')//说明该课程在该课程节次已经在理论评价表中被该老师评价过了，此时valueID 不可能为 0 或者null
                    {
//                   当有valueID时，同样在
                        if($SubmitF[0]->valueID == $valueID)
                            break;//此时就是该评价表本身修改
                        else
                            return '您已保存过该时间段内的评论表！';
                    }
                }

            }

            $F = DB::table($TableName1)
                ->where('valueID','=',$valueID)
                ->get();
//Log::write('info',$F);
            if($F[0]->评价状态 == '已完成')
            {
                return '您已提交该课程评价，请勿重复提交。';
            }
            //此时的状态为可提交可保存
            if($F[0]->评价状态 == '待提交' || $F[0]->评价状态 == '待提交1')
            {
//头部更新
                DB::table($TableName1)
                    ->where('valueID','=',$valueID)
                    ->delete();
                $flag0 = DB::table($TableName1)
                    ->insert(
                        [
                            'valueID'=>$valueID,
                            $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                            $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                            $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                            $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                            $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                            $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                            $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                            $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                            $headdata[8]['key']=> $headdata[8]['value'],//督导id
                            $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                            '评价状态'=>$lesson_state,
                            '填表时间'=>date("Y-m-d")
                        ]
                    );

                //评价表正面内容更新
                for ($i=0;$i<count($frontdata);$i++)
                {
                    DB::table($TableName1)
                        ->where('valueID','=',$valueID)
                        ->update([$frontdata[$i]['key']=> $frontdata[$i]['value']]);
                }


                //评价表背面内容更新
                DB::table($TableName2)
                    ->where('valueID','=',$valueID)
                    ->delete();

                //将表背面数据项制空
                $flag1 = DB::table($TableName2)
                    ->insert(
                        [
                            'valueID'=>$valueID,
                            $headdata[0]['key']=> $headdata[0]['value'],//章节目录
                            $headdata[1]['key']=> $headdata[1]['value'],//课程名称
                            $headdata[2]['key']=> $headdata[2]['value'],//任课教师
                            $headdata[3]['key']=> $headdata[3]['value'],//上课班级
                            $headdata[4]['key']=> $headdata[4]['value'],//上课地点
                            $headdata[5]['key']=> $headdata[5]['value'],//听课时间
                            $headdata[6]['key']=> $headdata[6]['value'],//督导姓名
                            $headdata[7]['key']=> $headdata[7]['value'],//课程属性
                            $headdata[8]['key']=> $headdata[8]['value'],//督导id
                            $headdata[9]['key']=> '第'.$headdata[9]['value'].'节',//听课节次
                            '评价状态'=>$lesson_state,
                            '填表时间'=>date("Y-m-d")
                        ]
                    );

                for ($i=0;$i<count($backdata1);$i++)
                {
                    DB::table($TableName2)
                        ->where('valueID','=',$valueID)
                        ->update([$backdata1[$i]=>'1']);
                }
                //从theory评价表中取出三个评述
                $textarea = DB::select('SELECT text FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where text LIKE \'如果以上各方面不能准确表达您的意见%\'
                                     AND fid = ANY(SELECT id FROM back_contents'.$TableNamePostfix[0]->Table_Name.' where fid = 3)');

                //将三个评论插入数据库
                for ($a = 0;$a<count($textarea);$a++)
                {

                    DB::table($TableName2)
                        ->where('valueID','=',$valueID)
                        ->update([$textarea[$a]->text=>$backdata2[$a]['value']]);
                }
                if ($lesson_state == '已完成')
                    return '提交成功';
                else
                    return '保存成功';
            }
        }
        return '未知错误，请联系开发人员';

    }
}
