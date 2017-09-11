<!DOCTYPE html>
<meta name="render" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=11">
<html lang="en">
<link rel="stylesheet" href="css/Theory.css" />

<head>
    <meta charset="utf-8" />
    <title>北林教学评价系统</title>
    <meta name="keywords" content="北林教学评价系统" />
    <meta name="description" content="北林教学评价系统" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- basic styles -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/css/dashboard.css" />
    <link rel="stylesheet" href="assets/css/common.css" />
    <link rel="stylesheet" href="assets/css/evaluation-table.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-material-btndesign.css" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<style>
    .col-lg-1{
        width: 6.7%;
        float:left;
        padding-left:10px;
        padding-right:0;
        text-align: center;
    }
    .col-sm-1 {
        width: 10.33333%;
    }
    #newmain{
    }
    #front .checkboxgrade{
        margin-left: 25px;
    }
    #back .grade1 .checkboxgrade{
        margin-left: 25px;
    }
    #search-suggest{
        left: 95%;
        top: 200px;
    }
    #mianban{
        padding-right: 0px;
    }
    @media screen and (max-width: 1666px){
        #mianban{
            padding-left: 3%;
        }
    }
    @media screen and (max-width: 1214px){
        .form-content{
            padding-right: 3px;
        }
    }
    @media screen and (max-width: 768px){
        #mianban{
            padding-left: 0px;
        }
        #newmain{
            overflow: auto;float: left;
            margin-left:44%;width: 1660px;
        }
        #KIKO{
            float: left;
            margin-left:15px;
            margin-bottom: 3px;
            text-align: left;
            width: 70%;
        }
        #KIKO1{
            float: none;
            margin-left:15px;
            margin-bottom: 3px;
            width: 80%;
        }
        #search-suggest{
            left: -11px;
            top: 440px;
        }
        #Lesson-suggest{
            top: 510px;
        }

    }
    @media screen and (max-width: 415px){
        #newmain{
            overflow: auto;float: left;
            margin-left:70%;width: 1660px;
        }
        #KIKO{
            float: none;
            margin-left:15px;
            margin-bottom: 3px;
            text-align: left;
            width: 70%;
        }
        #KIKO1{
            float: none;
            margin-left:15px;
            margin-bottom: 3px;
            width: 80%;
        }
        #search-suggest {
            left: -11px;
            top: 400px;
        }
        #Lesson-suggest{
            top: 464px;
        }
    }
</style>

{{--日历相关--}}
<link rel="stylesheet" href="calendar1/css/bootstrap-datetimepicker.css" />

<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="calendar/DateTimePicker-ltie9.css" />
<script type="text/javascript" src="calendar/DateTimePicker-ltie9.js"></script>
<![endif]-->

{{--<link rel="stylesheet" type="text/css" href="css/EvaluationTableCss/reset.css" />--}}
{{--<link rel="stylesheet" type="text/css" href="css/EvaluationTableCss/style.css" />--}}
{{--<link rel="stylesheet" type="text/css" href="css/EvaluationTableCss/zzsc.css" />--}}
<script src="assets/js/jquery-2.0.3.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script>
    var url=location.href;
    var flag = null;
    var LessonWeekday=null;//听课星期的值
    var lessonTime = null;
    url = url.replace(/</g,"&lt;").replace(/>/g,"&gt");
    var tmp1=url.split("?")[1];
    if (tmp1!=null)
    {
        tmp1=decodeURI(tmp1);
        flag=tmp1.split("&")[0].split("=")[1];
        if(flag ==0)//课程表跳转
        {
            var unit=tmp1.split("&")[1].split("=")[1];
            var year=tmp1.split("&")[2].split("=")[1];
            var semester=tmp1.split("&")[3].split("=")[1];
            var Teacname=tmp1.split("&")[4].split("=")[1];
            var lesson=tmp1.split("&")[5].split("=")[1];
            var room=tmp1.split("&")[6].split("=")[1];
            var Class=tmp1.split("&")[7].split("=")[1];
            LessonWeekday=tmp1.split("&")[8].split("=")[1];
            lessonTime=tmp1.split("&")[9].split("=")[1];
        }
        if(flag !=0)//评价详情跳转
        {
            var chapter=tmp1.split("&")[1].split("=")[1];
            var lesson=tmp1.split("&")[2].split("=")[1];
            var Teacname=tmp1.split("&")[3].split("=")[1];
            var Class=tmp1.split("&")[4].split("=")[1];
            var room=tmp1.split("&")[5].split("=")[1];
            var Listendate=tmp1.split("&")[6].split("=")[1];
            var lessontime=tmp1.split("&")[7].split("=")[1];
            var Attr=tmp1.split("&")[8].split("=")[1];
            var Super=tmp1.split("&")[9].split("=")[1];
            LessonWeekday=new Date(Listendate).getDay();
            //获取课程节次和周数
            $.ajax({
                type: "get",
                async: false,
                url: "/GetLessonTimeBylistendate",
                data: {
                    Lesson_name: lesson,
                    Teacher: Teacname,
                    Class: Class,
                    Room:room,
                    LessonWeekday:LessonWeekday
                },//传递学院名称
                success: function (result) {
                    if (result != '')
                        lessonTime=result[0]['lesson_time'];
                    else
                        lessonTime = '';
                }
            });

        }
    }
</script>
<body onLoad="isReady=true">

@include('layout.header')

