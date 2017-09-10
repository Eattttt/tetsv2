<!DOCTYPE html>
<meta name="render" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=11">

<style type="text/css">
    * {
        font-family: "Microsoft YaHei"
    }

    .nav_a {
        cursor: pointer;
    }

    .panel-add {
        color: #fff;
        background-color: #97d3c5 !important;
        border-color: #97d3c5 !important;
    }

    .panel-red {
        color: #fff;
        background-color: #f68484 !important;
        border-color: #f68484 !important;
    }

    .panel-blue {
        color: #fff;
        background-color: #75B9E6 !important;
        border-color: #75B9E6 !important;
    }

    .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
        padding: 3px !important;
    }

    .load-all {
        color: #428bca;
        float: right;
        margin-top: -10px;
        cursor: pointer;
    }

</style>
<head>
    <meta charset="utf-8"/>
    <title>北京林业大学教评中心业务平台</title>
    <meta name="keywords" content="北京林业大学教评中心业务平台"/>
    <meta name="description" content="北京林业大学教评中心业务平台"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- basic styles -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/bootstrap-table/src/bootstrap-table.css')}}" rel="stylesheet"/>


    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/dashboard.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/common.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/activity_css/modify.css')}}"/>

    {{--the style of school-term-choose--}}
    <link rel="stylesheet" type="text/css" href="{{asset('calendar/DateTimePicker.css')}}" />


   {{--the style of school-term-choose--}}
    <link rel="stylesheet" type="text/css" href="{{asset('calendar1/css/bootstrap-datetimepicker.css')}}" />


    <!--[if IE 7]>
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome-ie7.min.css')}}"/>
    <![endif]-->

    <!--[if lt IE 9]>
    <link rel="stylesheet" href="{{asset('assets/js/html5shiv.js')}}"/>
    <link rel="stylesheet" href="{{asset('assets/js/respond.min.js')}}"/>
    <![endif]-->
    <script src="{{asset('assets/js/jquery-2.0.3.min.js')}}"></script>
    <script src="{{asset('origin_bootstrap/js/bootstrap.min.js')}}"></script>

    {{--活动表格--}}
    <script src="{{asset('assets/bootstrap-table/src/bootstrap-table.js')}}"></script>
    <script src="{{asset('assets/bootstrap-table/src/locale/bootstrap-table-zh-CN.js')}}"></script>

    {{--活动时间选择--}}
    {{--<script src="{{asset('calendar1/js/locales/bootstrap-datetimepicker.zh-CN.js')}}"></script>--}}

    <script src="{{asset('calendar1/js/bootstrap-datetimepicker.js')}}"></script>
</head>
{{--<link rel="stylesheet" href="{{asset('css/Index.css')}}">--}}
<html lang="en">
<body>

@include('layout.header')
@include('layout.sidebar')

