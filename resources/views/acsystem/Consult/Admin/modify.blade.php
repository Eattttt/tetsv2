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

    <div class="modal fade" id="create-consult" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <form class="form-horizontal" role="form" method="POST" action="/consult/admin/create">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="name" class="col-md-2 control-label">咨询名称</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="name" id="name" value=""
                                           autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3 add-button-group">
                                    <button type="submit" class="btn btn-primary btn-md">
                                        <i class="fa fa-plus-circle"></i>
                                        增加
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


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                        <h3 class="panel-title">咨询名称修改</h3>
                </div>
                <div class="button-group">
                    <button class="btn btn-primary btn" data-toggle="modal" data-target="#create-consult">
                        增加内容
                    </button>
                    <button id="del-consult" class="btn btn-danger btn" >
                        删除内容
                    </button>
                </div>
                @include('acsystem.partials.errors')

                <div class="panel-body">
                    @include('acsystem.partials.success')

                    <table data-toggle="table" id="consultTable"
                           data-halign="center" data-align="center"
                           data-url="/consult/ConsultContent" style="font-size: initial;">
                        <thead>
                        <tr>
                            <th data-field="choose" data-checkbox="true"></th>
                            <th data-field="Number" data-formatter="actionFormatterNumber" data-halign="center" data-align="center" >序号</th>
                            <th data-field="name" data-halign="center" data-align="center">咨询名称</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/consult/modify.js')}}"></script>
<script src="{{asset('js/HelpFunction.js')}}"></script>
<script>
    var csrf_token='{{csrf_token()}}';
</script>
</body>


</html>

