<?php

namespace App\Http\Controllers\Auth;

use App\Model\back_content;
use App\Model\front_content;
use App\Model\table_head;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\Console\Helper\Table;
use App\Http\Controllers\Help\HelpController;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['only',['index','TheoryEvaluationTable','PhysicalEvaluationTable','PracticeEvaluationTable']]);
    }


    //校级登录首页
//    public function index()
//    {
//        $data = [
//            'SupervisorNum' => $this->SupervisorNum(),
//            'NecessaryTask' => $this->NecessaryTask(),
//            'FineshedNecess' => $this->FineshedNecess(),
//            'Fineshed' => $this->Fineshed(),
//        ];
//        return view ('index',compact('data'));
//    }

//    protected function SupervisorNum()
//    {
//        $num = DB::table("users")->count();
//        return $num;
//    }
//    protected function NecessaryTask()//当前学期必听任务总数
//    {
//        $time = date("Y-m-d");
//        $YearSemester=new HelpController;
//        $version = $YearSemester->GetYearSemester($time);
//        $num = DB::table("lessons")
//            ->where('lesson_level','=','关注课程')
//            ->where('lesson_year','=',$version['Year'])
//            ->where('lesson_semester','=',$version['Semester'])
//            ->count('lesson_level');
//        return $num;
//    }
//    protected function FineshedNecess()//当前学期已经完成必听任务总数
//    {
//        $time = date("Y-m-d");
//        $YearSemester=new HelpController;
//        $version = $YearSemester->GetYearSemester($time);
//        $num = DB::table("lessons")
//            ->where('lesson_level','=','关注课程')
//            ->where('lesson_year','=',$version['Year'])
//            ->where('lesson_semester','=',$version['Semester'])
//            ->where('lesson_state','已完成')->count('lesson_name');
//        return $num;
//    }
//    protected function Fineshed()//当前学期所有已完成听课的总数
//    {
//        $time = date("Y-m-d");
//        $YearSemester=new HelpController;
//        $version = $YearSemester->GetYearSemester($time);
//        $num = DB::table("lessons")
//            ->where('lesson_year','=',$version['Year'])
//            ->where('lesson_semester','=',$version['Semester'])
//            ->where('lesson_state','已完成')->count('lesson_name');
//        return $num;
//    }



    //院级登录首页


//理论表视图，需要数据，正面评价体系，背面评价体系
    public function TheoryEvaluationTableView()
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
        return view('TheoryEvaluationTable',compact('front','back'));
    }
// a weixin test
    public function weixinTheoryEvaluationTableView()
    {
        $frontdata = $this->GetFrontValueTable();
        $backdata = $this->GetBackValueTable();
        $front =array(
            '1'=>$frontdata[2][0],//一级菜单项
            '2'=>$frontdata[3][0],//二级菜单项
            '3'=>$frontdata[4][0]//三级菜单项
        );
        $back =array(
            '1'=>$backdata[2][0],//背面1级
            '2'=>$backdata[3][0]//背面2级
        );
        return view('weixin.TheoryEvaluationTable',compact('front','back'));
    }
//    实践评价表，需要数据理论评价表
    public function PracticeEvaluationTableView()
    {
        $frontdata=$this->GetFrontValueTable();
        for($i=0;$i<count($frontdata[1]);$i++)
            if($frontdata[1][$i]->text=='实践课评价表')break;
        $front=array(
            '1'=>array_key_exists($i,$frontdata[2])?$frontdata[2][$i]:array(),//一级菜单项
            '2'=>array_key_exists($i,$frontdata[3])?$frontdata[3][$i]:array(),//二级菜单项
            '3'=>array_key_exists($i,$frontdata[4])?$frontdata[4][$i]:array()//三级菜单项
        );
        $backdata=$this->GetBackValueTable();
        for($i=0;$i<count($backdata[1]);$i++)
            if($backdata[1][$i]->text=='实践课评价表')break;
        $back=array(
            '1'=>array_key_exists($i,$backdata[2])?$backdata[2][$i]:array(),//一级菜单项
            '2'=>array_key_exists($i,$backdata[3])?$backdata[3][$i]:array(),//二级菜单项
            '3'=>array_key_exists($i,$backdata[4])?$backdata[4][$i]:array()//三级菜单项
        );
        return view('PracticeEvaluationTable',compact('front','back'));
    }
    public function weixinPracticeEvaluationTableView()
    {
        $frontdata = $this->GetFrontValueTable();
        $backdata = $this->GetBackValueTable();
        $front =array(
            '1'=>$frontdata[2][1],
            '2'=>$frontdata[3][1],
            '3'=>$frontdata[4][1]
        );
        $back =array(
            '1'=>$backdata[2][1],
            '2'=>$backdata[3][1]
        );
        return view('weixin.PracticeEvaluationTable',compact('front','back'));
    }
