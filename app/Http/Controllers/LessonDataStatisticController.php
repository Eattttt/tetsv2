<?php

namespace App\Http\Controllers;

use App\Model\back_content;
use App\Model\Role;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Help\HelpController;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\NullHandlerTest;
use PhpParser\Node\Expr\Array_;

class obj {
    public $level = '';
    public $num = 0;

    public function set_attribute($level_name, $num){
        $this->level = $level_name;
        $this->num = $num;
    }
}

class LessonDataStatisticController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['only',['TheoryStatistics','PracticeStatistic','PhysicalStatistic']]);
    }
    public function TheoryStatisticsView()
    {
        return view('TheoryStatistics');
    }

    public function PracticeStatisticsView()
    {
        return view('PracticeStatistics');
    }

    public function PhysicalStatisticsView()
    {
        return view('PhysicalStatistics');
    }

    public function TheoryStatisticsData(Request $request)
    {
        $value1 = $request->value1;
        $value2 = $request->value2;
        $value3 = $request->value3;
        $value4 = $request->value4;
        $value5 = $request->value5;


        $mytime = new HelpController;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;

        $unit = $request->unit;
        $group = $request->group;

        $year = $year1."-".$year2;
        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        //学年学期 得到 该学年学期所属的时间段

        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);
//Log::info($YearSemesterTime);
        $TableName = $mytime->GetCurrentTableName($TableFlag);

        $table1 = "front_theory_evaluation".$TableName;
        $table = "front_contents".$TableName;

        $Comparison = ['明显不足','不足','正常','满意','非常满意'];

        //授课总体评价统计图
        $data = array();

        if($unit == null && $group == null)
        {
            $sql= 'SELECT `授课总体评价` as \'level\',COUNT(授课总体评价),
                ROUND(COUNT(*)/(SELECT COUNT(*) FROM '.$table1.' WHERE 评价状态 = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'")*100,2) as \'num\'
                FROM '.$table1.'
                WHERE `评价状态` = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                GROUP BY `授课总体评价`';
//            Log::info($sql);
        }

        elseif($unit!=null && $group==null)
        {
            $sql= 'SELECT T.`授课总体评价` as \'level\',COUNT(T.`授课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table1.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table1.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`授课总体评价`';
        }
        elseif($unit==null && $group!=null)
        {
            $sql= 'SELECT T.`授课总体评价` as \'level\',COUNT(T.`授课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table1.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table1.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`授课总体评价`';
        }
        $DataArrTeaching = DB::select($sql);

        for($i=0; $i<count($DataArrTeaching); $i++)
        {
            array_push($data,$DataArrTeaching[$i]->level);
        }
        $data = array_diff($Comparison,$data);

        if(count($data)!=0)
        {
            foreach($data as $item)
            {
                $Evaluation_Item_Obj = new Obj();
                $Evaluation_Item_Obj->set_attribute($item, 0);
                array_push($DataArrTeaching,$Evaluation_Item_Obj);
            }
        }



        //听课总体评价统计图
        $data = array();
        if($unit == null && $group == null)
        {
            $sql = 'SELECT `听课总体评价`  as \'level\',COUNT(听课总体评价),
                ROUND(COUNT(*)/(SELECT COUNT(*) FROM '.$table1.' WHERE 评价状态 = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'")*100,2)AS\'num\'
                FROM '.$table1.'
                WHERE `评价状态` = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                GROUP BY `听课总体评价`';
        }
        elseif($unit!=null && $group==null)
        {
            $sql = 'SELECT T.`听课总体评价`as \'level\',COUNT(T.`听课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table1.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table1.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`听课总体评价`';
        }
        elseif($unit==null && $group!=null)
        {
            $sql = 'SELECT T.`听课总体评价` as \'level\',COUNT(T.`听课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table1.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table1.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`听课总体评价`';
        }

        $DataArrLearning = DB::select($sql);

        for($i=0; $i<count($DataArrLearning); $i++)
        {
            array_push($data,$DataArrLearning[$i]->level);
        }
        $data = array_diff($Comparison,$data);

        if(count($data)!=0)
        {
            foreach($data as $item)
            {
                $Evaluation_Item_Obj = new Obj();
                $Evaluation_Item_Obj->set_attribute($item, 0);
                array_push($DataArrLearning,$Evaluation_Item_Obj);
            }
        }



        //评价项目（细项）得分情况---按评价数量排 统计图
        $sql = 'SELECT d.text AS LEVEL4
                FROM '.$table.' AS a
                LEFT JOIN '.$table.' AS b ON b.fid = a.id
                LEFT JOIN '.$table.' AS c ON c.fid = b.id
                LEFT JOIN '.$table.' AS d ON d.fid = c.id
                WHERE a.text = \'理论课评价表\' AND b.text = \'教师授课情况\' AND d.text IS NOT NULL';

        $DataArr = DB::select($sql);

        $DataArrMinorByCount = array();
        for($j=0;$j<count($DataArr);$j++)
        {
            if($unit == null && $group == null)
            {
                $sql ='SELECT '.$DataArr[$j]->LEVEL4.' AS  level ,COUNT('.$DataArr[$j]->LEVEL4.') AS num
                    FROM '.$table1.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$j]->LEVEL4.' ';
            }
            elseif($unit!=null && $group==null)
            {
                $sql ='SELECT '.$DataArr[$j]->LEVEL4.' AS level, COUNT('.$DataArr[$j]->LEVEL4.') AS num
              FROM (SELECT DISTINCT valueID ,'.$DataArr[$j]->LEVEL4.'
              FROM '.$table1.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY '.$DataArr[$j]->LEVEL4.' ';
            }
            elseif($unit==null && $group!=null)
            {
                $sql ='SELECT '.$DataArr[$j]->LEVEL4.' AS level, COUNT('.$DataArr[$j]->LEVEL4.') AS num
              FROM (SELECT DISTINCT valueID ,'.$DataArr[$j]->LEVEL4.'
              FROM '.$table1.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY '.$DataArr[$j]->LEVEL4.' ';
            }

           // Log::info($sql);
            $DataArr6[$j] = DB::select($sql);

            $data = array();

            for($i=0; $i<count($DataArr6[$j]); $i++)
            {
                array_push($data,$DataArr6[$j][$i]->level);
            }
            $data = array_diff($Comparison,$data);
            // Log::info($data);
            if(count($data)!=0)
            {
                foreach($data as $item)
                {
                    $Evaluation_Item_Obj = new Obj();
                    $Evaluation_Item_Obj->set_attribute($item, 0);
                    array_push($DataArr6[$j],$Evaluation_Item_Obj);
                }
            }


            foreach($DataArr6[$j] as $item)
            {
                if(array_key_exists($item->level, $DataArrMinorByCount))
                {
                    array_push($DataArrMinorByCount[$item->level], $item->num);
                }
                else
                {
                    $DataArrMinorByCount[$item->level]= array();
                    array_push($DataArrMinorByCount[$item->level], $item->num);
                }
            }

        }

        //评价项目（大项）得分情况统计图

        $sql = 'SELECT c.text AS LEVEL3
            FROM '.$table.' AS a
            LEFT JOIN '.$table.' AS b ON b.fid = a.id
            LEFT JOIN '.$table.' AS c ON c.fid = b.id
            WHERE a.text = \'理论课评价表\' AND b.text = \'教师授课情况\' ';

        $DataArr9 = DB::select($sql);

        for($i=0; $i<count($DataArr9)-1; $i++)
        {
            $content[] = $DataArr9[$i]->LEVEL3;
        }

        for($i = 0;$i<count($content);$i++)
        {
            $sql = 'SELECT d.text AS LEVEL4
                FROM '.$table.' AS a
                LEFT JOIN '.$table.' AS b ON b.fid = a.id
                LEFT JOIN '.$table.' AS c ON c.fid = b.id
                LEFT JOIN '.$table.' AS d ON d.fid = c.id
                WHERE c.text = "'.$content[$i].'" AND a.text = \'理论课评价表\' ';

            $DataArr[$i] = DB::select($sql);

            if($unit == null && $group == null)
            {
                if(count($DataArr[$i])==1)
                {
                    $sql1 = $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as d
                    FROM '.$table1.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.'';
                }
                else
                {
                    $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as b
                    FROM '.$table1.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.' UNION ALL ';
                    for($j=1;$j<count($DataArr[$i]);$j++)
                    {

                        $sql = $sql.'SELECT '.$DataArr[$i][$j]->LEVEL4.' ,COUNT('.$DataArr[$i][$j]->LEVEL4.')
                    FROM '.$table1.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i][$j]->LEVEL4.'';
                        if($j<count($DataArr[$i])-1)
                        {
                            $sql = $sql.' UNION ALL ';
                        }
                    }
                    $sql1 = 'SELECT a , sum(c.b) as d from( '
                        . $sql .
                        ') as c GROUP BY a ';
                }
            }
            elseif($unit!=null && $group==null)
            {
                if(count($DataArr[$i])==1)
                {
                    $sql1 = $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as d
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table1.' AS X
                    LEFT JOIN lessons
                    ON X.`任课教师` = lesson_teacher_name AND X.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.'';
                }
                else
                {
                    $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as b
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table1.' AS X
                    LEFT JOIN lessons
                    ON X.`任课教师` = lesson_teacher_name AND X.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.' UNION ALL ';
                    for($j=1;$j<count($DataArr[$i]);$j++)
                    {

                        $sql = $sql.'SELECT '.$DataArr[$i][$j]->LEVEL4.' ,COUNT('.$DataArr[$i][$j]->LEVEL4.')
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][$j]->LEVEL4.'
                    FROM '.$table1.' AS X
                    LEFT JOIN lessons
                    ON X.`任课教师` = lesson_teacher_name AND X.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][$j]->LEVEL4.'';
                        if($j<count($DataArr[$i])-1)
                        {
                            $sql = $sql.' UNION ALL ';
                        }
                    }
                    $sql1 = 'SELECT a , sum(c.b) as d from( '
                        . $sql .
                        ') as c GROUP BY a ';
                }
            }
            elseif($unit==null && $group!=null)
            {
                if(count($DataArr[$i])==1)
                {
                    $sql1 = $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as d
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table1.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.'';
                }
                else
                {
                    $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as b
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table1.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.' UNION ALL ';
                    for($j=1;$j<count($DataArr[$i]);$j++)
                    {

                        $sql = $sql.'SELECT '.$DataArr[$i][$j]->LEVEL4.' ,COUNT('.$DataArr[$i][$j]->LEVEL4.')
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][$j]->LEVEL4.'
                    FROM '.$table1.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][$j]->LEVEL4.'';
                        if($j<count($DataArr[$i])-1)
                        {
                            $sql = $sql.' UNION ALL ';
                        }
                    }
                    $sql1 = 'SELECT a , sum(c.b) as d from( '
                        . $sql .
                        ') as c GROUP BY a ';
                }
            }
            $DataArr4[$i] = DB::select($sql1);

            $sumDataArr4 = '';
            $CountDataArr4 = '';

            for($k=0; $k<count($DataArr4[$i]);$k++)
            {
                $weight = '';
                switch($DataArr4[$i][$k]->a)
                {
                    case "非常满意":
                        $weight = $DataArr4[$i][$k]->d * $value1;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "满意":
                        $weight = $DataArr4[$i][$k]->d * $value2;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "正常":
                        $weight = $DataArr4[$i][$k]->d * $value3;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "不足":
                        $weight = $DataArr4[$i][$k]->d * $value4;

                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "明显不足":
                        $weight = $DataArr4[$i][$k]->d * $value5;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                }
                $sumDataArr4 += $weight;
                $CountDataArr4 += $sum;
            }
            if($CountDataArr4==0)
                $sumDataArr4 = 0;
            else
                $sumDataArr4 = round($sumDataArr4/$CountDataArr4 ,2);
            $DataArrMajorTerm[] = $sumDataArr4;
        }



        //评价项目（细项）得分情况---按平均分排统计图
        $sql = 'SELECT d.text AS LEVEL4
                FROM '.$table.' AS a
                LEFT JOIN '.$table.' AS b ON b.fid = a.id
                LEFT JOIN '.$table.' AS c ON c.fid = b.id
                LEFT JOIN '.$table.' AS d ON d.fid = c.id
                WHERE a.text = \'理论课评价表\' AND b.text = \'教师授课情况\' AND d.text IS NOT NULL';
        $DataArr = DB::select($sql);

        for($i=0;$i<count($DataArr);$i++)
        {
            if($unit == null && $group == null)
            {
                $sql ='SELECT '.$DataArr[$i]->LEVEL4.' AS  level ,COUNT('.$DataArr[$i]->LEVEL4.') AS num
                    FROM '.$table1.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i]->LEVEL4.' ';
            }
            elseif($unit!=null && $group==null)
            {
                $sql ='SELECT '.$DataArr[$i]->LEVEL4.' AS  level ,COUNT('.$DataArr[$i]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i]->LEVEL4.'
                    FROM '.$table1.' AS a
                    LEFT JOIN lessons
                    ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i]->LEVEL4.' ';
            }
            elseif($unit==null && $group!=null)
            {
                $sql ='SELECT '.$DataArr[$i]->LEVEL4.' AS  level ,COUNT('.$DataArr[$i]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i]->LEVEL4.'
                    FROM '.$table1.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i]->LEVEL4.' ';
            }
            $DataArr5 = DB::select($sql);

            $sumDataArr5 = '';
            $CountDataArr5 = '';

            for($j=0;$j<count($DataArr5) ;$j++)
            {
                $weight = '';
                $sum = '';
                switch($DataArr5[$j]->level)
                {
                    case "非常满意":
                        $weight = $DataArr5[$j]->num * $value1;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "满意":
                        $weight = $DataArr5[$j]->num * $value2;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "正常":
                        $weight = $DataArr5[$j]->num * $value3;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "不足":
                        $weight = $DataArr5[$j]->num * $value4;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "明显不足":
                        $weight = $DataArr5[$j]->num * $value5;
                        $sum = $DataArr5[$j]->num;
                        break;
                }

                $sumDataArr5 += $weight;
                $CountDataArr5 += $sum;
            }
            if($CountDataArr5==0)
                $sumDataArr5 = 0;
            else
                $sumDataArr5 = round($sumDataArr5/$CountDataArr5 , 2);
            $DataArrMinorByAVG[] = $sumDataArr5;

        }



        $data = [
            'ChartTeaching' => $DataArrTeaching,
            'ChartLearning' => $DataArrLearning,
            'ChartMajorTerm'=> $DataArrMajorTerm,
            'ChartMinorByAVG'=> $DataArrMinorByAVG,
            'ChartMinorByCount' => $DataArrMinorByCount,
            'ChartYAxis'=> $DataArr
        ];
