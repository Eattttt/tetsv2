<!DOCTYPE html>
<meta name="render" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=11">
<html lang="en">
<style type="text/css">
    *{font-family:"Microsoft YaHei"}
    .nav_a{
        cursor: pointer;
    }
    #infoForm{
        margin: 0 40px 40px 40px;
        text-align: center;
    }

    #infoForm input{
        width: 300px!important;
        display: inline-block!important;
        margin: 11px 0px 14px 22px!important;
    }
    .submit{
        width: 400px!important;
    }
    #infoForm span{
        font-size: 16px;
    }
    #infoForm select{
        display: inline-block!important;
        width:300px;margin:11px 0px 14px 22px!important;
    }
    .error{
        text-align: center;
        list-style: none;
        margin-bottom: 0px!important;
        padding: 5px!important;
    }
    .required{
        font-size: 16px;
        color:#f00 ;
        margin-left:80px;
        font-weight:400;
    }
    .form-div{
        background-color: rgba(255, 255, 255, 0.27);
        border-radius: 10px;
        margin: 0 auto;
        padding: 30px 0 20px 0;
        font-size: 12px;
        box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.5), 0 0 15px rgba(75, 75, 75, 0.3);
        text-align: left;
        /*margin-bottom: 50px;*/
        margin-top: 30px;;
    }
    #name{
        width: 220px;
        margin-left: 20px;
        padding: 8px;
    }
    .add_task{
        margin-left:30px;
    }
    #search-suggest{
        position: absolute;
        display: none;
        /*z-index: 100;*/
    }
    .suggest{
        width:183px;
        background-color: white;
        border: 1px solid #999;
        left:400px;
    }
    .suggest ul
    {

        list-style: none;
        margin: 0;
        padding: 0;
    }
    .suggest ul li{
        padding:3px;
        font-size: 14px;
        line-height: 25px;
        cursor: pointer;
    }
    .suggest ul li:hover{
        text-decoration: underline;
        background-color: grey;
    }
    .seeDetail{
        cursor: pointer;
        color: #428bca;
    }
    .modal-dialog{
        width: 1200px !important;
        padding-top: 60px !important;
        margin-left: 480px !important;
    }
    @media screen and (max-width: 1400px){
        .modal-dialog{
            width: 1120px !important;
            margin-left: 180px !important;
        }
    }

    .btn-add{
        background-color:#B6D9B1;
        border-color:#B6D9B1;
    }
    .btn-add,.btn-remove,.btn-daoru,.btn-daochu{
        color: #fff;
    }
    .btn:hover,.btn:focus{
        color: #fff !important;
    }
    .search input{
        margin-top: 10px;
    }
    #newmain{
        float: left;margin-left:255px;width: 84%;
    }
    @media screen and (max-width: 1400px){
        #newmain{
            float: left;margin-left:255px;width: 79%;
        }
    }
    @media screen and (max-width: 1100px){
        #newmain{
            float: left;margin-left:255px;width: auto;
        }
    }
    @media screen and (max-width: 768px){
        #newmain{
            overflow: auto;float: left;
            margin-left:39%;width: 1660px;
        }
        #dtpick{
            left: -13%;
            top: 38%;
        }
    }
    @media screen and (max-width: 415px) {
        #newmain{
            overflow: auto;float: left;
            margin-left:69%;width: 1660px;
        }
        #dtpick{
            top: 19%;
            left: -19%;
        }
    }
</style>
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
    {{--<link rel="stylesheet" href="calendar1/css/bootstrap-datetimepicker.css" />--}}


    {{--学期选择 日历相关--}}
    <link rel="stylesheet" type="text/css" href="calendar/DateTimePicker.css" />
    <script type="text/javascript" src="calendar/jquery-1.11.0.min.js"></script>
    <!--[if IE 7]>
    <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
