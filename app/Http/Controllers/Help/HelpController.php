<?php

namespace App\Http\Controllers\Help;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    public function GetYearSemester($LessonTime)
    {
        //LessonTime：2016-01-01
        $arr = explode('-', $LessonTime);

        $year=$arr[0];
        $Month=$arr[1];
        if ($Month < '03' || $Month >='08')
        {
            $semester = 1;

            if ($Month<'03')
            {
                $year1=intval($year)-1;
                $year2 =$year;
            }
            if($Month>='08')
            {
                $year1=$year;
                $year2 =intval($year)+1;
            }

        }
        if ($Month >= '03' && $Month<'08')
        {
            $year1=intval($year)-1;
            $year2 =$year;
            $semester = 2;

        }
        $YearSemester=$year1.'-'.$year2.'-'.$semester;
        $Year = $year1.'-'.$year2;
        $Semester = $semester;

        return $data=[
            'YearSemester'=>$YearSemester,
            'Year'=>$Year,
            'Semester'=>$Semester
        ];

    }

    public function GetTimeByYearSemester($YearSemester)
    {
        //YearSemester：2016-2017-1
        $arr = explode('-', $YearSemester);

        $year1=$arr[0];
        $year2=$arr[1];
        $Semester=$arr[2];
        if ($Semester=='1')
        {
            $time1 = $year1.'-08';
            $time2 = $year2.'-03';
        }
        else{
            $time1 = $year2.'-03';
            $time2 = $year2.'-08';
        }
        return $data=[
            'time1'=>$time1,
            'time2'=>$time2
        ];
    }


    //当前关注课程的属性：理论、实践、体育？返回该种类的表（只知道课程名称以及授课教师，用于评价内容的精确查找）
    public function GetCurrentTableName2($lesson_name,$lesson_teacher,$lesson_date,$lesson_time,$supervisorId,$TableFlag)
    {

        $CurrentTable = $this->GetCurrentTableName($TableFlag);//确定使用哪个版本
        $table1 = "front_theory_evaluation".$CurrentTable;
        $table2 = "front_practice_evaluation".$CurrentTable;
        $table3 = "front_physical_evaluation".$CurrentTable;
        $table4 = "back_theory_evaluation".$CurrentTable;
        $table5 = "back_practice_evaluation".$CurrentTable;
        $table6 = "back_physical_evaluation".$CurrentTable;

        $record1 = DB::table($table1)
            ->where('课程名称','=',$lesson_name)
            ->where('听课时间','=',$lesson_date)
            ->where('听课节次','=',$lesson_time)
            ->where('督导id','=',$supervisorId)
            ->where('任课教师','=',$lesson_teacher)->get();

        $record2 = DB::table($table2)
            ->where('课程名称','=',$lesson_name)
            ->where('听课时间','=',$lesson_date)
            ->where('听课节次','=',$lesson_time)
            ->where('督导id','=',$supervisorId)
            ->where('任课教师','=',$lesson_teacher)->get();
        if ($record1 != null)
        {
            return $data =[
                '0'=>$table1,
                '1'=>$table4
            ];
        }else if ($record2 != null )
        {
            return $data =[
                '0'=>$table2,
                '1'=>$table5
            ];
        }else
        {
            return $data =[
                '0'=>$table3,
                '1'=>$table6
            ];
        }
    }
    //当前关注课程的属性：理论、实践、体育？返回该种类的表（只知道课程名称以及授课教师，用于概览表格中）
    public function GetCurrentTableName1($lesson_name,$lesson_teacher,$TableFlag)
    {

        $CurrentTable = $this->GetCurrentTableName($TableFlag);//确定使用哪个版本
        $table1 = "front_theory_evaluation".$CurrentTable;
        $table2 = "front_practice_evaluation".$CurrentTable;
        $table3 = "front_physical_evaluation".$CurrentTable;
        $table4 = "back_theory_evaluation".$CurrentTable;
        $table5 = "back_practice_evaluation".$CurrentTable;
        $table6 = "back_physical_evaluation".$CurrentTable;

        $record1 = DB::table($table1)
            ->where('课程名称','=',$lesson_name)
            ->where('任课教师','=',$lesson_teacher)->get();

        $record2 = DB::table($table2)
            ->where('课程名称','=',$lesson_name)
            ->where('任课教师','=',$lesson_teacher)->get();
        if ($record1 != null)
        {
            return $data =[
                '0'=>$table1,
                '1'=>$table4
            ];
        }else if ($record2 != null )
        {
            return $data =[
                '0'=>$table2,
                '1'=>$table5
            ];
        }else
        {
            return $data =[
                '0'=>$table3,
                '1'=>$table6
            ];
        }
    }

    //获取当前使用评价表的后缀名  版本号$TableFlag='2016-2017-1'
    public function GetCurrentTableName($TableFlag)
    {
        $CreateTime = DB::table("Evaluation_Migration")->where("Create_Year",'=',$TableFlag)->get();
        if( $CreateTime!=null)
            return $CreateTime[0]->Table_Name;
        else{
            return -1;
        }
    }
    //获取数据lesson表中学院的名称
    public function UnitName()
    {
//        $UnitArr = DB::select("select DISTINCT lesson_teacher_unit from lessons");
        $UnitArr = DB::table('lessons')->select('lesson_unit')
            ->distinct()->get();
        return json_encode($UnitArr);
    }

    //每个组对应的负责学院
    public function GetUnitByGroup($group)
    {
        $UnitArr = DB::table('users')->select('unit')
            ->where('group','=',$group)
            ->distinct()
            ->get();
        return $UnitArr;
    }

    //获取数据库列数
    public function GetDBColumnsNUm($DBTableName)
    {

        $num = DB::select('select count(*) as a from information_schema.columns where table_schema=\'tets\' and table_name="'.$DBTableName.'";');
//        $num = DB::select('select count(*) as a from information_schema.columns where table_schema=\'tets\' and table_name=\'back_theory_evaluation_2016_08\' ;');
        return $num[0]->a;
    }

    //通过数字 计算学期数
    protected function CaculateTerm_Num($startTerm,$Num)
    {
        $year1 = strval(explode('-',$startTerm)[0]);
        $year2 = strval(explode('-',$startTerm)[1]);
        $term = strval(explode('-',$startTerm)[2]);
//Log::write('info',$year1);
//        Log::write('info',$year2);
//
//        Log::write('info',$term);

        $termArr = [];
        array_push($termArr,$startTerm);
        for ($i=1;$i<$Num;$i++)
        {
            if($term==1)
            {
                $term=2;
                $NewYearTerm = $year1.'-'.$year2.'-'.$term;
                array_push($termArr,$NewYearTerm);
                continue;
            }
            if($term==2)
            {
                $term=1;
                $year1=$year1+1;
                $year2=$year2+1;
                $NewYearTerm = $year1.'-'.$year2.'-'.$term;
                array_push($termArr,$NewYearTerm);
                continue;
            }
        }
        return $termArr;
    }

    //计算学期数
    protected function CaculateTerm_EndTime($startTerm,$endTime)
    {
        $startyear1 = strval(explode('-',$startTerm)[0]);
        $startyear2 = strval(explode('-',$startTerm)[1]);
        $startterm = strval(explode('-',$startTerm)[2]);
        $endyear1 = strval(explode('-',$endTime)[0]);
        $endyear2 = strval(explode('-',$endTime)[1]);
        $endterm = strval(explode('-',$endTime)[2]);

       $num = intval($endyear1)- intval($startyear1) + intval($endyear2) - intval($startyear2) + intval($endterm)-intval($startterm)+1;
        return $this->CaculateTerm_Num($startTerm, $num);
    }

    //通过开始、结束学期计算学期数
    public function CaculateTerm()
    {
        $numargs  = func_num_args();
        $varArray = func_get_args();
//        required value:
        $startTerm = $varArray[0];
        //判断第二个参数是数字还是字符串
        if ($numargs == 2 && is_integer($varArray[1]))
        {
            $num = $varArray[1];
            return $this->CaculateTerm_Num($startTerm,$num);
        }
        elseif ($numargs == 2 && is_string($varArray[1])){
            $endTime = $varArray[1];
            return $this->CaculateTerm_EndTime($startTerm,$endTime);
        }
    }



    //获取理论\s实践课课程每门课程的听课节次
    public function GetLessonTime(Request $request)
    {
        $lesson_name = $request->Lesson_name;
        $Teacher = $request->Teacher;
        $Class = $request->Class;
        $Room = $request->Room;


        $time = date("Y-m-d");
        $ys = $this->GetYearSemester($time);
        $data = DB::table('lessons')->select('lesson_time','lesson_weekday')
            ->where('lesson_year','=',$ys['Year'])
            ->where('lesson_semester','=',$ys['Semester'])
            ->where('lesson_teacher_name','=',$Teacher)
            ->where('lesson_room','=',$Room)
            ->where('lesson_class','=',$Class)
            ->where('lesson_name','=',$lesson_name)->get();
        if($data==null)//可能是实践课，他自己填的教室
        {
            $data = DB::table('lessons')->select('lesson_time','lesson_weekday')
                ->where('lesson_year','=',$ys['Year'])
                ->where('lesson_semester','=',$ys['Semester'])
                ->where('lesson_teacher_name','=',$Teacher)
                ->where('lesson_room','=',$Room)
                ->where('lesson_name','=',$lesson_name)->get();
        }
        return $data;
    }


    //获取理论\s实践课课程每门课程的听课节次
    public function GetLessonTimeBylistendate(Request $request)
    {
        $lesson_name = $request->Lesson_name;
        $Teacher = $request->Teacher;
        $Class = $request->Class;
        $Room = $request->Room;
        $lesson_weekday = $request->LessonWeekday;

        $time = date("Y-m-d");
        $ys = $this->GetYearSemester($time);
        $data = DB::table('lessons')->select('lesson_time')
            ->where('lesson_year','=',$ys['Year'])
            ->where('lesson_semester','=',$ys['Semester'])
            ->where('lesson_teacher_name','=',$Teacher)
            ->where('lesson_room','=',$Room)
            ->where('lesson_weekday','=',$lesson_weekday)
            ->where('lesson_class','=',$Class)
            ->where('lesson_name','=',$lesson_name)->get();
        if($data==null)//可能是实践课，他自己填的教室
        {
            $data = DB::table('lessons')->select('lesson_time')
                ->where('lesson_year','=',$ys['Year'])
                ->where('lesson_semester','=',$ys['Semester'])
                ->where('lesson_teacher_name','=',$Teacher)
                ->where('lesson_weekday','=',$lesson_weekday)
                ->where('lesson_room','=',$Room)
                ->where('lesson_name','=',$lesson_name)->get();
        }
        return $data;
    }

    //拆字段，统计督导听课节次中，可能有1-3节的情况
    public function Evalued_LessonTimes($table,$superID,$lesson_state,$timeInterval)
    {
        //  $two_num统计听两节课的数量
        //  $three_num统计听三节课的数量
        //  $four_num统计听四节课的数量
        $num = 0;
        $one_num = 0;
        $two_num = 0;
        $three_num = 0;
        $four_num = 0;
//        $table='front_theory_evaluation_2016_08';
//        $superID='19490202';
        $data = DB::table($table)
            ->select('听课节次')
            ->where('督导id','=',$superID)
            ->where('听课节次','like','%-%')
            ->where('评价状态','like',$lesson_state)
            ->whereBetween('听课时间', [$timeInterval['time1'], $timeInterval['time2']])
            ->get();
        for ($i=0;$i<count($data);$i++)
        {
            preg_match_all('/\d+/',$data[$i]->听课节次,$arr);
            $num = $num+($arr[0][1]-$arr[0][0]);

            if ($arr[0][1]-$arr[0][0] == 1)
            {
                $two_num ++;
            }else if ($arr[0][1]-$arr[0][0] == 2)
            {
                $three_num ++;
            }else if ($arr[0][1]-$arr[0][0] == 3)
            {
                $four_num ++;
            }
        }
        return $data =[
            'num' => $num,
            'one' => $one_num,
            'two' => $two_num,
            'three' => $three_num,
            'four' => $four_num,
        ];

    }
//合并角色
    public function UnionRole($record)
    {
        for($i=0;$i<count($record);$i++)
        {
            for($j=$i+1;$j<count($record);$j++)
            {
                if ($record[$i]->user_id == $record[$j]->user_id
                    && $record[$i]->supervise_time == $record[$j]->supervise_time )
                {
                    if($record[$j]->level == '教师')
                    {
                        array_splice($record,$j,1);
                        $j--;
                    }
                    else{
                        $record[$i]->level = $record[$i]->level.'/'.$record[$j]->level;
                        array_splice($record,$j,1);
                        $j--;
                    }

                }
            }
        }
        return $record;
    }

    //通过任职时间段计算学期数
    //2016-01
    //2017-07
//    public function GetTermBySuperviseTime($StartTime,$EndTime)
    public function GetTermBySuperviseTime()
    {
        $StartTime='2016-01';
        $EndTime='2018-07';

        $StartTerm = $this->GetYearSemester($StartTime);
        $EndTerm = $this->GetYearSemester($EndTime);
        $StartYear = explode('-',$StartTerm['Year'])[0];
        $EndYear = explode('-',$EndTerm['Year'])[0];
        dd();
    }


}


