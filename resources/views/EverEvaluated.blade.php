<!DOCTYPE html>
<html lang="en">
<meta name="render" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=11">
<link rel="stylesheet" href="css/EverEvaluated.css" />

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
        width: 9.7%!important;
        float:left!important;
    }
    #newmain{

    }
    @media screen and (max-width: 1550px){
        #newmain{
            padding-left: 50px;
        }
    }
    @media screen and (max-width: 768px){
        #newmain{
            overflow: auto;float: left;
            margin-left:38%;width: 1660px;
        }
        #dtpick{
            left: 8%;
            top: 20%;
        }
        #search-suggest{
            left:745px!important;
        }
    }
    @media screen and (max-width: 415px) {
        #newmain{
            overflow: auto;float: left;
            margin-left:70%;width: 1660px;
        }
        #dtpick{
            top: 10%;
            left: 10%;
        }
    }

</style>
{{--日历相关--}}
<link rel="stylesheet" type="text/css" href="calendar/DateTimePicker.css" />
<script type="text/javascript" src="calendar/jquery-1.11.0.min.js"></script>

<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="calendar1/DateTimePicker-ltie9.css" />
<script type="text/javascript" src="calendar1/DateTimePicker-ltie9.js"></script>
<![endif]-->

<body>
@include('layout.header')
@include('layout.sidebar')
<div id="newmain" class="container-fluid clearfix" style="">
    <div class="row clearfix">
        <!-- 面板开始 -->
        <div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2">
            <!-- .breadcrumb -->
            @if(session('role')=='校级' || session('role')=='大组长' || session('role')=='小组长')
                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb" style="padding-top: 22px; padding-bottom: 12px;">

                    </ul>
                </div>
            @endif
            @if(session('role')=='院级')
                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb" style="padding-top: 22px; padding-bottom: 12px;">
                        <li>
                            <i class="icon-home home-icon"></i>
                            <a style="color: #225081;" href="#">评价结果</a>
                        </li>
                        {{--<li style="color:gray">评价结果</li>--}}
                    </ul>
                </div>
            @endif
            @if(session('role')=='督导')
                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb" style="padding-top: 22px; padding-bottom: 12px;">
                        <li>
                            <i class="icon-home home-icon"></i>
                            <a style="color: #225081;" href="#">我的评价</a>
                        </li>
                    </ul>
                </div>
                @endif
                        <!-- .breadcrumb -->
                <!-- .page-content 开始 -->
                <input id="getlevel" value="{{session('role')}}" style="display: none"/>
                <input id="getunit" value="{{Auth::User()->unit}}" style="display: none"/>
                <input id="getgroup" value="{{Auth::User()->group}}" style="display: none"/>
                <input id="getid" value="{{Auth::User()->user_id}}" style="display: none"/>
                <input id="getname" value="{{Auth::User()->name}}" style="display: none"/>

                <div class="page-content form-content">
                    <div class="Teacher-Table">
                        <div>
                            @if(session('role')=='校级' || session('role')=='大组长')
                                <p style="display: inline-block;">
                                    学年学期<input id="calender" class="select" type="text" data-field="date" >
                                </p>
                                <span>查询方式
                                <select class="form-control" name="ViewWay" id="ViewWay" style="display: inline-block;width: 80px;">
                                    <option>学院</option>
                                    <option>小组</option>
                                    <option>督导</option>
                                </select>
                            </span>
                                <input  id="name" class="select" type="text" value="" placeholder="显示所有">
                            @endif

                            @if(session('role')=='小组长')
                                <p class="learnYear" style="display: inline-block;margin-left:60px">
                                    学年学期<input id="calender" class="select-year" type="text" data-field="date" >
                                </p>
                                <p class="selectZu" style="display: inline-block; margin-left: 100px;">
                                    <input  id="groupName" class="form-control" type="text" value="{{Auth::User()->group}}" placeholder="{{Auth::User()->group}}" style="margin-left:110px;width:60%">
                                </p>
                            @endif

                            @if(session('role')=='院级')
                                <p style="display: inline-block;">
                                    学年学期<input id="calender" class="select-year" type="text" data-field="date" readonly>
                                </p>
                                <p style="display: inline-block; margin-left: 30px;">
                                    <input  id="name" type="text" value="{{Auth::User()->unit}}" style="display: none">
                                </p>
                            @endif

                            @if(session('role')=='督导')
                                <p style="display: inline-block;">
                                    学年学期<input id="calender" class="select-year" type="text" data-field="date" readonly>
                                </p>
                                <p style="display: inline-block; margin-left: 30px;">
                                    <input  id="name" type="text" value="{{Auth::User()->user_id}}" style="display: none">
                                </p>
                            @endif


                            <button id="view" class="btn btn-primary btn-raised">
                                <i class="glyphicon glyphicon-search"></i> 查询
                            </button>
                            <button id="export" class="btn btn-danger btn-raised">
                                <i class="glyphicon glyphicon-export"></i> 导出
                            </button>

                        </div>

                        {{--搜索框下拉列表--}}
                        <div class="suggest" id="search-suggest" style="z-index: 9998;">
                            <ul id="search_result">

                            </ul>
                        </div>

                        <div class="panel panel-primary" style="margin-top:20px;">

                            <div class="panel-heading">
                                @if(session('role')=='校级'||session('role')=='大组长')
                                    <h2 class="panel-title" style="font-family:'Microsoft YaHei'">
                                <span class="type_tit2 h3">所有督导课程评价统计表
                                </span>
                                    </h2>
                                @elseif(session('role')=='督导' )
                                    <h2 class="panel-title" style="font-family:'Microsoft YaHei'">
                                <span class="type_tit2 h3">{{Auth::User()->name}}督导课程评价统计表
                                </span>
                                    </h2>
                                @elseif(session('role')=='院级')
                                    <h2 class="panel-title" style="font-family:'Microsoft YaHei'">
                                <span class="type_tit2 h3">{{Auth::User()->unit}} 课程评价统计表
                                </span>
                                </h2>
                            @elseif(session('role')=='小组长')
                                <h2 class="panel-title" style="font-family:'Microsoft YaHei'">
                                <span class="type_tit2 h3">{{Auth::User()->group}} 课程评价统计表
                                </span>
                                </h2>
                            @endif


                        </div>

                        <div class="panel-body panel1">
                            @if(session('role')=='院级')
                                <div class="finishedTable">
                                    <table id="finished" data-toggle="table"
                                           data-classes="table table-hover table-bordered"
                                           data-click-to-select="true"
                                           data-show-pagination-switch="true"
                                           data-pagination="true"
                                           data-search = "true"
                                           data-page-list="[5, 10, 20, 50, 100, 200]">
                                        <thead>
                                        <tr>
                                            <th data-field="课程名称" data-halign="center" data-align="center">课程名称</th>
                                            <th data-field="任课教师" data-halign="center" data-align="center">任课教师</th>
                                            <th data-field="课程属性" data-halign="center" data-align="center">课程属性</th>
                                            <th data-field="" data-halign="center" data-align="center">听课督导</th>
                                            {{--<th data-field="assign_group" data-halign="center" data-align="center">关注课程所在组</th>--}}
                                            <th data-field="听课时间" data-halign="center" data-align="center">听课时间</th>
                                            <th data-field="填表时间" data-halign="center" data-align="center">填表时间</th>
                                            <th data-field="听课节次" data-halign="center" data-align="center">听课节次</th>

                                            {{--<th data-field="上课地点" data-halign="center" data-align="center">上课地点</th>--}}
                                            {{--<th data-field="上课班级" data-halign="center" data-align="center">上课班级</th>--}}
                                            {{--<th data-field="章节目录" data-halign="center" data-align="center">章节目录</th>--}}
                                            <th data-field="lesson_level" data-halign="center" data-align="center">课程等级</th>
                                            <th data-field="action" data-align="center" data-formatter="actionUnitFormatter" data-events="actionEvents">评价详情</th>
                                            {{--<th data-field="action1" data-formatter="actionResetFormatter" data-events="actionEvents">重置</th>--}}
                                        </tr>
                                        </thead>
                                    </table>
                                </div>

                                @else
                                    <ul id="myTab" class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#Finished" data-toggle="tab">
                                                已完成评价课程
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#Saved" data-toggle="tab">
                                                待提交评价课程
                                            </a>
                                        </li>
                                    </ul>

                                    <div id="myTabContent" class="tab-content">
                                        <div class="tab-pane fade in active" id="Finished">

                                            <div class="finishedTable">
                                                <table id="finished" data-toggle="table"
                                                       data-classes="table table-hover table-bordered"
                                                       data-click-to-select="true"
                                                       data-show-pagination-switch="true"
                                                       data-pagination="true"
                                                       data-search = "true"
                                                       data-page-list="[5, 10, 20, 50, 100, 200]">
                                                    <thead>
                                                    <tr>
                                                        @if( session('role')=='校级')
                                                            <th data-field="课程名称" data-halign="center" data-align="center">课程名称</th>
                                                            <th data-field="任课教师" data-halign="center" data-align="center">任课教师</th>
                                                            {{--<th data-field="课程属性" data-halign="center" data-align="center">课程属性</th>--}}
                                                            <th data-field="督导姓名" data-halign="center" data-align="center">听课督导</th>
                                                            <th data-field="assign_group" data-halign="center" data-align="center">关注课程所在组</th>
                                                            <th data-field="听课时间" data-halign="center" data-align="center">听课时间</th>
                                                            <th data-field="填表时间" data-halign="center" data-align="center">填表时间</th>
                                                            <th data-field="听课节次" data-halign="center" data-align="center">听课节次</th>
                                                            {{--<th data-field="授课总体评价" data-halign="center" data-align="center">授课总体评价</th>--}}
                                                            <th data-field="上课地点" data-halign="center" data-align="center">上课地点</th>
                                                            {{--<th data-field="上课班级" data-halign="center" data-align="center">上课班级</th>--}}
                                                            {{--<th data-field="章节目录" data-halign="center" data-align="center">章节目录</th>--}}
                                                            <th data-field="lesson_level" data-halign="center" data-align="center">课程分类</th>
                                                            <th data-field="action"  data-align="center" data-formatter="actionUnitFormatter" data-events="actionEvents">评价详情</th>
                                                            <th data-field="action1" data-align="center" data-formatter="actionResetFormatter" data-events="actionEvents">重置</th>

                                                        @elseif( session('role')=='大组长')
                                                            <th data-field="课程名称" data-halign="center" data-align="center">课程名称</th>
                                                            <th data-field="任课教师" data-halign="center" data-align="center">任课教师</th>
                                                            {{--<th data-field="课程属性" data-halign="center" data-align="center">课程属性</th>--}}
                                                            <th data-field="督导姓名" data-halign="center" data-align="center">听课督导</th>
                                                            <th data-field="assign_group" data-halign="center" data-align="center">关注课程所在组</th>
                                                            <th data-field="听课时间" data-halign="center" data-align="center">听课时间</th>
                                                            <th data-field="填表时间" data-halign="center" data-align="center">填表时间</th>
                                                            <th data-field="听课节次" data-halign="center" data-align="center">听课节次</th>
                                                            {{--<th data-field="授课总体评价" data-halign="center" data-align="center">授课总体评价</th>--}}
                                                            <th data-field="lesson_level"  data-halign="center" data-align="center">课程分类</th>
                                                            <th data-field="action" data-align="center" data-formatter="actionUnitFormatter" data-events="actionEvents">评价详情</th>

                                                        @elseif(session('role')=='小组长')
                                                            <th data-field="课程名称" data-halign="center" data-align="center">课程名称</th>
                                                            <th data-field="任课教师" data-halign="center" data-align="center">任课教师</th>
                                                            {{--<th data-field="课程属性" data-halign="center" data-align="center">课程属性</th>--}}
                                                            <th data-field="督导姓名" data-halign="center" data-align="center">听课督导</th>
                                                            <th data-field="assign_group" data-halign="center" data-align="center">关注课程所在组</th>
                                                            <th data-field="听课时间" data-halign="center" data-align="center">听课时间</th>
                                                            <th data-field="填表时间" data-halign="center" data-align="center">填表时间</th>
                                                            <th data-field="听课节次" data-halign="center" data-align="center">听课节次</th>
                                                            {{--<th data-field="授课总体评价" data-halign="center" data-align="center">授课总体评价</th>--}}

                                                            {{--<th data-field="上课地点" data-halign="center" data-align="center">上课地点</th>--}}
                                                            {{--<th data-field="上课班级" data-halign="center" data-align="center">上课班级</th>--}}
                                                            {{--<th data-field="章节目录" data-halign="center" data-align="center">章节目录</th>--}}
                                                            <th data-field="lesson_level" data-halign="center" data-align="center">课程等级</th>
                                                            <th data-field="action" data-align="center"  data-formatter="actionUnitFormatter" data-events="actionEvents">评价详情</th>
                                                            {{--<th data-field="action1" data-formatter="actionResetFormatter" data-events="actionEvents">重置</th>--}}

                                                        @elseif(session('role')=='督导')
                                                            <th data-field="课程名称" data-halign="center" data-align="center">课程名称</th>
                                                            <th data-field="任课教师" data-halign="center" data-align="center">任课教师</th>
                                                            <th data-field="assign_group" data-halign="center" data-align="center">关注课程所在组</th>
                                                            <th data-field="听课时间" data-halign="center" data-align="center">听课时间</th>
                                                            <th data-field="填表时间" data-halign="center" data-align="center">填表时间</th>
                                                            <th data-field="听课节次" data-halign="center" data-align="center">听课节次</th>
                                                            {{--<th data-field="授课总体评价" data-halign="center" data-align="center">授课总体评价</th>--}}

                                                            <th data-field="lesson_level" data-halign="center" data-align="center">课程等级</th>
                                                            <th data-field="action" data-align="center"  data-formatter="actionUnitFormatter" data-events="actionEvents">评价详情</th>

                                                        @endif
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>


                                        </div>

                                        {{--这里是分界线，下面是保存了但没有提交的评价内容--}}
                                        {{--这里是分界线，下面是保存了但没有提交的评价内容--}}
                                        {{--这里是分界线，下面是保存了但没有提交的评价内容--}}
                                        {{--这里是分界线，下面是保存了但没有提交的评价内容--}}
                                        {{--这里是分界线，下面是保存了但没有提交的评价内容--}}
                                        {{--这里是分界线，下面是保存了但没有提交的评价内容--}}
                                        <div class="tab-pane fade" id="Saved">

                                            <div class="EverSavedTable">
                                                <table id="EverSaved"
                                                       data-toggle="table"
                                                       data-classes="table table-hover table-bordered"
                                                       data-cache = "false"
                                                       data-show-pagination-switch="true"
                                                       data-query-params="queryParams"
                                                       data-page-size="20"
                                                       data-pagination="true"
                                                       data-search = "true"
                                                       data-page-list="[5, 10, 20, 50, 100, 200]">
                                                    <thead>
                                                    <tr>
                                                        @if( session('role')=='校级')
                                                            <th data-field="课程名称" data-halign="center" data-align="center">课程名称</th>
                                                            <th data-field="任课教师" data-halign="center" data-align="center">授课教师</th>
                                                            {{--<th data-field="课程属性" data-halign="center" data-align="center">课程属性</th>--}}
                                                            <th data-field="督导姓名" data-halign="center" data-align="center">听课督导</th>
                                                            <th data-field="assign_group" data-halign="center" data-align="center">关注课程所在组</th>
                                                            <th data-field="听课时间" data-halign="center" data-align="center">听课时间</th>
                                                            <th data-field="填表时间" data-halign="center" data-align="center">填表时间</th>
                                                            <th data-field="听课节次" data-halign="center" data-align="center">听课节次</th>

                                                            <th data-field="上课地点" data-halign="center" data-align="center">上课地点</th>
                                                            {{--<th data-field="上课班级" data-halign="center" data-align="center">上课班级</th>--}}
                                                            {{--<th data-field="章节目录" data-halign="center" data-align="center">章节目录</th>--}}
                                                            <th data-field="lesson_level" data-halign="center" data-align="center">课程分类</th>
                                                            <th data-field="action" data-align="center" data-halign="center" data-formatter="actionFormatter" data-events="actionEvents">预览</th>
                                                            <th data-field="actionSubmit"  data-align="center" data-formatter="actionSubmitFormatter" data-events="actionEvents">提交</th>
                                                            <th data-field="actionDel"  data-align="center" data-formatter="actionDelFormatter" data-events="actionEvents">撤销</th>
                                                            {{--<th data-field="actionSubmit" data-align="center" data-halign="center" data-formatter="actionSubmitFormatter" data-events="actionEvents">提交</th>--}}
                                                        @elseif( session('role')=='大组长')
                                                            <th data-field="课程名称" data-halign="center" data-align="center">课程名称</th>
                                                            <th data-field="任课教师" data-halign="center" data-align="center">授课教师</th>
                                                            {{--<th data-field="课程属性" data-halign="center" data-align="center">课程属性</th>--}}
                                                            <th data-field="督导姓名" data-halign="center" data-align="center">听课督导</th>
                                                            <th data-field="assign_group" data-halign="center" data-align="center">关注课程所在组</th>
                                                            <th data-field="听课时间" data-halign="center" data-align="center">听课时间</th>
                                                            <th data-field="填表时间" data-halign="center" data-align="center">填表时间</th>
                                                            <th data-field="听课节次" data-halign="center" data-align="center">听课节次</th>

                                                            <th data-field="上课地点" data-halign="center" data-align="center">上课地点</th>
                                                            {{--<th data-field="上课班级" data-halign="center" data-align="center">上课班级</th>--}}
                                                            {{--<th data-field="章节目录" data-halign="center" data-align="center">章节目录</th>--}}
                                                            <th data-field="lesson_level" data-halign="center" data-align="center">课程分类</th>
                                                            <th data-field="action" data-align="center" data-halign="center" data-formatter="actionFormatter" data-events="actionEvents">预览</th>
                                                            {{--<th data-field="actionSubmit" data-formatter="actionSubmitFormatter" data-events="actionEvents"></th>--}}
                                                        @elseif(session('role')=='小组长')
                                                            <th data-field="课程名称" data-halign="center" data-align="center">课程名称</th>
                                                            <th data-field="任课教师" data-halign="center" data-align="center">授课教师</th>
                                                            {{--<th data-field="课程属性" data-halign="center" data-align="center">课程属性</th>--}}
                                                            <th data-field="督导姓名" data-halign="center" data-align="center">听课督导</th>
                                                            <th data-field="assign_group" data-halign="center" data-align="center">关注课程所在组</th>
                                                            <th data-field="听课时间" data-halign="center" data-align="center">听课时间</th>
                                                            <th data-field="填表时间" data-halign="center" data-align="center">填表时间</th>
                                                            <th data-field="听课节次" data-halign="center" data-align="center">听课节次</th>

                                                            <th data-field="上课地点" data-halign="center" data-align="center">上课地点</th>
                                                            {{--<th data-field="上课班级" data-halign="center" data-align="center">上课班级</th>--}}
                                                            {{--<th data-field="章节目录" data-halign="center" data-align="center">章节目录</th>--}}
                                                            <th data-field="lesson_level" data-halign="center" data-align="center">课程分类</th>
                                                            <th data-field="action" data-align="center" data-halign="center" data-formatter="actionFormatter" data-events="actionEvents">预览</th>
                                                            {{--<th data-field="actionSubmit" data-formatter="actionSubmitFormatter" data-events="actionEvents"></th>--}}
                                                        @elseif(session('role')=='督导')
                                                            <th data-field="课程名称" data-halign="center" data-align="center">课程名称</th>
                                                            <th data-field="任课教师" data-halign="center" data-align="center">授课教师</th>
                                                            <th data-field="课程属性" data-halign="center" data-align="center">课程属性</th>
                                                            <th data-field="assign_group" data-halign="center" data-align="center">关注课程所在组</th>
                                                            <th data-field="听课时间" data-halign="center" data-align="center">听课时间</th>
                                                            <th data-field="填表时间" data-halign="center" data-align="center">填表时间</th>
                                                            <th data-field="听课节次" data-halign="center" data-align="center">听课节次</th>

                                                            {{--<th data-field="上课地点" data-halign="center" data-align="center">上课地点</th>--}}
                                                            {{--<th data-field="上课班级" data-halign="center" data-align="center">上课班级</th>--}}
                                                            {{--<th data-field="章节目录" data-halign="center" data-align="center">章节目录</th>--}}
                                                            <th data-field="lesson_level" data-halign="center" data-align="center">课程分类</th>
                                                            <th data-field="action" data-align="center" data-formatter="actionFormatter" data-events="actionEvents">预览</th>
                                                            <th data-field="actionSubmit"  data-align="center" data-formatter="actionSubmitFormatter" data-events="actionEvents">提交</th>
                                                            <th data-field="actionDel"  data-align="center" data-formatter="actionDelFormatter" data-events="actionEvents">撤销</th>

                                                        @endif

                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                @endif


                                <button id="detail" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal1" style="display: none">
                                    开始演示模态框
                                </button>
                                <!-- 模态框（Modal） -->
                                <div class="modal fade bs-example-modal-lg" id="myModal1" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">
                                                    评价详情
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="panel-heading">
                                                    <p style="font-size:20px; " class="panel-title">北京林业大学本科教学督导听课评价表</p>
                                                </div>
                                                <div class="panel-body">
                                                    <form class="form-horizontal">
                                                        <div class="form-group">
                                                            <span for="inputChapter" class="col-lg-1" style="padding-top: 8px;" >章节内容</span>
                                                            <div class="col-sm-2">
                                                                <input type="text" class="form-control" id="inputChapter" readonly="readonly">
                                                            </div>
                                                            <span for="LessonSupervisor" class="col-lg-1" style="padding-top: 8px;" >听课督导</span>
                                                            <div class="col-sm-2">
                                                                <input type="text" class="form-control" id="LessonSupervisor" readonly="readonly">
                                                            </div>
                                                            <span for="LessonAttr" class="col-lg-1" style="padding-top: 8px;" >课程属性</span>
                                                            <div class="col-sm-2">
                                                                <input type="text" class="form-control" id="LessonAttr" readonly="readonly">
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>课程名称</th>
                                                            <th>任课教师</th>
                                                            <th>听课节次</th>
                                                            <th>上课班级</th>
                                                            <th>上课地点</th>
                                                            <th>听课时间</th>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="text" class="form-control" id="LessonName" readonly="readonly"></td>
                                                            <td><input type="text" class="form-control" id="Teacher" readonly="readonly"></td>
                                                            <td><input type="text" class="form-control" id="LessonTime" readonly="readonly"></td>
                                                            <td><input type="text" class="form-control" id="LessonClass" readonly="readonly"></td>
                                                            <td><input type="text" class="form-control" id="LessonRoom" readonly="readonly"></td>
                                                            <td><input type="text" class="form-control" id="ListenTime" readonly="readonly"></td>
                                                        </tr>
                                                    </table>
                                                    <div class="alert alert-danger" style="font-size: 20px; text-align: center;">
                                                        <strong>  &nbsp;&nbsp;&nbsp;&nbsp; 注：</strong>
                                                        &nbsp;&nbsp;&nbsp;（1）5个评价等级为：非常满意、满意、正常、存在不足、存在明显不足。<br>
                                                        （2）评价内容共两部分：评价表正面和评价表背面。<br>
                                                        （3）评价表正面除“章节目录、课程属性、学生到课情况、其他”外均为必填项，背面为选填项。<br>

                                                    </div>
                                                    <br><br>
                                                    <ul id="myTab1" class="nav nav-tabs">
                                                        <li class="active"><a href="#front" data-toggle="tab">评价表正面</a></li>
                                                        <li><a href="#back" data-toggle="tab" >评价表背面</a></li>
                                                    </ul>
                                                    {{--<div id="myTabContent" class="tab-content content-font" style="padding-bottom: 70px">--}}
                                                    <div id="myTabContent" class="tab-content content-font" style="padding-bottom: 70px">
                                                        {{--@include('particle.TheoryForm')--}}
                                                        <div class="tab-pane fade in active" id="front" style="height:1300px;">
                                                            <div>
                                                                {{--front 第一维数组是菜单等级，第二维数组是表的种类，第三维内容--}}


                                                            </div>

                                                        </div>
                                                        <div class="tab-pane fade content-back" id="back">


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="content-footer" class="modal-footer">
                                                <button class="btn btn-success btn-raised tabBack" style="float: left;display: block;" >{{--<a href="#back" data-toggle="tab" >--}}评价表背面{{--</a>--}}</button>
                                                <button class="btn btn-success btn-raised tabFront" style="float: left; display: none"  >{{--<a href="#front" data-toggle="tab">--}}评价表正面{{--</a>--}}</button>
                                                <button type="button" class="btn btn-default btn-raised"
                                                        data-dismiss="modal">关闭
                                                </button>
                                                @if(session('role')=='督导')
                                                <button id="rewrite" type="button" class="btn btn-warning btn-raised" style="display: none;">编辑</button>
                                                @endif
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->


                                {{--重置按钮提示框--}}
                                <button id="resetModel" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#resetmodal" style="display: none">
                                    开始演示模态框
                                </button>
                                <div class="modal fade" id="resetmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                    &times;
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">
                                                    重要！！！
                                                </h4>
                                            </div>
                                            <div id="resetContent" class="modal-body">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default btn-raised" data-dismiss="modal">
                                                    关闭
                                                </button>
                                                <button id='OK' type="button" class="btn btn-success btn-raised">
                                                    确定
                                                </button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal -->
                                </div>


                                {{--撤销按钮提示框--}}
                                <button id="cancelModel" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#cancelmodal" style="display: none">
                                    开始演示模态框
                                </button>
                                <div class="modal fade" id="cancelmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                    &times;
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">
                                                    重要！！！
                                                </h4>
                                            </div>
                                            <div id="cancelContent" class="modal-body">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default btn-raised" data-dismiss="modal">
                                                    关闭
                                                </button>
                                                <button id='cancelOK' type="button" class="btn btn-success btn-raised">
                                                    确定
                                                </button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal -->
                                </div>
                            </div>


                        </div>


                        <div id="dtBox" class="dtpicker-overlay dtpicker-mobile" style="display: none;">
                            <div class="dtpicker-bg">
                                <div id="dtpick" class="dtpicker-cont">
                                    <div class="dtpicker-content" style="margin-left: 266px;">
                                        <div class="dtpicker-subcontent">

                                            <div class="dtpicker-header">
                                                <div class="dtpicker-title">选择学期</div>
                                                <a class="dtpicker-close">×</a>
                                                <!--<div class="dtpicker-value"></div>-->
                                            </div>


                                            <div class="dtpicker-components">
                                                <div class="dtpicker-compOutline dtpicker-comp3">
                                                    <div class="dtpicker-comp year1_class">
                                                        <a id="year1_class1" class="dtpicker-compButton increment">+</a>
                                                        <input id="year1" type="text" class="dtpicker-compValue" maxlength="4">
                                                        <a id="year1_class2" class="dtpicker-compButton decrement">-</a>
                                                    </div>
                                                </div>

                                                <div class="dtpicker-compOutline dtpicker-comp3">
                                                    <div class="dtpicker-comp ">
                                                        <a class="dtpicker-compButton increment "></a>
                                                        <input type="text" class="dtpicker-compValue" id="year2" disabled="disabled">
                                                        <a class="dtpicker-compButton decrement "></a>
                                                    </div>
                                                </div>

                                                <div class="dtpicker-compOutline dtpicker-comp3">
                                                    <div class="dtpicker-comp ">
                                                        <a class="dtpicker-compButton increment dtpicker-compButtonEnable">+</a>
                                                        <input type="text" class="dtpicker-compValue" id="terminal" disabled="disabled">
                                                        <a class="dtpicker-compButton decrement dtpicker-compButtonEnable">-</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="dtpicker-buttonCont dtpicker-twoButtons">
                                                <a class="dtpicker-button dtpicker-buttonSet">确定</a>
                                                <a class="dtpicker-button dtpicker-buttonClear">取消</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
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

