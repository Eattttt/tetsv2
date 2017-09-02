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
    body {
        padding-top: 0px;
    }
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
    #search-suggest{
        left: 95%;
        top: 200px;
    }
    @media screen and (max-width: 768px){
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
            width: 94%;
        }
        #search-suggest{
            left: 27px;
            top: 440px;
        }
        #Lesson-suggest{
            top: 510px;
        }
        .content{
            padding-left: 12px;
        }

    }
    @media screen and (max-width: 415px){
        .navbar-brand{
            font-size: 16px;
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
            width: 91%;
        }
        #search-suggest {
            left: -11px;
            top: 400px;
        }
        #Lesson-suggest{
            top: 464px;
        }
        .content-font {
            padding-left: 0;
        }
    }
</style>

{{--日历相关--}}
<link rel="stylesheet" href="calendar1/css/bootstrap-datetimepicker.css" />

<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="calendar/DateTimePicker-ltie9.css" />
<script type="text/javascript" src="calendar/DateTimePicker-ltie9.js"></script>
<![endif]-->

<script src="assets/js/jquery-2.0.3.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="js/weixin/jquery.dropdown.css">
<script src="js/weixin/jquery.dropdown.js"></script>

<body onLoad="isReady=true">

@include('weixin.header')

<div class="container-fluid clearfix" style="overflow-x: hidden">
    <div class="row clearfix">
                <!-- 面板开始 -->
        <div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2">
            <div class="content">
                <div class="page-box">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <p style="font-size:20px; " class="panel-title">北京林业大学本科教学督导听课评价表(理论课评价用表)</p>
                        </div>
                        <div class="panel-body">
                            <div class="info">
                                <p style="font-size: 18px;">尊敬的督导：您辛苦了！请您在听课后，认真、负责地填写评价表。</p>
                            </div>
                            <form class="form-horizontal">
                                <div class="form-group">

                                    <span id="KIKO" for="inputChapter" class="col-lg-1" style="padding-top: 8px;">章节目录</span>
                                    <div id="KIKO1" class="col-sm-4">
                                        <input type="text" class="form-control" id="inputChapter">
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


                                        <span id="KIKO" for="inputChapterID" class="col-lg-1" style="padding-top: 8px;">督导ID</span>
                                        <div id="KIKO1" class="col-sm-1">
                                            <input type="text" class="form-control" id="SearchBarID" value="{{Auth::User()->user_id}}" readonly="readonly">
                                        </div>
                                        <span id="KIKO" for="inputChapter" class="col-lg-1" style="padding-top: 8px;">督导姓名</span>
                                        <div id="KIKO1" class="col-sm-1">
                                            <input type="text" class="form-control" id="SearchBar" value="{{Auth::User()->name}}" readonly="readonly">
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
                                    <th>课程信息</th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="dropdown-sin-1">
                                            <select  style="display:none" placeholder="">
                                                <option>1</option>

                                            </select>
                                        </div>
                                        <input type="text" class="form-control" id="LessonName" placeholder="课程名   教师名进行搜索" style="display: none">
                                    </td>
                                </tr>
                                <tr>
                                    <th>任课教师</th>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="Teacher" readonly="readonly"></td>
                                </tr>
                                <tr>
                                    <th>听课时间</th>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="ListenTime" placeholder="选择日期"></td>
                                </tr>
                                <tr>
                                    <th>听课节次</th>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="LessonTime" placeholder="请选择听课时长"></td>
                                </tr>
                                <tr>
                                    <th>上课班级</th>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="LessonClass" readonly="readonly"></td>
                                </tr>
                                <tr>
                                    <th>上课地点</th>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="LessonRoom"  readonly="readonly" ></td>
                                </tr>

                            </table>
                            <div class="alert alert-danger" style="font-size: 19px; text-align: left;">
                                <strong>  &nbsp;&nbsp;&nbsp;&nbsp; 注：</strong><br>
                                (1）5个评价等级为：非常满意、满意、正常、存在不足、存在明显不足。<br>
                                （2）评价内容共两部分：评价表正面和评价表背面。<br>
                                （3）评价表正面除“章节目录、课程属性、学生到课情况、其他”外均为必填项，背面为选填项。<br>
                                （4）此评价表为理论课评价表。
                            </div>



                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active"><a href="#front" data-toggle="tab">评价表正面</a></li>
                                <li><a href="#back" data-toggle="tab" >评价表背面</a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content content-font" style="padding-bottom: 70px">
                                <div class="tab-pane fade in active" id="front">

                                    <div>
                                        {{--front 第一维数组是菜单等级，第二维数组是表的种类，第三维内容--}}
                                        <?php
                                        for ($i=0;$i<count($front[1]);$i++)
                                        {
                                            echo '
                            <ul class="grade1">
                                <li>
                                    <span class="icon-folder-open-alt" style="float:left;margin-top: 4px;"></span>
                                    <h1>'.$front[1][$i].'</h1>';
                                            for($j=0;$j<count($front[2][$i]);$j++)
                                            {
                                                echo'
                                                <ul class="grade2">
                                <li>
                                    <span  style="float:left; margin-top: 4px;"></span>
                                    <h2 style="width: 600px;">'.$front[2][$i][$j].'</h2>';
                                                for ($k=0;$k<count($front[3][$i][$j]);$k++)
                                                {
                                                    echo '
                                <ul style="display: inline-block;" class="grade3">
                                  <li style="margin-top: 10px;">
                                  <h3>'.$front[3][$i][$j][$k].'</h3>';
                                                    echo '
                                  </li>
                              </ul>';
                                                }

                                                echo '
                    </li>
               </ul>';
                                            }
                                            echo'
            </li>
         </ul>';
                                        }?>
                                    </div>
                                </div>

                                <div class="tab-pane fade content-back" id="back">

                                    <dl>
                                        <?php
                                        echo '<dt>'.$back[1][0].'</dt>';
                                        echo '<dd>';
                                        for ($j=0;$j<count($back[2][0])-1;$j++)
                                        {
                                            echo '<div class="radio">';
                                            echo '    <label>';
                                            echo '    <input type="radio" name="optionsRadios" id="optionsRadios'.$j.'" value="option'.$j.'">';
                                            echo        $back[2][0][$j];
                                            echo '   </label>';
                                            echo '</div>';
                                        }
                                        echo '</dd>';

                                        for($i=1;$i<count($back[1]);$i++)
                                        {
                                            echo '<dt>'.$back[1][$i];
                                            echo '<dd>';
                                            for ($j=0;$j<count($back[2][$i]);$j++)
                                            {
                                                echo '<div class="checkbox">';
                                                echo '<label>';
                                                echo '<input type="checkbox" value="">';
                                                echo $back[2][$i][$j];
                                                echo '</label>';
                                                echo '</div>';
                                            }

                                            echo '</dd>';

                                            echo '</dt>';
                                        }
                                        ?>

                                    </dl>

                                    {{--<a href="javascript:doSaveAs()" style="color: #000;">保存本页</a>--}}
                                    {{--<a onclick="window.print()" style="color:#000;">打印本页页</a>　--}}
                                </div>

                                <button class="btn btn-success btn-raised tabBack" style="float: right;display: block;margin-top: 10px;" >评价表背面</button>
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

    })
    $('.tabBack').click(function () {
        $('#myTab li:eq(1) a').tab('show');
        $('.tabBack').css("display",'none');
        $('.tabFront,.submitTable,.saveTable').css("display",'block');
        //alert("a");
    })
    $('.tabFront').click(function () {
        $('#myTab li:eq(0) a').tab('show');
        $('.tabBack').css("display",'block');
        $('.tabFront,.submitTable,.saveTable').css("display",'none');
    })
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

    $('h2:contains("授课总体评价")').css({'position':'absolute','left':'-46px'})
            .parents('.grade2').css({'background':'#ff753a','padding-top':'5px'});
    $('h2:contains("听课总体评价")').css({'position':'absolute','left':'-46px'})
            .parents('.grade2').css({'background':'#ff753a','padding-top':'5px'});


    //    var oh2 = document.getElementsByTagName('h2');
    $('h2:contains("到课情况")').parent().children('ul').remove();
    $('h2:contains("到课情况")').parent().append('<ul  style="display: inline-block;" class="grade3"> ' +
            '<li class="student"> ' +
            '<form class="form-horizontal"> ' +
            '<div class="form-group"> ' +
            '<label class="col-sm-3 control-label">应到人数</label> ' +
            '<div class="col-sm-3"> ' +
            '<input type="text" class="form-control" onblur="checkNum(this)" placeholder="例：80"> ' +
            '</div> ' +
            '<label class="col-sm-3 control-label">实到人数约</label> ' +
            '<div class="col-sm-3"> ' +
            '<input type="text" class="form-control" onblur="checkNum(this)" placeholder="例：80"> ' +
            '</div> ' +
            '</div> ' +
            '<div class="form-group"> ' +
            '<label class="col-sm-3 control-label">迟到人数</label> ' +
            '<div class="col-sm-3"> ' +
            '<input type="text" class="form-control" onblur="checkNum(this)" placeholder="例：0"> </div> ' +
            '<label class="col-sm-3 control-label">早退人数</label> ' +
            '<div class="col-sm-3"> ' +
            '<input type="text" class="form-control" onblur="checkNum(this)" placeholder="例：0"> ' +
            '</div> ' +
            '</div> ' +
            '</form>' +
            '</li>' +
            '</ul>');
    //    $('h3:contains("应到人数")').parent().siblings('div').remove();
    //    $('h3:contains("实到人数约")').parent().siblings('div').remove();
    //    $('h3:contains("迟到人数")').parent().siblings('div').remove();
    $('h1:contains("其他")').parent().children('ul').remove();
    $('h1:contains("其他")').parent().append('<textarea class="form-control" rows="3" style="width: 95%;"></textarea>');

    //移除自动生成的评述
    $('label:contains("如果以上各方面不能准确表达您的意见")').parent().remove();

    //背面附加内容说明
    $('dd').append('<p>如果以上各方面不能准确表达您的意见，您可以自己评述或提出具体意见和建议。</br>评述：</p>' +
            '            <textarea class="form-control" rows="3"></textarea>');


    $('label:contains("青年教师，且")').parent().addClass('radio');
    $('label:contains("青年教师，且")').children().attr('type','radio').attr('name','qingnianjiaoshi');
