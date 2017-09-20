/**
 * Created by computer on 2016/7/12.
 */

var MyDate = new Date();
var CurrentYear = MyDate.getFullYear();

var SearchValue=document.getElementById('name');


var id=document.getElementById('id');

function actionFormatterNumber(value, row, index) {
    return index+1;
}

function actionFormatter(value, row, index) {
    return [
        '<a class="like seeDetail" href="javascript:void(0)" title="Like">',
        '修改信息</i>',
        '</a>'
    ].join('');
}
window.actionEvents = {
    //将督导信息从数据库中读出来，进行展示
    'click .like': function (e, value, row, index) {
        $("#detail").click();
//            alert('You click like icon, row: ' + JSON.stringify(row));
        $.ajax({
            type: "get",
            async: false,
            url: "/GetSupervisorInfo",
            data: {data:row.user_id},//搜索框的值
            success: function (result) {
                //console.log(result);
                $('#tid').val(result[0]['id']);
                $('#user_id').val(result[0]['user_id']);
                $('#account_name').val(result[0]['name']);
                $('#sex').val(result[0]['sex']);
                $('#level_change').val(result[0]['level']);
                $('#state').val(result[0]['state']);
                $('#supervise_time').val(result[0]['supervise_time']);
                $('#phone').val(result[0]['phone']);
                $('#workstate').val(result[0]['workstate']);
                $('#group').val(result[0]['group']);
                $('#ProRank').val(result[0]['prorank']);
                $('#skill').val(result[0]['skill']);
                $('#unit').val(result[0]['unit']);
                $('#email').val(result[0]['email']);
                $('#status').val(result[0]['status']);

                //状态重置
                $("#xiaoji").prop("checked",false);
                $("#dazuzhang").prop("checked",false);
                $("#xiaozuzhang").prop("checked",false);
                $("#dudao").prop("checked",false);

                for (var i=0;i<result.length;i++)
                {
                    if(result[i]['level'] == '校级'){
                        $("#xiaoji").prop("checked",true);
                    }
                    if(result[i]['level'] == '大组长'){
                        $("#dazuzhang").prop("checked",true);
                    }

                    if(result[i]['level'] == '小组长'){
                        $("#xiaozuzhang").prop("checked",true);
                    }

                    if(result[i]['level'] == '督导'){
                        $("#dudao").prop("checked",true);
                    }

                }


            }
        });
    }
};