//        Log::info($data['ChartMinorByAVG']);
        return ($data);

    }

    public function PracticeStatisticsData(Request $request)
    {
        $value1 = $request->value1;
        $value2 = $request->value2;
        $value3 = $request->value3;
        $value4 = $request->value4;
        $value5 = $request->value5;


        $mytime = new HelpController;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;

        $unit = $request->unit;
        $group = $request->group;

        $year = $year1."-".$year2;
        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        //学年学期 得到 该学年学期所属的时间段

        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);

        $TableName = $mytime->GetCurrentTableName($TableFlag);
//dd($TableName);

        $table2 = "front_practice_evaluation".$TableName;
        $table = "front_contents".$TableName;

        $Comparison = ['不足','明显不足','正常','满意','非常满意'];

        //授课总体评价统计图
        $data = array();
        if($unit == null && $group == null)
        {
            $sql= 'SELECT `授课总体评价`as \'level\',COUNT(授课总体评价),
                ROUND(COUNT(*)/(SELECT COUNT(*) FROM '.$table2.' WHERE 评价状态 = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'")*100,2) as \'num\'
                FROM '.$table2.'
                WHERE `评价状态` = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                GROUP BY `授课总体评价`';
        }
        elseif($unit!=null && $group==null)
        {
            $sql= 'SELECT T.`授课总体评价`as \'level\',COUNT(T.`授课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table2.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table2.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`授课总体评价`';
        }
        elseif($unit==null && $group!=null)
        {
            $sql= 'SELECT T.`授课总体评价` as \'level\',COUNT(T.`授课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table2.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table2.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`授课总体评价`';
        }
        $DataArrTeaching = DB::select($sql);

        for($i=0; $i<count($DataArrTeaching); $i++)
        {
            array_push($data,$DataArrTeaching[$i]->level);
        }
        $data = array_diff($Comparison,$data);

        if(count($data)!=0)
        {
            foreach($data as $item)
            {
                $Evaluation_Item_Obj = new Obj();
                $Evaluation_Item_Obj->set_attribute($item, 0);
                array_push($DataArrTeaching,$Evaluation_Item_Obj);
            }
        }

        //听课总体评价统计图
        $data = array();
        if($unit == null && $group == null)
        {
            $sql = 'SELECT `听课总体评价`as \'level\',COUNT(听课总体评价),
                ROUND(COUNT(*)/(SELECT COUNT(*) FROM '.$table2.' WHERE 评价状态 = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'")*100,2)AS\'num\'
                FROM '.$table2.'
                WHERE `评价状态` = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                GROUP BY `听课总体评价`';
        }
        elseif($unit!=null && $group==null)
        {
            $sql = 'SELECT T.`听课总体评价`as \'level\',COUNT(T.`听课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table2.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table2.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`听课总体评价`';
        }
        elseif($unit==null && $group!=null)
        {
            $sql = 'SELECT T.`听课总体评价`as \'level\',COUNT(T.`听课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table2.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table2.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`听课总体评价`';
        }


        $DataArrLearning = DB::select($sql);

        for($i=0; $i<count($DataArrLearning); $i++)
        {
            array_push($data,$DataArrLearning[$i]->level);
        }
        $data = array_diff($Comparison,$data);

        if(count($data)!=0)
        {
            foreach($data as $item)
            {
                $Evaluation_Item_Obj = new Obj();
                $Evaluation_Item_Obj->set_attribute($item, 0);
                array_push($DataArrLearning,$Evaluation_Item_Obj);
            }
        }

        //评价项目（细项）得分情况---按评价数量排统计图
        $sql = 'SELECT d.text AS LEVEL4
                FROM '.$table.' AS a
                LEFT JOIN '.$table.' AS b ON b.fid = a.id
                LEFT JOIN '.$table.' AS c ON c.fid = b.id
                LEFT JOIN '.$table.' AS d ON d.fid = c.id
                WHERE a.text = \'实践课评价表\' AND b.text = \'教师授课情况\' AND d.text IS NOT NULL';
        $DataArr = DB::select($sql);

        $DataArrMinorByCount = array();
        for($j=0;$j<count($DataArr);$j++)
        {
            if($unit == null && $group == null)
            {
                $sql ='SELECT '.$DataArr[$j]->LEVEL4.' AS  level ,COUNT('.$DataArr[$j]->LEVEL4.') AS num
                    FROM '.$table2.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$j]->LEVEL4.' ';
            }
            elseif($unit!=null && $group==null)
            {
                $sql ='SELECT '.$DataArr[$j]->LEVEL4.' AS level, COUNT('.$DataArr[$j]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$j]->LEVEL4.'
                    FROM '.$table2.' AS a
                    LEFT JOIN lessons
                    ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$j]->LEVEL4.' ';
            }
            elseif($unit==null && $group!=null)
            {
                $sql ='SELECT '.$DataArr[$j]->LEVEL4.' AS level, COUNT('.$DataArr[$j]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$j]->LEVEL4.'
                    FROM '.$table2.' AS a
                    LEFT JOIN users
                    ON a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$j]->LEVEL4.' ';
            }

            //Log::info($sql);
            $DataArr6[$j] = DB::select($sql);

            $data = array();

            for($i=0; $i<count($DataArr6[$j]); $i++)
            {
                array_push($data,$DataArr6[$j][$i]->level);
            }
            $data = array_diff($Comparison,$data);
            // Log::info($data);
            if(count($data)!=0)
            {
                foreach($data as $item)
                {
                    $Evaluation_Item_Obj = new Obj();
                    $Evaluation_Item_Obj->set_attribute($item, 0);
                    array_push($DataArr6[$j],$Evaluation_Item_Obj);
                }
            }

            foreach($DataArr6[$j] as $item)
            {
                if(array_key_exists($item->level, $DataArrMinorByCount))
                {
                    array_push($DataArrMinorByCount[$item->level], $item->num);
                }
                else
                {
                    $DataArrMinorByCount[$item->level]= array();
                    array_push($DataArrMinorByCount[$item->level], $item->num);
                }
            }
        }


        //评价项目（大项）得分情况统计图
        $sql = 'SELECT c.text AS LEVEL3
            FROM '.$table.' AS a
            LEFT JOIN '.$table.' AS b ON b.fid = a.id
            LEFT JOIN '.$table.' AS c ON c.fid = b.id
            WHERE a.text = \'实践课评价表\' AND b.text = \'教师授课情况\' ';

        $DataArr9 = DB::select($sql);

        for($i=0; $i<count($DataArr9)-1; $i++)
        {
            $content[] = $DataArr9[$i]->LEVEL3;
        }

        for($i = 0;$i<count($content);$i++)
        {
            $sql = 'SELECT d.text AS LEVEL4
                FROM '.$table.' AS a
                LEFT JOIN '.$table.' AS b ON b.fid = a.id
                LEFT JOIN '.$table.' AS c ON c.fid = b.id
                LEFT JOIN '.$table.' AS d ON d.fid = c.id
                WHERE c.text = "'.$content[$i].'" AND a.text = \'实践课评价表\'';

            $DataArr[$i] = DB::select($sql);
//            Log::info($DataArr[$i]);
            if($unit == null && $group == null)
            {
                if(count($DataArr[$i])==1)
                {
                    $sql1 = $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as d
                    FROM '.$table2.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.'';
                }
                else
                {
                    $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as b
                    FROM '.$table2.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.' UNION ALL ';
                    for($j=1;$j<count($DataArr[$i]);$j++)
                    {

                        $sql = $sql.'SELECT '.$DataArr[$i][$j]->LEVEL4.' ,COUNT('.$DataArr[$i][$j]->LEVEL4.')
                    FROM '.$table2.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i][$j]->LEVEL4.'';
                        if($j<count($DataArr[$i])-1)
                        {
                            $sql = $sql.' UNION ALL ';
                        }
                    }
                    $sql1 = 'SELECT a , sum(c.b) as d from( '
                        . $sql .
                        ') as c GROUP BY a ';
                }
            }
            elseif($unit!=null && $group==null)
            {
                if(count($DataArr[$i])==1)
                {
                    $sql1 = $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as d
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table2.' AS X
                    LEFT JOIN lessons
                    ON X.`任课教师` = lesson_teacher_name AND X.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.'';
                }
                else
                {
                    $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as b
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table2.' AS X
                    LEFT JOIN lessons
                    ON X.`任课教师` = lesson_teacher_name AND X.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.' UNION ALL ';
                    for($j=1;$j<count($DataArr[$i]);$j++)
                    {

                        $sql = $sql.'SELECT '.$DataArr[$i][$j]->LEVEL4.' ,COUNT('.$DataArr[$i][$j]->LEVEL4.')
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][$j]->LEVEL4.'
                    FROM '.$table2.' AS X
                    LEFT JOIN lessons
                    ON X.`任课教师` = lesson_teacher_name AND X.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][$j]->LEVEL4.'';
                        if($j<count($DataArr[$i])-1)
                        {
                            $sql = $sql.' UNION ALL ';
                        }
                    }
                    $sql1 = 'SELECT a , sum(c.b) as d from( '
                        . $sql .
                        ') as c GROUP BY a ';
                }
            }
            elseif($unit==null && $group!=null)
            {
                if(count($DataArr[$i])==1)
                {
                    $sql1 = $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as d
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table2.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.'';
                }
                else
                {
                    $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as b
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table2.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.' UNION ALL ';
                    for($j=1;$j<count($DataArr[$i]);$j++)
                    {

                        $sql = $sql.'SELECT '.$DataArr[$i][$j]->LEVEL4.' ,COUNT('.$DataArr[$i][$j]->LEVEL4.')
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][$j]->LEVEL4.'
                    FROM '.$table2.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][$j]->LEVEL4.'';
                        if($j<count($DataArr[$i])-1)
                        {
                            $sql = $sql.' UNION ALL ';
                        }
                    }
                    $sql1 = 'SELECT a , sum(c.b) as d from( '
                        . $sql .
                        ') as c GROUP BY a ';
                }
            }

            $DataArr4[$i] = DB::select($sql1);
            //Log::info($DataArr4[$i]);
            $sumDataArr4 = '';
            $CountDataArr4 = '';

            for($k=0; $k<count($DataArr4[$i]);$k++)
            {
                $weight = '';
                switch($DataArr4[$i][$k]->a)
                {
                    case "非常满意":
                        $weight = $DataArr4[$i][$k]->d * $value1;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "满意":
                        $weight = $DataArr4[$i][$k]->d * $value2;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "正常":
                        $weight = $DataArr4[$i][$k]->d * $value3;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "不足":
                        $weight = $DataArr4[$i][$k]->d * $value4;

                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "明显不足":
                        $weight = $DataArr4[$i][$k]->d * $value5;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                }
                $sumDataArr4 += $weight;
                $CountDataArr4 += $sum;
            }
            if($CountDataArr4 == 0)
                $sumDataArr4 = 0;
            else
                $sumDataArr4 = round($sumDataArr4/$CountDataArr4 ,2);
            $DataArrMajorTerm[] = $sumDataArr4;
        }



        //评价项目（细项）得分情况---按平均分排统计图
        $sql = 'SELECT d.text AS LEVEL4
                FROM '.$table.' AS a
                LEFT JOIN '.$table.' AS b ON b.fid = a.id
                LEFT JOIN '.$table.' AS c ON c.fid = b.id
                LEFT JOIN '.$table.' AS d ON d.fid = c.id
                WHERE a.text = \'实践课评价表\' AND b.text = \'教师授课情况\' AND d.text IS NOT NULL';
        $DataArr = DB::select($sql);


        for($i=0;$i<count($DataArr);$i++)
        {
            if($unit == null && $group == null)
            {
                $sql ='SELECT '.$DataArr[$i]->LEVEL4.' AS  level ,COUNT('.$DataArr[$i]->LEVEL4.') AS num
                    FROM '.$table2.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i]->LEVEL4.' ';
            }
            elseif($unit!=null && $group==null)
            {
                $sql ='SELECT '.$DataArr[$i]->LEVEL4.' AS  level ,COUNT('.$DataArr[$i]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i]->LEVEL4.'
                    FROM '.$table2.' AS a
                    LEFT JOIN lessons
                    ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i]->LEVEL4.' ';
            }
            elseif($unit==null && $group!=null)
            {
                $sql ='SELECT '.$DataArr[$i]->LEVEL4.' AS  level ,COUNT('.$DataArr[$i]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i]->LEVEL4.'
                    FROM '.$table2.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i]->LEVEL4.' ';
            }

            $DataArr5 = DB::select($sql);

            $sumDataArr5 = '';
            $CountDataArr5 = '';

            for($j=0;$j<count($DataArr5) ;$j++)
            {
                $weight = '';
                $sum = '';
                switch($DataArr5[$j]->level)
                {
                    case "非常满意":
                        $weight = $DataArr5[$j]->num * $value1;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "满意":
                        $weight = $DataArr5[$j]->num * $value2;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "正常":
                        $weight = $DataArr5[$j]->num * $value3;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "不足":
                        $weight = $DataArr5[$j]->num * $value4;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "明显不足":
                        $weight = $DataArr5[$j]->num * $value5;
                        $sum = $DataArr5[$j]->num;
                        break;
                }
//Log::info($weight);

                $sumDataArr5 += $weight;
                $CountDataArr5 += $sum;
            }
            $sumDataArr5 = round($sumDataArr5/$CountDataArr5 , 2);
            $DataArrMinorByAVG[] = $sumDataArr5;