//    体育评价表，需要数据同理论评价表
    public function PhysicalEvaluationTableView()
    {
        $frontdata=$this->GetFrontValueTable();
        for($i=0;$i<count($frontdata[1]);$i++)
            if($frontdata[1][$i]->text=='体育课评价表')break;
        $front=array(
            '1'=>array_key_exists($i,$frontdata[2])?$frontdata[2][$i]:array(),//一级菜单项
            '2'=>array_key_exists($i,$frontdata[3])?$frontdata[3][$i]:array(),//二级菜单项
            '3'=>array_key_exists($i,$frontdata[4])?$frontdata[4][$i]:array()//三级菜单项
        );
        $backdata=$this->GetBackValueTable();
        for($i=0;$i<count($backdata[1]);$i++)
            if($backdata[1][$i]->text=='体育课评价表')break;
        $back=array(
            '1'=>array_key_exists($i,$backdata[2])?$backdata[2][$i]:array(),//一级菜单项
            '2'=>array_key_exists($i,$backdata[3])?$backdata[3][$i]:array(),//二级菜单项
            '3'=>array_key_exists($i,$backdata[4])?$backdata[4][$i]:array()//三级菜单项
        );
        return view('PhysicalEvaluationTable',compact('front','back'));
    }
    public function weixinPhysicalEvaluationTableView()
    {
        $frontdata = $this->GetFrontValueTable();
        $backdata = $this->GetBackValueTable();
        $front =array(
            '1'=>$frontdata[2][2],
            '2'=>$frontdata[3][2],
            '3'=>$frontdata[4][2]
        );
        $back =array(
            '1'=>$backdata[2][2],
            '2'=>$backdata[3][2]
        );
        return view('weixin.PhysicalEvaluationTable',compact('front','back'));
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

    //获取正面评价表内容

    public function UpdateEvaluation_Migration(Request $request)
    {
        $time = date("Y_m");

        $year = $request->year;
        $semester = $request->semester;
        $current = $year.'-'.$semester;
//        拼接学年学期：2016-2017-1
        $flag = $request->flag;


        if ($flag == '1')//版本改变
        {
            DB::table('evaluation_migration')
                ->insert([
                    'Create_Year' => $current,
                    'Table_Name' => '_'.$time
                ]);
        }
        else{//仍然使用当前版本。只是生成新的评价表
            $LastTime = DB::select('select Table_Name,MAX(Create_Year) as A from evaluation_migration');
            DB::table('evaluation_migration')
                ->insert([
                    'Create_Year' => $current,
                    'Table_Name' => $LastTime[0]->A
                ]);
        }
    }

    public function DeepFirstSearchFrontTheoryTable(&$str,$result)
    {
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $con=mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');

        for($i=0;$i<count($result);$i++)
        {
            $str .=$result[$i]->text .' varchar(255),';
            $newresult=DB::table('front_contents'.$TableName)->where('fid','=',$result[$i]->id)->get();
            $this->DeepFirstSearchFrontTheoryTable($str,$newresult);
        }
    }
    public function DeepFirstSearchBackTheoryTable(&$str,$result)
    {
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $con=mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');

        for($i=0;$i<count($result);$i++)
        {
            $str .=$result[$i]->text .' varchar(255),';
            $newresult=DB::table('back_contents'.$TableName)->where('fid','=',$result[$i]->id)->get();
            $this->DeepFirstSearchBackTheoryTable($str,$newresult);
        }
    }

    public function DeepFirstSearchFrontPracticeTable(&$str,$result)
    {
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $con=mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');

        for($i=0;$i<count($result);$i++)
        {
            $str .=$result[$i]->text .' varchar(255),';
            $newresult=DB::table('front_contents'.$TableName)->where('fid','=',$result[$i]->id)->get();
            $this->DeepFirstSearchFrontPracticeTable($str,$newresult);
        }
    }
    public function DeepFirstSearchBackPracticeTable(&$str,$result)
    {
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $con=mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');

        for($i=0;$i<count($result);$i++)
        {
            $str .=$result[$i]->text .' varchar(255),';
            $newresult=DB::table('back_contents'.$TableName)->where('fid','=',$result[$i]->id)->get();
            $this->DeepFirstSearchBackPracticeTable($str,$newresult);
        }
    }

    public function DeepFirstSearchFrontPhysicalTable(&$str,$result)
    {
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $con=mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');

        for($i=0;$i<count($result);$i++)
        {
            $str .=$result[$i]->text .' varchar(255),';
            $newresult=DB::table('front_contents'.$TableName)->where('fid','=',$result[$i]->id)->get();
            $this->DeepFirstSearchFrontPhysicalTable($str,$newresult);
        }
    }
    public function DeepFirstSearchBackPhysicalTable(&$str,$result)
    {
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $con=mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');

        for($i=0;$i<count($result);$i++)
        {
            $str .=$result[$i]->text .' varchar(255),';
            $newresult=DB::table('back_contents'.$TableName)->where('fid','=',$result[$i]->id)->get();
            $this->DeepFirstSearchBackPhysicalTable($str,$newresult);
        }
    }
    public function TheoryEvaluationEdit(Request $request)
    {
        $mytime=new HelpController;
        $Time=$mytime->GetYearSemester(date('Y-m'));
        $TableName=$mytime->GetCurrentTableName($Time['YearSemester']);
        $FrontName = "front_theory_evaluation".$TableName;
        $BackName = "back_theory_evaluation".$TableName;
        $flag = $request->Flag;
        $tableF = DB::table($FrontName)
            ->where('valueID','=',$flag)
            ->get();
        $tableB = DB::table($BackName)
            ->where('valueID','=',$flag)
            ->get();
        return $result=
            [
                'front'=>$tableF,
                'back'=>$tableB
            ];
    }
    public function PhysicalEvaluationEdit(Request $request)
    {
        $mytime=new HelpController;
        $Time=$mytime->GetYearSemester(date('Y-m'));
        $TableName=$mytime->GetCurrentTableName($Time['YearSemester']);
        $FrontName = "front_physical_evaluation".$TableName;
        $BackName = "back_physical_evaluation".$TableName;
        $flag = $request->Flag;
        $tableF = DB::table($FrontName)
            ->where('valueID','=',$flag)
            ->get();
        $tableB = DB::table($BackName)
            ->where('valueID','=',$flag)
            ->get();
        return $result=
            [
                'front'=>$tableF,
                'back'=>$tableB
            ];
    }
    public function PracticeEvaluationEdit(Request $request)
    {
        $mytime=new HelpController;
        $Time=$mytime->GetYearSemester(date('Y-m'));
        $TableName=$mytime->GetCurrentTableName($Time['YearSemester']);
        $FrontName = "front_practice_evaluation".$TableName;
        $BackName = "back_practice_evaluation".$TableName;
        $flag = $request->Flag;
        $tableF = DB::table($FrontName)
            ->where('valueID','=',$flag)
            ->get();
        $tableB = DB::table($BackName)
            ->where('valueID','=',$flag)
            ->get();
        return $result=
            [
                'front'=>$tableF,
                'back'=>$tableB
            ];
    }
    public function CreateFrontTheoryEvalTable(Request $request)
    {
        //更新表evaluation_migration
        $year = $request->year;
        $semester = $request->semester;
        $current = $year.'-'.$semester;
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $time = date("Y_m"); //时间戳
        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
        $str = '';                                                              //数据库表字段
        $head = '';                                                             //table_head中字段
        $table_name = '';

        $idstr = 'valueID int (4) primary key not null auto_increment,';
        //选择front_contents 的版本
        $result = DB::table('front_contents'.$TableName)->get();
        $tree=$this->getTree($result);
        $table_head=table_head::all()->toArray();
        foreach($table_head as $item)
            $head .=$item['head_content'].' text,';
        $head=$idstr.$head;
        $str=$head;
        $result=DB::table('front_contents'.$TableName)->where('text','=',"理论课评价表")->get();
        $id=$result[0]->id;
        $result=DB::table('front_contents'.$TableName)->where('fid','=',$id)->get();
        $this->DeepFirstSearchFrontTheoryTable($str,$result);
        $str=trim($str,',');
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."front_theory_evaluation$TableName"."'"))==1)
            mysqli_query($con, "drop table front_theory_evaluation$TableName;");