var level = $('#getlevel').val();
var unit = $('#getunit').val();
var group = $('#getgroup').val();
$(document).ready(function() {

    Calendat_function();

    Supervise_Time_Calendat_function();


    $('.user-menu').addClass('active');

    //默认显示督导列表
    var data=null;

    if (level != '校级' && level != '大组长')
    {
        if (level == '院级')
        {
            $.ajax({
                type: "get",
                async: false,
                url: "/GetUnitSupervisorInfo",
                data:{
                    unit:unit,
                    TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]
                },
                success: function (result) {
                    console.log(result);

                    $("#Usertable").bootstrapTable({
                        data:result
                    });

                    $('.fixed-table-loading').hide();
                }
            });

        }
        if(level == '小组长')
        {
            $.ajax({
                type: "get",
                async: false,
                url: "/GetGroupSupervisorInfo",
                data:{group:group,TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                success: function (result) {
                    data = JSON.parse(result);
                }
            });
            $("#Usertable").bootstrapTable({
                data:data
            });

            $('.fixed-table-loading').hide();
        }
    }
    else{
        if(level == '校级')
        {
            //修改担任督导聘期
            Change_Time_Calendat_function1();
            Change_Time_Calendat_function2();
            $.ajax({
                type: "get",
                async: false,
                url: "/GetAllSupervisorInfo",
                data:{TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                success: function (result) {
                    data = JSON.parse(result);
                }
            });
            $("#Usertable").bootstrapTable({
                data:data
            });
        }
        if(level == '大组长')
        {
            $.ajax({
                type: "get",
                async: false,
                url: "/GetBigGroupSupervisorInfo",
                data:{TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                success: function (result) {
                    data = JSON.parse(result);
                }
            });
            $("#Usertable").bootstrapTable({
                data:data
            });
        }
    }


    $('#check1').click(function (){
        var checked = $('#check1').prop('checked');
        $("#Usertable").bootstrapTable('removeAll');//清除表格数据
        if (checked) {//显示全部
            var data=null;
            if (level != '校级' && level != '大组长')
            {
                if (level == '院级')
                {
                    $.ajax({
                        type: "get",
                        async: false,
                        url: "/GetUnitSupervisorInfo",
                        data:{unit:unit,TimeFlag:null},
                        success: function (result) {
                            data = JSON.parse(result);
                            $("#Usertable").bootstrapTable('load',data);
                        }
                    });

                    $('.fixed-table-loading').hide();
                }
                if(level == '小组长')
                {
                    $.ajax({
                        type: "get",
                        async: false,
                        url: "/GetGroupSupervisorInfo",
                        data:{group:group,TimeFlag:null},
                        success: function (result) {
                            data = JSON.parse(result);
                            $("#Usertable").bootstrapTable('load',data);

                        }
                    });


                    $('.fixed-table-loading').hide();
                }
            }
            else{
                $.ajax({
                    type: "get",
                    async: false,
                    url: "/GetAllSupervisorInfo",
                    data:{TimeFlag:null},
                    success: function (result) {
                        data = JSON.parse(result);
                        $("#Usertable").bootstrapTable('load',data);
                    }
                });
            }
        }

        //显示本学期
        else {

            var data=null;

            if (level != '校级' && level != '大组长')
            {
                if (level == '院级')
                {
                    $.ajax({
                        type: "get",
                        async: false,
                        url: "/GetUnitSupervisorInfo",
                        data:{unit:unit,TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                        success: function (result) {
                            //console.log(result);
                            data = JSON.parse(result);
                            $("#Usertable").bootstrapTable('load',data);
                            $('.fixed-table-loading').hide();
                        }
                    });

                }
                if(level == '小组长')
                {
                    $.ajax({
                        type: "get",
                        async: false,
                        url: "/GetGroupSupervisorInfo",
                        data:{group:group,TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                        success: function (result) {
                            //console.log(result);
                            data = JSON.parse(result);
                            $("#Usertable").bootstrapTable('load',data);
                            $('.fixed-table-loading').hide();
                        }
                    });
                }
            }
            else{
                $.ajax({
                    type: "get",
                    async: false,
                    url: "/GetAllSupervisorInfo",
                    data:{TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                    success: function (result) {
                        data = JSON.parse(result);
                        //console.log(data);
                        $("#Usertable").bootstrapTable('load',data);
                    }
                });

            }
        }
    });


    $('.fixed-table-loading').hide();

    $("#reset").click(function (){
        $.ajax({
            type: "get",
            async: false,
            url: "/ResetPass",
            data:{
                data: $('#user_id').val(),
                TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]
            },
            success: function (result) {
                if(result!=0)
                    alert('重置密码成功！');
                else{
                    alert("重置密码失败！");
                }
            }
        });
    });

    $('#DeleteSupInfo').click(function(){
        var div1=$("#change_begin_time").val().split("-");
        var div2=$("#change_end_time").val().split("-");
        $.ajax({
            type: "get",
            async: false,
            url: "/DeleteSupInfo",
            data:{
                id: $('#user_id').val(),
                time_start_year1 :div1[0],
                //time_start_year2 :$("#year2C1").val(),
                time_start_terminal :div1[2],
                time_end_year1 :div2[0],
                //time_end_year2 :$("#year2C2").val(),
                time_end_terminal :div2[2]
            },
            success: function(result){
                if(result!=0)
                    alert('删除成功！');
                else{
                    alert("删除失败！");
                }
            }
        });
    });

    $('#level1').blur(function (){
        if($('#level1 option:selected').text()=='院级' || $('#level1 option:selected').text()=='校级' || $('#level1 option:selected').text()=='大组长')
        {
            $('#group1').val('');
            $('#group1').attr('disabled','disabled');
        }

        else {
            $('#group1').val('第一组');
            $('#group1').removeAttr('disabled');

        }
    });
    $('#level_change').blur(function (){
        if($('#level_change option:selected').text()=='院级'  || $('#level_change option:selected').text()=='校级' || $('#level_change option:selected').text()=='大组长')
        {
            console.log( $('#group').val());
            $('#group').val('');
            $('#group').attr('disabled','disabled');
        }

        else {
            $('#group').val('第一组');
            $('#group').removeAttr('disabled');

        }
    });
});


function Calendat_function()
{
    Get_CurrentYear(CurrentYear);
    $("#calender").val( $("#year1").val()+"学年 ～ "+$("#year2").val()+"学年："+$("#terminal").val());
    $("#calender").focus(function (){
        $("#dtBox").show();
        Get_CurrentYear(CurrentYear);
    });
//        X 按钮
    $(".dtpicker-close").click(function(){
        $("#dtBox").hide();
    });
//        取消按钮
    $(".dtpicker-buttonClear").click(function(){
        $("#dtBox").hide();
    });
//        确定按钮
    $(".dtpicker-buttonSet").click(function(){
        $("#dtBox").hide();
        $("#calender").val( $("#year1").val()+"学年 ～ "+$("#year2").val()+"学年："+$("#terminal").val());
        $("#Usertable").bootstrapTable('removeAll');//清除表格数据
        var data=null;

        if (level != '校级' && level != '大组长')
        {
            if (level == '院级')
            {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "/GetUnitSupervisorInfo",
                    data:{unit:unit,TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                    success: function (result) {
                        // console.log(result);
                        data = JSON.parse(result);
                        $("#Usertable").bootstrapTable('load',data);
                        $('.fixed-table-loading').hide();
                    }
                });



            }
            if(level == '小组长')
            {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "/GetGroupSupervisorInfo",
                    data:{group:group,TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                    success: function (result) {
                        //console.log(result);
                        data = JSON.parse(result);
                        $("#Usertable").bootstrapTable('load',data);
                        $('.fixed-table-loading').hide();
                    }
                });

            }
        }
        else{
            if(level == '校级')
            {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "/GetAllSupervisorInfo",
                    data:{TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                    success: function (result) {
                        data = JSON.parse(result);
                        $("#Usertable").bootstrapTable('load',data);
                    }
                });
            }
            if(level == '大组长')
            {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "/GetBigGroupSupervisorInfo",
                    data:{TimeFlag:$("#year1").val()+"-"+$("#year2").val()+"-"+$("#terminal").val().match(/\d/g)[0]},
                    success: function (result) {
                        data = JSON.parse(result);
                        $("#Usertable").bootstrapTable('load',data);
                    }
                });
            }
        }
    });

    //year1 + 按钮
    $("#year1_class1").click(function(){
        CurrentYear++;
        $("#year1").val(CurrentYear);
        $("#year2").val(parseInt(CurrentYear)+1);
    });
    //year1 - 按钮
    $("#year1_class2").click(function(){
        CurrentYear--;
        $("#year1").val(CurrentYear);
        $("#year2").val(parseInt(CurrentYear)+1);
    });
    //学期选择的 +、- 按钮
    $(".dtpicker-compButtonEnable").click(function(){
        if ( $("#terminal").val() == "第1学期")
            $("#terminal").val("第2学期");
        else
            $("#terminal").val("第1学期");
    })
    $("#year1").bind('keyup',function(){
        CurrentYear=$("#year1").val();
        if( CurrentYear.length==4)
        {
            $("#year1").blur(function(){
                console.log(CurrentYear.match(/^\d{4}$/));
                if ( parseInt(CurrentYear)>2050 ||  parseInt(CurrentYear)<=1970 || CurrentYear.match(/^\d{4}$/) == null)
                {
                    alert("请输入正确的4位数字！有效范围为1970～2049");
                    CurrentYear = MyDate.getFullYear();
                }
                $("#year1").val(CurrentYear);
                $("#year2").val(parseInt(CurrentYear)+1);

            });
        }
    });
    function Get_CurrentYear(year)
    {

        if ( (MyDate.getMonth()+1) <8 && (MyDate.getMonth()+1) >= 3)
        {
            $("#terminal").val("第2学期");
            terminal = 2;
            $("#year1").val(year-1);
            $("#year2").val(year);
        }
        else
        {
            $("#terminal").val("第1学期");
            terminal=1;

            if (MyDate.getMonth()+1 < 3)
            {
                $("#year1").val(year-1);
                $("#year2").val(year);
            }
            if(MyDate.getMonth()+1 >= 8)
            {
                $("#year1").val(year);
                $("#year2").val(year+1);
            }
        }

    }
}

