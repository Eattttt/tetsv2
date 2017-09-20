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

     }
    @media screen and (max-width: 1830px){
        #newmain{
            padding-left: 74px;
        }
    }
    @media screen and (max-width: 768px){
        #newmain{
            overflow: auto;float: left;
            margin-left:39%;width: 1660px;
        }
    }
    @media screen and (max-width: 415px) {
        #newmain{
            overflow: auto;float: left;
            margin-left:69%;width: 1660px;
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
        <div class="breadcrumbs" id="breadcrumbs">
            <ul class="breadcrumb" style="padding-top: 22px; padding-bottom: 12px;">

            </ul>
        </div>

        <input id="getlevel" value="{{session('role')}}" style="display: none"/>
        <input id="getgroup" value="{{Auth::User()->group}}" style="display: none"/>
        <input id="getunit" value="{{Auth::User()->unit}}" style="display: none"/>

        <div class="page-content col-md-12">

            <table id="UnitUsertable"
                   data-classes="table table-hover table-bordered"
                   data-click-to-select="true"
                   data-show-pagination-switch="true"
                   data-query-params="queryParams"
                   data-pagination="true"
                   data-search = "true"
                   data-page-size="20"
                   data-sort-order="desc"
                   data-row-style="YearStyle"
                   data-page-list="[5, 10, 20, 50, 100, 200]">
                <thead>
                <tr>
                    <th data-field="Number" data-formatter="actionFormatterNumber" data-halign="center" data-align="center" >序号</th>
                    <th data-field="alias" data-halign="center" data-align="center">用户角色</th>
                    <th data-field="unit" data-halign="center" data-align="center">所属单位</th>
                    <th data-field="email" data-halign="center" data-align="center">邮箱</th>
                    <th data-field="phone" data-halign="center" data-align="center">电话</th>

                    @if(session('role')=='校级')
                        <th data-field="action" data-halign="center" data-align="center" data-formatter="actionFormatter" data-events="actionEvents">修改信息</th>
                    @endif
                </tr>
                </thead>
            </table>
        </div>

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
                            用户信息
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div  class="form-div" style="margin-bottom:50px; margin-left: 50px;margin-right: 50px;background-color: #fff">
                            <div class="panel-body ">
                                <form action="/ChangeUnitUserInfo" method="post" id="infoForm">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <div style="width: 860px; margin:0 auto;">
                                        <div class="form-group">
                                            <img src="assets/images/user.jpg" style="margin-bottom:30px;">
                                        </div>

                                        <div style="float: left;">

                                            <div class="form-group">
                                                <span for="name">用户&nbsp;&nbsp;ID</span>
                                                <input class="form-control" name="user_id" type="text" id="user_id" value="" readonly="readonly" required>
                                            </div>

                                            <div class="form-group">
                                                <span for="phone">电话号码</span>
                                                <input class="form-control" name="phone" type="number" id="phone" value="" >
                                            </div>

                                        </div>
                                        <div style="float: right">
                                            <div class="form-group">
                                                <span for="name">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</span>
                                                <input class="form-control" name="account_name" type="text" id="account_name" value="" disabled="disabled" >
                                            </div>

                                            <div class="form-group ">
                                                <span for="email">邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 箱</span>
                                                <input class="form-control input-small" name="email" type="email" id="email" value="">
                                            </div>

                                        </div>


                                    </div>
                                    <button class="btn btn-info btn-raised" style="float: right; width:100px;margin-left:10px;" >保存信息</button>
                                    <a class="btn btn-warning btn-raised" id="reset" style="float:right;width:100px;">重置密码</a>
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
</body>
<script src="assets/js/jquery-2.0.3.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/bootstrap-table.js"></script>
<script src="assets/js/bootstrap-table-zh-CN.js"></script>

<script src="{{asset('js/UnitUserManage.js')}}"></script>

<script>

    if("{{ $title }}" != '')
    {
        alert('{{ $title }}');
    }

</script>
</html>