<div id="newmain" class="container-fluid clearfix">
    <div class="row clearfix">
        @include('layout.sidebar')
                <!-- 面板开始 -->
        <div id="mianban" class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2"  style="margin-top: 20px">
            <div class="page-content form-content">
                <div class="page-box">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <p style="font-size:20px; " class="panel-title">北京林业大学本科教学督导听课评价表(体育课评价用表)</p>
                        </div>
                        <div class="panel-body">

                            <form class="form-horizontal">
                                <div class="form-group">

                                    <span id="KIKO" for="inputChapter" class="col-lg-1" style="padding-top: 8px;">章节目录</span>
                                    <div id="KIKO1" class="col-sm-4">
                                        <input type="text" class="form-control" id="inputChapter" placeholder="必填">
                                    </div>

                                    <span id="KIKO" for="inputLessonAttr" class="col-lg-1" style="padding-top: 8px;">课程属性</span>
                                    <div id="KIKO1"  class="col-sm-2">
                                        <select class="form-control" name="inputLessonAttr" id="inputLessonAttr"
                                                style="display: inline-block!important; color: lightgrey"
                                                onchange="inputLessonAttrAddCss()">
                                            <option disabled="disabled" selected="selected" style="color: lightgrey">请选择</option>
                                            <option style="color: black">必修课</option>
                                            <option style="color: black">专业选修课</option>
                                            <option style="color: black">全校公共选修课</option>
                                        </select>
                                    </div>

                                    @if(session('role')=='校级')
                                        <span id="KIKO"  for="inputChapterID" class="col-lg-1" style="padding-top: 8px;">督导ID</span>
                                        {{--原来的搜索框--}}
                                        <div id="KIKO1" class="col-sm-1">
                                            <div class="search" >
                                                <form target="_blank" id="search-formID">
                                                    <input id="SearchBarID" class="form-control icon-remove"
                                                           type="text" value="" autocomplete="off" readonly="readonly"
                                                           style="width: 90%;">
                                                </form>
                                            </div>
                                        </div>
                                        <span id="KIKO" for="inputChapter" class="col-lg-1" style="padding-top: 8px;">督导姓名</span>
                                        {{--原来的搜索框--}}
                                        <div id="KIKO1" class="col-sm-1">
                                            <div class="search" >
                                                <form target="_blank" id="search-form">
                                                    <input id="SearchBar" class="form-control icon-remove"
                                                           type="text" value="" autocomplete="off" placeholder="督导姓名"
                                                           style="width: 100%;" unselectable="on"
                                                            {{--onkeydown="$('#SearchBar').val('');$('#SearchBarID').val('');"--}}
                                                    >
                                                </form>
                                            </div>
                                        </div>
                                    @endif

                                    @if(session('role')=='督导'||session('role')=='小组长')
                                        <span id="KIKO" for="inputChapterID" class="col-lg-1" style="padding-top: 8px;">督导ID</span>
                                        <div id="KIKO1" class="col-sm-1">
                                            <input type="text" class="form-control" id="SearchBarID" value="{{Auth::User()->user_id}}" readonly="readonly">
                                        </div>
                                        <span id="KIKO" for="inputChapter" class="col-lg-1" style="padding-top: 8px;">督导姓名</span>
                                        <div id="KIKO1" class="col-sm-1">
                                            <input type="text" class="form-control" id="SearchBar" value="{{Auth::User()->name}}" readonly="readonly">
                                        </div>
                                    @endif

                                    @if(session('role')=='校级')
                                        <div class="suggest" id="search-suggest" style="z-index: 9999">
                                            <ul id="search_result">

                                            </ul>
                                        </div>
                                    @endif

                                    {{--课程搜索框下拉列表--}}
                                    <div class="suggestClass" id="Lesson-suggest" >
                                        <ul id="Lesson_result">

                                        </ul>
                                    </div>

                                    {{--课程节次框--}}
                                    <div id="LessonTimeStyle">
                                        <div id="LessonTime-suggest" >
                                        </div>
                                        <div class="box_down">
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <table class="table table-bordered">
                                <tr>
                                    <th>课程名称</th>
                                    <th>任课教师</th>
                                    <th>听课时间</th>
                                    <th>听课节次</th>
                                    <th>上课班级</th>
                                    <th>上课地点</th>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="LessonName" placeholder="课程名   教师名进行搜索"></td>
                                    <td><input type="text" class="form-control" id="Teacher" readonly="readonly"></td>
                                    <input type="text" class="form-control" id="TeacherID" readonly="readonly" style="display: none">
                                    <td><input type="text" class="form-control" id="ListenTime" placeholder="选择日期"></td>
                                    <td><input type="text" class="form-control" id="LessonTime" placeholder="请选择听课时长"></td>
                                    <td><input type="text" class="form-control" id="LessonClass" readonly="readonly"></td>
                                    <td><input type="text" class="form-control" id="LessonRoom"  readonly="readonly" ></td>

                                </tr>
                            </table>
                            <div class="alert alert-danger" style="font-size: 19px; text-align: left;">
                                <strong>注：</strong><br \>
                                （1）“教师授课情况总体评价”为“非常满意”，需满足如下条件：8项评价中，非常满意≥5，其中标★项目评价必须为非常满意；且没有“存在不足”及以下。<br \>
                                （2）“教师授课情况总体评价”为“存在明显不足”，需满足如下条件：8项评价中，存在明显不足≥3。
                            </div>



                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active"><a href="#front" data-toggle="tab">评价表正面</a></li>
                                <li><a href="#back" data-toggle="tab" >评价表背面（选填）</a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content content-font" style="padding-bottom: 70px">
                                <div class="tab-pane fade in active" id="front">
                                    <div>
                                        {{--front 第一维数组是菜单等级，第二维数组是表的种类，第三维内容--}}
                                        <?php
                                        for($i=0;$i<count($front[1]);$i++)
                                        {
                                            echo '
                                            <ul class="grade1">
                                                <li>
                                                <span class="icon-folder-open-alt" style="display:none"></span>
                                                <h1 style="width: 100%;"><strong>'.$front[1][$i]->text.'</strong></h1>';
                                            if(!array_key_exists($i,$front[2]))continue;
                                            $first=0;$last=-1;
                                            $cnt=0;
                                            while(1)
                                            {
                                                ++$cnt;
                                                $first=$last+1;
                                                while($last+1<count($front[2][$i])&&$front[2][$i][$last+1]->cssstyle==$front[2][$i][$first]->cssstyle)$last++;
                                                $cssstyle=$front[2][$i][$first]->cssstyle;
                                                switch($cssstyle)
                                                {
                                                    case 1:
                                                        for($j=$first;$j<=$last;$j++)
                                                        {
                                                            echo'
                                                                <ul class="grade2">
                                                                    <li>
                                                                    <span style="float:left; margin-top: 4px;"></span>
                                                                    <h2 style="width: 500px;">'.$front[2][$i][$j]->text.'</h2>';
                                                            if(!array_key_exists($j,$front[3][$i]))continue;
                                                            for($k=0;$k<count($front[3][$i][$j]);$k++)
                                                            {
                                                                echo '
                                                                        <ul style="display: inline-block;" class="grade3">
                                                                            <li style="margin-top: 20px;">
                                                                            <h3>'.$front[3][$i][$j][$k]->text.'</h3>';
                                                                echo '
                                                                            </li>
                                                                        </ul>';
                                                            }
                                                            echo '
                                                                    </li>
                                                                </ul>';
                                                        }
                                                        break;
                                                    case 2:
                                                        echo '<div style="padding-top: 30px" class="radiograde">';
                                                        echo '<dd >';
                                                        for ($j=$first;$j<=$last;$j++)
                                                        {
                                                            echo '<div class="radio" >';
                                                            echo '    <label >';
                                                            echo '    <input type="radio" name="optionsRadios'.$cnt.'" id="optionsRadios'.$j.'" value="option'.$j.'">';
                                                            echo $front[2][$i][$j]->text;
                                                            echo '   </label>';
                                                            echo '</div>';
                                                        }
                                                        echo '</dd>';
                                                        echo '</div>';
                                                        break;
                                                    case 3:
                                                        echo '<div style="padding-top: 30px" class="checkboxgrade">';
                                                        echo '<dd>';
                                                        for ($j=$first;$j<=$last;$j++)
                                                        {
                                                            echo '<div class="checkbox">';
                                                            echo '<label>';
                                                            echo '<input type="checkbox" name="checkbox" value="checkbox">';
                                                            echo $front[2][$i][$j]->text;
                                                            echo '</label>';
                                                            echo '</div>';
                                                        }
                                                        echo '</dd>';
                                                        echo '</div>';
                                                        break;
                                                    case 4:
                                                        echo '<ul  style="ul{text-align:center;list-style-type:none;}" class="textareagrade"> ';
                                                        for ($j=$first;$j<=$last;$j++)
                                                        {
                                                            echo '<li class="textarea" style=""> ';
                                                            echo '<form class="form-horizontal" > ';
                                                            echo '<div  class="form-group" style="width: 100%"> ';
                                                            echo '<label style="width: auto" class="col-sm-3 control-label" >';
                                                            echo $front[2][$i][$j]->text;
                                                            echo '</label> ';
                                                            echo '<div class="col-sm-3" style="width: auto"> ';
                                                            echo '<input type="text" class="form-control"> ';
                                                            echo '</form>';
                                                            echo '</li>';
                                                        }
                                                        echo '</ul>';
                                                        break;
                                                    default:
                                                        break;
                                                }
                                                if($last+1==count($front[2][$i]))break;
                                            }

                                            echo'
                                                </li>
                                            </ul>';
                                        }?>
                                    </div>
                                </div>
                                <div class="tab-pane fade content-back" id="back">
                                    <div>
                                        {{--front 第一维数组是菜单等级，第二维数组是表的种类，第三维内容--}}
                                        <?php
                                        for($i=0;$i<count($back[1]);$i++)
                                        {
                                            echo '
                                            <ul class="grade1">
                                                <li>
                                                <span class="icon-folder-open-alt" style="display:none"></span>
                                                <h1>'.$back[1][$i]->text.'</h1>';
                                            if(!array_key_exists($i,$back[2]))continue;
                                            $first=0;$last=-1;
                                            $cnt=0;
                                            while(1)
                                            {
                                                ++$cnt;
                                                $first=$last+1;
                                                while($last+1<count($back[2][$i])&&$back[2][$i][$last+1]->cssstyle==$back[2][$i][$first]->cssstyle)$last++;
                                                $cssstyle=$back[2][$i][$first]->cssstyle;

                                                switch($cssstyle)
                                                {
                                                    case 1:
                                                        for($j=$first;$j<=$last;$j++)
                                                        {
                                                            echo'
                                                            <ul class="grade2">
                                                                <li>
                                                                <span style="float:left; margin-top: 4px;"></span>
                                                                <h2 style="width: 600px;">'.$back[2][$i][$j]->text.'</h2>';
                                                            if(!array_key_exists($j,$back[3][$i]))continue;
                                                            for($k=0;$k<count($back[3][$i][$j]);$k++)
                                                            {
                                                                echo '
                                                                        <ul style="display: inline-block;" class="grade3">
                                                                            <li style="margin-top: 20px;">
                                                                            <h3>'.$back[3][$i][$j][$k]->text.'</h3>';
                                                                echo '
                                                                            </li>
                                                                      </ul>';
                                                            }
                                                            echo '
                                                                </li>
                                                            </ul>';
                                                        }
                                                        break;
                                                    case 2:
                                                        echo '<div style="margin-top: 40px;padding-top: 20px" class="radiograde">';
                                                        echo '<dd >';
                                                        for ($j=$first;$j<=$last;$j++)
                                                        {
                                                            echo '<div class="radio" >';
                                                            echo '    <label >';
                                                            echo '    <input type="radio" name="optionsRadios'.$cnt.'" id="optionsRadios'.$j.'" value="option'.$j.'">';
                                                            echo $back[2][$i][$j]->text;
                                                            echo '   </label>';
                                                            echo '</div>';
                                                        }
                                                        echo '</dd>';
                                                        echo '</div>';
                                                        break;
                                                    case 3:
                                                        echo '<div style="padding-top: 20px" class="checkboxgrade">';
                                                        echo '<dd>';
                                                        for ($j=$first;$j<=$last;$j++)
                                                        {
                                                            echo '<div class="checkbox">';
                                                            echo '<label>';
                                                            echo '<input type="checkbox" name="checkbox" value="checkbox">';
                                                            echo $back[2][$i][$j]->text;
                                                            echo '</label>';
                                                            echo '</div>';
                                                        }
                                                        echo '</dd>';
                                                        echo '</div>';
                                                        break;
                                                    case 4:
                                                        echo '<ul  style="ul{text-align:center;list-style-type:none;}" class="textareagrade"> ';
                                                        for ($j=$first;$j<=$last;$j++)
                                                        {
                                                            echo '<li class="textarea" style=""> ';
                                                            echo '<form class="form-horizontal" > ';
                                                            echo '<div  class="form-group"> ';
                                                            echo '<label style="width: auto" class="col-sm-3 control-label" >';
                                                            echo $back[2][$i][$j]->text;
                                                            echo '</label> ';
                                                            echo '<div class="col-sm-3" style="width: auto"> ';
                                                            echo '<input type="text" class="form-control"> ';
                                                            echo '</form>';
                                                            echo '</li>';
                                                        }
                                                        echo '</ul>';
                                                        break;
                                                    default:
                                                        break;
                                                }
                                                if($last+1==count($back[2][$i]))break;
                                            }

                                            echo'
                                                </li>
                                            </ul>';
                                        }?>
                                    </div>
                                </div>
                                <button class="btn btn-success btn-raised tabBack" style="float: right;display: block;margin-top: 10px;" >评价表背面（选填）</button>
                                <button class="btn btn-success btn-raised tabFront" style="float: right; display: none;"  >评价表正面</button>
                            </div>
                            <button class="btn btn-success btn-raised submitTable" style="float: right;margin-top: 46px;margin-left:8px;display: none" >提交评价表</button>
                            <button class="btn btn-warning btn-raised saveTable" style="float: right; margin-top: 46px;display: none" >保存</button>

                        </div>
                    </div>
                </div>
            </div>
            <!-- .page-content 结束 -->
        </div>
        <!-- 面板结束 -->
    </div>