@include('layout.header')
@include('layout.sidebar')
<div id="newmain" class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2">
    <div class="row">
        <!-- .breadcrumb -->
        <div class="breadcrumbs" id="breadcrumbs">
            <ul class="breadcrumb" style="padding-top: 22px; padding-bottom: 12px;">
                {{--<li>--}}
                    {{--<i class="icon-home home-icon"></i>--}}
                    {{--<a style="color: #225081;" href="#">用户管理</a>--}}
                {{--</li>--}}
            </ul>
        </div>

        <input id="getlevel" value="{{session('role')}}" style="display: none"/>
        <input id="getunit" value="{{Auth::User()->unit}}" style="display: none"/>
        <input id="getgroup" value="{{Auth::User()->group}}" style="display: none"/>

        <div class="page-content col-md-12">
            @if(session('role')=='校级')
                <div class="bs-bars pull-left">
                    <button id="add" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-raised">
                        <i class="glyphicon glyphicon-plus"></i>&nbsp;添加</button>
                </div>

                <button class="btn btn-danger btn-raised" data-toggle="modal" data-target="#RenewContact">
                    <i class="icon-warning-sign"></i>一键续约
                </button>
                <!-- 模态框（Modal） -->
                <div class="modal fade" id="RenewContact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h4 class="modal-title" id="myModalLabel">
                                    一键续约
                                </h4>
                            </div>
                            <div class="modal-body">
                                尊敬的管理员老师，续约功能只可在聘期结束前的最后一个学期使用。
                                本次续约至{{$nextTime}}，续约过程大概需要10s，请您耐心等待。
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                                </button>
                                <a  href="/RenewContacts" class="btn btn-primary">
                                    提交
                                </a>

                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal -->
                </div>
            @endif

            <p style="display: inline-block;">
                学年学期
                <input id="calender" class="select-year" type="text" data-field="date" readonly>
            </p>
                <input type="checkbox" id="check1" value="0" />显示历年所有督导信息

                <div id="dtBox" class="dtpicker-overlay dtpicker-mobile" style="display: none;">
                    <div class="dtpicker-bg">
                        <div id="dtpick" class="dtpicker-cont">
                            <div class="dtpicker-content">
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
                <div id="dtBoxS" class="dtpicker-overlay dtpicker-mobile" style="display: none;">
                    <div class="dtpicker-bg">
                        <div class="dtpicker-cont">
                            <div class="dtpicker-content">
                                <div class="dtpicker-subcontent">

                                    <div class="dtpicker-header">
                                        <div class="dtpicker-title">选择学期</div>
                                        <a class="dtpicker-closeS">×</a>
                                        <!--<div class="dtpicker-value"></div>-->
                                    </div>


                                    <div class="dtpicker-components">
                                        <div class="dtpicker-compOutline dtpicker-comp3">
                                            <div class="dtpicker-comp year1_class">
                                                <a id="year1_class1S" class="dtpicker-compButton increment">+</a>
                                                <input id="year1S" type="text" class="dtpicker-compValue" maxlength="4">
                                                <a id="year1_class2S" class="dtpicker-compButton decrement">-</a>
                                            </div>
                                        </div>

                                        <div class="dtpicker-compOutline dtpicker-comp3">
                                            <div class="dtpicker-comp ">
                                                <a class="dtpicker-compButton increment "></a>
                                                <input type="text" class="dtpicker-compValue" id="year2S" disabled="disabled">
                                                <a class="dtpicker-compButton decrement "></a>
                                            </div>
                                        </div>

                                        <div class="dtpicker-compOutline dtpicker-comp3">
                                            <div class="dtpicker-comp ">
                                                <a class="dtpicker-compButton increment dtpicker-compButtonEnableS">+</a>
                                                <input type="text" class="dtpicker-compValue" id="terminalS" disabled="disabled">
                                                <a class="dtpicker-compButton decrement dtpicker-compButtonEnableS">-</a>
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