<div class="container-fluid col-md-offset-2">
    <div class="row page-title-row">
        <div class="col-md-12">
            <h3>
                <small></small>
            </h3>
        </div>
    </div>

    <div class="row">
        <div id="time-select" class="col-md-8 col-md-offset-1">
            <div class="col-md-2">
                <a class="btn btn-default btn-xs" href="/activity/modify/all">
                    全部
                </a>
            </div>

            <div class="col-md-3">
                选择学年
                @include('acsystem.partials.year-calender')
            </div>

            <div class="col-md-5">
                学年学期
                @include('acsystem.partials.year-term-calender')
            </div>



        </div>

        <!-- 增加活动的 模态框（Modal） -->
        <div class="modal fade" id="create-activity" tabindex="-1" role="dialog" aria-hidden="true">
            <div id="#add-activity-modal" class="modal-dialog" style="width:50%; margin-top: 10%">
                <div class="modal-content">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">增加活动
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="/activity/admin/create">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @include('acsystem.partials.createActform')
                                <div class="form-group">
                                    <div class="col-md-3 add-button-group">
                                        <button type="submit" class="btn btn-primary btn-md" id="submit">
                                            <i class="fa fa-plus-circle"></i>
                                            确定
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            关闭
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </div>
        <!-- 修改活动的 模态框（Modal） -->
        <div class="modal fade" id="change-activity" tabindex="-1" role="dialog" aria-hidden="true">
            <div id="#add-activity-modal" class="modal-dialog" style="width:50%; margin-top: 10%">
                <div class="modal-content">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">修改活动
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="/activity/admin/change">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @include('acsystem.partials.changeActform')
                                <div class="form-group">
                                    <div class="col-md-3 add-button-group">
                                        <button type="submit" class="btn btn-primary btn-md" id="submit">
                                            <i class="fa fa-plus-circle"></i>
                                            确定
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            关闭
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </div>
        <!-- 查看详情的 模态框（Modal） -->
        <div class="modal fade" id="checkout-activity" tabindex="-1" role="dialog" aria-hidden="true">
            <div id="#add-activity-modal" class="modal-dialog" style="width:50%; margin-top: 10%">
                <div class="modal-content">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title">活动详情
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                            </h2>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <p><span style="font-weight: bold;">活动名称：&nbsp;&nbsp;</span>
                                    <span id="act-name" ></span>
                                </p>
                                <p><span style="font-weight: bold;">活动地点：&nbsp;&nbsp;</span>
                                    <span id="act-place" ></span>
                                </p>
                                <p><span style="font-weight: bold;">活动时间：&nbsp;&nbsp;</span>
                                    <span id="act-time" ></span>
                                </p>
                                <p><span style="font-weight: bold;">报名时间：&nbsp;&nbsp;</span>
                                    <span id="apply_act-time" ></span>
                                </p>
                                <p><span style="font-weight: bold;">活动信息：&nbsp;&nbsp;</span>
                                    <span id="act-info" ></span>
                                </p>
                            </div>
                            <div class="row">
                                <table data-toggle="table" id="attendTable"
                                       data-halign="center" data-align="center"
                                       data-pagination="true"
                                       data-page-size="10"
                                       data-page-list="[5, 10, 20, 50, 100, 200]"
                                       data-search="true"
                                     >
                                    <thead>
                                    <tr>
                                        <th data-field="Number" data-formatter="actionFormatterNumber"
                                            data-halign="center" data-align="center" >序号</th>
                                        <th data-field="unit" data-halign="center" data-align="center">参与教师所在学院</th>
                                        <th data-field="name" data-halign="center" data-align="center">参与教师姓名</th>
                                    </tr>
                                    </thead>
                                </table>
                                <button id="#add-joined-teacher" class="btn btn-primary btn" data-toggle="modal" data-target="#add-joined">
                                    增加参与教师
                                </button>
                                <button id="teacher—list-btn" class="btn btn-primary btn" data-toggle="modal" data-target="#teacher—list">
                                    教师名单审核与导出
                                </button>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </div>

        <!-- 增加参与教师 模态框（Modal） -->
        <div class="modal fade" id="add-joined" tabindex="-1" role="dialog" aria-hidden="true">
            <div id="#add-activity-modal" class="modal-dialog" style="width:50%; margin-top: 10%">
                <div class="modal-content">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">增加参与教师
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="/activity/admin/addteacher">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @include('acsystem.partials.addActTeacher')
                                <div class="form-group">
                                    <div class="col-md-3 add-button-group">
                                        <button type="submit" class="btn btn-primary btn-md" id="submit">
                                            <i class="fa fa-plus-circle"></i>
                                            确定
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            关闭
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </div>
        <!-- 教师名单的 模态框（Modal） -->
        <div class="modal fade" id="teacher—list" tabindex="-1" role="dialog" aria-hidden="true">
            <div id="#add-activity-modal" class="modal-dialog" style="width:50%; margin-top: 10%">
                <div class="modal-content">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">教师名单
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <p type="text" class="form-control" name="activityID" id="activityID" style="display: none" value="" autofocus>
                            </p>
                            <p type="text" class="form-control" name="activityName" id="activityName" style="display: none" value="" autofocus>
                            </p>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @include('acsystem.partials.teacherList')
                                <div class="form-group" style="margin-top: 10px">
                                    <div class="col-md-8 add-button-group">
                                        <button id="review11" class="btn btn-primary btn" >
                                            报名已参加
                                        </button>
                                        <button id="review10" class="btn btn-primary btn" >
                                            报名未参加
                                        </button>
                                        <button id="review01" class="btn btn-primary btn" >
                                            未报名参加
                                        </button>
                                        <button id="export-teacher" class="btn btn-warning btn">
                                            导出名单
                                        </button>
                                    </div>
                                </div>
                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </div>
    </div>


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if($titleterm=='all')
                        <h3 class="panel-title" style="text-align: center; font-size: larger">历年活动列表</h3>
                    @else
                        <h3 class="panel-title" style="text-align: center; font-size: larger">{{$titleterm}} 活动列表</h3>
                    @endif

                </div>

                @include('acsystem.partials.errors')

                <div class="panel-body">
                    @include('acsystem.partials.success')

                    <table
                            data-toggle="table"
                            id="activityTable"
                            data-halign="center" data-align="center"
                            data-url="/activity/show/{{ $titleterm }}"
                            data-search="true">
                        <thead>
                        <tr>
                            <th data-field="choose" data-checkbox="true"></th>
                            <th data-field="name" data-halign="center" data-align="center">活动名称</th>
                            <th data-field="teacher" data-halign="center" data-align="center">主讲人</th>
                            <th data-field="place" data-halign="center" data-align="center">活动地点</th>
                            <th data-field="start_time" data-halign="center" data-align="center">活动开始时间</th>
                            <th data-field="end_time" data-halign="center" data-align="center">活动结束时间</th>
                            <th data-field="apply_start_time" data-halign="center" data-align="center">报名开始时间</th>
                            <th data-field="apply_end_time" data-halign="center" data-align="center">报名结束时间</th>
                            <th data-field="attend_num" data-halign="center" data-align="center">报名人数</th>
                            <th data-field="remainder_num" data-halign="center" data-align="center">剩余名额</th>
                            <th data-field="action" data-halign="center" data-align="center"
                                data-formatter="activityInfo" data-events="actionEvents" >活动详情</th>
                            <th data-field="apply_state" data-halign="center" data-align="center">报名状态</th>
                            @if($titleterm=='all')
                            <th data-field="term" data-halign="center" data-align="center">活动学期</th>
                            @endif
                            <th data-field="modify" data-halign="center" data-align="center"
                                data-formatter="activityModify" data-events="actionEvents" >修改活动</th>

                        </tr>
                        </thead>
                    </table>
                    <div class="button-group">
                        <button id="add-activity" class="btn btn-primary btn" data-toggle="modal" data-target="#create-activity">
                            增加活动
                        </button>
                        <button id="check-activity" data-toggle="modal" data-target="#checkout-activity" style="display: none">
                            查看活动
                        </button>
                        <button id="change-activity-btn" data-toggle="modal" data-target="#change-activity" style="display: none">
                            查看活动
                        </button>
                        <button id="del-activity" class="btn btn-danger btn" >
                            删除活动
                        </button>
                        <button id="export-activity" class="btn btn-warning btn" >
                            导出活动
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/HelpFunction.js')}}"></script>
<script src="{{asset('js/activity/modify.js')}}"></script>
<script>
    var csrf_token='{{csrf_token()}}';
    var term = '{{$titleterm}}';
</script>
</body>


</html>