</div>
@include('layout.footer')
</body>
<script>
    /*------------点击正反页按钮-----------------*/
    $("#myTab li").eq(1).click(function () {
        $('.tabBack').css("display",'none');
        $('.tabFront,.submitTable,.saveTable').css("display",'block');

    });
    $("#myTab li").eq(0).click(function () {
        $('.tabBack').css("display",'block');
        $('.tabFront,.submitTable,.saveTable').css("display",'none');

    });
    $('.tabBack').click(function () {
        $('#myTab li:eq(1) a').tab('show');
        $('.tabBack').css("display",'none');
        $('.tabFront,.submitTable,.saveTable').css("display",'block');
    });
    $('.tabFront').click(function () {
        $('#myTab li:eq(0) a').tab('show');
        $('.tabBack').css("display",'block');
        $('.tabFront,.submitTable,.saveTable').css("display",'none');
    });
    /*---------------------结束-----------------*/
    $(".grade3").append(' ' +
            '<div class="box demo2" style="display: inline-block;width:600px;"> ' +
            '<ul class="tab_menu" style="display: inline-block;"> ' +
            '<li class="bar1">非常满意</li> ' +
            '<li class="bar2">满意</li> ' +
            '<li class="bar3">正常</li> ' +
            '<li class="bar4">不足</li> ' +
            '<li class="bar5">明显不足</li> ' +
            '</ul> ' +
            '</div>');
    $('h2:contains("总体评价")').parent().parent().append(' ' +
            '<div class="box demo2" style="display: inline-block;width:600px;"> ' +
            '<ul class="tab_menu" style="display: inline-block;"> ' +
            '<li class="bar1">非常满意</li> ' +
            '<li class="bar2">满意</li> ' +
            '<li class="bar3">正常</li> ' +
            '<li class="bar4">不足</li> ' +
            '<li class="bar5">明显不足</li> ' +
            '</ul> ' +
            '</div>');
    $('label:contains("评述")').parent().children('div').css("width","500px");
    $('h2:contains("到课情况")').css("width","100%");
    $('h2:contains("到课情况")').css("padding","15px");

    $('h2:contains("如果以上各方面不能准确表达您的意见")').css("width","100%");
    $('h2:contains("如果以上各方面不能准确表达您的意见")').css("padding","10px");

    $(function(){
        $(":radio").click(function(){
            //检查当前单选框是否为选中状态
            if($(this).attr('checked'))
                $(this).attr('checked',false);
            else
                $(this).attr('checked',true);
        });
    });
