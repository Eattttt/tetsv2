<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Help\HelpController;

class EverEvaluationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',['only',['EverEvaluated']]);
    }
    /**
     * 每个督导完成情况视图页面
     */
    public function EverEvaluated()
    {
        $frontdata=$this->GetFrontValueTable();
//        dd($frontdata);
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
        return view('EverEvaluated',compact('front','back'));
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

    //校级督导查询，返回所有督导完成听课情况 简介
    public function GetAllEveryEvaluated(Request $request)
    {


        $mytime = new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        $year = $year1."-".$year2;
        $semester = $request->semester;

        $group = $request->group;

//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $group='第一组';

        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);


        $data = array();

        $TableName = $mytime->GetCurrentTableName($TableFlag);
//dd($TableName);
        $table1 = "front_theory_evaluation".$TableName;
        $table2 = "front_practice_evaluation".$TableName;
        $table3 = "front_physical_evaluation".$TableName;

        $DataArr1 = DB::select('SELECT distinct '.$table1.'.valueID, '.$table1.'.课程名称, '.$table1.'.任课教师, '.$table1.'.课程属性,
				'.$table1.'.上课班级,
				'.$table1.'.章节目录,
				'.$table1.'.`督导姓名`,
				'.$table1.'.督导id,
				'.$table1.'.`听课时间`,
				'.$table1.'.`上课地点`,
				'.$table1.'.`听课节次`,
				'.$table1.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table1.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table1.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table1.'.`任课教师`
            and T2.lesson_class= '.$table1.'.`上课班级`
            WHERE
             '.$table1.'.`评价状态`="已完成"
            and '.$table1.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr1);$l++)
            array_push($data,$DataArr1[$l]);

//        '.$table2.'.`授课总体评价`,
        $DataArr2 = DB::select('SELECT distinct '.$table2.'.valueID, '.$table2.'.课程名称, '.$table2.'.任课教师, '.$table2.'.课程属性,
				'.$table2.'.上课班级,
				'.$table2.'.章节目录,
                '.$table2.'.督导id,
				'.$table2.'.`督导姓名`,
				'.$table2.'.`听课时间`,
				'.$table2.'.`上课地点`,
				'.$table2.'.`听课节次`,
				'.$table2.'.`填表时间`,

	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table2.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table2.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table2.'.`任课教师`
            and T2.lesson_class= '.$table2.'.`上课班级`
            WHERE  '.$table2.'.`评价状态`="已完成"
            and '.$table2.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr2);$l++)
            array_push($data,$DataArr2[$l]);

        $DataArr3 = DB::select('SELECT distinct '.$table3.'.valueID, '.$table3.'.课程名称, '.$table3.'.任课教师, '.$table3.'.课程属性,
				'.$table3.'.上课班级,
				'.$table3.'.章节目录,
				'.$table3.'.督导id,
				'.$table3.'.`督导姓名`,
				'.$table3.'.`听课时间`,
				'.$table3.'.`上课地点`,
				'.$table3.'.`听课节次`,
				'.$table3.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table3.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table3.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table3.'.`任课教师`
            and T2.lesson_class= '.$table3.'.`上课班级`
            WHERE '.$table3.'.`评价状态`="已完成"
            and '.$table3.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr3);$l++)
            array_push($data,$DataArr3[$l]);

        return json_encode($data);
    }

    //院级督导完成听课情况 简介
    public function GetUnitEveryEvaluated(Request $request)
    {
        $mytime = new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        $year = $year1."-".$year2;
        $semester = $request->semester;

        $unit = $request->unit;

//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $group='第一组';

        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);


        $data = array();

        $TableName = $mytime->GetCurrentTableName($TableFlag);
//dd($TableName);
        $table1 = "front_theory_evaluation".$TableName;
        $table2 = "front_practice_evaluation".$TableName;
        $table3 = "front_physical_evaluation".$TableName;

        $DataArr1 = DB::select('SELECT distinct '.$table1.'.valueID, '.$table1.'.课程名称, '.$table1.'.任课教师, '.$table1.'.课程属性,
				'.$table1.'.上课班级,
				'.$table1.'.章节目录,
				'.$table1.'.`督导姓名`,
				'.$table1.'.督导id,
				'.$table1.'.`听课时间`,
				'.$table1.'.`上课地点`,
				'.$table1.'.`听课节次`,
				'.$table1.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table1.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table1.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table1.'.`任课教师`
            and T2.lesson_class= '.$table1.'.`上课班级`
            WHERE(
	            '.$table1.'.`课程名称`= ANY (select lesson_name from lessons where lessons.lesson_unit="'.$unit.'")

            )and '.$table1.'.`评价状态`="已完成"
            and '.$table1.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr1);$l++)
            array_push($data,$DataArr1[$l]);

        $DataArr2 = DB::select('SELECT distinct '.$table2.'.valueID, '.$table2.'.课程名称, '.$table2.'.任课教师, '.$table2.'.课程属性,
				'.$table2.'.上课班级,
				'.$table2.'.章节目录,
                '.$table2.'.督导id,
				'.$table2.'.`督导姓名`,
				'.$table2.'.`听课时间`,
				'.$table2.'.`上课地点`,
				'.$table2.'.`听课节次`,
				'.$table2.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table2.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table2.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table2.'.`任课教师`
            and T2.lesson_class= '.$table2.'.`上课班级`
            WHERE(
	            '.$table2.'.`课程名称`= ANY (select lesson_name from lessons where lessons.lesson_unit="'.$unit.'")

            )and '.$table2.'.`评价状态`="已完成"
            and '.$table2.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr2);$l++)
            array_push($data,$DataArr2[$l]);

        $DataArr3 = DB::select('SELECT distinct '.$table3.'.valueID, '.$table3.'.课程名称, '.$table3.'.任课教师, '.$table3.'.课程属性,
				'.$table3.'.上课班级,
				'.$table3.'.章节目录,
				'.$table3.'.督导id,
				'.$table3.'.`督导姓名`,
				'.$table3.'.`听课时间`,
				'.$table3.'.`上课地点`,
				'.$table3.'.`听课节次`,
				'.$table3.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table3.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table3.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table3.'.`任课教师`
            and T2.lesson_class= '.$table3.'.`上课班级`
            WHERE(
	            '.$table3.'.`课程名称`= ANY (select lesson_name from lessons where lessons.lesson_unit="'.$unit.'")

            )and '.$table3.'.`评价状态`="已完成"
            and '.$table3.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr3);$l++)
            array_push($data,$DataArr3[$l]);

        return json_encode($data);
    }

    //小组 完成听课情况 简介
    public function GetGroupEveryEvaluated(Request $request)
    {


        $mytime = new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        $year = $year1."-".$year2;
        $semester = $request->semester;

        $group = $request->group;

//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $group='第一组';

        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);


        $data = array();

        $TableName = $mytime->GetCurrentTableName($TableFlag);
//dd($TableName);
        $table1 = "front_theory_evaluation".$TableName;
        $table2 = "front_practice_evaluation".$TableName;
        $table3 = "front_physical_evaluation".$TableName;

        $DataArr1 = DB::select('SELECT distinct
                '.$table1.'.valueID,
                '.$table1.'.课程名称,
                '.$table1.'.任课教师,
                '.$table1.'.课程属性,
				'.$table1.'.上课班级,
				'.$table1.'.章节目录,
				'.$table1.'.`督导姓名`,
				'.$table1.'.督导id,
				'.$table1.'.`听课时间`,
				'.$table1.'.`上课地点`,
				'.$table1.'.`听课节次`,
				'.$table1.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table1.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table1.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table1.'.`任课教师`
            and T2.lesson_class= '.$table1.'.`上课班级`
            WHERE(
	            '.$table1.'.`督导id`= ANY (select user_id from users where users.`group`="'.$group.'")

            )and '.$table1.'.`评价状态`="已完成"
            and '.$table1.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr1);$l++)
            array_push($data,$DataArr1[$l]);

        $DataArr2 = DB::select('SELECT distinct '.$table2.'.valueID, '.$table2.'.课程名称, '.$table2.'.任课教师, '.$table2.'.课程属性,
				'.$table2.'.上课班级,
				'.$table2.'.章节目录,
                '.$table2.'.督导id,
				'.$table2.'.`督导姓名`,
				'.$table2.'.`听课时间`,
				'.$table2.'.`上课地点`,
				'.$table2.'.`听课节次`,
				'.$table2.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table2.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table2.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table2.'.`任课教师`
            and T2.lesson_class= '.$table2.'.`上课班级`
            WHERE(
	            '.$table2.'.`督导id`= ANY (select user_id from users where users.`group`="'.$group.'")

            )and '.$table2.'.`评价状态`="已完成"
            and '.$table2.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr2);$l++)
            array_push($data,$DataArr2[$l]);

        $DataArr3 = DB::select('SELECT distinct '.$table3.'.valueID, '.$table3.'.课程名称, '.$table3.'.任课教师, '.$table3.'.课程属性,
				'.$table3.'.上课班级,
				'.$table3.'.章节目录,
				'.$table3.'.督导id,
				'.$table3.'.`督导姓名`,
				'.$table3.'.`听课时间`,
				'.$table3.'.`上课地点`,
				'.$table3.'.`听课节次`,
				'.$table3.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table3.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table3.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table3.'.`任课教师`
            and T2.lesson_class= '.$table3.'.`上课班级`
            WHERE(
	            '.$table3.'.`督导id`= ANY (select user_id from users where users.`group`="'.$group.'")

            )and '.$table3.'.`评价状态`="已完成"
            and '.$table3.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr3);$l++)
            array_push($data,$DataArr3[$l]);

        return json_encode($data);
    }

    //校级和督导：完成听课情况简介
    /*
     * 评价表和课程表建立左联合查询，主要获取
     * 评价表中的简介内容+课程表中的 课程等级和分配组别
     * */
    public function GetEveryEvaluated(Request $request)
    {
        $Content =new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        //test code :
//        $year1='2016';
//        $year2='2017';
        $year = $year1."-".$year2;
        $semester = $request->semester;




//        $semester = array('0'=>'1');

        $supervisor = $request->supervisor;
        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        $YearSemesterTime = $Content->GetTimeByYearSemester($TableFlag);

        //test code :
//        $TableFlag='2016-2017-1';
//        $supervisor='19490202';
//Log::write('info',$TableFlag);
//        Log::write('info',$supervisor);
//        Log::write('info',$semester);
//        Log::write('info',$year);

        $TableName = $Content->GetCurrentTableName($TableFlag);
//        Log::write('info',$TableName);

        if($TableName == -1)
            return -1;
        else
        {
            $table1 = "front_theory_evaluation".$TableName;
            $table2 = "front_practice_evaluation".$TableName;
            $table3 = "front_physical_evaluation".$TableName;

            $DataArr1 = DB::select('select DISTINCT
                '.$table1.'.valueID,
                '.$table1.'.课程名称,
                '.$table1.'.任课教师,
                '.$table1.'.课程属性,
				'.$table1.'.上课班级,
				'.$table1.'.章节目录,
				'.$table1.'.督导id,
				'.$table1.'.`督导姓名`,
				'.$table1.'.`听课时间`,
				'.$table1.'.`上课地点`,
				'.$table1.'.`听课节次`,
				'.$table1.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            from '.$table1.'
            left JOIN
            (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON
            T2.lesson_name = '.$table1.'.`课程名称`
            and T2.lesson_teacher_name = '.$table1.'.`任课教师`
            and T2.lesson_class= '.$table1.'.`上课班级`
            where '.$table1.'.督导id="'.$supervisor.'"
            AND '.$table1.'.`评价状态`="已完成"
            AND '.$table1.'.`听课时间`BETWEEN "'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');

            $DataArr2 = DB::select('select DISTINCT
                '.$table2.'.valueID,
                '.$table2.'.课程名称,
                '.$table2.'.任课教师,
                '.$table2.'.课程属性,
				'.$table2.'.上课班级,
				'.$table2.'.章节目录,
				'.$table2.'.督导id,
				'.$table2.'.`督导姓名`,
				'.$table2.'.`听课时间`,
				'.$table2.'.`上课地点`,
				'.$table2.'.`听课节次`,
				'.$table2.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            from '.$table2.'
            left JOIN
            (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON
            T2.lesson_name = '.$table2.'.`课程名称`
            and T2.lesson_teacher_name = '.$table2.'.`任课教师`
            and T2.lesson_class= '.$table2.'.`上课班级`
            where '.$table2.'.督导id="'.$supervisor.'" AND '.$table2.'.`评价状态`="已完成"
            AND '.$table2.'.`听课时间`BETWEEN "'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');

            $DataArr3 = DB::select('select DISTINCT
                '.$table3.'.valueID,
                '.$table3.'.课程名称,
                '.$table3.'.任课教师,
                '.$table3.'.课程属性,
				'.$table3.'.上课班级,
				'.$table3.'.章节目录,
				'.$table3.'.督导id,
				'.$table3.'.`督导姓名`,
				'.$table3.'.`听课时间`,
				'.$table3.'.`上课地点`,
				'.$table3.'.`听课节次`,
				'.$table3.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            from '.$table3.'
            left JOIN
            (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON
            T2.lesson_name = '.$table3.'.`课程名称`
            and T2.lesson_teacher_name = '.$table3.'.`任课教师`
            and T2.lesson_class= '.$table3.'.`上课班级`
            where '.$table3.'.督导id="'.$supervisor.'" AND '.$table3.'.`评价状态`="已完成"
            AND '.$table3.'.`听课时间`BETWEEN "'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');

            $DataArr = [];
            for ($l=0;$l<count($DataArr1);$l++)
                array_push($DataArr,$DataArr1[$l]);
            for ($l=0;$l<count($DataArr2);$l++)
                array_push($DataArr,$DataArr2[$l]);
            for ($l=0;$l<count($DataArr3);$l++)
                array_push($DataArr,$DataArr3[$l]);

            if ($DataArr==null)
                return -1;
            else{
                return json_encode($DataArr);
            }

        }

    }


    /**
     * @param Request $request
     * @return string
     * 保存待提交
     * 校级和院级可以通过lessons表确定查询课程的所属单位，但是
     * lessons表中没有存储关于第几组老师分配的信息，故而需要遍历三张表，
     * 确定每组督导听课的全部课程
     */
    //校级督导查询，返回所有督导待提交听课情况 简介
    public function GetAllEverySaved(Request $request)
    {


        $mytime = new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        $year = $year1."-".$year2;
        $semester = $request->semester;

//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $group='第一组';

        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);


        $data = array();

        $TableName = $mytime->GetCurrentTableName($TableFlag);
//dd($TableName);
        $table1 = "front_theory_evaluation".$TableName;
        $table2 = "front_practice_evaluation".$TableName;
        $table3 = "front_physical_evaluation".$TableName;

        $DataArr1 = DB::select('SELECT distinct
                '.$table1.'.valueID,
                '.$table1.'.课程名称,
                '.$table1.'.任课教师,
                '.$table1.'.课程属性,
				'.$table1.'.上课班级,
				'.$table1.'.章节目录,
				'.$table1.'.`督导姓名`,
				'.$table1.'.督导id,
				'.$table1.'.`听课时间`,
				'.$table1.'.`上课地点`,
				'.$table1.'.`听课节次`,
				'.$table1.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table1.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table1.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table1.'.`任课教师`
            and T2.lesson_class= '.$table1.'.`上课班级`
            WHERE
            '.$table1.'.`评价状态` like "待提交%"
            and '.$table1.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr1);$l++)
            array_push($data,$DataArr1[$l]);

        $DataArr2 = DB::select('SELECT distinct '.$table2.'.valueID, '.$table2.'.课程名称, '.$table2.'.任课教师, '.$table2.'.课程属性,
				'.$table2.'.上课班级,
				'.$table2.'.章节目录,
                '.$table2.'.督导id,
				'.$table2.'.`督导姓名`,
				'.$table2.'.`听课时间`,
				'.$table2.'.`上课地点`,
				'.$table2.'.`听课节次`,
				'.$table2.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table2.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table2.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table2.'.`任课教师`
            and T2.lesson_class= '.$table2.'.`上课班级`
            WHERE
            '.$table2.'.`评价状态` like "待提交%"
            and '.$table2.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr2);$l++)
            array_push($data,$DataArr2[$l]);

        $DataArr3 = DB::select('SELECT distinct '.$table3.'.valueID, '.$table3.'.课程名称, '.$table3.'.任课教师, '.$table3.'.课程属性,
				'.$table3.'.上课班级,
				'.$table3.'.章节目录,
				'.$table3.'.督导id,
				'.$table3.'.`督导姓名`,
				'.$table3.'.`听课时间`,
				'.$table3.'.`上课地点`,
				'.$table3.'.`听课节次`,
				'.$table3.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table3.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table3.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table3.'.`任课教师`
            and T2.lesson_class= '.$table3.'.`上课班级`
            WHERE
            '.$table3.'.`评价状态` like "待提交%"
            and '.$table3.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr3);$l++)
            array_push($data,$DataArr3[$l]);

        return json_encode($data);
    }

    //院级督导完成听课情况 简介
    public function GetUnitEverySaved(Request $request)
    {


        $mytime = new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        $year = $year1."-".$year2;
        $semester = $request->semester;

        $unit = $request->unit;

//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $group='信息学院';


        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);

        $TableName = $mytime->GetCurrentTableName($TableFlag);
        $data = array();

        $table1 = "front_theory_evaluation".$TableName;
        $table2 = "front_practice_evaluation".$TableName;
        $table3 = "front_physical_evaluation".$TableName;
//光知道停了哪门课是没有用的，在这里需要定位到具体时间的课程
        $DataArr1 = DB::select('SELECT distinct
                '.$table1.'.valueID,
                '.$table1.'.课程名称,
                '.$table1.'.任课教师,
                '.$table1.'.课程属性,
				'.$table1.'.上课班级,
				'.$table1.'.章节目录,
				'.$table1.'.督导id,
				'.$table1.'.`督导姓名`,
				'.$table1.'.`听课时间`,
				'.$table1.'.`上课地点`,
				'.$table1.'.`听课节次`,
				'.$table1.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table1.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table1.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table1.'.`任课教师`
            and T2.lesson_class= '.$table1.'.`上课班级`
            WHERE(
	            '.$table1.'.`课程名称`= ANY (select lesson_name from lessons where lessons.lesson_unit="'.$unit.'")

            )and '.$table1.'.`评价状态` like "待提交%"
            and '.$table1.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr1);$l++)
            array_push($data,$DataArr1[$l]);
        $DataArr2 = DB::select('SELECT distinct '.$table2.'.valueID, '.$table2.'.课程名称, '.$table2.'.任课教师, '.$table2.'.课程属性,
				'.$table2.'.上课班级,
				'.$table2.'.章节目录,
				'.$table2.'.督导id,
				'.$table2.'.`督导姓名`,
				'.$table2.'.`听课时间`,
				'.$table2.'.`上课地点`,
				'.$table2.'.`听课节次`,
				'.$table2.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table2.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table2.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table2.'.`任课教师`
            and T2.lesson_class= '.$table2.'.`上课班级`
            WHERE(
	            '.$table2.'.`课程名称`= ANY (select lesson_name from lessons where lessons.lesson_unit="'.$unit.'")

            )and '.$table2.'.`评价状态` like "待提交%"
            and '.$table2.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr2);$l++)
            array_push($data,$DataArr2[$l]);

        $DataArr3 = DB::select('SELECT distinct '.$table3.'.valueID, '.$table3.'.课程名称, '.$table3.'.任课教师, '.$table3.'.课程属性,
				'.$table3.'.上课班级,
				'.$table3.'.章节目录,
				'.$table3.'.督导id,
				'.$table3.'.`督导姓名`,
				'.$table3.'.`听课时间`,
				'.$table3.'.`上课地点`,
				'.$table3.'.`听课节次`,
				'.$table3.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table3.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table3.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table3.'.`任课教师`
            and T2.lesson_class= '.$table3.'.`上课班级`
            WHERE(
	            '.$table3.'.`课程名称`= ANY (select lesson_name from lessons where lessons.lesson_unit="'.$unit.'")

            )and '.$table3.'.`评价状态` like "待提交%"
            and '.$table3.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr3);$l++)
            array_push($data,$DataArr3[$l]);

        return json_encode($data);
    }

    //小组 完成听课情况 简介
    public function GetGroupEverySaved(Request $request)
    {


        $mytime = new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        $year = $year1."-".$year2;
        $semester = $request->semester;

        $group = $request->group;

//        $year='2016-2017';
//        $semester=array(0=>'1');
//        $group='信息学院';


        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        //学年学期 得到 该学年学期所属的时间段
        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);

        $TableName = $mytime->GetCurrentTableName($TableFlag);
        $data = array();

        $table1 = "front_theory_evaluation".$TableName;
        $table2 = "front_practice_evaluation".$TableName;
        $table3 = "front_physical_evaluation".$TableName;
//光知道停了哪门课是没有用的，在这里需要定位到具体时间的课程
        $DataArr1 = DB::select('SELECT distinct
                '.$table1.'.valueID,
                '.$table1.'.课程名称,
                '.$table1.'.任课教师,
                '.$table1.'.课程属性,
				'.$table1.'.上课班级,
				'.$table1.'.章节目录,
				'.$table1.'.督导id,
				'.$table1.'.`督导姓名`,
				'.$table1.'.`听课时间`,
				'.$table1.'.`上课地点`,
				'.$table1.'.`听课节次`,
				'.$table1.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table1.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table1.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table1.'.`任课教师`
            and T2.lesson_class= '.$table1.'.`上课班级`
            WHERE(
	            '.$table1.'.`督导id`= ANY (select user_id from users where users.`group`="'.$group.'")

            )and '.$table1.'.`评价状态` like "待提交%"
            and '.$table1.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr1);$l++)
            array_push($data,$DataArr1[$l]);
        $DataArr2 = DB::select('SELECT distinct '.$table2.'.valueID, '.$table2.'.课程名称, '.$table2.'.任课教师, '.$table2.'.课程属性,
				'.$table2.'.上课班级,
				'.$table2.'.章节目录,
				'.$table2.'.督导id,
				'.$table2.'.`督导姓名`,
				'.$table2.'.`听课时间`,
				'.$table2.'.`上课地点`,
				'.$table2.'.`听课节次`,
				'.$table2.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table2.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table2.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table2.'.`任课教师`
            and T2.lesson_class= '.$table2.'.`上课班级`
            WHERE(
	            '.$table2.'.`督导id`= ANY (select user_id from users where users.`group`="'.$group.'")

            )and '.$table2.'.`评价状态` like "待提交%"
            and '.$table2.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr2);$l++)
            array_push($data,$DataArr2[$l]);

        $DataArr3 = DB::select('SELECT distinct '.$table3.'.valueID, '.$table3.'.课程名称, '.$table3.'.任课教师, '.$table3.'.课程属性,
				'.$table3.'.上课班级,
				'.$table3.'.章节目录,
				'.$table3.'.督导id,
				'.$table3.'.`督导姓名`,
				'.$table3.'.`听课时间`,
				'.$table3.'.`上课地点`,
				'.$table3.'.`听课节次`,
				'.$table3.'.`填表时间`,
	            T2.lesson_level,
	            T2.assign_group
            FROM '.$table3.'
            LEFT JOIN (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON '.$table3.'.课程名称=T2.lesson_name
            and T2.lesson_teacher_name = '.$table3.'.`任课教师`
            and T2.lesson_class= '.$table3.'.`上课班级`
            WHERE(
	            '.$table3.'.`督导id`= ANY (select user_id from users where users.`group`="'.$group.'")

            )and '.$table3.'.`评价状态` like "待提交%"
            and '.$table3.'.`听课时间` BETWEEN"'.$YearSemesterTime['time1'].'" and "'.$YearSemesterTime['time2'].'"');
        for ($l=0;$l<count($DataArr3);$l++)
            array_push($data,$DataArr3[$l]);

        return json_encode($data);
    }


    //校级每个督导完成听课情况  这是按照督导姓名查询的路由
    public function GetEverySaved(Request $request)
    {
        $Content =new HelpController;

        $year1 = $request->year1;
        $year2 = $request->year2;
        //test code :
//        $year1='2016';
//        $year2='2017';
        $year = $year1."-".$year2;
        $semester = $request->semester;

//通过学年学期转化为具体日期 2016-2017-1  =》  date1：2016-09 date2:2017-03
        $mydate=$Content->GetTimeByYearSemester($year1.'-'.$year2.'-'.$semester[0]);
//        $semester = array('0'=>'1');

        $supervisor = $request->supervisor;
        $TableFlag = $year."-".$semester[0];//使用表版本的标识
//        Log::write('info',$TableFlag);
        //test code :
//        $TableFlag='2016-2017-1';
//        $supervisor='19490202';

        $TableName = $Content->GetCurrentTableName($TableFlag);
//        Log::write('info',$TableName);

        if($TableName == -1)
            return -1;
        else
        {
            $table1 = "front_theory_evaluation".$TableName;
            $table2 = "front_practice_evaluation".$TableName;
            $table3 = "front_physical_evaluation".$TableName;

            $DataArr1 = DB::select('select DISTINCT * from '.$table1.'
            left JOIN
            (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON
            T2.lesson_name = '.$table1.'.`课程名称`
            and T2.lesson_teacher_name = '.$table1.'.`任课教师`
            and T2.lesson_class= '.$table1.'.`上课班级`
            where '.$table1.'.`督导id`="'.$supervisor.'"AND '.$table1.'.`评价状态` like "待提交%"
            AND '.$table1.'.`听课时间`BETWEEN "'.$mydate['time1'].'" and "'.$mydate['time2'].'"');

            $DataArr2 = DB::select('select DISTINCT * from '.$table2.'
            left JOIN
            (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON
            T2.lesson_name = '.$table2.'.`课程名称`
            and T2.lesson_teacher_name = '.$table2.'.`任课教师`
            and T2.lesson_class= '.$table2.'.`上课班级`
            where '.$table2.'.`督导id`="'.$supervisor.'" AND '.$table2.'.`评价状态` like "待提交%"
            AND '.$table2.'.`听课时间`BETWEEN "'.$mydate['time1'].'" and "'.$mydate['time2'].'"');

            $DataArr3 = DB::select('select DISTINCT * from '.$table3.'
            left JOIN
            (select lesson_name,lesson_teacher_name,lesson_level,assign_group,lesson_class from lessons
            where lessons.lesson_year="'.$year.'" and lessons.lesson_semester="'.$semester[0].'") AS T2
            ON
            T2.lesson_name = '.$table3.'.`课程名称`
            and T2.lesson_teacher_name = '.$table3.'.`任课教师`
            and T2.lesson_class= '.$table3.'.`上课班级`
            where '.$table3.'.`督导id`="'.$supervisor.'" AND '.$table3.'.`评价状态` like "待提交%"
            AND '.$table3.'.`听课时间`BETWEEN "'.$mydate['time1'].'" and "'.$mydate['time2'].'"');

            $DataArr = [];
            for ($l=0;$l<count($DataArr1);$l++)
            {
                $DataArr1[$l]->tableName=$table1;
                array_push($DataArr,$DataArr1[$l]);
            }
            for ($l=0;$l<count($DataArr2);$l++)
            {
                $DataArr2[$l]->tableName=$table2;
                array_push($DataArr,$DataArr2[$l]);

            }
            for ($l=0;$l<count($DataArr3);$l++)
            {
                $DataArr3[$l]->tableName=$table3;
                array_push($DataArr,$DataArr3[$l]);

            }
//dd($DataArr);
            if ($DataArr==null)
                return -1;
            else{
//                file_put_contents("data/EveryEvaluated.json",json_encode($DataArr));
                return json_encode($DataArr);
            }

        }

    }

    //统计表中的提交按钮
    public function SubmitEvaluationContent(Request $request)
    {
        $Help=new HelpController;
        $valueID = $request->valueID;
        $year1 = $request->year1;
        $year2 = $request->year2;
        //test code :
//        $year1='2016';
//        $year2='2017';
        $year = $year1."-".$year2;
        $semester = $request->semester;
        $lessonname = $request->lessonname;
        $lessonteacher = $request->lessonteacher;
        $lessonvaluetime = $request->lessonvaluetime;//听课时间
        $LessonTime = $request->LessonTime;//听课节次
        $supervisorId = $request->LessonSupervisorID;
//        $superID='8';
        //1确定课程属于哪张表
        //得到学年学期
        $TimeData = $Help->GetYearSemester($lessonvaluetime);
        //表的版本号
        $TableName = $Help->GetCurrentTableName2($lessonname,$lessonteacher,$lessonvaluetime,$LessonTime,$supervisorId,$TimeData['YearSemester']);

//Log::write('info',$valueID);Log::write('info',$year);
//        Log::write('info',$semester[0]);Log::write('info',$lessonname);
//        Log::write('info',$lessonteacher);Log::write('info',$lessonvaluetime);

        $sure= DB::table($TableName[0])
            ->where('valueID','=',$valueID)
            ->select('评价状态')
            ->get();
        Log::write('info',$sure);
//        若该表中必填字段未完成，则不允许提交
        if ($sure[0]->评价状态 == '待提交1')
            return '评价表未完成';
        else//必填字段完成后，更新lesson表和评价表的正面和反面
        {
            //        设置lessons 表中的课程状态字段
            DB::table('lessons')
                ->where('lesson_name','=',$lessonname)
                ->where('lesson_teacher_name','=',$lessonteacher)
                ->where('lesson_year','=',$year)
                ->where('lesson_semester','=',$semester[0])
                ->update([
                    'lesson_state'=>'已完成',
                ]);

            $flag1= DB::table($TableName[0])
                ->where('valueID','=',$valueID)
                ->update([
                    '评价状态'=>'已完成',
                ]);
            $flag2= DB::table($TableName[1])
                ->where('valueID','=',$valueID)
                ->update([
                    '评价状态'=>'已完成',
                ]);
            if ($flag1 && $flag2)
                return "提交成功";
            else
                return "提交失败";
        }

    }

    public function ResetEvaluationContent(Request $request)
    {
        $year1 = $request->year1;
        $year2 = $request->year2;

        $year = $year1."-".$year2;
        $semester = $request->semester;
        $supervisor = $request->supervisor;
        $lessonname = $request->lessonname;
        $lessonteacher = $request->lessonteacher;
        $lessontime = $request->lessontime;//听课节次
        $lessonvaluetime = $request->lessonvaluetime;//听课时间

        $Tableflag = $year.'-'.$semester[0];
        //test code :
//        $Tableflag = '2016-2017-1';

        //1、确定版本以及该课程所在表
        $version = new HelpController;
        $TableName = $version->GetCurrentTableName2($lessonname,$lessonteacher,$lessonvaluetime,$lessontime,$supervisor,$Tableflag);
        //2、统计个数
        $count = DB::table($TableName[0])->select("督导id")
            ->where('任课教师','=',$lessonteacher)
            ->where('课程名称','=',$lessonname)
            ->where('评价状态','=','已完成')
            ->count();
        Log::write('info',$count);

        if($count == 1 )//课程表中只有一个督导对其评价，则更改lesson表为待提交
        {
            DB::table('lessons')
                ->where('lesson_teacher_name','=',$lessonteacher)
                ->where('lesson_name','=',$lessonname)
                ->where('lesson_year','=',$year)
                ->where('lesson_semester','=',$semester[0])
                ->update([
                    'lesson_state'=>'待提交'
                ]);
        }
        $flag = DB::table($TableName[0])
            ->where('任课教师','=',$lessonteacher)
            ->where('课程名称','=',$lessonname)
            ->where('听课节次','=',$lessontime)
            ->where('督导id','=',$supervisor)
            ->where('听课时间','=',$lessonvaluetime)
            ->update([
                '评价状态'=>'待提交'
            ]);
        $flag = DB::table($TableName[1])
            ->where('任课教师','=',$lessonteacher)
            ->where('课程名称','=',$lessonname)
            ->where('听课节次','=',$lessontime)
            ->where('督导id','=',$supervisor)
            ->where('听课时间','=',$lessonvaluetime)
            ->update([
                '评价状态'=>'待提交'
            ]);
//        Log::write('info',$flag);
        return $flag;
    }

    public function DelEvaluationContent(Request $request)
    {
        $Help=new HelpController;
        $valueID = $request->valueID;
        $year1 = $request->year1;
        $year2 = $request->year2;
        //test code :
//        $year1='2016';
//        $year2='2017';
        $year = $year1."-".$year2;
        $semester = $request->semester;
        $lessonname = $request->lessonname;
        $LessonSupervisorID = $request->LessonSupervisorID;
        $LessonTime = $request->LessonTime;
        $lessonteacher = $request->lessonteacher;
        $lessonvaluetime = $request->lessonvaluetime;
//        $superID='8';
        //1确定课程属于哪张表
        //得到学年学期
        $TimeData = $Help->GetYearSemester($lessonvaluetime);
        //表的版本号
        $TableName = $Help->GetCurrentTableName2($lessonname,$lessonteacher,$lessonvaluetime,$LessonTime,$LessonSupervisorID,$TimeData['YearSemester']);

        $sure= DB::table($TableName[0])
            ->where('valueID','=',$valueID)
            ->select('评价状态')
            ->get();
//        Log::write('info',$sure[0]->评价状态);
//        若该表中必填字段未完成，则不允许提交
        if ($sure[0]->评价状态 == '待提交1' ||$sure[0]->评价状态 == '待提交')
        {
            //需要计算$TableName[0]中的数量，改变lessons状态
            $LessonNum = DB::table($TableName[0])
                ->where('课程名称','=',$lessonname)
                ->where('任课教师','=',$lessonteacher)
                ->count('督导姓名');
            if ($LessonNum<=1)
            {
                DB::table('lessons')
                    ->where('lesson_teacher_name','=',$lessonteacher)
                    ->where('lesson_name','=',$lessonname)
                    ->where('lesson_year','=',$year)
                    ->where('lesson_semester','=',$semester[0])
                    ->update([
                        'lesson_state'=>'未完成'
                    ]);
            }

            $flag1= DB::table($TableName[0])
                ->where('valueID','=',$valueID)
                ->delete();
            $flag2= DB::table($TableName[1])
                ->where('valueID','=',$valueID)
                ->delete();
            if ($flag1 && $flag2)
                return "撤销成功";
            else
                return "撤销失败";
        }
        else//评价状态为已提交
        {
            return "已提交的评价表不允许撤销";
        }

    }
}