{{--修改信息时候所用的两个--}}
                <div id="dtBoxC1" class="dtpicker-overlay dtpicker-mobile" style="display: none;">
                    <div class="dtpicker-bg">
                        <div class="dtpicker-cont">
                            <div class="dtpicker-content">
                                <div class="dtpicker-subcontent">

                                    <div class="dtpicker-header">
                                        <div class="dtpicker-title">选择学期</div>
                                        <a class="dtpicker-closeC1">×</a>
                                        <!--<div class="dtpicker-value"></div>-->
                                    </div>


                                    <div class="dtpicker-components">
                                        <div class="dtpicker-compOutline dtpicker-comp3">
                                            <div class="dtpicker-comp year1_class">
                                                <a id="year1_class1C1" class="dtpicker-compButton increment">+</a>
                                                <input id="year1C1" type="text" class="dtpicker-compValue" maxlength="4">
                                                <a id="year1_class2C1" class="dtpicker-compButton decrement">-</a>
                                            </div>
                                        </div>

                                        <div class="dtpicker-compOutline dtpicker-comp3">
                                            <div class="dtpicker-comp ">
                                                <a class="dtpicker-compButton increment "></a>
                                                <input type="text" class="dtpicker-compValue" id="year2C1" disabled="disabled">
                                                <a class="dtpicker-compButton decrement "></a>
                                            </div>
                                        </div>

                                        <div class="dtpicker-compOutline dtpicker-comp3">
                                            <div class="dtpicker-comp ">
                                                <a class="dtpicker-compButton increment dtpicker-compButtonEnableC1">+</a>
                                                <input type="text" class="dtpicker-compValue" id="terminalC1" disabled="disabled">
                                                <a class="dtpicker-compButton decrement dtpicker-compButtonEnableC1">-</a>
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
                <div id="dtBoxC2" class="dtpicker-overlay dtpicker-mobile" style="display: none;">
                    <div class="dtpicker-bg">
                        <div class="dtpicker-cont">
                            <div class="dtpicker-content">
                                <div class="dtpicker-subcontent">

                                    <div class="dtpicker-header">
                                        <div class="dtpicker-title">选择学期</div>
                                        <a class="dtpicker-closeC2">×</a>
                                        <!--<div class="dtpicker-value"></div>-->
                                    </div>


                                    <div class="dtpicker-components">
                                        <div class="dtpicker-compOutline dtpicker-comp3">
                                            <div class="dtpicker-comp year1_class">
                                                <a id="year1_class1C2" class="dtpicker-compButton increment">+</a>
                                                <input id="year1C2" type="text" class="dtpicker-compValue" maxlength="4">
                                                <a id="year1_class2C2" class="dtpicker-compButton decrement">-</a>
                                            </div>
                                        </div>

                                        <div class="dtpicker-compOutline dtpicker-comp3">
                                            <div class="dtpicker-comp ">
                                                <a class="dtpicker-compButton increment "></a>
                                                <input type="text" class="dtpicker-compValue" id="year2C2" disabled="disabled">
                                                <a class="dtpicker-compButton decrement "></a>
                                            </div>
                                        </div>

                                        <div class="dtpicker-compOutline dtpicker-comp3">
                                            <div class="dtpicker-comp ">
                                                <a class="dtpicker-compButton increment dtpicker-compButtonEnableC2">+</a>
                                                <input type="text" class="dtpicker-compValue" id="terminalC2" disabled="disabled">
                                                <a class="dtpicker-compButton decrement dtpicker-compButtonEnableC2">-</a>
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


                <table id="Usertable"
                   data-classes="table table-hover table-bordered"
                   data-click-to-select="true"
                   data-show-pagination-switch="true"
                   data-query-params="queryParams"
                   data-pagination="true"
                   data-search = "true"
                   data-page-size="20"
                   data-sort-name="group"
                   data-sort-order="desc"
                   data-row-style="YearStyle"
                   data-page-list="[5, 10, 20, 50, 100, 200]">
                <thead>
                <tr>
                    <th data-field="Number" data-formatter="actionFormatterNumber" data-halign="center" data-align="center" >序号</th>
                    <th data-field="name" data-halign="center" data-align="center">督导姓名</th>
                    <th data-field="level" data-halign="center" data-align="center">督导角色</th>
                    {{--<th data-field="sex" data-halign="center" data-align="center">性别</th>--}}
                    <th data-field="state" data-halign="center" data-align="center">教师状态</th>
                    <th data-field="status" data-halign="center" data-align="center">督导状态</th>
                    <th data-field="group" data-halign="center" data-align="center">组别</th>
                    <th data-field="workstate" data-halign="center" data-align="center">督导类型</th>
                    <th data-field="unit" data-halign="center" data-align="center">所属单位</th>
                    {{--<th data-field="email" data-halign="center" data-align="center">邮箱</th>--}}
                    <th data-field="phone" data-halign="center" data-align="center">电话</th>
                    <th data-field="supervise_time" data-halign="center" data-align="center"  data-sortable="true">
                        任职学期
                    </th>
                    {{--<th data-field="skill" data-halign="center" data-align="center">专业领域</th>--}}
                    @if(session('role')=='校级')
                    <th data-field="action" data-formatter="actionFormatter" data-events="actionEvents">修改信息</th>
                    @endif
                </tr>
                </thead>
            </table>
        </div>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document" >
                <div class="modal-content" >
                    {{--<form action="/AddSupervisorInfo" method="post" >--}}
                    <form action="/ChangeSupervisorInfo" method="post" >
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">添加督导信息</h4>
                        </div>
                        <div class="modal-body" id="infoForm">
                            <div  class="form-div" style="margin-left: 50px;margin-right: 50px; height: 630px;">
                                <div style="width: 860px; margin:0 auto;">
                                    <div style="float: left;">
                                        <div class="form-group">
                                            <span class="h4">督导ID</span>
                                            <input name="user_id" type="text" class="form-control add_task" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别</span>
                                            <select class="form-control" name="sex" style="display: inline-block!important;">
                                                <option>男</option>
                                                <option>女</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <span class="h4">教师状态
                                                <select class="form-control" name="state"  style="display: inline-block!important;" required>
                                                    <option>在职</option>
                                                    <option>退休</option>
                                                </select>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span for="name">开始学期</span>
                                            <input class="form-control" name="change_begin_time" id="supervise_time1" type="text"
                                                   value="" required>
                                        </div>

                                        <div class="form-group">
                                            <span class="h4">电话号码
                                                <input type="number" name="phone" class="form-control add_task" >
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">签约状态
                                                <select class="form-control" name="status"  style="display: inline-block!important;">
                                                    <option>正在担任督导</option>
                                                    <option>不再担任督导</option>
                                                </select>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">督导类型
                                                <select class="form-control" name="workstate" style="display: inline-block!important;">
                                                    <option>专职</option>
                                                    <option>兼职</option>
                                                </select>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">用户角色</span>
                                                    <div>
                                                        <input type="checkbox" class="ace" name="xiaoji" value="1"/>
                                                        <span class="lbl">校级</span>
                                                    </div>
                                                    <div>
                                                        <input type="checkbox" class="ace" name="dazuzhang"  value="1"/>
                                                        <span class="lbl">大组长</span>
                                                    </div>
                                        </div>
                                    </div>
                                    <div style="float: right">
                                        <div class="form-group">
                                            <span class="h4">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</span>
                                            <input name="account_name" type="text" class="form-control add_task" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">所属机构
                                                <select class="form-control add_task" type="text" name="unit"
                                                        style="display: inline-block!important;">
                                                    <option>校级行政单位</option>
                                                    <option>水土保持学院</option>
                                                    <option>理学院</option>
                                                    <option>材料科学与技术学院</option>
                                                    <option>自然保护区学院</option>
                                                    <option>环境科学与工程学院</option>
                                                    <option>生物科学与技术学院</option>
                                                    <option>经济管理学院</option>
                                                    <option>工学院</option>
                                                    <option>信息学院</option>
                                                    <option>人文社会科学学院</option>
                                                    <option>外语学院</option>
                                                    <option>园林学院</option>
                                                    <option>艺术设计学院</option>
                                                    <option>林学院</option>
                                                    <option>体育教学部</option>
                                                    <option>马克思主义学院</option>
                                                    <option>其他</option>
                                                </select>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱
                                                <input type="email" class="form-control" name="email" placeholder="Email" data-error="Bruh, that email address is invalid" >
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">结束学期
                                                <input name="change_end_time"
                                                       class="select-year" type="text" data-field="date"
                                                       value="{{$currentEndTerm}}"
                                                       readonly="readonly">
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">组&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别
                                                <select class="form-control" name="group" style="display: inline-block!important;">
                                                    <option>第一组</option>
                                                    <option>第二组</option>
                                                    <option>第三组</option>
                                                    <option>第四组</option>
                                                </select>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称
                                                <select class="form-control" name="ProRank" style="display: inline-block!important;">
                                                    <option>教授</option>
                                                    <option>副教授</option>
                                                    <option>讲师</option>
                                                </select>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">专业领域
                                                <input name="skill" type="text" class="form-control add_task">
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="h4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                <span class="h4">
                                                    <div>
                                                        <input type="checkbox" class="ace" name="xiaozuzhang"  value="1"/>
                                                        <span class="lbl"> 小组长</span>
                                                    </div>
                                                    <div>
                                                        <input type="checkbox" class="ace" name="dudao" value="1"/>
                                                        <span class="lbl"> 督导</span>
                                                    </div>

                                                </span>
                                        </div>
                                    </div>

                                </div>


                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-raised"
                                    data-dismiss="modal">关闭
                            </button>
                            <button  class="btn btn-info btn-raised">添加</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>