function Supervise_Time_Calendat_function()
{
    Get_CurrentYear(CurrentYear);
    $("#supervise_time1").val( $("#year1S").val()+"-"+$("#year2S").val()+"-"+$("#terminalS").val());
    $("#supervise_time1").focus(function (){
        $("#dtBoxS").show();
        Get_CurrentYear(CurrentYear);
    });
//        X 按钮
    $(".dtpicker-close").click(function(){
        $("#dtBoxS").hide();
    });
//        取消按钮
    $(".dtpicker-buttonClear").click(function(){
        $("#dtBoxS").hide();
    });
//        确定按钮
    $(".dtpicker-buttonSet").click(function(){
        $("#dtBoxS").hide();
        $("#supervise_time").val( $("#year1S").val()+"-"+$("#year2S").val()+"-"+$("#terminalS").val());
    });

    //year1 + 按钮
    $("#year1_class1S").click(function(){
        CurrentYear++;
        $("#year1S").val(CurrentYear);
        $("#year2S").val(parseInt(CurrentYear)+1);
    });
    //year1 - 按钮
    $("#year1_class2S").click(function(){
        CurrentYear--;
        $("#year1S").val(CurrentYear);
        $("#year2S").val(parseInt(CurrentYear)+1);
    });
    //学期选择的 +、- 按钮
    $(".dtpicker-compButtonEnableS").click(function(){
        if ( $("#terminalS").val() == "1")
            $("#terminalS").val("2");
        else
            $("#terminalS").val("1");
    })
    $("#year1S").bind('keyup',function(){
        CurrentYear=$("#year1S").val();
        if( CurrentYear.length==4)
        {
            $("#year1S").blur(function(){
                console.log(CurrentYear.match(/^\d{4}$/));
                if ( parseInt(CurrentYear)>2050 ||  parseInt(CurrentYear)<=1970 || CurrentYear.match(/^\d{4}$/) == null)
                {
                    alert("请输入正确的4位数字！有效范围为1970～2049");
                    CurrentYear = MyDate.getFullYear();
                }
                $("#year1S").val(CurrentYear);
                $("#year2S").val(parseInt(CurrentYear)+1);

            });
        }
    });
    function Get_CurrentYear(time)
    {

        $("#year1S").val(time);
        $("#year2S").val(time+1);
        if ( (MyDate.getMonth()+1) <=9 && (MyDate.getMonth()+1) >= 2)
        {
            $("#terminalS").val("1");
            terminal = 1;
        }
        else
        {
            $("#terminalS").val("2");
            terminal=2;
        }

    }
}