<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/bootstrap-table.js"></script>
<script src="assets/js/bootstrap-table-zh-CN.js"></script>
<script src="{{asset('js/EverEvaluated.js')}}"></script>
<script>
    function actionFormatter(value, row, index) {
        return [
            '<a class="like seeDetail" href="javascript:void(0)" title="预览">',
            '预览</i>',
            '</a>'
        ].join('');
    }
    function actionUnitFormatter(value, row, index) {
        return [
            '<a class="edit ml10 seeDetail" href="javascript:void(0)" title="评价详情">',
            '评价详情</i>',
            '</a>'
        ].join('');
    }
    function actionResetFormatter(value, row, index) {
        return [
            '<a class="remove ml10 seeDetail" href="javascript:void(0)" title="重置">',
            '重置</i>',
            '</a>'
        ].join('');
    }
    function actionSubmitFormatter(value, row, index) {
        return [
            '<a class="SSubmit" href="javascript:void(0)" title="提交">',
            '提交</i>',
            '</a>'
        ].join('');
    }
    function actionDelFormatter(value, row, index) {
        return [
            '<a class="SDEL seeDetail" href="javascript:void(0)" title="撤销">',
            '撤销</i>',
            '</a>'
        ].join('');
    }

    $(".grade3").append(' ' +
            '<div class="box demo2" style="display:inline-block;width:600px;"> ' +
            '<ul class="tab_menu" style="display:inline-block;"> ' +
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

    var headnum = 14 ;//评价表头的个数


    window.actionEvents = {
        'click .like': function (e, value, row, index) {//预览
            $("#detail").click();
            $('#front').empty();
            $('#back').empty();
            var valurID = row.valueID;
            var LessonName=row.课程名称.replace(/(^\s+)|(\s+$)/g,"");
            var LessonTeacher=row.任课教师.replace(/(^\s+)|(\s+$)/g,"");
            var LessonSupervisor=row.督导id.replace(/(^\s+)|(\s+$)/g,"");
            var LessonValueTime=row.听课时间.replace(/(^\s+)|(\s+$)/g,"");
            var LessonTime=row.听课节次.replace(/(^\s+)|(\s+$)/g,"");
            $.ajax({
                type: "get",
                async: false,
                url: "/EvaluationContent",
                data: {
                    year1:$("#year1").val(),
                    year2:$("#year2").val(),
                    semester:$("#terminal").val().match(/\d/g),
                    Lesson_name:LessonName,
                    Teacher:LessonTeacher,
                    Spuervisor:LessonSupervisor,
                    Lesson_date:LessonValueTime,
                    Lessontime:LessonTime},//传递学院名称
                success: function (result) {
                    console.log(result);
                    var chapterVal=result[1][0].章节目录;
                    var LessonNameVal=result[1][0].课程名称;
                    var TeacherVal=result[1][0].任课教师;
                    var LessonClassVal=result[1][0].上课班级;
                    var LessonRoomVal=result[1][0].上课地点;
                    var ListenDateVal=result[1][0].听课时间;
                    var ListenAttrVal=result[1][0].课程属性;
                    var ListenSupervisorVal=result[1][0].督导姓名;
                    var ListenSupervisorIDVal=result[1][0].督导id;
                    var ListenTimeVal=result[1][0].听课节次;



                    $('#inputChapter').val(chapterVal);
                    $('#LessonName').val(LessonNameVal);
                    $('#Teacher').val(TeacherVal);
                    $('#LessonClass').val(LessonClassVal);
                    $('#LessonRoom').val(LessonRoomVal);
                    $('#ListenTime').val(ListenDateVal);
                    $('#LessonAttr').val(ListenAttrVal);
                    $('#LessonTime').val(ListenTimeVal);

                    if($('#getlevel').val()=='院级')
                        $('#LessonSupervisor').val('***');
                    else{
                        $('#LessonSupervisor').val(ListenSupervisorIDVal+" "+ListenSupervisorVal);
                    }

                    var FrontOne =[];
                    var FrontTwo =[];
                    var FrontThree =[];

                    for (i=headnum;i<result[3].length;i++)
                    {
                        a='COLUMN_NAME';
                        $('#front').append('<div class="line-1">'+'<div class="lineName">'+'<span class="icon-folder-open-alt">'+'</span>'+result[3][i][a]+'</div>'+
                                '<div class="lineData">'+
                                '<ul>'+
                                '<li class="current">'+
                                result[1][0][result[3][i][a]]+
                                '</li>'+
                                '</ul>'+
                                '</div>'+'</div>');
                        if ( result[1][0][result[3][i][a]]==null )
                        {
                            if(result[1][0][result[3][i+1][a]] == null )
                                FrontOne.push(result[3][i][a]);
                            else
                                FrontTwo.push(result[3][i][a]);
                        }
                        else{
                            FrontThree.push(result[3][i][a]);
                        }
                    }

                    $('.line-1').each(function () {
                        for(var i=0;i<FrontOne.length;i++)
                        {
                            if ($(this).text().indexOf(FrontOne[i])>=0)
                            {
                                $(this).addClass('front1');
                                $(this).find('.lineData').css('display','none');
                            }
                        }
                        $('.front1').css('font-size','200px');
                        for(var i=0;i<FrontTwo.length;i++)
                        {
                            if ($(this).text().indexOf(FrontTwo[i])>=0)
                            {
                                $(this).addClass('front2');
                                $(this).find('.lineData').css('display','none');
                                $(this).find('.icon-folder-open-alt').css('display','none');
                            }
                        }
                        for(var i=0;i<FrontThree.length-1;i++)
                        {
                            if ($(this).text().indexOf(FrontThree[i])>=0)
                            {
                                $(this).addClass('front3');
                                $(this).find('.lineData').css('display','inline-block');
                                $(this).find('.lineName').css('display','inline-block');
                                $(this).find('.icon-folder-open-alt').css('display','none');
                                $(this).find('.current').css('width','100px');
                            }
                        }
                    });


                    var BackOne =[];
                    var BackTwo =[];
                    var BackThree =[];
                    for (i=headnum;i<result[4].length;i++)
                    {
                        a='COLUMN_NAME';
                        if ( result[4][i][a].match(/\？/) != null )
                        {
                            BackOne.push(result[4][i][a]);
                            $('#back').append('<div class="question">'+'<div class="icon-check">'+'</div>'+'<span>'+result[4][i][a]+'</span>'+
                                    '<div class="questionData">'+result[2][0][result[4][i][a]]+'</div>'+'</div>');
                        }
                        else if(result[4][i][a].match("评述1") != null||result[4][i][a].match("评述2") != null||result[4][i][a].match("评述3") != null){
                            BackTwo.push(result[4][i][a]);
                            $('#back').append('<div class="question">'+'<div class="icon-check">'+'</div>'+'<span>'+result[4][i][a]+'</span>'+
                                    '<div class="questionData">'+result[2][0][result[4][i][a]]+'</div>'+'</div>');
                        }
                        else if(result[4][i][a].match(1) != null||result[4][i][a].match(2) != null||result[4][i][a].match(3) != null){
//                            $('#back').append('<div class="question">'+'<span><strong>·'+result[4][i][a]+'</strong></span>'+'</div>');
                        }
                        else{
                            if(result[2]["0"][result[4][i][a]]=="1")
                            {
                                BackThree.push(result[4][i][a]);
                                $('#back').append('<div class="question">'+'<div class="icon-check">'+'</div>'+'<span>'+result[4][i][a]+'</span>'+
                                        '<div class="questionData">'+result[2][0][result[4][i][a]]+'</div>'+'</div>');
                            }
                        }

                    }
                    console.log(BackOne);
                    console.log(BackTwo);
                    console.log(BackThree);
                    $('.question').each(function () {
                        for(var i=0;i<BackOne.length;i++)
                        {
                            if ($(this).text().indexOf(BackOne[i])>=0)
                            {
                                $(this).addClass('back1');
                                $(this).append( '<i class="icon-chevron-right" style="float: left;margin-right: 6px;color: #CCCCCC;">'+
                                        '</i>');
                                $(this).find('.questionData').css('display','none');
                                $(this).find('.icon-check').css('display','none');
                            }
                        }
                        for(var i=0;i<BackTwo.length;i++)
                        {
                            if ($(this).text().indexOf(BackTwo[i])>=0)
                            {
                                $(this).addClass('back2');
                                $(this).find('.icon-check').css('display','none');
                            }
                        }
                        for(var i=0;i<BackThree.length;i++)
                        {
                            if ($(this).text().indexOf(BackThree[i])>=0)
                            {
                                $(this).find('.questionData').css('display','inline-block');
                                if($(this).find('.questionData').text()=='null'){
                                    $(this).find('.questionData').css('display','none');
                                    $(this).find('.icon-check').css('display','none');
                                    $(this).append( '<i class="icon-check-empty" style="float: left;margin-right: 6px;color: #CCCCCC;">'+
                                            '</i>');
                                }
                                else {
                                    $(this).find('.questionData').css('display','none');
                                    $(this).find('.icon-check').css('margin-right','6px');
                                }
                            }
                        }

                    });

                    //移除标签中的类
                    //移除标签中的类
//                    $('li').removeClass("current");
//                    $('input[type=checkbox]').prop('checked',false);
//                    for(i=0;i<$($('#front').children()[0]).children().length;i++)
//                    {
//                        var textlevel1=$($($($('#front').children()[0]).children()[i]).children()[0]).children()[1].innerText;
//                        for(var j=2;j<$($($($('#front').children()[0]).children()[i]).children()[0]).children().length;j++)
//                        {
//                            var cssstyle=$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).attr("class");
//                            if(cssstyle=="grade2")
//                            {
//                                var textlevel2=$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[1].innerText;
//                                if(textlevel2.indexOf("总体评价")>=0)
//                                {
//                                    textlevel2=$.trim(textlevel2);
//                                    for(var p=0;p<$($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children().length;p++)
//                                        if($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children()[p].innerText==result[1][0][textlevel2])
//                                        {
//                                            console.log($($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children()))
//                                            $($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children()[p]).addClass("current");
//                                            break;
//                                        }
//                                    continue;
//                                }
//                                for(var k=2;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var textlevel3=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children().children()[0].innerText;
//                                    textlevel3=$.trim(textlevel3);
//                                    for(var p=0;p<$($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children().length;p++)
//                                        if($($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children()[p].innerText==result[1][0][textlevel3])
//                                        {
//                                            $($($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children()[p]).addClass("current");
//                                            break;
//                                        }
//                                }
//                            }
//                            if(cssstyle=="radiograde")
//                            {
//                                for(var k=0;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var choosecontent=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
//                                    choosecontent=$.trim(choosecontent);
//                                    if(result[1][0][choosecontent]==1)
//                                        $($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).prop("checked",true);
//                                }
//                            }
//                            if(cssstyle=="checkboxgrade")
//                            {
//                                for(var k=0;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var choosecontent=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
//                                    choosecontent=$.trim(choosecontent);
//                                    if(result[1][0][choosecontent]==1)
//                                        $($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).prop("checked",true);
//                                }
//                            }
//                            if(cssstyle=="textareagrade")
//                            {
//                                for(var k=0;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children().length;k++)
//                                {
//                                    var text=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[k]).children()[0].innerText;
//                                    text=$.trim(text);
//                                    $($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[k]).children()[1]).children().val(result[1][0][text]);
//                                }
//                            }
//                        }
//                    }
//                    for(i=0;i<$($('#back').children()[0]).children().length;i++)
//                    {
//                        var textlevel1=$($($($('#back').children()[0]).children()[i]).children()[0]).children()[1].innerText;
//                        for(var j=2;j<$($($($('#back').children()[0]).children()[i]).children()[0]).children().length;j++)
//                        {
//                            var cssstyle=$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).attr("class");
//                            if(cssstyle=="grade2")
//                            {
//                                var textlevel2=$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[1].innerText;
//                                if(textlevel2.indexOf("总体评价")>=0)
//                                {
//                                    textlevel2=$.trim(textlevel2);
//                                    for(var p=0;p<$($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children().length;p++)
//                                        if($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children()[p].innerText==result[2][0][textlevel2])
//                                        {
//                                            $($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children()[p]).addClass("current");
//                                            break;
//                                        }
//                                    continue;
//                                }
//                                for(var k=2;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var textlevel3=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children().children()[0].innerText;
//                                    textlevel3=$.trim(textlevel3);
//                                    for(var p=0;p<$($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children().length;p++)
//                                        if($($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children()[p].innerText==result[2][0][textlevel3])
//                                        {
//                                            $($($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children()[p]).addClass("current");
//                                            break;
//                                        }
//                                }
//                            }
//                            if(cssstyle=="radiograde")
//                            {
//                                for(var k=0;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var choosecontent=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
//                                    choosecontent=$.trim(choosecontent);
//                                    if(result[2][0][choosecontent]==1)
//                                        $($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).prop("checked",true)
//                                }
//                            }
//                            if(cssstyle=="checkboxgrade")
//                            {
//                                for(var k=0;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var choosecontent=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
//                                    choosecontent=$.trim(choosecontent);
//                                    if(result[2][0][choosecontent]==1)
//                                        $($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).prop("checked",true);
//                                }
//                            }
//                            if(cssstyle=="textareagrade")
//                            {
//                                var text=$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children().context.innerText;
//                                text=$.trim(text);
//                                $($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[0]).children()[1]).children()[0]).val(result[2][0][text]);
//                            }
//                        }
//                    }

                    if ($('#getid').val() == LessonSupervisor)
                    {
                        $('#rewrite').css('display','inline-block');
                        $('#rewrite').click(function (){

                            if (row.tableName.indexOf('theory') > 0)
                            {
                                window.location.replace('/TheoryEvaluationTableView?flag=' +valurID+
                                        '&chapter='+chapterVal+'&LessonName='+LessonNameVal+'&Teacher='+TeacherVal+
                                        '&Class='+LessonClassVal+'&LessonRoom='+LessonRoomVal+'&Date='+ListenDateVal+
                                        '&Time='+ListenTimeVal+ '&Attr='+ListenAttrVal+
                                        '&Super='+ListenSupervisorIDVal+' '+ListenSupervisorVal);
                            }
                            else if (row.tableName.indexOf('practice') > 0)
                            {
                                window.location.replace('/PracticeEvaluationTableView?flag=' +valurID+
                                        '&chapter='+chapterVal+'&LessonName='+LessonNameVal+'&Teacher='+TeacherVal+
                                        '&Class='+LessonClassVal+'&LessonRoom='+LessonRoomVal+'&Date='+ListenDateVal+
                                        '&Time='+ListenTimeVal+ '&Attr='+ListenAttrVal+
                                        '&Super='+ListenSupervisorIDVal+' '+ListenSupervisorVal);
                            }else if (row.tableName.indexOf('physical') > 0)
                            {
                                window.location.replace('/PhysicalEvaluationTableView?flag=' +valurID+
                                        '&chapter='+chapterVal+'&LessonName='+LessonNameVal+'&Teacher='+TeacherVal+
                                        '&Class='+LessonClassVal+'&LessonRoom='+LessonRoomVal+'&Date='+ListenDateVal+
                                        '&Time='+ListenTimeVal+ '&Attr='+ListenAttrVal+
                                        '&Super='+ListenSupervisorIDVal+' '+ListenSupervisorVal);
                            }
                        });
                    }
                }
            });
            $('span:contains("评价状态")').css('display','none');
            $('span:contains("评价状态")').prev().css('display','none');
            for (var x=0;x<$('li.current').length;x++)
            {
                if($($('li.current')[x])["context"].innerText == "null" || $($('li.current')[x])["context"].innerText == "0" || $($('li.current')[x])["context"].innerText == "")
                {
                    $($($('li.current')[x]).parents('.line-1.front3')).css('display','none');
                }
            }
            $.ajax({
                type: "get",
                async: false,
                url: "/EvaluationCheckBox",
                data: {
                    year1:$("#year1").val(),
                    year2:$("#year2").val(),
                    semester:$("#terminal").val().match(/\d/g)
                },
                success: function (result) {
//                    console.log(result.back);
                    for (var x=0;x<$('.line-1.front3 .lineName').length;x++)
                    {
                        for(var key in result.front)
                        {
                            if($($('.line-1.front3 .lineName')[x])["0"].innerText == result.front[key].text)
                            {
                                $($('.line-1.front3 .lineName')[x]).next().find('li')["0"].innerHTML ="√";
                                break;
                            }
                        }
                    }
//                    var long=$('#back .question span').length;
//                    for (var x=0;x<long;x++)
//                    {
//                        if($('#back .question span')[x].innerText.indexOf("1")>=0 || $('#back .question span')[x].innerText.indexOf("2")>=0 || $('#back .question span')[x].innerText.indexOf("3")>=0 )
//                        {
//                            $ChangeText=$('#back .question span')[x].innerText;
//                            $('#back .question')[x].innerHTML = "<h5><span><strong>·" + $ChangeText +"</strong></span></h5>";
//                        }
//                    }

                }
            });

        },
        'click .edit': function (e, value, row, index) {//评价详情
            $("#detail").click();
            $('#front').empty();
            $('#back').empty();
            var LessonName=row.课程名称.replace(/(^\s+)|(\s+$)/g,"");
            var LessonTeacher=row.任课教师.replace(/(^\s+)|(\s+$)/g,"");
            var LessonSupervisor=row.督导id.replace(/(^\s+)|(\s+$)/g,"");
            var LessonValueTime=row.听课时间.replace(/(^\s+)|(\s+$)/g,"");
            var LessonTime=row.听课节次.replace(/(^\s+)|(\s+$)/g,"");

            $.ajax({
                type: "get",
                async: false,
                url: "/EvaluationContent",
                data: {
                    year1:$("#year1").val(),
                    year2:$("#year2").val(),
                    semester:$("#terminal").val().match(/\d/g),
                    Lesson_name:LessonName,
                    Teacher:LessonTeacher,
                    Spuervisor:LessonSupervisor,
                    Lesson_date:LessonValueTime,
                    Lessontime:LessonTime},//传递学院名称
                success: function (result) {
                    var chapterVal=result[1][0].章节目录;
                    var LessonNameVal=result[1][0].课程名称;
                    var TeacherVal=result[1][0].任课教师;
                    var LessonClassVal=result[1][0].上课班级;
                    var LessonRoomVal=result[1][0].上课地点;
                    var ListenDateVal=result[1][0].听课时间;
                    var ListenAttrVal=result[1][0].课程属性;
                    var ListenSupervisorVal=result[1][0].督导姓名;
                    var ListenSupervisorIDVal = result[1][0].督导id;
                    var ListenTimeVal=result[1][0].听课节次;

                    $('#inputChapter').val(chapterVal);
                    $('#LessonName').val(LessonNameVal);
                    $('#Teacher').val(TeacherVal);
                    $('#LessonClass').val(LessonClassVal);
                    $('#LessonRoom').val(LessonRoomVal);
                    $('#ListenTime').val(ListenDateVal);
                    $('#LessonTime').val(ListenTimeVal);
                    $('#LessonAttr').val(ListenAttrVal);
                    if($('#getlevel').val()=='院级')
                        $('#LessonSupervisor').val('***');
                    else{
                        $('#LessonSupervisor').val(ListenSupervisorIDVal+" "+ListenSupervisorVal);
                    }

                    var FrontOne =[];
                    var FrontTwo =[];
                    var FrontThree =[];

                    for (i=headnum;i<result[3].length;i++)
                    {
                        a='COLUMN_NAME';
                        $('#front').append('<div class="line-1">'+'<div class="lineName">'+'<span class="icon-folder-open-alt">'+'</span>'+result[3][i][a]+'</div>'+
                                '<div class="lineData">'+
                                '<ul>'+
                                '<li class="current">'+
                                result[1][0][result[3][i][a]]+
                                '</li>'+
                                '</ul>'+
                                '</div>'+'</div>');
                        if ( result[1][0][result[3][i][a]]==null )
                        {
                            if(result[1][0][result[3][i+1][a]] == null )
                                FrontOne.push(result[3][i][a]);
                            else
                                FrontTwo.push(result[3][i][a]);
                        }
                        else{
                            FrontThree.push(result[3][i][a]);
                        }
                    }

                    $('.line-1').each(function () {
                        for(var i=0;i<FrontOne.length;i++)
                        {
                            if ($(this).text().indexOf(FrontOne[i])>=0)
                            {
                                $(this).addClass('front1');
                                $(this).find('.lineData').css('display','none');
                            }
                        }
                        $('.front1').css('font-size','200px');
                        for(var i=0;i<FrontTwo.length;i++)
                        {
                            if ($(this).text().indexOf(FrontTwo[i])>=0)
                            {
                                $(this).addClass('front2');
                                $(this).find('.lineData').css('display','none');
                                $(this).find('.icon-folder-open-alt').css('display','none');
                            }
                        }
                        for(var i=0;i<FrontThree.length-1;i++)
                        {
                            if ($(this).text().indexOf(FrontThree[i])>=0)
                            {
                                $(this).addClass('front3');
                                $(this).find('.lineData').css('display','inline-block');
                                $(this).find('.lineName').css('display','inline-block');
                                $(this).find('.icon-folder-open-alt').css('display','none');
                                $(this).find('.current').css('width','100px');
                            }
                        }
                    });


                    var BackOne =[];
                    var BackTwo =[];
                    var BackThree =[];
                    for (i=headnum;i<result[4].length;i++)
                    {
                        a='COLUMN_NAME';
                        $('#back').append('<div class="question">'+'<div class="icon-check">'+'</div>'+'<span>'+result[4][i][a]+'</span>'+
                                '<div class="questionData">'+result[2][0][result[4][i][a]]+'</div>'+'</div>');
                        //$('#back').append(result[4][i][a]+'<br>');
                        if ( result[4][i][a].match(/\？/) != null )
                        {
                            BackOne.push(result[4][i][a]);
                        }
                        else if(result[4][i][a].match(/\。/) != null){
                            BackTwo.push(result[4][i][a]);
                        }
                        else{
                            BackThree.push(result[4][i][a]);
                        }

                    }
                    $('.question').each(function () {
                        for(var i=0;i<BackOne.length;i++)
                        {
                            if ($(this).text().indexOf(BackOne[i])>=0)
                            {
                                $(this).addClass('back1');
                                $(this).append( '<i class="icon-chevron-right" style="float: left;margin-right: 6px;color: #CCCCCC;">'+
                                        '</i>');
                                $(this).find('.questionData').css('display','none');
                                $(this).find('.icon-check').css('display','none');
                            }
                        }
                        for(var i=0;i<BackTwo.length;i++)
                        {
                            if ($(this).text().indexOf(BackTwo[i])>=0)
                            {
                                $(this).addClass('back2');
                                $(this).find('.icon-check').css('display','none');
                            }
                        }
                        for(var i=0;i<BackThree.length;i++)
                        {
                            if ($(this).text().indexOf(BackThree[i])>=0)
                            {
                                $(this).find('.questionData').css('display','inline-block');
                                if( $(this).find('.questionData').text()=='null'){
                                    $(this).find('.questionData').css('display','none');
                                    $(this).find('.icon-check').css('display','none');
                                    $(this).append( '<i class="icon-check-empty" style="float: left;margin-right: 6px;color: #CCCCCC;">'+
                                            '</i>');
                                }
                                else {
                                    $(this).find('.questionData').css('display','none');
                                    $(this).find('.icon-check').css('margin-right','6px');
                                }
                            }
                        }

                    });

                    //移除标签中的类
                    //移除标签中的类
//                    $('li').removeClass("current");
//                    $('input[type=checkbox]').prop('checked',false);
//                    for(i=0;i<$($('#front').children()[0]).children().length;i++)
//                    {
//                        var textlevel1=$($($($('#front').children()[0]).children()[i]).children()[0]).children()[1].innerText;
//                        for(var j=2;j<$($($($('#front').children()[0]).children()[i]).children()[0]).children().length;j++)
//                        {
//                            var cssstyle=$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).attr("class");
//                            if(cssstyle=="grade2")
//                            {
//                                var textlevel2=$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[1].innerText;
//                                if(textlevel2.indexOf("总体评价")>=0)
//                                {
//                                    textlevel2=$.trim(textlevel2);
//                                    for(var p=0;p<$($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children().length;p++)
//                                        if($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children()[p].innerText==result[1][0][textlevel2])
//                                        {
//                                            $($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children()[p]).addClass("current");
//                                            break;
//                                        }
//                                    continue;
//                                }
//                                for(var k=2;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var textlevel3=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children().children()[0].innerText;
//                                    textlevel3=$.trim(textlevel3);
//                                    for(var p=0;p<$($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children().length;p++)
//                                        if($($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children()[p].innerText==result[1][0][textlevel3])
//                                        {
//                                            $($($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children()[p]).addClass("current");
//                                            break;
//                                        }
//                                }
//                            }
//                            if(cssstyle=="radiograde")
//                            {
//                                for(var k=0;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var choosecontent=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
//                                    choosecontent=$.trim(choosecontent);
//                                    if(result[1][0][choosecontent]==1)
//                                        $($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).prop("checked",true);
//                                }
//                            }
//                            if(cssstyle=="checkboxgrade")
//                            {
//                                for(var k=0;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var choosecontent=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
//                                    choosecontent=$.trim(choosecontent);
//                                    if(result[1][0][choosecontent]==1)
//                                        $($($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).prop("checked",true);
//                                }
//                            }
//                            if(cssstyle=="textareagrade")
//                            {
//                                for(var k=0;k<$($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children().length;k++)
//                                {
//                                    var text=$($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[k]).children()[0].innerText;
//                                    text=$.trim(text);
//                                    $($($($($($($('#front').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[k]).children()[1]).children().val(result[1][0][text]);
//                                }
//                            }
//                        }
//                    }
//
//                    for(i=0;i<$($('#back').children()[0]).children().length;i++)
//                    {
//                        var textlevel1=$($($($('#back').children()[0]).children()[i]).children()[0]).children()[1].innerText;
//                        for(var j=2;j<$($($($('#back').children()[0]).children()[i]).children()[0]).children().length;j++)
//                        {
//                            var cssstyle=$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).attr("class");
//                            if(cssstyle=="grade2")
//                            {
//                                var textlevel2=$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[1].innerText;
//                                if(textlevel2.indexOf("总体评价")>=0)
//                                {
//                                    textlevel2=$.trim(textlevel2);
//                                    for(var p=0;p<$($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children().length;p++)
//                                        if($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children()[p].innerText==result[2][0][textlevel2])
//                                        {
//                                            $($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children()[1]).children()[0]).children()[p]).addClass("current");
//                                            break;
//                                        }
//                                    continue;
//                                }
//                                for(var k=2;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var textlevel3=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children().children()[0].innerText;
//                                    textlevel3=$.trim(textlevel3);
//                                    for(var p=0;p<$($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children().length;p++)
//                                        if($($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children()[p].innerText==result[2][0][textlevel3])
//                                        {
//                                            $($($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[1]).children()[0]).children()[p]).addClass("current");
//                                            break;
//                                        }
//                                }
//                            }
//                            if(cssstyle=="radiograde")
//                            {
//                                for(var k=0;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var choosecontent=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
//                                    choosecontent=$.trim(choosecontent);
//                                    if(result[2][0][choosecontent]==1)
//                                        $($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).prop("checked",true);
//                                }
//                            }
//                            if(cssstyle=="checkboxgrade")
//                            {
//                                for(var k=0;k<$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().length;k++)
//                                {
//                                    var choosecontent=$($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0].innerText;
//                                    choosecontent=$.trim(choosecontent);
//                                    if(result[2][0][choosecontent]==1)
//                                        $($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children()[k]).children()[0]).children()[0]).prop("checked",true);
//                                }
//                            }
//                            if(cssstyle=="textareagrade")
//                            {
//                                var text=$($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children().context.innerText;
//                                text=$.trim(text);
//                                $($($($($($($($('#back').children()[0]).children()[i]).children()[0]).children()[j]).children().children().children()[0]).children()[1]).children()[0]).val(result[2][0][text]);
//                            }
//                        }
//                    }
                }
            });
            $('span:contains("评价状态")').css('display','none');
            $('span:contains("评价状态")').prev().css('display','none');
        },
        'click .remove': function (e, value, row, index){//重置
            $('#resetModel').click();
            $('#resetContent').html('确定要重置 '+row.督导id+" "+row.督导姓名+"督导 在"+row.听课时间+"日，"+row.听课节次+"课中" +
                    " 对 "+row.课程名称+" 的评价?");
            $('#OK').click(function (){
                var LessonName=row.课程名称.replace(/(^\s+)|(\s+$)/g,"");
                var LessonTeacher=row.任课教师.replace(/(^\s+)|(\s+$)/g,"");
                var LessonSupervisor=row.督导id.replace(/(^\s+)|(\s+$)/g,"");
                var LessonTime=row.听课节次.replace(/(^\s+)|(\s+$)/g,"");
                var LessonValueTime=row.听课时间.replace(/(^\s+)|(\s+$)/g,"");
                $.ajax({
                    type: "get",
                    async: false,
                    url: "/ResetEvaluationContent",
                    data: {
                        year1:$("#year1").val(),
                        year2:$("#year2").val(),
                        semester:$("#terminal").val().match(/\d/g),
                        supervisor:LessonSupervisor,
                        lessonname:LessonName,
                        lessontime:LessonTime,//听课节次
                        lessonteacher:LessonTeacher,
                        lessonvaluetime:LessonValueTime//听课时间
                    },//传递学院名称
                    success: function (result) {
                        if (result == 1)
                            alert('成功！');
                        else
                            alert('失败');
                        window.location.reload();
                    }
                });
            });


        },
        'click .SSubmit': function (e, value, row, index){//提交
            var LessonName=row.课程名称.replace(/(^\s+)|(\s+$)/g,"");
            var LessonTeacher=row.任课教师.replace(/(^\s+)|(\s+$)/g,"");
            var LessonSupervisorID=row.督导id.replace(/(^\s+)|(\s+$)/g,"");
            var LessonValueTime=row.听课时间.replace(/(^\s+)|(\s+$)/g,"");
            var LessonTime=row.听课节次.replace(/(^\s+)|(\s+$)/g,"");
            var valueID = row.valueID;
//需要加上撤销的节次！
            $.ajax({
                type: "get",
                async: false,
                url: "/SubmitEvaluationContent",
                data: {
                    year1:$("#year1").val(),
                    year2:$("#year2").val(),
                    semester:$("#terminal").val().match(/\d/g),
                    lessonname:LessonName,
                    lessonteacher:LessonTeacher,
                    lessonvaluetime:LessonValueTime,//听课时间
                    LessonTime:LessonTime,//听课节次
                    LessonSupervisorID:LessonSupervisorID,
                    valueID:valueID
                },//传递学院名称
                success: function (result) {
                    alert(result);
                    if(result=='提交成功')
                        window.location.reload();
                }
            });
        },
        'click .SDEL': function (e, value, row, index){//撤销
            $('#cancelModel').click();
            $('#cancelContent').html('确定要取消 '+row.督导姓名+"督导 在"+row.听课时间+"日，"+row.听课节次+"课中" +
                    " 对 "+row.课程名称+" 的评价?");
            $('#cancelOK').click(function (){
                var LessonName=row.课程名称.replace(/(^\s+)|(\s+$)/g,"");
                var LessonTeacher=row.任课教师.replace(/(^\s+)|(\s+$)/g,"");
                var LessonSupervisorID=row.督导id.replace(/(^\s+)|(\s+$)/g,"");
                var LessonValueTime=row.听课时间.replace(/(^\s+)|(\s+$)/g,"");
                var LessonTime=row.听课节次.replace(/(^\s+)|(\s+$)/g,"");
                var valueID = row.valueID;

                $.ajax({
                    type: "get",
                    async: false,
                    url: "/DelEvaluationContent",
                    data: {
                        year1:$("#year1").val(),
                        year2:$("#year2").val(),
                        semester:$("#terminal").val().match(/\d/g),
                        lessonname:LessonName,
                        lessonteacher:LessonTeacher,
                        lessonvaluetime:LessonValueTime,//听课时间
                        LessonSupervisorID:LessonSupervisorID,
                        LessonTime:LessonTime,//听课节次
                        valueID:valueID
                    },//传递学院名称
                    success: function (result) {
                        alert(result);
                        if(result=='撤销成功')
                            window.location.reload();
                    }
                });
            });
        }
    };
    /*------------点击正反页按钮-----------------*/
    $("#myTab1 li").eq(1).click(function () {
        $('.tabBack').css("display",'none');
        $('.tabFront').css("display",'block');

    });
    $("#myTab1 li").eq(0).click(function () {
        $('.tabBack').css("display",'block');
        $('.tabFront').css("display",'none');

    })
    $('.tabBack').click(function () {
        $('#myTab1 li:eq(1) a').tab('show');
        $('.tabBack').css("display",'none');
        $('.tabFront').css("display",'block');
        //alert("a");
    })
    $('.tabFront').click(function () {
        $('#myTab1 li:eq(0) a').tab('show');
        $('.tabBack').css("display",'block');
        $('.tabFront').css("display",'none');
    })
    /*---------------------结束-----------------*/
</script>
</html>