{{--修改督导信息莫泰框--}}
        <button id="detail" class="btn btn-primary btn-lg" data-toggle="modal" style="display: none"
                data-target="#superinfoModal">
            开始演示模态框
        </button>
        <!-- 模态框（Modal） -->
        <div class="modal fade" id="superinfoModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            督导信息
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div  class="form-div" style="margin-bottom:50px; margin-left: 50px;margin-right: 50px;background-color: #fff">
                            <div class="panel-body ">
                                <form action="/ChangeSupervisorInfo" method="post" id="infoForm">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <div style="width: 860px; margin:0 auto;">
                                        <div class="form-group">
                                            <img src="assets/images/user.jpg" style="margin-bottom:30px;">
                                        </div>

                                        <div style="float: left;">
                                            <div class="form-group" style="display: none;">
                                                <input class="form-control" type="text" name="tid" id="tid" value="">
                                            </div>
                                            <div class="form-group">
                                                <span for="name">督导ID</span>
                                                <input class="form-control" name="user_id" type="text" id="user_id"
                                                       value="" readonly="readonly" required>
                                            </div>
                                            <div class="form-group">
                                                <span class="h4">性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别</span>
                                                <select class="form-control" name="sex" id="sex" style="display: inline-block!important;">
                                                    <option>男</option>
                                                    <option>女</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <span class="h4">教师状态
                                                <select class="form-control" name="state" id="state" style="display: inline-block!important;">
                                                    <option>在职</option>
                                                    <option>退休</option>
                                                </select>
                                            </span>
                                            </div>
                                            <div class="form-group">
                                                <span for="phone">电话号码</span>
                                                <input class="form-control" name="phone" type="number" id="phone" value="">
                                            </div>
                                            <div class="form-group ">
                                                <span for="email">签约状态</span>
                                                <select class="form-control" name="status" id="status">
                                                    <option>正在担任督导</option>
                                                    <option>不再担任督导</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <span for="name">开始学期</span>
                                                <input class="form-control" name="change_begin_time" type="text"
                                                       id="change_begin_time" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <span class="h4" >督导类型
                                                    <select class="form-control" name="workstate" id="workstate" style="display: inline-block!important;">
                                                        <option>专职</option>
                                                        <option>兼职</option>
                                                    </select>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <span class="h4" style="margin-left: -320px;">用户角色
                                                <div>
                                                    <input type="checkbox" class="ace" name="xiaoji" value="1"/>
                                                    <span class="lbl"> 校级</span>
                                                </div>
                                                <div>
                                                    <input type="checkbox" class="ace" name="dazuzhang"  value="1"/>
                                                    <span class="lbl"> 大组长</span>
                                                </div>
                                                </span>
                                            </div>



                                        </div>
                                        <div style="float: right">
                                            <div class="form-group">
                                                <span for="name">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</span>
                                                <input class="form-control" name="account_name" type="text" id="account_name" value="" required >
                                            </div>

                                            <div class="form-group">
                                                <span class="h4">所属机构
                                                <select class="form-control add_task" type="text" name="unit" id="unit" style="display: inline-block!important;">
                                                    <option>校级行政部门</option>
                                                    <option>水保学院</option>
                                                    <option>理学院</option>
                                                    <option>材料学院</option>
                                                    <option>保护区学院</option>
                                                    <option>环境学院</option>
                                                    <option>生物学院</option>
                                                    <option>经管学院</option>
                                                    <option>工学院</option>
                                                    <option>信息学院</option>
                                                    <option>人文学院</option>
                                                    <option>外语学院</option>
                                                    <option>园林学院</option>
                                                    <option>艺设学院</option>
                                                    <option>林学院</option>
                                                    <option>体育教学部</option>
                                                    <option>马克思主义学院</option>
                                                    <option>其他</option>
                                                </select>
                                                </span>
                                            </div>
                                            <div class="form-group ">
                                                <span for="email">邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 箱</span>
                                                <input class="form-control input-small" name="email" type="email" id="email" value="">
                                            </div>
                                            <div class="form-group">
                                                <span class="h4">组&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别
                                                    {{--<input class="form-control input-small" name="group"  id="group" value="">--}}

                                                    <select class="form-control" name="group" id="group" style="display: inline-block!important;">
                                                        <option>第一组</option>
                                                        <option>第二组</option>
                                                        <option>第三组</option>
                                                        <option>第四组</option>
                                                    </select>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <span class="h4">职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称
                                                    <select class="form-control" name="ProRank" id="ProRank" style="display: inline-block!important;">
                                                        <option>教授</option>
                                                        <option>副教授</option>
                                                        <option>讲师</option>
                                                    </select>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <span for="name">结束学期</span>
                                                <input class="form-control" name="change_end_time" type="text"
                                                       id="change_end_time" value="{{$currentEndTerm}}" required>
                                            </div>
                                            <div class="form-group">
                                            <span class="h4">专业领域
                                                <input name="skill" id="skill" type="text" class="form-control add_task">
                                            </span>
                                            </div>
                                            <div class="form-group">
                                                <span class="h4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                <div>
                                                <input type="checkbox" class="ace" name="xiaozuzhang" id="xiaozuzhang" value="1"/>
                                                <span class="lbl"> 小组长</span>
                                                </div>
                                                <div>
                                                <input type="checkbox" class="ace" name="dudao" id="dudao" value="1"/>
                                                <span class="lbl"> 督导</span>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <button class="btn btn-info btn-raised" style="float: right; width:100px;margin-left:10px;" >保存信息</button>
                                    <a class="btn btn-warning btn-raised" id="reset" style="float:right;width:100px;">重置密码</a>
                                    <a class="btn btn-danger btn-raised" id="DeleteSupInfo" style="float:right;margin-right: 10px;" >删除对应学期信息</a>
                                </form>


                            </div>
                        </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </div>
        <!-- .breadcrumb -->

    </div>


</div>
@include('layout.footer')
</body>
<script src="assets/js/jquery-2.0.3.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/bootstrap-table.js"></script>
<script src="assets/js/bootstrap-table-zh-CN.js"></script>
<script src="calendar1/js/bootstrap-datetimepicker.js"></script>
{{--<script type="text/javascript" src="dateRangeCalendar/js/moment.js"></script>--}}
{{--<script type="text/javascript" src="dateRangeCalendar/js/daterangepicker.js"></script>--}}
<script src="{{asset('js/SuperviseInfo.js')}}"></script>

<script>
    var url=location.href;
    url = url.replace(/</g,"&lt;").replace(/>/g,"&gt");
    var tmp1=url.split("?")[1];
    if (tmp1!=null)
    {
        var title=tmp1.split("=")[1];
        alert(decodeURI(title));
        window.location.href = '/SupervisorInfo';
    }

    {{--if("{{ $title }}" != '')--}}
    {{--{--}}
        {{--alert('{{ $title }}');--}}
    {{--}--}}

</script>
</html>