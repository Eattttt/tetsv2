<!DOCTYPE html>
<meta name="render" content="webkit|ie-comp|ie-stand" xmlns="http://www.w3.org/1999/html">
<meta http-equiv="X-UA-Compatible" content="IE=11">
<meta name="_token" content="{{ csrf_token() }}"/>
<html lang="en">
<style type="text/css">
    *{font-family:"Microsoft YaHei"}
    .nav_a{
        cursor: pointer;
    }
    .panel-add{
        color: #fff;
        background-color: #97d3c5 !important;
        border-color: #97d3c5 !important;
    }
    .panel-red{
        color: #fff;
        background-color: #f68484 !important;
        border-color: #f68484 !important;
    }
    .select{
        height: 34px;
        vertical-align: middle;
        border:1px solid #ccc;
        border-radius: 4px;
    }
    #search-suggest{
        position: absolute;
        display: none;
        /*z-index: 100;*/
    }
    .suggest{
        width:165px;
        background-color: white;
        /*border: 1px solid #999;*/
        border-radius:4px;
        left:805px;
        top: 142px;
    }
    .suggestClass{
        width:208px;
        background-color: white;
        /*border: 1px solid #999;*/
        border-radius:4px;
        z-index: 99;
        margin-left: -256px;
        margin-top: -36px;
    }
    .suggest ul,.suggestClass ul
    {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .suggest ul li,.suggestClass ul li{
        padding:3px;
        font-size: 14px;
        line-height: 25px;
        cursor: pointer;
    }
    .suggest ul li:hover,.suggestClass ul li:hover{
        text-decoration: underline;
        background-color: grey;
    }
    .widget-header {
        text-align: center;
        padding-left: 0;
        min-height: 38px;
    }
    .header-color-green {
        background: #75B9E6;
        color: white!important;
    }
    .widget-box-green{
        margin-top:20px;

    }
    .widget-box-red{
        margin-top:20px;
    }
    .header-color-red {
        background: #97D3C5;
        color: white!important;
    }
    .header-color-orange {
        background:#ECA347;
        border-color: #e8b10d;
        color: white!important;
    }
    .widget-body-orange{
        background-color: #FEFDE1;
    }
    .widget-header h5{
        line-height: 36px;
        font-size: 20px;
        margin-top: 0px!important;
        margin-bottom: 0px!important;
        font-weight: bold;
    }
    .widget-body{
        padding: 30px 20px 0 20px;
        height: 393px;
        overflow-y:auto;
    }
    .widget-group-body{
        padding: 30px 20px 0 20px;
        height: 593px;
        overflow-y:auto;
    }
    .widget-body li{
        margin-bottom: 6px;
    }
    .green{
        color: #87B87F;
    }
    .widget-body-red{
        background-color: rgba(167, 208, 198, 0.15);
    }
    .widget-body-blue{
        background-color: rgba(148, 193, 222, 0.14);
    }
    .div-inline{ display:inline}

    #newmain{
        overflow: auto;margin-left:30px;
    }
    @media screen and (max-width: 768px){
        #newmain{
            overflow: auto;float: left;
            margin-left:40%;width: 1660px;
        }
        #dtpick{
            left: 0%;
            top: 38%;
        }

    }
    @media screen and (max-width: 415px) {
        #newmain{
            overflow: auto;float: left;
            margin-left:70%;width: 1660px;
        }
        #minisize{
            float: none;
        }
        #dtpick{
            top: 20%;
            left: 10%;
        }
    }
</style>
<head>
    <meta charset="utf-8" />
    <title>北京林业大学督导评价管理系统</title>
    <meta name="keywords" content="北京林业大学督导评价管理系统"/>
    <meta name="description" content="北京林业大学督导评价管理系统"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- basic styles -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/css/dashboard.css" />
    <link rel="stylesheet" href="assets/css/common.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
    <script src="assets/js/jquery-2.0.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    {{--日历相关--}}
    <link rel="stylesheet" type="text/css" href="calendar/DateTimePicker.css" />
    <script type="text/javascript" src="calendar/jquery-1.11.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="calendar/DateTimePicker-ltie9.css" />
    <script type="text/javascript" src="calendar/DateTimePicker-ltie9.js"></script>

</head>

<body>
@include('layout.header')
@include('layout.sidebar')

<script src="assets/js/echarts.js"></script>