//修改督导信息时候所用的两个日历函数
function Change_Time_Calendat_function1()
{
    Get_CurrentYear(CurrentYear);
    $("#change_begin_time").val( $("#year1C1").val()+"-"+$("#year2C1").val()+"-"+$("#terminalC1").val());
    $("#change_begin_time").focus(function (){
        $("#dtBoxC1").show();
        Get_CurrentYear(CurrentYear);
    });
//        X 按钮
    $(".dtpicker-close").click(function(){
        $("#dtBoxC1").hide();
    });
//        取消按钮
    $(".dtpicker-buttonClear").click(function(){
        $("#dtBoxC1").hide();
    });
//        确定按钮
    $(".dtpicker-buttonSet").click(function(){
        $("#dtBoxC1").hide();
        $("#change_begin_time").val( $("#year1C1").val()+"-"+$("#year2C1").val()+"-"+$("#terminalC1").val());
    });

    //year1 + 按钮
    $("#year1_class1C1").click(function(){
        CurrentYear++;
        $("#year1C1").val(CurrentYear);
        $("#year2C1").val(parseInt(CurrentYear)+1);
    });
    //year1 - 按钮
    $("#year1_class2C1").click(function(){
        CurrentYear--;
        $("#year1C1").val(CurrentYear);
        $("#year2C1").val(parseInt(CurrentYear)+1);
    });
    //学期选择的 +、- 按钮
    $(".dtpicker-compButtonEnableC1").click(function(){
        if ( $("#terminalC1").val() == "1")
            $("#terminalC1").val("2");
        else
            $("#terminalC1").val("1");
    })
    $("#year1C1").bind('keyup',function(){
        CurrentYear=$("#year1C1").val();
        if( CurrentYear.length==4)
        {
            $("#year1C1").blur(function(){
                if ( parseInt(CurrentYear)>2050 ||  parseInt(CurrentYear)<=1970 || CurrentYear.match(/^\d{4}$/) == null)
                {
                    alert("请输入正确的4位数字！有效范围为1970～2049");
                    CurrentYear = MyDate.getFullYear();
                }
                $("#year1C1").val(CurrentYear);
                $("#year2C1").val(parseInt(CurrentYear)+1);

            });
        }
    });
    function Get_CurrentYear(time)
    {

        $("#year1C1").val(time);
        $("#year2C1").val(time+1);
        if ( (MyDate.getMonth()+1) <=9 && (MyDate.getMonth()+1) >= 2)
        {
            $("#terminalC1").val("1");
            terminal = 1;
        }
        else
        {
            $("#terminalC1").val("2");
            terminal=2;
        }

    }
}
function Change_Time_Calendat_function2()
{
    Get_CurrentYear(CurrentYear);
    $("#change_end_time").val( $("#year1C2").val()+"-"+$("#year2C2").val()+"-"+$("#terminalC2").val());
    $("#change_end_time").focus(function (){
        $("#dtBoxC2").show();
        Get_CurrentYear(CurrentYear);
    });
//        X 按钮
    $(".dtpicker-close").click(function(){
        $("#dtBoxC2").hide();
    });
//        取消按钮
    $(".dtpicker-buttonClear").click(function(){
        $("#dtBoxC2").hide();
    });
//        确定按钮
    $(".dtpicker-buttonSet").click(function(){
        $("#dtBoxC2").hide();
        $("#change_end_time").val( $("#year1C2").val()+"-"+$("#year2C2").val()+"-"+$("#terminalC2").val());
    });

    //year1 + 按钮
    $("#year1_class1C2").click(function(){
        CurrentYear++;
        $("#year1C2").val(CurrentYear);
        $("#year2C2").val(parseInt(CurrentYear)+1);
    });
    //year1 - 按钮
    $("#year1_class2C2").click(function(){
        CurrentYear--;
        $("#year1C2").val(CurrentYear);
        $("#year2C2").val(parseInt(CurrentYear)+1);
    });
    //学期选择的 +、- 按钮
    $(".dtpicker-compButtonEnableC2").click(function(){
        if ( $("#terminalC2").val() == "1")
            $("#terminalC2").val("2");
        else
            $("#terminalC2").val("1");
    })
    $("#year1C2").bind('keyup',function(){
        CurrentYear=$("#year1C2").val();
        if( CurrentYear.length==4)
        {
            $("#year1C1").blur(function(){
                if ( parseInt(CurrentYear)>2050 ||  parseInt(CurrentYear)<=1970 || CurrentYear.match(/^\d{4}$/) == null)
                {
                    alert("请输入正确的4位数字！有效范围为1970～2049");
                    CurrentYear = MyDate.getFullYear();
                }
                $("#year1C2").val(CurrentYear);
                $("#year2C2").val(parseInt(CurrentYear)+1);

            });
        }
    });
    function Get_CurrentYear(time)
    {

        $("#year1C2").val(time);
        $("#year2C2").val(time+1);
        if ( (MyDate.getMonth()+1) <=9 && (MyDate.getMonth()+1) >= 2)
        {
            $("#terminalC2").val("1");
            terminal = 1;
        }
        else
        {
            $("#terminalC2").val("2");
            terminal=2;
        }

    }
}

//不同学期的督导添加样式
function YearStyle(row,index){
    var classes=['active','warning'];
    if (row.supervise_time.split("-")[2]=="2")
        return{
          classes:classes[0]
        };
    else {
        return {
            classes:classes[1]
        }
    }
}