//            Log::info($DataArrMinorByAVG);
        }





        $data = [
            'ChartTeaching' => $DataArrTeaching,
            'ChartLearning' => $DataArrLearning,
            'ChartMajorTerm'=> $DataArrMajorTerm,
            'ChartYAxis3'=> $DataArr9,
            'ChartMinorByAVG'=> $DataArrMinorByAVG,
            'ChartMinorByCount' => $DataArrMinorByCount,
            'ChartYAxis'=> $DataArr
        ];
        //  Log::info($data);
        return ($data);

    }

    public function PhysicalStatisticsData(Request $request)
    {
        $value1 = $request->value1;
        $value2 = $request->value2;
        $value3 = $request->value3;
        $value4 = $request->value4;
        $value5 = $request->value5;


        $mytime = new HelpController;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;

        $unit = $request->unit;
        $group = $request->group;

        $year = $year1."-".$year2;
        $TableFlag = $year."-".$semester[0];//使用表版本的标识
        //学年学期 得到 该学年学期所属的时间段

        $YearSemesterTime = $mytime->GetTimeByYearSemester($TableFlag);

        $TableName = $mytime->GetCurrentTableName($TableFlag);
//dd($TableName);

        $table3 = "front_physical_evaluation".$TableName;
        $table = "front_contents".$TableName;

        $Comparison = ['明显不足','不足','正常','满意','非常满意'];

        //授课总体评价统计图
        $data = array();
        if($unit == null && $group == null)
        {
            $sql= 'SELECT `授课总体评价` as \'level\',COUNT(授课总体评价),
                ROUND(COUNT(*)/(SELECT COUNT(*) FROM '.$table3.' WHERE 评价状态 = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'")*100,2) as \'num\'
                FROM '.$table3.'
                WHERE `评价状态` = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                GROUP BY `授课总体评价`';
        }
        elseif($unit!=null && $group==null)
        {
            $sql= 'SELECT T.`授课总体评价`as \'level\',COUNT(T.`授课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table3.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table3.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`授课总体评价`';
        }
        elseif($unit==null && $group!=null)
        {
            $sql= 'SELECT T.`授课总体评价`as \'level\',COUNT(T.`授课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table3.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,授课总体评价
              FROM '.$table3.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`授课总体评价`';
        }


        $DataArrTeaching = DB::select($sql);

        for($i=0; $i<count($DataArrTeaching); $i++)
        {
            array_push($data,$DataArrTeaching[$i]->level);
        }
        $data = array_diff($Comparison,$data);

        if(count($data)!=0)
        {
            foreach($data as $item)
            {
                $Evaluation_Item_Obj = new Obj();
                $Evaluation_Item_Obj->set_attribute($item, 0);
                array_push($DataArrTeaching,$Evaluation_Item_Obj);
            }
        }

        //听课总体评价统计图
        $data = array();
        if($unit == null && $group == null)
        {
            $sql = 'SELECT `听课总体评价`as \'level\',COUNT(听课总体评价),
                ROUND(COUNT(*)/(SELECT COUNT(*) FROM '.$table3.' WHERE 评价状态 = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'")*100,2)AS\'num\'
                FROM '.$table3.'
                WHERE `评价状态` = \'已完成\'
                AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                GROUP BY `听课总体评价`';
        }
        elseif($unit!=null && $group==null)
        {
            $sql = 'SELECT T.`听课总体评价`as \'level\',COUNT(T.`听课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table3.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table3.' AS a
              LEFT JOIN lessons
              ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
              WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`听课总体评价`';
        }
        elseif($unit==null && $group!=null)
        {
            $sql = 'SELECT T.`听课总体评价`as \'level\',COUNT(T.`听课总体评价`) ,
              ROUND(COUNT(*)/(SELECT COUNT(*) FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table3.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T)*100,2) as \'num\'
              FROM (SELECT DISTINCT valueID ,听课总体评价
              FROM '.$table3.' AS a
              LEFT JOIN users
              ON a.`督导id` = users.user_id
              WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
              AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
              )AS T
              GROUP BY T.`听课总体评价`';
        }


        $DataArrLearning = DB::select($sql);

        for($i=0; $i<count($DataArrLearning); $i++)
        {
            array_push($data,$DataArrLearning[$i]->level);
        }
        $data = array_diff($Comparison,$data);

        if(count($data)!=0)
        {
            foreach($data as $item)
            {
                $Evaluation_Item_Obj = new Obj();
                $Evaluation_Item_Obj->set_attribute($item, 0);
                array_push($DataArrLearning,$Evaluation_Item_Obj);
            }
        }


        //评价项目（细项）得分情况---按评价数量排统计图

        $sql = 'SELECT d.text AS LEVEL4
                FROM '.$table.' AS a
                LEFT JOIN '.$table.' AS b ON b.fid = a.id
                LEFT JOIN '.$table.' AS c ON c.fid = b.id
                LEFT JOIN '.$table.' AS d ON d.fid = c.id
                WHERE a.text = \'体育课评价表\' AND b.text = \'教师授课情况\' AND d.text IS NOT NULL';
        $DataArr = DB::select($sql);

        $DataArrMinorByCount = array();

        for($j=0;$j<count($DataArr);$j++)
        {
            if($unit == null && $group == null)
            {
                $sql ='SELECT '.$DataArr[$j]->LEVEL4.' AS  level ,COUNT('.$DataArr[$j]->LEVEL4.') AS num
                    FROM '.$table3.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$j]->LEVEL4.' ';
            }
            elseif($unit!=null && $group==null)
            {
                $sql ='SELECT '.$DataArr[$j]->LEVEL4.' AS level, COUNT('.$DataArr[$j]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$j]->LEVEL4.'
                    FROM '.$table3.' AS a
                    LEFT JOIN lessons
                    ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$j]->LEVEL4.' ';
            }
            elseif($unit==null && $group!=null)
            {
                $sql ='SELECT '.$DataArr[$j]->LEVEL4.' AS level, COUNT('.$DataArr[$j]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$j]->LEVEL4.'
                    FROM '.$table3.' AS a
                    LEFT JOIN users
                    ON a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$j]->LEVEL4.' ';
            }

            $DataArr6[$j] = DB::select($sql);

            $data = array();

            for($i=0; $i<count($DataArr6[$j]); $i++)
            {
                array_push($data,$DataArr6[$j][$i]->level);
            }
            $data = array_diff($Comparison,$data);
           // Log::info($data);
            if(count($data)!=0)
            {
                foreach($data as $item)
                {
                    $Evaluation_Item_Obj = new Obj();
                    $Evaluation_Item_Obj->set_attribute($item, 0);
                    array_push($DataArr6[$j],$Evaluation_Item_Obj);
                }
            }

            //Log::info($DataArr6);
            foreach($DataArr6[$j] as $item)
            {
                if(array_key_exists($item->level, $DataArrMinorByCount))
                {
                    array_push($DataArrMinorByCount[$item->level], $item->num);
                }
                else
                {
                    $DataArrMinorByCount[$item->level]= array();
                    array_push($DataArrMinorByCount[$item->level], $item->num);
                }
            }
        }
      //  Log::info($DataArrMinorByCount);

        //评价项目（大项）得分情况统计图
        $sql = 'SELECT c.text AS LEVEL3
            FROM '.$table.' AS a
            LEFT JOIN '.$table.' AS b ON b.fid = a.id
            LEFT JOIN '.$table.' AS c ON c.fid = b.id
            WHERE a.text = \'体育课评价表\' AND b.text = \'教师授课情况\' ';

        $DataArr9 = DB::select($sql);


        for($i=0; $i<count($DataArr9)-1; $i++)
        {
            $content[] = $DataArr9[$i]->LEVEL3;
        }

        for($i = 0;$i<count($content);$i++)
        {
            $sql = 'SELECT d.text AS LEVEL4
                FROM '.$table.' AS a
                LEFT JOIN '.$table.' AS b ON b.fid = a.id
                LEFT JOIN '.$table.' AS c ON c.fid = b.id
                LEFT JOIN '.$table.' AS d ON d.fid = c.id
                WHERE c.text = "'.$content[$i].'" AND a.text = \'体育课评价表\'';
//            Log::info($sql);
            $DataArr[$i] = DB::select($sql);

            if($unit == null && $group == null)
            {
                if(count($DataArr[$i])==1)
                {
                    $sql1 = $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as d
                    FROM '.$table3.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.'';
                }
                else
                {
                    $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as b
                    FROM '.$table3.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.' UNION ALL ';
                    for($j=1;$j<count($DataArr[$i]);$j++)
                    {

                        $sql = $sql.'SELECT '.$DataArr[$i][$j]->LEVEL4.' ,COUNT('.$DataArr[$i][$j]->LEVEL4.')
                    FROM '.$table3.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i][$j]->LEVEL4.'';
                        if($j<count($DataArr[$i])-1)
                        {
                            $sql = $sql.' UNION ALL ';
                        }
                    }
                    $sql1 = 'SELECT a , sum(c.b) as d from( '
                        . $sql .
                        ') as c GROUP BY a ';
                }
            }
            elseif($unit!=null && $group==null)
            {
                if(count($DataArr[$i])==1)
                {
                    $sql1 = $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as d
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table3.' AS X
                    LEFT JOIN lessons
                    ON X.`任课教师` = lesson_teacher_name AND X.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.'';
                }
                else
                {
                    $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as b
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table3.' AS X
                    LEFT JOIN lessons
                    ON X.`任课教师` = lesson_teacher_name AND X.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.' UNION ALL ';
                    for($j=1;$j<count($DataArr[$i]);$j++)
                    {

                        $sql = $sql.'SELECT '.$DataArr[$i][$j]->LEVEL4.' ,COUNT('.$DataArr[$i][$j]->LEVEL4.')
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][$j]->LEVEL4.'
                    FROM '.$table3.' AS X
                    LEFT JOIN lessons
                    ON X.`任课教师` = lesson_teacher_name AND X.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][$j]->LEVEL4.'';
                        if($j<count($DataArr[$i])-1)
                        {
                            $sql = $sql.' UNION ALL ';
                        }
                    }
                    $sql1 = 'SELECT a , sum(c.b) as d from( '
                        . $sql .
                        ') as c GROUP BY a ';
                }
            }
            elseif($unit==null && $group!=null)
            {
                if(count($DataArr[$i])==1)
                {
                    $sql1 = $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as d
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table3.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.'';
                }
                else
                {
                    $sql = 'SELECT '.$DataArr[$i][0]->LEVEL4.' as a ,COUNT('.$DataArr[$i][0]->LEVEL4.') as b
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][0]->LEVEL4.'
                    FROM '.$table3.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][0]->LEVEL4.' UNION ALL ';
                    for($j=1;$j<count($DataArr[$i]);$j++)
                    {

                        $sql = $sql.'SELECT '.$DataArr[$i][$j]->LEVEL4.' ,COUNT('.$DataArr[$i][$j]->LEVEL4.')
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i][$j]->LEVEL4.'
                    FROM '.$table3.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i][$j]->LEVEL4.'';
                        if($j<count($DataArr[$i])-1)
                        {
                            $sql = $sql.' UNION ALL ';
                        }
                    }
                    $sql1 = 'SELECT a , sum(c.b) as d from( '
                        . $sql .
                        ') as c GROUP BY a ';
                }
            }

            $DataArr4[$i] = DB::select($sql1);

            $sumDataArr4 = '';
            $CountDataArr4 = '';

            for($k=0; $k<count($DataArr4[$i]);$k++)
            {
                $weight = '';
                switch($DataArr4[$i][$k]->a)
                {
                    case "非常满意":
                        $weight = $DataArr4[$i][$k]->d * $value1;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "满意":
                        $weight = $DataArr4[$i][$k]->d * $value2;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "正常":
                        $weight = $DataArr4[$i][$k]->d * $value3;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "不足":
                        $weight = $DataArr4[$i][$k]->d * $value4;

                        $sum = $DataArr4[$i][$k]->d;
                        break;
                    case "明显不足":
                        $weight = $DataArr4[$i][$k]->d * $value5;
                        $sum = $DataArr4[$i][$k]->d;
                        break;
                }
                $sumDataArr4 += $weight;
                $CountDataArr4 += $sum;
            }
            if($CountDataArr4 == 0)
                $sumDataArr4 = 0;
            else
                $sumDataArr4 = round($sumDataArr4/$CountDataArr4 ,2);
            $DataArrMajorTerm[] = $sumDataArr4;
        }



        //评价项目（细项）得分情况---按平均分排统计图
        $sql = 'SELECT d.text AS LEVEL4
                FROM '.$table.' AS a
                LEFT JOIN '.$table.' AS b ON b.fid = a.id
                LEFT JOIN '.$table.' AS c ON c.fid = b.id
                LEFT JOIN '.$table.' AS d ON d.fid = c.id
                WHERE a.text = \'体育课评价表\' AND b.text = \'教师授课情况\' AND d.text IS NOT NULL';
        $DataArr = DB::select($sql);


        for($i=0;$i<count($DataArr);$i++)
        {
            if($unit == null && $group == null)
            {
                $sql ='SELECT '.$DataArr[$i]->LEVEL4.' AS  level ,COUNT('.$DataArr[$i]->LEVEL4.') AS num
                    FROM '.$table3.'
                    WHERE `评价状态` = \'已完成\'
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    GROUP BY '.$DataArr[$i]->LEVEL4.' ';
            }
            elseif($unit!=null && $group==null)
            {
                $sql ='SELECT '.$DataArr[$i]->LEVEL4.' AS  level ,COUNT('.$DataArr[$i]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i]->LEVEL4.'
                    FROM '.$table3.' AS a
                    LEFT JOIN lessons
                    ON a.`任课教师` = lesson_teacher_name AND a.`课程名称`= lesson_name
                    WHERE `评价状态` = \'已完成\' AND lesson_unit = "'.$unit.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i]->LEVEL4.' ';
            }
            elseif($unit==null && $group!=null)
            {
                $sql ='SELECT '.$DataArr[$i]->LEVEL4.' AS  level ,COUNT('.$DataArr[$i]->LEVEL4.') AS num
                    FROM (SELECT DISTINCT valueID ,'.$DataArr[$i]->LEVEL4.'
                    FROM '.$table3.' AS a
                    LEFT JOIN users
                    ON  a.`督导id` = users.user_id
                    WHERE `评价状态` = \'已完成\' AND `group` = "'.$group.'"
                    AND `听课时间` BETWEEN "'.$YearSemesterTime['time1'].'" AND "'.$YearSemesterTime['time2'].'"
                    )AS T
                    GROUP BY '.$DataArr[$i]->LEVEL4.' ';
            }

            $DataArr5 = DB::select($sql);

            $sumDataArr5 = '';
            $CountDataArr5 = '';

            for($j=0;$j<count($DataArr5) ;$j++)
            {
                $weight = '';
                $sum = '';
                switch($DataArr5[$j]->level)
                {
                    case "非常满意":
                        $weight = $DataArr5[$j]->num * $value1;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "满意":
                        $weight = $DataArr5[$j]->num * $value2;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "正常":
                        $weight = $DataArr5[$j]->num * $value3;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "不足":
                        $weight = $DataArr5[$j]->num * $value4;
                        $sum = $DataArr5[$j]->num;
                        break;
                    case "明显不足":
                        $weight = $DataArr5[$j]->num * $value5;
                        $sum = $DataArr5[$j]->num;
                        break;
                }

                $sumDataArr5 += $weight;
                $CountDataArr5 += $sum;
            }
            $sumDataArr5 = round($sumDataArr5/$CountDataArr5 , 2);
            $DataArrMinorByAVG[] = $sumDataArr5;
        }





        $data = [
            'ChartTeaching' => $DataArrTeaching,
            'ChartLearning' => $DataArrLearning,
            'ChartMajorTerm'=> $DataArrMajorTerm,
            'ChartYAxis3'=> $DataArr9,
            'ChartMinorByAVG'=> $DataArrMinorByAVG,
            'ChartMinorByCount' => $DataArrMinorByCount,
            'ChartYAxis'=> $DataArr
        ];
//        Log::info($data);
        return ($data);
    }

}