<div id="newmain" class="container-fluid clearfix">
    <div class="row clearfix">

        <input id="getlevel" value="{{session('role')}}" style="display: none"/>
        <input id="getunit" value="{{Auth::User()->unit}}" style="display: none"/>
        <input id="getgroup" value="{{Auth::User()->group}}" style="display: none"/>

        <div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 ">
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

                        <li style="color:gray">评价结果</li>
                    </ul>
                </div>
                @endif
            <!-- .breadcrumb -->
            <div class="page-content form-content">
                <div class="Statistics-Table">
                    {{--选择条件--}}
                    <p style="display: inline-block;margin-left: 10px;">学期学年</p>
                    <input id="calender" class="select" type="text" data-field="date" readonly>

                    {{--<select class="select" name="inputDataAttr" id="inputDataAttr"--}}
                    {{--style="display: inline-block!important; color: lightgrey"--}}
                    {{--onchange="inputLessonAttrAddCss()">--}}
                    {{--<option value="1" selected="selected" style="color: black">理论课总体评价</option>--}}
                    {{--<option value="2" style="color: black">评价项目（大项）得分情况及学生课堂表现</option>--}}
                    {{--<option value="3" style="color: black">评价项目（细项）得分情况---按平均分排</option>--}}
                    {{--<option value="4" style="color: black">评价项目（细项）得分情况---按评价数量排</option>--}}
                    {{--<option value="5" style="color: black">督导评教数据与学生评教数据对照</option>--}}
                    {{--</select>--}}

                    <p style="display: inline-block;margin-left: 10px;">非常满意</p>
                    <input id="weighted_value1" class="select" type="text" style="width:50px " value="1">
                    <p style="display: inline-block;margin-left: 10px;">满意</p>
                    <input id="weighted_value2" class="select" type="text" style="width:50px " value="0.875">
                    <p style="display: inline-block;margin-left: 10px;">正常</p>
                    <input id="weighted_value3" class="select" type="text" style="width:50px " value="0.75">
                    <p style="display: inline-block;margin-left: 10px;">存在不足</p>
                    <input id="weighted_value4" class="select" type="text" style="width:50px " value="0.625">
                    <p style="display: inline-block;margin-left: 10px;">存在明显不足</p>
                    <input id="weighted_value5" class="select" type="text" style="width:50px " value="0.5">
                    <button id="search" class="btn btn-back-message-list btn-raised">
                        <i class="glyphicon glyphicon-search"></i> 确定
                    </button>

                </div>

                <div id="dtBox" class="dtpicker-overlay dtpicker-mobile" style="display: none;">
                    <div class="dtpicker-bg">
                        <div id="dtpick" class="dtpicker-cont">
                            <div class="dtpicker-content" style="margin-left: 15%;">
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


                <div id="FirstPage">
                    <div id="minisize" class="col-xs-6 col-sm-6">
                        <div class="widget-box-green">
                            <div class="widget-header header-color-green">
                                @if(session('role')=='校级'||session('role')=='大组长')
                                    <h5 class="bigger lighter">体育课授课总体评价</h5>
                                @elseif(session('role')=='院级')
                                    <input  id="name" type="text" value="{{Auth::User()->unit}}" style="display: none">
                                    <h5 class="bigger lighter">{{Auth::User()->unit}}课授课总体评价</h5>
                                @elseif(session('role')=='小组长')
                                    <h5 class="bigger lighter">{{Auth::User()->group}}体育课授课总体评价</h5>
                                @endif
                            </div>
                            <div class="widget-body widget-body-blue">
                                <div id="TChartOne" style="width: 720px;height:350px;"></div>
                            </div>
                        </div>
                    </div>
                    <div id="minisize" class="col-xs-6 col-sm-6">
                        <div class="widget-box-red">
                            <div class="widget-header header-color-red">
                                @if(session('role')=='校级'||session('role')=='大组长')
                                    <h5 class="bigger lighter">体育课听课总体评价</h5>
                                @elseif(session('role')=='院级')
                                    <h5 class="bigger lighter">{{Auth::User()->unit}}体育课听课总体评价</h5>
                                @elseif(session('role')=='小组长')
                                    <h5 class="bigger lighter">{{Auth::User()->group}}体育课听课总体评价</h5>
                                @endif
                            </div>

                            <div class="widget-body widget-body-red">
                                <div id="TChartTwo" style="width: 720px;height:350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="SecondPage">
                    <div id="minisize" class="col-xs-6 col-sm-6">
                        <div class="widget-box-green">
                            <div class="widget-header header-color-green">
                                @if(session('role')=='校级'||session('role')=='大组长')
                                    <h5 class="bigger lighter">评价项目（大项）得分情况</h5>
                                @elseif(session('role')=='院级')
                                    <h5 class="bigger lighter">{{Auth::User()->unit}}评价项目（大项）得分情况</h5>
                                @elseif(session('role')=='小组长')
                                    <h5 class="bigger lighter">{{Auth::User()->group}}评价项目（大项）得分情况</h5>
                                @endif
                            </div>
                            <div class="widget-body widget-body-blue">

                                <div id="TChartThree" style="width: 720px;height:350px;"></div>


                            </div>
                        </div>
                    </div>


                    {{--<div id="minisize" class="col-xs-6 col-sm-6">--}}
                        {{--<div class="widget-box-red">--}}
                            {{--<div class="widget-header header-color-red">--}}
                                {{--<h5 class="bigger lighter">学生课堂表现（按评价个数排）</h5>--}}
                            {{--</div>--}}

                            {{--<div class="widget-body widget-body-red">--}}

                                {{--<div id="TChartFour" style="width: 720px;height:350px;"></div>--}}
                                {{--<script type="text/javascript">--}}
                                    {{--// 基于准备好的dom，初始化echarts实例--}}
                                    {{--var myChart = echarts.init(document.getElementById('TChartFour'));--}}
                                    {{--// 指定图表的配置项和数据--}}
                                    {{--var option = {--}}

                                        {{--tooltip : {},--}}
                                        {{--legend: {--}}
                                            {{--data: ["非常满意", "满意", "正常", "存在不足", "存在明显不足"]--}}
                                        {{--},--}}

                                        {{--xAxis : [--}}
                                            {{--{--}}
                                                {{--type : 'category',--}}
                                                {{--data : ['遵守课堂纪律，听课认真','能积极思，参与互动环节并回答问题']--}}
                                            {{--}--}}
                                        {{--],--}}
                                        {{--yAxis : [--}}
                                            {{--{--}}
                                                {{--type : 'value'--}}
                                            {{--}--}}
                                        {{--],--}}
                                        {{--series : [--}}
                                            {{--{--}}
                                                {{--name:'非常满意',--}}
                                                {{--type:'bar',--}}
                                                {{--label: {--}}
                                                    {{--normal: {--}}
                                                        {{--show: true,--}}
                                                        {{--position: 'top'--}}
                                                    {{--}--}}
                                                {{--},--}}
                                                {{--data:[12, 24]--}}

                                            {{--},--}}
                                            {{--{--}}
                                                {{--name:'满意',--}}
                                                {{--type:'bar',--}}
                                                {{--label: {--}}
                                                    {{--normal: {--}}
                                                        {{--show: true,--}}
                                                        {{--position: 'top'--}}
                                                    {{--}--}}
                                                {{--},--}}
                                                {{--data:[125, 92]--}}

                                            {{--},--}}
                                            {{--{--}}
                                                {{--name:'正常',--}}
                                                {{--type:'bar',--}}
                                                {{--label: {--}}
                                                    {{--normal: {--}}
                                                        {{--show: true,--}}
                                                        {{--position: 'top'--}}
                                                    {{--}--}}
                                                {{--},--}}
                                                {{--data:[73, 99]--}}

                                            {{--},{--}}
                                                {{--name:'存在不足',--}}
                                                {{--type:'bar',--}}
                                                {{--label: {--}}
                                                    {{--normal: {--}}
                                                        {{--show: true,--}}
                                                        {{--position: 'top'--}}
                                                    {{--}--}}
                                                {{--},--}}
                                                {{--data:[21, 28]--}}

                                            {{--},{--}}
                                                {{--name:'存在明显不足',--}}
                                                {{--type:'bar',--}}
                                                {{--label: {--}}
                                                    {{--normal: {--}}
                                                        {{--show: true,--}}
                                                        {{--position: 'top'--}}
                                                    {{--}--}}
                                                {{--},--}}
                                                {{--data:[11, 14]--}}

                                            {{--},--}}
                                        {{--]--}}
                                    {{--};--}}

                                    {{--myChart.setOption(option);--}}
                                {{--</script>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>

                <div id="ThirdPage">
                    <div id="minisize" class="col-xs-6 col-sm-12">
                        <div class="widget-box-green">
                            <div class="widget-header header-color-green">
                                @if(session('role')=='校级'||session('role')=='大组长')
                                    <h5 class="bigger lighter">评价项目（细项）得分情况---按平均分排</h5>
                                @elseif(session('role')=='院级')
                                    <h5 class="bigger lighter">{{Auth::User()->unit}}评价项目（细项）得分情况---按平均分排</h5>
                                @elseif(session('role')=='小组长')
                                    <h5 class="bigger lighter">{{Auth::User()->group}}评价项目（细项）得分情况---按平均分排</h5>
                                @endif
                            </div>
                            <div class="widget-body widget-body-blue">
                                <div id="TChartFive" style="width: 1480px;height:350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="ForthPage">
                    <div id="minisize" class="col-xs-6 col-sm-12" >
                        <div style="height:1px; margin-top:-1px;clear: both;overflow:hidden;"></div>
                        <div class="widget-box-green">
                            <div class="widget-header header-color-green">
                                @if(session('role')=='校级'||session('role')=='大组长')
                                    <h5 class="bigger lighter">评价项目（细项）得分情况---按评价数量排</h5>
                                @elseif(session('role')=='院级')
                                    <h5 class="bigger lighter">{{Auth::User()->unit}}评价项目（细项）得分情况---按评价数量排</h5>
                                @elseif(session('role')=='小组长')
                                    <h5 class="bigger lighter">{{Auth::User()->group}}评价项目（细项）得分情况---按评价数量排</h5>
                                @endif
                            </div>
                            <div class="widget-body widget-body-blue">
                                <div id="TChartSix" style="width: 1480px;height:350px;"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('layout.footer')
<script>

</script>
</body>

<script src="assets/js/bootstrap-table.js"></script>
<script src="assets/js/bootstrap-table-zh-CN.js"></script>
<script src="js/PhysicalStatistic.js"></script>

</html>