//        Log::info("create table front_theory_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
        mysqli_query($con,"create table front_theory_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
    }
    public function CreateBackTheoryEvalTable(Request $request)
    {
        //更新表evaluation_migration
        $year = $request->year;
        $semester = $request->semester;
        $current = $year.'-'.$semester;
        $version = new HelpController;
        $TableName = $version->GetCurrentTableName($current);

        $time = date("Y_m"); //时间戳
        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
        $str = '';                                                              //数据库表字段
        $head = '';                                                             //table_head中字段
        $table_name = '';

        $idstr = 'valueID int (4) primary key not null auto_increment,';
        //选择back_contents的版本
        $result = DB::table('back_contents'.$TableName)->get();
        $tree=$this->getTree($result);
        $table_head=table_head::all()->toArray();
        foreach($table_head as $item)
            $head .=$item['head_content'].' text,';
        $head=$idstr.$head;
        $str=$head;
        $result=DB::table('back_contents'.$TableName)->where('text','=',"理论课评价表")->get();
        $id=$result[0]->id;
        $result=DB::table('back_contents'.$TableName)->where('fid','=',$id)->get();
        $this->DeepFirstSearchBackTheoryTable($str,$result);
        $str=trim($str,',');
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."back_theory_evaluation$TableName"."'"))==1)
            mysqli_query($con, "drop table back_theory_evaluation$TableName;");