//
//    $('label:contains("板书存在问题")').parent().addClass('radio');
//    $('label:contains("板书存在问题")').children().attr('type','radio').attr('name','banshu');
//
//    $('label:contains("多媒体使用或搭配存在一些问题")').parent().addClass('radio');
//    $('label:contains("多媒体使用或搭配存在一些问题")').children().attr('type','radio').attr('name','duomeiti');
    //功能部分

    //单选框取消问题：即radio问题
/*
* 1、确定是哪个单选框被选了
* */
    $(function(){
            $(":radio").click(function(){
                //检查当前单选框是否为选中状态

                if($(this).attr('checked')){
                    $(this).attr('checked',false);
                }else{
                    $(this).attr('checked',true);
                }

            });
    });

</script>
<script src="js/jquery.tabs.js"></script>
<script src="calendar1/js/bootstrap-datetimepicker1.min.js"></script>
<script src="calendar1/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="js/weixin/HelpFunction.js"></script>
<script>
    var split_flag='___';//课程信息的分隔符

    //函数声明部分
    //听课时间格式检测
    function GetContent(LessonState)
    {

        var flagC =checkNeceHead_Input(LessonState);//0:成功通过验证，1：提交必填项失败 2：保存必填项失败
//        2017-01-15暂时取消必填项检查功能
//正面内容的值
        var Frontlist = [];//正面选择框的值

        //存储所有的选项框
        for (i=0;i<$('.current').length;i++)
        {
            key = $('.current').eq(i).parent().parent().prev().children()[0].innerHTML;
            if (key=='') {
                key=$('.current').eq(i).parent().parent().parent().prev()[0].innerHTML;
            }
            value = $('.current').eq(i)[0].innerHTML;
            obj = {
                key : key,
                value : value
            };
            Frontlist.push(obj);
        }
        //存储学生听课状态中的人数
        for (k=0;k<$('label:contains("人数")').length;k++)
        {
            key = $('label:contains("人数")').eq(k)[0].innerHTML;
            value = $('label:contains("人数")').eq(k).next().children().val();
            if (value == '')
                    value = '-';
            obj = {
                key : key,
                value : value
            };
            Frontlist.push(obj);
        }
        //其他部分
        obj = {
            key : '其他',
            value : $('h1:contains("其他")').next().eq(0).val()
        }
        Frontlist.push(obj);
        console.log(Frontlist);
//如果是待提交状态，将正面未完成标识写入lessonstate，并将其置为可提交状态
        if(LessonState=='待提交')
        {
            if (flagC == 1)//必填项未完成
            {
                LessonState+=flagC;
                flagC = 0;
            }
        }

        //后台传数据
        if (flagC == 0)
        {
            var Headlist=TableHeadData();
            console.log(Headlist);
//背面的值
            var Backlist1 =GetBackList1();
            var Backlist2 =GetBackList2();

            var frontflag=null;
            $.ajax({
                type: "post",
                async: false,
                url: "/DBTheoryFrontEvaluationTable",
                data:{
                    '_token':'{{csrf_token()}}',
                    headdata:Headlist,
                    LessonState:LessonState,
                    frontdata:Frontlist,
                    backdata1:Backlist1,
                    backdata2:Backlist2,
//                    valueID:flag
                },
                success: function (result) {
                    if (result!='')
                    {
                        frontflag = result;
                    }
                }
            });

            if (frontflag !='')
            {
                alert(frontflag);
//                window.location.href="/EverEvaluated";
            }
        }
    }


    var ack=[];
    var ArrLessonName=[];
    $(document).ready(function() {
        $('.evaluated-menu').addClass('active');
        $('.supervise-menu').addClass('active');
        $('.work-menu').addClass('active');
        //督导姓名输入框的操作事宜
        chooseSupervisor();

         $.ajax({
                type: "get",
                async: false,
                url: "/GetLessonArrThe",
                data:{dataIn:""},
                success: function (result) {
                    for (var i=0;i<result.length;i++)
                    {
                        ArrLessonName[i]= result[i]['lesson_name']+split_flag
                            +result[i]['lesson_teacher_name']+split_flag
                            +result[i]['lesson_week']+'周'+split_flag+'星期'
                            +result[i]['lesson_weekday']+split_flag
                            +result[i]['lesson_time'] +split_flag
                            +result[i]['lesson_class']+split_flag
                            +result[i]['lesson_room'];
                    }
                }
            });
            for(var i=0;i<ArrLessonName.length;i++)
            {
                ack[i]={
                    "name": ArrLessonName[i],
                    "id": i+1,
                    "disabled": false,
                    "selected": false
                };
            }
            $('.dropdown-sin-1').data('dropdown').update(ack, true);

        //若一开始课程选择框为空时，则将课程节次选择框置为空
//
        if($('#LessonName').val()=='')
            $('#LessonTime').attr("disabled",true);
        else
            $('#LessonTime').attr("disabled",false);

        if($('#LessonName').val()=='')//课程节次
            $('#ListenTime').attr("disabled",true);
        else
            $('#ListenTime').attr("disabled",false);



//听课节次面板控制
        $(function ()
        {
            $("#LessonTime").click(function (event)
            {
                // $(".box_classify").show();
                $(document).one("click", function ()
                {//对document绑定一个影藏Div方法
                    $("#LessonTimeStyle").hide();
                });
                event.stopPropagation();//阻止事件向上冒泡
            });
            $("#LessonTimeStyle").click(function (event)
            {
                event.stopPropagation();//阻止事件向上冒泡
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

    $('.dropdown-sin-1').dropdown({
        input: '<input type="text" id="LessonName" maxLength="20" placeholder="搜索：输入课程名或教师名">',
        choice: function() {

        }
    });
    //页面内容提交
    $('.submitTable').click(function(){
        var LessonState='已完成';
        GetContent(LessonState);
    });
    $('.saveTable').click(function(){
        var LessonState='待提交';
        GetContent(LessonState);
    });


</script>
</html>