</script>
<script src="js/jquery.tabs.js"></script>
<script src="calendar1/js/bootstrap-datetimepicker1.min.js"></script>
<script src="calendar1/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="js/HelpFunction.js"></script>
<script>
    var split_flag='___';//课程信息的分隔符
    function GetContent(LessonState)
    {
        var flagC =checkNeceHead_Input(LessonState);//0:成功通过验证，1：提交必填项失败 2：保存必填项失败
        var Frontlist=[];//正面选择框的值
        for(i=0;i<$($('#front').children()[0]).children().length;i++)
        {
            var textlevel1=$($($($('#front').children()[0]).children()[i]).children()[0]).children()[1].innerText;
            for(var j=2;j<$($($($('#front').children()[0]).children()[i]).children()[0]).children().length;j++)
            {
                var cssstyle=$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).attr("class");
                if(cssstyle=="grade2")
                {
                    var textlevel2=$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[1].innerText;
                    if(textlevel2.indexOf("总体评价")>=0)
                    {
                        var choose=$($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children().filter(".current")[0];
                        textlevel2=$.trim(textlevel2);
                        if(choose)
                        {
                            choose=choose.innerHTML;
                            choose=$.trim(choose);
                            obj=
                            {
                                key:textlevel2,
                                value:choose
                            };
                        }
                        else
                        {
                            obj=
                            {
                                key:textlevel2,
                                value:""
                            };
                        }
                        Frontlist.push(obj);
                        continue;
                    }
                    for(var k=2;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
                    {
                        var textlevel3=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children().children()[0].innerText;
                        var choose=$($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children().filter(".current")[0];
                        textlevel3=$.trim(textlevel3);
                        if(choose)
                        {
                            choose=choose.innerHTML;
                            choose=$.trim(choose);
                            obj=
                            {
                                key:textlevel3,
                                value:choose
                            };
                        }
                        else
                        {
                            obj=
                            {
                                key:textlevel3,
                                value:""
                            };
                        }
                        Frontlist.push(obj);
                    }
                }
                if(cssstyle=="radiograde")
                {
                    for(var k=0;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
                    {
                        var choosecontent=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
                        var checked=$($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).attr('checked');
                        checked=$.trim(checked);
                        choosecontent=$.trim(choosecontent);
                        if(checked=="checked")
                        {
                            obj=
                            {
                                key:choosecontent,
                                value:1
                            };
                            Frontlist.push(obj);
                        }
                        else {
                            obj=
                            {
                                key:choosecontent,
                                value:0
                            };
                            Frontlist.push(obj);
                        }

                    }
                }
                if(cssstyle=="checkboxgrade")
                {
                    for(var k=0;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
                    {
                        var choosecontent=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
                        var checked=$($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).is(":checked");
                        checked=$.trim(checked);
                        choosecontent=$.trim(choosecontent);
                        if(checked=="true")
                        {
                            obj=
                            {
                                key:choosecontent,
                                value:1
                            };
                            Frontlist.push(obj);
                        }
                        else {
                            obj=
                            {
                                key:choosecontent,
                                value:0
                            };
                            Frontlist.push(obj);
                        }

                    }
                }
                if(cssstyle=="textareagrade")
                {
                    for(var k=0;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children().length;k++)
                    {
                        var text=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[k]).children()[0].innerText;
                        var val=$($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[k]).children()[1]).children().val();
                        text=$.trim(text);
                        val=$.trim(val);
                        obj=
                        {
                            key:text,
                            value:val
                        };
                        console.log(obj);
                        Frontlist.push(obj);
                    }
                }
            }
        }

        var Backlist=[];//背面选择框的值
        for(i=0;i<$($('#back').children()[0]).children().length;i++)
        {
            var textlevel1=$($($($('#back').children()[0]).children()[i]).children()[0]).children()[1].innerText;
            for(var j=2;j<$($($($('#back').children()[0]).children()[i]).children()[0]).children().length;j++)
            {
                var cssstyle=$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).attr("class");
                if(cssstyle=="grade2")
                {
                    var textlevel2=$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[1].innerText;
                    if(textlevel2.indexOf("总体评价")>=0)
                    {
                        var choose=$($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children().filter(".current")[0];
                        textlevel2=$.trim(textlevel2);
                        if(choose)
                        {
                            choose=choose.innerHTML;
                            choose=$.trim(choose);
                            obj=
                            {
                                key:textlevel2,
                                value:choose
                            };
                        }
                        else
                        {
                            obj=
                            {
                                key:textlevel2,
                                value:""
                            };
                        }
                        Backlist.push(obj);
                        continue;
                    }
                    for(var k=2;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
                    {
                        var textlevel3=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children().children()[0].innerText;
                        var choose=$($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children().filter(".current")[0];
                        textlevel3=$.trim(textlevel3);
                        if(choose)
                        {
                            choose=choose.innerHTML;
                            choose=$.trim(choose);
                            obj=
                            {
                                key:textlevel3,
                                value:choose
                            };
                        }
                        else
                        {
                            obj=
                            {
                                key:textlevel3,
                                value:""
                            };
                        }
                        Backlist.push(obj);
                    }
                }
                if(cssstyle=="radiograde")
                {
                    for(var k=0;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
                    {
                        var choosecontent=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
                        var checked=$($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).attr('checked');
                        checked=$.trim(checked);
                        choosecontent=$.trim(choosecontent);
                        if(checked=="checked")
                        {
                            obj=
                            {
                                key:choosecontent,
                                value:1
                            };
                            Backlist.push(obj);
                        }
                        else {
                            obj=
                            {
                                key:choosecontent,
                                value:0
                            };
                            Backlist.push(obj);
                        }
                    }
                }
                if(cssstyle=="checkboxgrade")
                {
                    for(var k=0;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
                    {
                        var choosecontent=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
                        var checked=$($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).is(":checked");
                        checked=$.trim(checked);
                        choosecontent=$.trim(choosecontent);
                        if(checked=="true")
                        {
                            obj=
                            {
                                key:choosecontent,
                                value:1
                            };
                            Backlist.push(obj);
                        }
                        else {
                            obj=
                            {
                                key:choosecontent,
                                value:0
                            };
                            Backlist.push(obj);
                        }
                    }
                }
                if(cssstyle=="textareagrade")
                {
                    for(var k=0;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children().length;k++)
                    {
                        var text=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[k]).children()[0].innerText;
                        var val=$($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[k]).children()[1]).children().val();
                        text=$.trim(text);
                        val=$.trim(val);
                        obj=
                        {
                            key:text,
                            value:val
                        };
                        console.log(obj);
                        Backlist.push(obj);
                    }
                }
            }
        }
        //如果是待提交状态，将正面未完成标识写入lessonstate，并将其置为可提交状态
        if(LessonState=='待提交')
            if (flagC==1)//必填项未完成
            {
                LessonState+=flagC;
                flagC = 0;
            }
        //后台传数据
        if(flagC==0)
        {
            var frontflag=null;
            var Headlist=TableHeadData();
            console.log(Headlist);
            $.ajax({
                type: "post",
                async: false,
                url: "/DBPhysicalEvaluationTable",
                data:{
                    '_token':'{{csrf_token()}}',
                    headdata: Headlist,
                    LessonState:LessonState,
                    frontdata:Frontlist,
                    backdata:Backlist,
                    valueID:flag
                },
                success:function(result)
                {
                    alert("填写评价表成功！");
                    if(result!='')
                        frontflag = result;
                }
            });
            if(frontflag!='')
            {
                alert(frontflag);
//                window.location.href="/EverEvaluated";
            }
        }
    }
    //督导姓名提示框
    var SearchValue=document.getElementById('SearchBar');//督导姓名
    var SearchValueID=document.getElementById('SearchBarID');//督导ID
    //课程提示框
    var LessonValue=document.getElementById('LessonName');//课程信息
    var TeacherValue=document.getElementById('Teacher');//任课教师
    var LessonClassValue=document.getElementById('LessonClass');//上课班级
    var LessonRoomValue=document.getElementById('LessonRoom');//上课地点
    var lessondata=[];
    $(document).ready(function() {
        $('.evaluated-menu').addClass('active');
        $('.supervise-menu').addClass('active');
        $('.work-menu').addClass('active');
        //督导姓名输入框的操作事宜
        chooseSupervisor();
        var timeoutObj;//用于计时，课程信息请求次数限制
        //课程名称输入框的操作事宜
        $('#LessonName').bind('input propertychange',function(){
            var LessonText = $('#LessonName').val();
            if( LessonValue.value=='')
            {
                TeacherValue.value='';
                LessonClassValue.value='';
                LessonRoomValue.value='';
                $('#ListenTime').val('');
            }
            if(timeoutObj)
            {
                clearTimeout(timeoutObj);
            }
            timeoutObj = setTimeout(function(){
                var LessonText = $('#LessonName').val();
                $.ajax({
                    type: "get",
                    async: false,
                    url: "/GetLessonArrPhy",
                    data:{dataIn:LessonText},
                    success: function (result) {
                        lessondata = result;
                        var html='';
                        for (var i=0;i<result.length;i++)
                        {
                            html+='<li class="list-group-item" style="font-size: 14px; ' +
                                    'border-bottom: lightgrey solid 2px">'
                                    +result[i]['lesson_name']+split_flag
                                    +result[i]['lesson_teacher_name']+split_flag
                                    +result[i]['lesson_teacher_id']+split_flag
                                    +result[i]['lesson_week']+'周'+split_flag+'星期'
                                    +result[i]['lesson_weekday']+split_flag
                                    +result[i]['lesson_time'] +split_flag
                                    +result[i]['lesson_class']+split_flag
                                    +result[i]['lesson_room']+'</li>';
                        }
                        $('#Lesson_result').html(html);

                        $('#Lesson-suggest').show().css({
                            position:'absolute',
                            height:'220'
                        });
                        $('.suggestClass').css('border','1px solid #CCC');
                    }
                });
                $(document).bind('click',function(){
                    $('#Lesson-suggest').hide();
                });
                $('#Lesson_result').delegate('li','click',function(){
                    LessonValue.value=$(this).text().split(split_flag)[0];
                    TeacherValue.value=$(this).text().split(split_flag)[1];
                    $('#TeacherID').val($(this).text().split(split_flag)[2]);
                    LessonWeekday=$(this).text().split(split_flag)[4].match(/\d/g);
                    LessonClassValue.value=$(this).text().split(split_flag)[6];
                    LessonRoomValue.value=$(this).text().split(split_flag)[7];
                    lessonTime =$(this).text().split(split_flag)[5];
                    $('#LessonTime').attr("disabled",false).val('');
                    $('#ListenTime').attr("disabled",false).val('');
                    //如果上课地点或者上课班级为空，则开放上课班级和上课地点编辑框
                    if(LessonClassValue.value=='')
                    {
                        $('#LessonClass').attr("readonly",false);
                        $('#LessonClass').attr("disabled",false);
                    }
                    if(LessonRoomValue.value=='')
                    {
                        $('#LessonRoom').attr("disabled",false);
                        $('#LessonRoom').attr("readonly",false);
                    }
                    /************************************************日历相关函数***************************************************/
                    var date_arr = null;
                    if (LessonWeekday!=null)
                    {
                        date_arr = new Array(0,1,2,3,4,5,6);
                        date_arr.splice(LessonWeekday,1);
                    }
                    chooseDate(date_arr);
                });
            },400);
        }).bind('click',function(ev){
            var oEvent=ev||event;
            oEvent.stopPropagation();
            var LessonText = $('#LessonName').val();
            if(LessonValue.value=='')
            {
                TeacherValue.value='';
                LessonClassValue.value='';
                LessonRoomValue.value='';
                $('#ListenTime').val('');
            }
            $.ajax({
                type: "get",
                async: false,
                url: "/GetLessonArrPhy",
                data:{dataIn:LessonText},
                success: function (result) {
                    lessondata=result;
                    var html='';
                    for(var i=0;i<result.length;i++)
                    {
                        html+='<li class="list-group-item" style="font-size: 14px; ' +
                                'border-bottom: lightgrey solid 2px">'
                                +result[i]['lesson_name']+split_flag
                                +result[i]['lesson_teacher_name']+split_flag
                                +result[i]['lesson_teacher_id']+split_flag
                                +result[i]['lesson_week']+'周'+split_flag+'星期'
                                +result[i]['lesson_weekday']+split_flag
                                +result[i]['lesson_time'] +split_flag
                                +result[i]['lesson_class']+split_flag
                                +result[i]['lesson_room']+'</li>';
                    }
                    $('#Lesson_result').html(html);
                    $('#Lesson-suggest').show().css({
                        position:'absolute',
                        height:'220'
                    });
                    $('.suggestClass').css('border','1px solid #CCC');
                }
            });
            $(document).bind('click',function(){
                $('#Lesson-suggest').hide();
            });
            $('#Lesson_result').delegate('li','click',function(){
                LessonValue.value=$(this).text().split(split_flag)[0];
                TeacherValue.value=$(this).text().split(split_flag)[1];
                $('#TeacherID').val($(this).text().split(split_flag)[2]);
                LessonClassValue.value=$(this).text().split(split_flag)[6];
                LessonRoomValue.value=$(this).text().split(split_flag)[7];
                LessonWeekday=$(this).text().split(split_flag)[4].match(/\d/g);
                lessonTime =$(this).text().split(split_flag)[5];
                $('#LessonTime').attr("disabled",false).val('');
                $('#ListenTime').attr("disabled",false).val('');
                //如果上课地点或者上课班级为空，则开放上课班级和上课地点编辑框
                if(LessonClassValue.value=='')
                {
                    $('#LessonClass').attr("readonly",false);
                    $('#LessonClass').attr("disabled",false);
                }
                if(LessonRoomValue.value=='')
                {
                    $('#LessonRoom').attr("disabled",false);
                    $('#LessonRoom').attr("readonly",false);
                }
                /************************************************日历相关函数***************************************************/
                var date_arr = null;
                if (LessonWeekday!=null)
                {
                    date_arr = new Array(0,1,2,3,4,5,6);
                    date_arr.splice(LessonWeekday,1);
                }
                chooseDate(date_arr);
            });
        });
        //如果是从课程表中跳转过来的话，日历函数的操作为
        //课程节次
        //课程节次的坐标
        var LessonTime_X = $('#LessonTime').position().left;
        var LessonTime_Y = $('#LessonTime').position().top;
        $(window).resize(function (){
            LessonTime_X = $('#LessonTime').position().left;
            LessonTime_Y = $('#LessonTime').position().top;
        });
        if($('#LessonName').val()=='')
            $('#LessonTime').attr("disabled",true);
        else
            $('#LessonTime').attr("disabled",false);

        if($('#LessonName').val()=='')//课程节次
            $('#ListenTime').attr("disabled",true);
        else
            $('#ListenTime').attr("disabled",false);
        //如果是从课程表中跳转过来的话，日历函数的操作为
        if(flag==0)
        {
            var date_arr=null;
            if(LessonWeekday!=null)
            {
                date_arr=new Array(0,1,2,3,4,5,6);
                date_arr.splice(LessonWeekday,1);
            }
            chooseDate(date_arr);
            //如果是从课程表中跳转过来的话，若课程没有提供上课地点，则为
            if(LessonClassValue.value=='')
            {
                $('#LessonClass').attr("readonly",false);
                $('#LessonClass').attr("disabled",false);
            }
            if(LessonRoomValue.value=='')
            {
                $('#LessonRoom').attr("disabled",false);
                $('#LessonRoom').attr("readonly",false);
            }
        }
        if(flag != 0 && flag != null){
            $.ajax({
                type: "GET",
                async: false,
                url: "/PhysicalEvaluationEdit",
                data: {
                    Flag: flag
                },
                success: function(result){
                    //正面评价表信息填写
                    console.log(result.front);
                    //3级内容
                    for (var i=0;i<$('#front ul.grade3').length;i++)
                    {
                        var $KeyCheck=$('#front ul.grade3 h3')[i].innerText;
                        for (var key in result.front["0"])
                        {
                            if(key == $KeyCheck)
                            {
                                if(result.front["0"][key] == "非常满意")
                                {
                                    $($('#front ul.grade3 li.bar1')[i]).addClass("current");
                                }
                                else if (result.front["0"][key] == "满意")
                                {
                                    $($('#front ul.grade3 li.bar2')[i]).addClass("current");
                                }
                                else if (result.front["0"][key] == "正常")
                                {
                                    $($('#front ul.grade3 li.bar3')[i]).addClass("current");
                                }
                                else if (result.front["0"][key] == "不足")
                                {
                                    $($('#front ul.grade3 li.bar4')[i]).addClass("current");
                                }
                                else if (result.front["0"][key] == "明显不足")
                                {
                                    $($('#front ul.grade3 li.bar5')[i]).addClass("current");
                                }
                                break;
                            }
                        }
                    }
                    //总体评价
                    for (var i=0;i<$('#front ul.grade2 h2').length;i++)
                    {
                        var $KeyCheck=$('#front ul.grade2 h2')[i].innerText;
                        if($KeyCheck.indexOf("总体评价")>=0)
                        {
//                            console.log($KeyCheck);
                            for (var key in result.front["0"]) {
//                                console.log(key);
                                if (key == $KeyCheck) {
                                    if (result.front["0"][key] == "非常满意") {
                                        $($($('#front ul.grade2')[i]).find("li.bar1")).addClass("current");
                                    }
                                    else if (result.front["0"][key] == "满意") {
                                        $($($('#front ul.grade2')[i]).find("li.bar2")).addClass("current");
                                    }
                                    else if (result.front["0"][key] == "正常") {
                                        $($($('#front ul.grade2')[i]).find("li.bar3")).addClass("current");
                                    }
                                    else if (result.front["0"][key] == "不足") {
                                        $($($('#front ul.grade2')[i]).find("li.bar4")).addClass("current");
                                    }
                                    else if (result.front["0"][key] == "明显不足") {
                                        $($($('#front ul.grade2')[i]).find("li.bar5")).addClass("current");
                                    }
                                    break;
                                }
                            }
                        }
                    }
                    //radiograde
                    for (var i=0;i<$('#front .radio').length;i++)
                    {
                        var $KeyCheck=$.trim($($('#front .radio label')[i])["0"].innerText);
                        for(var key in result.front["0"])
                        {
                            if (key == $KeyCheck && result.front["0"][key] == "1")
                            {
                                $($($('#front .radio input')[i])["0"]).prop("checked","true");
                                break;
                            }
                        }
                    }
                    //checkboxgrade
                    for (var i=0;i<$('#front .checkbox').length;i++)
                    {
                        var $KeyCheck=$.trim($($('#front .checkbox label')[i])["0"].innerText);
                        for(var key in result.front["0"])
                        {
                            if (key == $KeyCheck && result.front["0"][key] == "1")
                            {
                                $($($('#front .checkbox input')[i])["0"]).prop("checked","true");
                                break;
                            }
                        }
                    }
                    //textareagrade
                    for (var i=0;i<$('#front .textarea label').length;i++)
                    {
                        var $KeyCheck=$($('#front .textarea label')[i])["0"].innerText;
                        for(var key in result.front["0"])
                        {
                            if (key == $KeyCheck)
                            {
                                $($($('#front .textarea')[i]).find("input")).val(result.front["0"][key]);
                            }
                        }
                    }

                    //背面评价表信息填写
                    console.log(result.back);
                    //3级内容
                    for (var i=0;i<$('#back ul.grade3').length;i++)
                    {
                        var $KeyCheck=$('#back ul.grade3 h3')[i].innerText;
                        for (var key in result.back["0"])
                        {
                            if(key == $KeyCheck)
                            {
                                if(result.back["0"][key] == "非常满意")
                                {
                                    $($('#back ul.grade3 li.bar1')[i]).addClass("current");
                                }
                                else if (result.back["0"][key] == "满意")
                                {
                                    $($('#back ul.grade3 li.bar2')[i]).addClass("current");
                                }
                                else if (result.back["0"][key] == "正常")
                                {
                                    $($('#back ul.grade3 li.bar3')[i]).addClass("current");
                                }
                                else if (result.back["0"][key] == "不足")
                                {
                                    $($('#back ul.grade3 li.bar4')[i]).addClass("current");
                                }
                                else if (result.back["0"][key] == "明显不足")
                                {
                                    $($('#back ul.grade3 li.bar5')[i]).addClass("current");
                                }
                                break;
                            }
                        }
                    }
                    //总体评价
                    for (var i=0;i<$('#back ul.grade2 h2').length;i++)
                    {
                        var $KeyCheck=$('#back ul.grade2 h2')[i].innerText;
                        if($KeyCheck.indexOf("总体评价")>=0)
                        {
//                            console.log($KeyCheck);
                            for (var key in result.back["0"]) {
//                                console.log(key);
                                if (key == $KeyCheck) {
                                    if (result.back["0"][key] == "非常满意") {
                                        $($($('#back ul.grade2')[i]).find("li.bar1")).addClass("current");
                                    }
                                    else if (result.back["0"][key] == "满意") {
                                        $($($('#back ul.grade2')[i]).find("li.bar2")).addClass("current");
                                    }
                                    else if (result.back["0"][key] == "正常") {
                                        $($($('#back ul.grade2')[i]).find("li.bar3")).addClass("current");
                                    }
                                    else if (result.back["0"][key] == "不足") {
                                        $($($('#back ul.grade2')[i]).find("li.bar4")).addClass("current");
                                    }
                                    else if (result.back["0"][key] == "明显不足") {
                                        $($($('#back ul.grade2')[i]).find("li.bar5")).addClass("current");
                                    }
                                    break;
                                }
                            }
                        }
                    }
                    //radiograde
                    for (var i=0;i<$('#back .radio').length;i++)
                    {
                        var $KeyCheck=$.trim($($('#back .radio label')[i])["0"].innerText);
                        for(var key in result.back["0"])
                        {
                            if (key == $KeyCheck && result.back["0"][key] == "1")
                            {
                                $($($('#back .radio input')[i])["0"]).prop("checked","true");
                                break;
                            }
                        }
                    }
                    //checkboxgrade
                    for (var i=0;i<$('#back .checkbox').length;i++)
                    {
                        var $KeyCheck=$.trim($($('#back .checkbox label')[i])["0"].innerText);
                        for(var key in result.back["0"])
                        {
                            if (key == $KeyCheck && result.back["0"][key] == "1")
                            {
                                $($($('#back .checkbox input')[i])["0"]).prop("checked","true");
                                break;
                            }
                        }
                    }
                    //textareagrade
                    for (var i=0;i<$('#back .textarea label').length;i++)
                    {
                        var $KeyCheck=$($('#back .textarea label')[i])["0"].innerText;
                        for(var key in result.back["0"])
                        {
                            if (key == $KeyCheck)
                            {
                                $($($('#back .textarea')[i]).find("input")).val(result.back["0"][key]);
                            }
                        }
                    }
                }
            });
        }
        $("#LessonTime").focus(function (){
            $('#LessonTime-suggest').children().remove();
            if(LessonValue.value=='')
            {
                alert('请先选择课程名称');
                $('#LessonTime').attr("disabled",true);
            }
            else
            {
                $('#LessonTimeStyle').css("display","block");
                if(lessonTime == '')
                    AddLessonTime(LessonTime_X,LessonTime_Y,'01020304050607080910');
                else
                    AddLessonTime(LessonTime_X,LessonTime_Y,lessonTime);
            }
        });
        for(var i=0;i<$('#front h2').length;i++)
        {
            if ($('#front h2')[i].innerText.indexOf("总体评价")>=0)
            {
                $($('#front h2')[i]).parents('.grade2').css("background-color","aliceblue");
            }
        }
        for(var i=0;i<$('#front .textarea label').length;i++)
        {
            if ($('#front .textarea label')[i].innerText.indexOf("人数")>=0)
            {
                $($('#front .textarea label')[i]).parent().css('display','none');
            }
        }
        $($('.radiograde')[0]).css('padding-top','60px');
        $(function () {
            $("#LessonTime").click(function (event)
            {
                $(document).one("click", function ()
                {
                    $("#LessonTimeStyle").hide();
                });
                event.stopPropagation();
            });
            $("#LessonTimeStyle").click(function (event)
            {
                event.stopPropagation();
            });
        });
        $(function(){
            $('.demo2').Tabs({
                event:'click'
            });
        });
        (function(){
            $('dl').on('click', 'dt', function() {
                $(this).next().slideToggle(200);
            });
        })();
    });

    $('.submitTable').click(function(){
        var LessonState='已完成';
        GetContent(LessonState);
    });

    $('.saveTable').click(function(){
        var LessonState='待提交';
        GetContent(LessonState);
    });
    var contentFrontdata=[];
    var contentBackdata=[];
    $("#LessonName").val(lesson);
    $("#Teacher").val(Teacname);
    $("#LessonClass").val(Class);
    $("#LessonRoom").val(room);
    if(flag != 0 && flag != null)
    {
        $("#inputChapter").val(chapter);
        $("#inputLessonAttr").val(Attr).css('color','black');
        $("#ListenTime").val(Listendate);
        //根据日期判断星期几
        var b=new Date(Date.parse(Listendate.replace(/\-/g,"/")));
        var date_arr=null;
        if(LessonWeekday!=null)
        {
            date_arr = new Array(0,1,2,3,4,5,6);
            date_arr.splice(b.getDay(),1);
        }
        chooseDate(date_arr);
        $("#LessonTime").val(lessontime);
        //向数据库请求填写信息
        $.ajax({
            type: "get",
            async: false,
            url: "/EvaluationContent",
            data: {
                year1: null,
                year2: null,
                semester: null,
                Lesson_name: lesson,
                Teacher: Teacname,
                Spuervisor: SearchValueID.value,
                Lesson_date: Listendate,
                Lessontime: lessontime
            },//传递学院名称
            success: function (result) {
            }
        });
    }
</script>
</html>