//        Log::info("create table back_theory_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
        mysqli_query($con,"create table back_theory_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
    }

    public function CreateFrontPracticeEvalTable(Request $request)
    {
        //更新表evaluation_migration
        $year = $request->year;
        $semester = $request->semester;
        $current = $year.'-'.$semester;
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $time = date("Y_m"); //时间戳
        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
        $str = '';                                                              //数据库表字段
        $head = '';                                                             //table_head中字段
        $table_name = '';

        $idstr = 'valueID int (4) primary key not null auto_increment,';
        //选择front_contents 的版本
        $result = DB::table('front_contents'.$TableName)->get();
        $tree=$this->getTree($result);
        $table_head=table_head::all()->toArray();
        foreach($table_head as $item)
            $head .=$item['head_content'].' text,';
        $head=$idstr.$head;
        $str=$head;
        $result=DB::table('front_contents'.$TableName)->where('text','=',"实践课评价表")->get();
        $id=$result[0]->id;
        $result=DB::table('front_contents'.$TableName)->where('fid','=',$id)->get();
        $this->DeepFirstSearchFrontPracticeTable($str,$result);
        $str=trim($str,',');
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."front_practice_evaluation$TableName"."'"))==1)
            mysqli_query($con, "drop table front_practice_evaluation$TableName;");
        Log::info("create table front_practice_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
        mysqli_query($con,"create table front_practice_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
    }
    public function CreateBackPracticeEvalTable(Request $request)
    {
        //更新表evaluation_migration
        $year = $request->year;
        $semester = $request->semester;
        $current = $year.'-'.$semester;
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $time = date("Y_m"); //时间戳
        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
        $str = '';                                                              //数据库表字段
        $head = '';                                                             //table_head中字段
        $table_name = '';

        $idstr = 'valueID int (4) primary key not null auto_increment,';
        //选择back_contents的版本
        $result = DB::table('back_contents'.$TableName)->get();
        $tree=$this->getTree($result);
        $table_head=table_head::all()->toArray();
        foreach($table_head as $item)
            $head .=$item['head_content'].' text,';
        $head=$idstr.$head;
        $str=$head;
        $result=DB::table('back_contents'.$TableName)->where('text','=',"实践课评价表")->get();
        $id=$result[0]->id;
        $result=DB::table('back_contents'.$TableName)->where('fid','=',$id)->get();
        $this->DeepFirstSearchBackPracticeTable($str,$result);
        $str=trim($str,',');
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."back_practice_evaluation$TableName"."'"))==1)
            mysqli_query($con, "drop table back_practice_evaluation$TableName;");
        log::info("create table back_practice_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
        mysqli_query($con,"create table back_practice_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
    }

    public function CreateFrontPhysicalEvalTable(Request $request)
    {
        //更新表evaluation_migration
        $year = $request->year;
        $semester = $request->semester;
        $current = $year.'-'.$semester;
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $time = date("Y_m"); //时间戳
        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
        $str = '';                                                              //数据库表字段
        $head = '';                                                             //table_head中字段
        $table_name = '';

        $idstr = 'valueID int (4) primary key not null auto_increment,';
        //选择front_contents 的版本
        $result = DB::table('front_contents'.$TableName)->get();
        $tree=$this->getTree($result);
        $table_head=table_head::all()->toArray();
        foreach($table_head as $item)
            $head .=$item['head_content'].' text,';
        $head=$idstr.$head;
        $str=$head;
        $result=DB::table('front_contents'.$TableName)->where('text','=',"体育课评价表")->get();
        $id=$result[0]->id;
        $result=DB::table('front_contents'.$TableName)->where('fid','=',$id)->get();
        $this->DeepFirstSearchFrontPhysicalTable($str,$result);
        $str=trim($str,',');
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."front_physical_evaluation$TableName"."'"))==1)
            mysqli_query($con, "drop table front_physical_evaluation$TableName;");
        Log::info("create table front_physical_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
        mysqli_query($con,"create table front_physical_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
    }
    public function CreateBackPhysicalEvalTable(Request $request)
    {
        //更新表evaluation_migration
        $year = $request->year;
        $semester = $request->semester;
        $current = $year.'-'.$semester;
        $version = new HelpController;
        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);

        $time = date("Y_m"); //时间戳
        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
        $str = '';                                                              //数据库表字段
        $head = '';                                                             //table_head中字段
        $table_name = '';

        $idstr = 'valueID int (4) primary key not null auto_increment,';
        //选择back_contents的版本
        $result = DB::table('back_contents'.$TableName)->get();
        $tree=$this->getTree($result);
        $table_head=table_head::all()->toArray();
        foreach($table_head as $item)
            $head .=$item['head_content'].' text,';
        $head=$idstr.$head;
        $str=$head;
        $result=DB::table('back_contents'.$TableName)->where('text','=',"体育课评价表")->get();
        $id=$result[0]->id;
        $result=DB::table('back_contents'.$TableName)->where('fid','=',$id)->get();
        $this->DeepFirstSearchBackPhysicalTable($str,$result);
        $str=trim($str,',');
        if(mysqli_num_rows(mysqli_query($con,"show tables like '"."back_physical_evaluation$TableName"."'"))==1)
            mysqli_query($con, "drop table back_physical_evaluation$TableName;");
        Log::info("create table back_physical_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
        mysqli_query($con,"create table back_physical_evaluation$TableName ($str)engine = InnoDB COLLATE=utf8_bin;");
    }

    public function CreateEvalBackTable(Request $request)
    {
        $version  = new HelpController;
        $year = $request->year;
        $semester = $request->semester;
        $current = $year.'-'.$semester;

        $current = '2017-2018-1';
        $TableName = $version->GetCurrentTableName($current);


        $con = mysqli_connect("localhost","root","","tets");
        mysqli_query($con,'set names utf8');
        $idstr = 'valueID int (4) primary key not null auto_increment,';

        $str = '';                                                              //数据库表字段
        $head = '';                                                             //table_head中字段
        $table_name = '';
        $time = date("Y_m");                                                         //时间戳
        $result = DB::table('back_contents'.$TableName)->get();

        $tree = $this->getTree($result);

        $table_head = table_head::all()->toArray();
        foreach ($table_head as $item)
            $head .= $item['head_content'].' varchar(255),';
        $head = $idstr.$head;
        $str=$head;
        for($i = 0; $i < count($tree); $i++)
        {
            if($tree[$i]->level == 1)
            {
                if($i != 0) {
                    $str = trim($str,',');
                    mysqli_query($con,"create table $table_name ($str);");
                    $str = $head;
                }
                if($tree[$i]->text == '理论课评价表')
                    $table_name = 'Back_theory_evaluation'.$TableName;
                elseif($tree[$i]->text == '实践课评价表')
                    $table_name = 'Back_practice_evaluation'.$TableName;
                else
                    $table_name = 'Back_physical_evaluation'.$TableName;
            }
            else
                if ($tree[$i]->text != '')
                    $str .= $tree[$i]->text.' varchar(255),';
        }
        $str = trim($str,',');
        mysqli_query($con,"create table $table_name ($str);");


    }

//    public function GetFrontValueTable()
//    {
//        $mytime = new HelpController;
//        $Time = $mytime->GetYearSemester(date('Y-m'));//将2016-8变为2016-2017-1的学年学期格式
//
////通过GetCurrentTableName函数将2016-2017-1格式  得到  当前使用评价体系使用版本的后缀名
//        $TableName = $mytime->GetCurrentTableName($Time['YearSemester']);
//
//        $DataTable []=Array();
//        $DataFirst []=Array();
//        $DataSecond []=Array();
//        $DataThird []=Array();
//
//        $TableType = DB::table('front_contents'.$TableName)->where('fid','=','0')->get();
//        for ($iType=0;$iType<count($TableType);$iType++)
//        {
//            $DataTable[$iType]=$TableType[$iType]->text;
//            //获取一级菜单
//            $IndexFirst = DB::table('front_contents'.$TableName)->where('fid','=',$TableType[$iType]->id)->get();
//            for ($iF=0;$iF<count($IndexFirst);$iF++)
//            {
//                $DataFirst[$iType][$iF]=$IndexFirst[$iF]->text;
//                $IndexSecond = DB::table('front_contents'.$TableName)->where('fid','=',$IndexFirst[$iF]->id)->get();
//                for($iS=0;$iS<count($IndexSecond);$iS++)
//                {
//                    $DataSecond[$iType][$iF][$iS]=$IndexSecond[$iS]->text;
//                    $IndexThird = DB::table('front_contents'.$TableName)->where('fid','=',$IndexSecond[$iS]->id)->get();
//                    for($iT=0;$iT<count($IndexThird);$iT++)
//                    {
//                        $DataThird[$iType][$iF][$iS][$iT]=$IndexThird[$iT]->text;
//                    }
//                }
//            }
//        }
////        $TheoryTable []=Array();
////        $PracticeTable []=Array();
////        $PhysicalTable []=Array();
//        $data = Array(
//            '1'=>$DataTable,
//            '2'=>$DataFirst,
//            '3'=>$DataSecond,
//            '4'=>$DataThird,
//        );
//        return $data;
//    }
//    public function GetBackValueTable()
//    {
////        见GetFrontValueTable()
//        $version  = new HelpController;
//        $mytime = new HelpController;
//        $Time = $mytime->GetYearSemester(date('Y-m'));
//        $TableName = $version->GetCurrentTableName($Time['YearSemester']);
//
//        $DataTable []=Array();
//        $DataFirst []=Array();
//        $DataSecond []=Array();
//
//        $TableType = DB::table('back_contents'.$TableName)->where('fid','=','0')->get();
//
//        for ($iType=0;$iType<count($TableType);$iType++)
//        {
//            $DataTable[$iType]=$TableType[$iType]->text;
//            //获取一级菜单
//            $IndexFirst = DB::table('back_contents'.$TableName)->where('fid','=',$TableType[$iType]->id)->get();
//            for ($iF=0;$iF<count($IndexFirst);$iF++)
//            {
//                $DataFirst[$iType][$iF]=$IndexFirst[$iF]->text;
//                $IndexSecond = DB::table('back_contents'.$TableName)->where('fid','=',$IndexFirst[$iF]->id)->get();
//                for($iS=0;$iS<count($IndexSecond);$iS++)
//                {
//                    $DataSecond[$iType][$iF][$iS]=$IndexSecond[$iS]->text;
//                }
//            }
//        }
//        $data = Array(
//            '1'=>$DataTable,
//            '2'=>$DataFirst,
//            '3'=>$DataSecond,
//        );
//        return $data;
//    }
//
//
//
    public function EvaluationContent(Request $request)
    {
        $version = new HelpController;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;

        if ($semester==null && $year1==null && $year2==null )
        {
            $Lesson_date = $request->Lesson_date;
            $Flag = $version->GetYearSemester($Lesson_date);//将听课时间2016-08-10与学年学期对应
            $TableFlag = $Flag['YearSemester'];
        }
        else
        {
            $year = $year1."-".$year2;
            $TableFlag = $year."-".$semester[0];//使用表版本的标识
        }



        $supervisor = $request->Spuervisor;//督导id
        $teacher = $request->Teacher;//教师姓名
        $lesson_date = $request->Lesson_date;//听课时间
        $lesson_time = $request->Lessontime;//听课节次

        $lesson_name = $request->Lesson_name;
        $LessonList = $version->GetCurrentTableName2($lesson_name,$teacher,$lesson_date,$lesson_time,$supervisor,$TableFlag);//确定该课程所在的表
        //把该表的字段返回

        $tableF = DB::select('select COLUMN_NAME from information_schema.COLUMNS where table_name = "'.$LessonList[0].'" and table_schema = \'tets\';');
        $tableB = DB::select('select COLUMN_NAME from information_schema.COLUMNS where table_name = "'.$LessonList[1].'" and table_schema = \'tets\';');

        //查表返回表的评价内容
        $frontContent = DB::table($LessonList[0])
            ->where('课程名称','=',$lesson_name)
            ->where('听课节次','=',$lesson_time)
            ->where('任课教师','=',$teacher)
            ->where('听课时间','=',$lesson_date)
            ->where('督导id','=',$supervisor)->get();
        $backContent = DB::table($LessonList[1])
            ->where('课程名称','=',$lesson_name)
            ->where('听课节次','=',$lesson_time)
            ->where('任课教师','=',$teacher)
            ->where('听课时间','=',$lesson_date)
            ->where('督导id','=',$supervisor)->get();


        return $data=
        [
            '1'=>$frontContent,
            '2'=>$backContent,
            '3'=>$tableF,
            '4'=>$tableB
        ];

    }
    public function EvaluationCheckBox(Request $request)
    {
        $version = new HelpController;
        $year1 = $request->year1;
        $year2 = $request->year2;
        $semester = $request->semester;
        if ($semester==null && $year1==null && $year2==null )
        {
            $Lesson_date = $request->Lesson_date;
            $Flag = $version->GetYearSemester($Lesson_date);//将听课时间2016-08-10与学年学期对应
            $TableFlag = $Flag['YearSemester'];
        }
        else
        {
            $year = $year1."-".$year2;
            $TableFlag = $year."-".$semester[0];//使用表版本的标识
        }
        $tableName = $version->GetCurrentTableName($TableFlag);
        $FrontName = "front_contents".$tableName;
//        $BackName = "back_contents".$tableName;
        $front = DB::select('select text from '.$FrontName.' where level = \'2\' and cssstyle = 2');
//        Log::info($front);
//        $back = DB::select('select DISTINCT text from '.$BackName.' where level = \'2\' and (cssstyle = 4 or cssstyle = 1)');
//        Log::info($back);
        return $result =[
            'front' => $front,
//            'back' => $back
        ];
    }
    public function getTree($result, $parent_id = 0, $level = 0)
    {
        static $arrTree = array();
        if(empty($result))
            return false;
        $level++;
        foreach ($result as $key => $value)
        {
            if($value->fid == $parent_id)
            {
                $value->level = $level;
                $arrTree[] = $value;
                unset($result[$key]);
                $this->getTree($result,$value->id,$level);
            }
        }
        return $arrTree;
    }
}
