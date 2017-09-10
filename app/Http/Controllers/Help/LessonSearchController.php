<?php

namespace App\Http\Controllers\Help;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LessonSearchController extends Controller
{
    public function GetLessonArr(Request $request)
    {
        $time = date("Y-m-d");//获取年月日
        $YearSemester=new HelpController;
        $version = $YearSemester->GetYearSemester($time);//将2017-09-01 -》 2016-2017-1
        $key = $request->get('dataIn');//
//        $InputValue = DB::select('
//select DISTINCT lesson_name,lesson_teacher_name,lesson_time,lesson_room,lesson_class,lesson_weekday
//from lessons where lesson_name like "' . $key . '%" and lesson_year="'.$version['Year'].'"
//and lesson_time != ""
//and lesson_semester="'.$version['Semester'].'" and lesson_class not like "%_补考%" limit 20');
        //课程名称（空格）教师名
        if (preg_match('/\s+/',$key))//如果匹配到空格
        {
            $arr= preg_split("/[\s]+/",$key);//去除多余的空格
            $InputValue=DB::table('lessons')
                ->where('lesson_name','like',$arr[0].'%')
                ->where('lesson_teacher_name','like',$arr[1].'%')
                ->where('lesson_year','=',$version['Year'])
//                ->where('lesson_time','!=','')
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->take(10)
                ->get();
        }
        else{
            $InputValue=DB::table('lessons')
                ->where('lesson_year','=',$version['Year'])
//                ->where('lesson_time','!=','')
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->where(function($query) use ($key){
                    $query->orwhere('lesson_name','like',$key.'%')
                        ->orwhere('lesson_teacher_name','like',$key.'%');
                })
                ->take(10)
                ->get();
        }

//        $data = [];
//
//        for ($i = 0; $i < count($InputValue); $i++) {
//            $data[$i] = array(
//                '1' => $InputValue[$i]->lesson_name,
//                '2' => $InputValue[$i]->lesson_teacher_name,
//                '3' => $InputValue[$i]->lesson_room,
//                '4' => $InputValue[$i]->lesson_class,
//                '5' => $InputValue[$i]->lesson_time,
//                '6' => $InputValue[$i]->lesson_weekday
//
//            );
//        }

        return $InputValue;
    }
    /**
     * 获取课程名称，增加必听任务时使用
     */
    public function GetLessonArrTNLN(Request $request)
    {
        $time = date("Y-m-d");//获取年月日
        $YearSemester=new HelpController;
        $version = $YearSemester->GetYearSemester($time);//将2017-09-01 -》 2016-2017-1
        $key = $request->get('dataIn');//

        //课程名称（空格）教师名
        if (preg_match('/\s+/',$key))//如果匹配到空格
        {
            $arr= preg_split("/[\s]+/",$key);//去除多余的空格
            $InputValue=DB::table('lessons')
                ->select('lesson_name','lesson_teacher_name')
                ->where('lesson_name','like',$arr[0].'%')
                ->where('lesson_teacher_name','like',$arr[1].'%')
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->distinct()
                ->take(10)
                ->get();
        }
        else{
            $InputValue=DB::table('lessons')
                ->select('lesson_name','lesson_teacher_name')
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->where(function($query) use ($key){
                    $query->orwhere('lesson_name','like',$key.'%')
                        ->orwhere('lesson_teacher_name','like',$key.'%');
                })
                ->distinct()

                ->take(10)
                ->get();
        }


        return $InputValue;
    }
//    获取理论课程列表时使用
    public function GetLessonArrThe(Request $request)
    {
        $time = date("Y-m-d");//获取年月日
        $YearSemester=new HelpController;
        $version = $YearSemester->GetYearSemester($time);//将2017-09-01 -》 2016-2017-1
        $key = $request->get('dataIn');//
//        $InputValue = DB::select('
//select DISTINCT lesson_name,lesson_teacher_name,lesson_time,lesson_room,lesson_class,lesson_weekday
//from lessons where lesson_name like "' . $key . '%" and lesson_year="'.$version['Year'].'"
//and lesson_time != ""
//and lesson_semester="'.$version['Semester'].'" and lesson_class not like "%_补考%" limit 20');
        //课程名称（空格）教师名
        if (preg_match('/\s+/',$key))//如果匹配到空格
        {
            $arr= preg_split("/[\s]+/",$key);//去除多余的空格
            $InputValue=DB::table('lessons')
                ->where('lesson_name','like',$arr[0].'%')
                ->where('lesson_teacher_name','like',$arr[1].'%')
                ->where('lesson_year','=',$version['Year'])
//                ->where('lesson_attribute','=','普通课')
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->take(10)
                ->get();
        }
        else{
            $InputValue=DB::table('lessons')
                ->where('lesson_year','=',$version['Year'])
//                ->where('lesson_attribute','=','普通课')
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->where(function($query) use ($key){
                    $query->orwhere('lesson_name','like',$key.'%')
                        ->orwhere('lesson_teacher_name','like',$key.'%');
                })
                ->take(10)
                ->get();
        }

//        $data = [];
//
//        for ($i = 0; $i < count($InputValue); $i++) {
//            $data[$i] = array(
//                '1' => $InputValue[$i]->lesson_name,
//                '2' => $InputValue[$i]->lesson_teacher_name,
//                '3' => $InputValue[$i]->lesson_room,
//                '4' => $InputValue[$i]->lesson_class,
//                '5' => $InputValue[$i]->lesson_time,
//                '6' => $InputValue[$i]->lesson_weekday
//
//            );
//        }

        return $InputValue;
    }
//获取实践课程列表时使用
    public function GetLessonArrPra(Request $request)
    {
        $time = date("Y-m-d");//获取年月日
        $YearSemester=new HelpController;
        $version = $YearSemester->GetYearSemester($time);//将2017-09-01 -》 2016-2017-1
        $key = $request->get('dataIn');//
//        $InputValue = DB::select('
//select DISTINCT lesson_name,lesson_teacher_name,lesson_time,lesson_room,lesson_class,lesson_weekday
//from lessons where lesson_name like "' . $key . '%" and lesson_year="'.$version['Year'].'"
//and lesson_time != ""
//and lesson_semester="'.$version['Semester'].'" and lesson_class not like "%_补考%" limit 20');
        //课程名称（空格）教师名
        if (preg_match('/\s+/',$key))//如果匹配到空格
        {
            $arr= preg_split("/[\s]+/",$key);//去除多余的空格
            $InputValue=DB::table('lessons')
                ->where('lesson_name','like',$arr[0].'%')
                ->where('lesson_teacher_name','like',$arr[1].'%')
                ->where('lesson_year','=',$version['Year'])
//                ->where('lesson_attribute','like','实%')
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->take(10)
                ->get();
        }
        else{
            $InputValue=DB::table('lessons')
                ->where('lesson_year','=',$version['Year'])
//                ->where('lesson_attribute','like','实%')
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->where(function($query) use ($key){
                    $query->orwhere('lesson_name','like',$key.'%')
                        ->orwhere('lesson_teacher_name','like',$key.'%');
                })
                ->take(10)
                ->get();
        }
        return $InputValue;
    }
    //获取体育课程列表时使用
    public function GetLessonArrPhy(Request $request)
    {
        $time = date("Y-m-d");//获取年月日
        $YearSemester=new HelpController;
        $version = $YearSemester->GetYearSemester($time);//将2017-09-01 -》 2016-2017-1
        $key = $request->get('dataIn');//

//        $InputValue = DB::select('
//select DISTINCT lesson_name,lesson_teacher_name,lesson_time,lesson_room,lesson_class,lesson_weekday
//from lessons where lesson_name like "' . $key . '%" and lesson_year="'.$version['Year'].'"
//and lesson_time != ""
//and lesson_semester="'.$version['Semester'].'" and lesson_class not like "%_补考%" limit 20');
        //课程名称（空格）教师名
        if (preg_match('/\s+/',$key))//如果匹配到空格
        {
            $arr= preg_split("/[\s]+/",$key);//去除多余的空格
            $InputValue=DB::table('lessons')
                ->where('lesson_name','like',$arr[0].'%')
                ->where('lesson_teacher_name','like',$arr[1].'%')
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_unit','=','体育教学部')
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->take(10)
                ->get();
        }
        else{
            $InputValue=DB::table('lessons')
                ->where('lesson_year','=',$version['Year'])
                ->where('lesson_unit','=','体育教学部')
                ->where('lesson_semester','=',$version['Semester'])
                ->where('lesson_class','not like',"%补考%")
                ->where(function($query) use ($key){
                    $query->orwhere('lesson_name','like',$key.'%')
                        ->orwhere('lesson_teacher_name','like',$key.'%');
                })
                ->take(10)
                ->get();
        }

        return $InputValue;
    }
}
