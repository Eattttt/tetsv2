<?php

?>

<style>
    a{
        color:#fff;
    }
    .sidebar{
        position: fixed;
    }
    .indents{
        margin-left:20px;
    }
    .indentManage{
        margin-left:11px;
    }
    .sidebar-collapse > .nav > li{
        border-bottom:1px solid rgba(107, 108, 109, 0.19);
        margin-left:6px;
    }
    .sidebar-collapse > .nav > li > a{
        padding:12px 10px;

    }
    #aside{
        width: 250px;
    }
    @media screen and (max-width: 1400px){
        .nav>li>a{
            padding: 10px 2px;
        }
        #aside{
            width: 220px;
        }
    }
    @media screen and (max-width: 768px){
        #aside{
            position: absolute;
            width: 220px;
            min-height: 750px;
        }
    }
</style>
<div id="aside" class="sidebar sidebar-collapse" style="top: 51px;bottom: 0;left: 0;z-index: 1000;
display: block;padding: 20px;overflow-x: hidden;overflow-y: auto;background-color: #0a1d2f;
border-right: 1px solid #eee;box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);">
{{--<div class="col-sm-2 col-md-2 sidebar sidebar-collapse">--}}
    <ul id="sidebar_id" class="nav nav-sidebar ">
        @if(session('role')=='')
            <script language="JavaScript">
                    window.location.reload();
            </script>
        @endif
        @if(session('role')=='校级')

            <li class="Upper-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                <a href="/index">
                    <i class="icon-dashboard"></i>
                    <span class="menu-text" style="font-size: 15px;">首页</span>
                </a>
            </li>
            <li class="evaluated-menu" style="margin-top: 20px; border-left:3px solid #4AA3DE;">
                <a href="#planManage" class="nav-header" data-toggle="collapse" style="font-size: 15px;">
                    <i class="icon-desktop"></i>
                    评价管理
                    <b class="arrow icon-angle-down"></b>
                </a>
                <ul id="planManage" class="nav nav-list collapse in">
                    <li class="indentManage">
                        <a href="/EverEvaluated">
                            <i class="glyphicon icon-bookmark"></i>
                            评价结果
                        </a>
                    </li>
                    <li class="indentManage">
                        <a href="/Evaluation">
                            <i class="glyphicon icon-flag"></i>
                            关注课程完成情况
                        </a>
                    </li>



                    <li class="indentManage">
                        <a href="#EvaluationStatistics" class="nav-header collapse" data-toggle="collapse">
                            <i class="icon-file-alt"></i>
                            评价统计
                            <b class="arrow icon-angle-down"></b>
                        </a>
                        <ul id="EvaluationStatistics" class="nav nav-list collapse ">
                            <li class="indents">
                                <a href="/Statistics">
                                    <i class="glyphicon glyphicon-minus" ></i>
                                    各小组听课情况
                                </a>
                            </li>

                            <li class="Upper_menu  indents">
                                <a href="#indents"  class="nav-header collapse" data-toggle="collapse">

                                    <i class="glyphicon glyphicon-minus" ></i>
                                    听课数据统计
                                    <b class="arrow icon-angle-down"></b>
                                </a>
                                <ul id="indents" class="nav nav-list collapse in">
                                    <li class="indents">
                                        <a href="/TheoryStatistics">
                                            <i class="glyphicon" ></i>
                                            理论课部分
                                        </a>
                                    </li>
                                    <li class="indents">
                                        <a href="/PracticeStatistics">
                                            <i class="glyphicon" ></i>
                                            实践课部分
                                        </a>
                                    </li>
                                    <li class="indents">
                                        <a href="/PhysicalStatistics">
                                            <i class="glyphicon"></i>
                                            体育课部分
                                        </a>
                                    </li>
                                </ul>

                            </li>
                        </ul>
                    </li>

                        <li class="Upper_menu indentManage">
                            <a href="#contentManager"  class="nav-header collapse" data-toggle="collapse">
                                <i class="glyphicon icon-edit" ></i>
                                评价表填写
                                <b class="arrow icon-angle-down"></b>
                            </a>
                            <ul id="contentManager" class="nav nav-list collapse in">
                                <li class="indents">
                                    <a href="/TheoryEvaluationTableView"> {{--a test link--}}
                                        <i class="glyphicon glyphicon-minus" ></i>
                                        理论课评价用表
                                    </a>
                                </li>
                                <li class="indents">
                                    <a href="/PracticeEvaluationTableView">
                                        <i class="glyphicon glyphicon-minus" ></i>
                                        实践课评价用表
                                    </a>
                                </li>
                                <li class="indents">
                                    <a href="/PhysicalEvaluationTableView">
                                        <i class="glyphicon glyphicon-minus"></i>
                                        体育课评价用表
                                    </a>
                                </li>
                            </ul>
                        </li>
                </ul>
            </li>
            <li class="setting-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                <a href="#Initsystem" class="nav-header collapse collapsed" data-toggle="collapse" style="font-size: 15px;">
                    <i class=" icon-cog"></i>
                    系统设置
                    <b class="arrow icon-angle-down"></b>
                </a>
                <ul id="Initsystem" class="nav nav-list collapse">

                    <li class="indents">
                        <a href="/LessonTable">
                            <i class="icon-list-alt" ></i>
                            教师课表管理
                        </a>
                    </li>
                    <li class="indents">
                        <a href="/NecessaryTask">
                            <i class="glyphicon icon-random"></i>
                            学期关注课程
                        </a>
                    </li>

                    <li class="Upper_menu indents">
                        <a href="#contentChange"  class="nav-header collapse collapsed" data-toggle="collapse">
                            <i class="glyphicon  icon-edit" ></i>
                            评价体系修改
                            <b class="arrow icon-angle-down"></b>
                        </a>
                        <ul id="contentChange" class="nav nav-list collapse collapsed">
                            <li class="indents">
                                <a href="/UpdateTheoryView">
                                    <i class="glyphicon glyphicon-minus" ></i>
                                    理论课用表修改
                                </a>
                            </li>
                            <li class="indents">
                                <a href="/UpdatePracticeView">
                                    <i class="glyphicon glyphicon-minus" ></i>
                                    实践课用表修改
                                </a>
                            </li>
                            <li class="indents">
                                <a href="/UpdatePhysicalView">
                                    <i class="glyphicon glyphicon-minus"></i>
                                    体育课用表修改
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="user-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #4AA3DE; ">
                <a href="#UserManage" class="nav-header " data-toggle="collapse" style="font-size: 15px;">
                    <i class=" icon-user"></i>
                    用户管理
                    <b class="arrow icon-angle-down"></b>
                </a>
                <ul id="UserManage" class="nav nav-list collapse">

                        <li class="indents">
                            <a href="/SupervisorInfo" class="nav-header" style="font-size: 15px;">
                                <i class="icon-minus"></i>
                                督导用户管理
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/UnitUserManage" class="nav-header" style="font-size: 15px;">
                                <i class="icon-minus"></i>
                                学院用户管理
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/teacherManage" class="nav-header" style="font-size: 15px;">
                                <i class="icon-minus"></i>
                                学校教师管理
                            </a>
                        </li>
                    </ul>
            </li>

            <li class="Activity-publish" style="margin-top: 20px;border-left:3px solid #73C4B1; ">
                <a href="/activity/modify">
                    <i class="glyphicon icon-briefcase"></i>
                    培训活动发布
                </a>
            </li>

                <li class="consult-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #4AA3DE; ">
                    <a href="#ConsultManage" class="nav-header " data-toggle="collapse" style="font-size: 15px;">
                        <i class=" icon-user"></i>
                        咨询管理
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul id="ConsultManage" class="nav nav-list collapse">

                        <li class="indents">
                            <a href="/consult/adjust">
                                <i class="glyphicon icon-font"></i>
                                咨询活动协调
                            </a>
                        </li>

                        <li class="indents">
                            <a href="/consult/modify">
                                <i class="glyphicon icon-bold"></i>
                                咨询内容修改
                            </a>
                        </li>

                    </ul>
                </li>

        @endif


            @if(session('role')=='大组长')

                <li class="Upper-menu" style="border-left:3px solid #4AA3DE;">
                    <a href="/index">
                        <i class="icon-dashboard"></i>
                        <span class="menu-text" style="font-size: 15px;">首页</span>
                    </a>
                </li>
                <li class="evaluated-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                    <a href="#planManage" class="nav-header" data-toggle="collapse" style="font-size: 15px;">
                        <i class="icon-desktop"></i>
                        评价管理
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul id="planManage" class="nav nav-list collapse in">
                        <li class="indentManage">
                            <a href="/EverEvaluated">
                                <i class="glyphicon icon-bookmark"></i>
                                评价结果
                            </a>
                        </li>
                        <li class="indentManage">
                            <a href="/Evaluation">
                                <i class="glyphicon icon-flag"></i>
                                关注课程完成情况
                            </a>
                        </li>
                        <li class="indentManage">
                            <a href="/Statistics">
                                <i class="icon-file-alt" ></i>
                                各小组听课情况
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="Upper_menu indentManage indents">
                    <a href="#indents" class="nav-header collapse" data-toggle="collapse">
                        <i class="glyphicon glyphicon-minus" ></i>
                        听课数据统计
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul id="indents" class="nav nav-list collapse in">
                        <li class="indents">
                            <a href="/TheoryStatistics">
                                <i class="glyphicon" ></i>
                                理论课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PracticeStatistics">
                                <i class="glyphicon" ></i>
                                实践课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PhysicalStatistics">
                                <i class="glyphicon"></i>
                                体育课部分
                            </a>
                        </li>
                    </ul>

                </li>

                <li class=" indents" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #4AA3DE; ">
                    <a href="#indents" class="nav-header collapse" data-toggle="collapse">
                        <i class="icon-file-alt" ></i>
                        听课数据统计
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul id="indents" class="nav nav-list collapse in">
                        <li class="indents">
                            <a href="/TheoryStatistics">
                                <i class="glyphicon icon-minus" ></i>

                                理论课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PracticeStatistics">
                                <i class="glyphicon icon-minus" ></i>
                                实践课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PhysicalStatistics">
                                <i class="glyphicon icon-minus"></i>
                                体育课部分
                            </a>
                        </li>
                    </ul>

                </li>
                {{--<li class="indentManage" style="border-left:3px solid #4AA3DE;">--}}
                    {{--<a href="/DataStatistics">--}}
                        {{--<i class="icon-file-alt"></i>--}}
                        {{--评价统计--}}
                    {{--</a>--}}
                {{--</li>--}}
                <li class="indents"   style="border-left:3px solid #73C4B1; ">
                    <a href="/NecessaryTask">
                        <i class="glyphicon icon-random"></i>
                        学期关注课程
                    </a>
                </li>
                <li class="class-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                    <a href="/LessonTable" style="font-size: 15px;">
                        <i class="icon-list-alt" ></i>
                        学院教师课表
                    </a>
                </li>
                <li class="user-menu" style="border-left:3px solid #4AA3DE;">
                    <a href="/SupervisorInfo" class="nav-header" style="font-size: 15px;">
                        <i class="icon-user"></i>
                        督导用户列表
                    </a>
                </li>
                <li class="Unituser-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                    <a href="/UnitUserManage" class="nav-header" style="font-size: 15px;">
                        <i class="icon-user"></i>
                        学院教学院长
                    </a>
                </li>
                {{--<li class="user-menu" style="border-left:3px solid #4AA3DE;">--}}
                    {{--<a href="#UserManage" class="nav-header" data-toggle="collapse" style="font-size: 15px;">--}}
                        {{--<i class="icon-user"></i>--}}
                        {{--培训活动与教学咨询--}}
                        {{--<b class="arrow icon-angle-down"></b>--}}
                    {{--</a>--}}
                    {{--<ul id="UserManage" class="nav nav-list collapse in">--}}
                        {{--<li class="indents">--}}
                            {{--<a href="/activity/index-suv" class="nav-header" style="font-size: 15px;">--}}
                                {{--<i class="icon-minus" ></i>--}}
                                {{--报名培训活动--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="indents">--}}
                            {{--<a href="/consult/index-suv" class="nav-header" style="font-size: 15px;">--}}
                                {{--<i class="icon-minus" ></i>--}}
                                {{--报名教学咨询--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="indents">--}}
                            {{--<a href="/teachEvaluation/index-suv" class="nav-header" style="font-size: 15px;">--}}
                                {{--<i class="icon-minus"></i>--}}
                                {{--对我的教学评价--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
            @endif

        @if(session('role')=='小组长')
                <li class="Upper-menu" style="border-left:3px solid #4AA3DE;">
                    <a href="/index">
                        <i class="icon-dashboard"></i>
                        <span class="menu-text" style="font-size: 15px;">首页</span>
                    </a>
                </li>
                <li class="group-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                    <a href="#EvaluatedManage" class="nav-header" data-toggle="collapse" style="font-size: 15px;">
                        <i class="icon-edit"></i>
                        小组评价管理
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul id="EvaluatedManage" class="nav nav-list collapse in">
                        <li class="indentManage">
                            <a href="/EverEvaluated">
                                <i class="glyphicon glyphicon-minus" ></i>
                                评价结果
                            </a>
                        </li>
                        <li class="indentManage">
                            <a href="/Evaluation">
                                <i class="glyphicon glyphicon-minus" ></i>
                                关注课程完成情况
                            </a>
                        </li>
                        <li class="indentManage">
                            <a href="/Statistics">
                                <i class="glyphicon glyphicon-minus" ></i>
                                小组听课情况
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="Upper_menu indentManage indents" style="border-left:3px solid #73C4B1; ">
                    <a href="#indents" class="nav-header collapse" data-toggle="collapse" style="font-size: 15px;">
                        <i class="icon-file-alt" ></i>
                        听课数据统计
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul id="indents" class="nav nav-list collapse in">
                        <li class="indents">
                            <a href="/TheoryStatistics">
                                <i class="glyphicon icon-minus" ></i>
                                理论课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PracticeStatistics">
                                <i class="glyphicon icon-minus" ></i>
                                实践课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PhysicalStatistics">
                                <i class="glyphicon icon-minus"></i>
                                体育课部分
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="necessary-menu" style="border-left:3px solid #4AA3DE;">
                    <a href="/NecessaryTask" style="font-size: 15px;">
                        <i class="glyphicon icon-random"></i>
                        学期关注课程
                    </a>
                </li>

                <li class="Upper_menu indentManage indents">
                    <a href="#indents" class="nav-header collapse" data-toggle="collapse">
                        <i class="glyphicon glyphicon-minus" ></i>
                        听课数据统计
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul id="indents" class="nav nav-list collapse in">
                        <li class="indents">
                            <a href="/TheoryStatistics">
                                <i class="glyphicon" ></i>
                                理论课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PracticeStatistics">
                                <i class="glyphicon" ></i>
                                实践课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PhysicalStatistics">
                                <i class="glyphicon"></i>
                                体育课部分
                            </a>
                        </li>
                    </ul>

                </li>

                <li class="class-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                    <a href="/LessonTable" style="font-size: 15px;">
                        <i class="icon-list-alt" ></i>
                        学院教师课表
                    </a>
                </li>
                <li class="user-menu" style="border-left:3px solid #4AA3DE;">
                    <a href="/SupervisorInfo" class="nav-header" style="font-size: 15px;">
                        <i class="icon-user"></i>
                        {{Auth::User()->group}}督导用户列表
                        {{--<b class="arrow icon-angle-down"></b>--}}
                    </a>
                </li>
                <li class="Unituser-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                    <a href="/UnitUserManage" class="nav-header" style="font-size: 15px;">
                        <i class="icon-user"></i>
                        学院教学院长
                    </a>
                </li>
                {{--<li class="user-menu" style="border-left:3px solid #4AA3DE;">--}}
                    {{--<a href="#UserManage" class="nav-header" data-toggle="collapse" style="font-size: 15px;">--}}
                        {{--<i class="icon-user"></i>--}}
                        {{--培训活动与教学咨询--}}
                        {{--<b class="arrow icon-angle-down"></b>--}}
                    {{--</a>--}}
                    {{--<ul id="UserManage" class="nav nav-list collapse in">--}}
                        {{--<li class="indents">--}}
                            {{--<a href="/activity/index-suv" class="nav-header" style="font-size: 15px;">--}}
                                {{--<i class="icon-minus" ></i>--}}
                                {{--报名培训活动--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="indents">--}}
                            {{--<a href="/consult/index-suv" class="nav-header" style="font-size: 15px;">--}}
                                {{--<i class="icon-minus" ></i>--}}
                                {{--报名教学咨询--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="indents">--}}
                            {{--<a href="/teachEvaluation/index-suv" class="nav-header" style="font-size: 15px;">--}}
                                {{--<i class="icon-minus"></i>--}}
                                {{--对我的教学评价--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
        @endif

        @if(session('role')=='院级')

                <li class="Upper_menu" style="border-left:3px solid #4AA3DE;">
                    <a href="/index" style="font-size: 15px;">
                        <i class="icon-dashboard"></i>
                        <span class="menu-text">首页</span>
                    </a>
                </li>


                <li class="indentManage" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                    <a href="/EverEvaluated">
                        <i class="glyphicon icon-bookmark"></i>
                        评价结果
                    </a>
                </li>


                <li class="Upper_menu indentManage indents" style="margin-top: 20px; border-left:3px solid #4AA3DE;">
                    <a href="#indents" class="nav-header collapse" data-toggle="collapse">
                        <i class="glyphicon icon-file-alt" ></i>

                        听课数据统计
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul id="indents" class="nav nav-list collapse in">
                        <li class="indents">
                            <a href="/TheoryStatistics">
                                <i class="glyphicon icon-minus" ></i>
                                理论课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PracticeStatistics">
                                <i class="glyphicon icon-minus" ></i>
                                实践课部分
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/PhysicalStatistics">

                                <i class="glyphicon icon-minus"></i>
                                体育课部分
                            </a>
                        </li>
                    </ul>

                </li>

                <li class="college-users" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                    <a href="/SupervisorInfo" style="font-size: 15px;">
                        <i class="icon-user"></i>
                       督导用户列表
                    </a>
                </li>
        @endif

        @if(session('role')=='督导')
            <li class="necessary-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                    <a href="/NecessaryTask" style="font-size: 15px;">
                        <i class="glyphicon icon-random"></i>
                        学期关注课程
                    </a>
                </li>

            <li class="class-menu" style="border-left:3px solid #4AA3DE;">
                    <a href="/LessonTable" style="font-size: 15px;">
                        <i class="icon-list-alt" ></i>
                        学院教师课表
                    </a>
                </li>
            <li class="supervise-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                <a href="#planManage" class="nav-header" data-toggle="collapse" style="font-size: 15px;">
                    <i class="icon-edit"></i>
                    我要评价
                    <b class="arrow icon-angle-down"></b>
                </a>
                <ul id="planManage" class="nav nav-list collapse in">
                    <li class="indentManage">
                        <a href="/TheoryEvaluationTableView">
                            <i class="glyphicon glyphicon-minus" ></i>
                            理论课评价用表
                        </a>
                    </li>
                    <li class="indentManage">
                        <a href="/PracticeEvaluationTableView">
                            <i class="glyphicon glyphicon-minus" ></i>
                            实践课评价用表
                        </a>
                    </li>
                    <li class="indentManage">
                        <a href="/PhysicalEvaluationTableView">
                            <i class="glyphicon glyphicon-minus"></i>
                            体育课评价用表
                        </a>
                    </li>
                </ul>
            </li>

            <li class="myEvaluation" style="border-left:3px solid #4AA3DE;">
                <a href="/EverEvaluated" style="font-size: 15px;">
                    <i class="icon-file-alt"></i>
                     我的评价
                </a>
            </li>



            <li class="Unituser-menu" style="margin-top: 20px;background-color: rgba(16, 33, 49, 0.86);border-left:3px solid #73C4B1; ">
                <a href="/UnitUserManage" class="nav-header" style="font-size: 15px;">
                     <i class="icon-user"></i>
                    学院教学院长
                </a>
            </li>

                <li class="user-menu" style="border-left:3px solid #4AA3DE;">
                    <a href="#UserManage" class="nav-header" data-toggle="collapse" style="font-size: 15px;">
                        <i class="icon-user"></i>
                        培训活动与教学咨询
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul id="UserManage" class="nav nav-list collapse in">
                        <li class="indents">
                            <a href="/activity/index-suv" class="nav-header" >
                                <i class="icon-minus" ></i>
                                培训活动报名
                            </a>
                        </li>
                        <li class="indents">
                            <a href="/consult/index-suv" class="nav-header" >
                                <i class="icon-minus" ></i>
                                预约教学咨询
                            </a>
                        </li>
                        {{--<li class="indents">--}}
                            {{--<a href="/teachEvaluation/index-suv" class="nav-header" style="font-size: 15px;">--}}
                                {{--<i class="icon-minus"></i>--}}
                                {{--对我的教学评价--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    </ul>
                </li>
        @endif
    </ul>
</div>


