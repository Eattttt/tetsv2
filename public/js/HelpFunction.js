

/**
 * Created by computer on 2016/8/17.
 */
function YearSemester(time)
{
    //LessonTime：2016-01-01
    arr = time.split('-');

    year=$arr[0];
    Month=$arr[1];
    if (Month < '3' || Month >='8')
    {
        semester = 1;

        if (Month<'03')
        {
            year1=parseInt(year)-1;
            year2 =year;
        }
        if(Month>='08')
        {
            year1=year;
            year2 =parseInt(year)+1;
        }

    }
    if (Month >= '03' && Month<'08')
    {
        year1=parseInt(year)-1;
        year2 =year;
        semester = 2;

    }
    YearSemester=year1+'-'+year2+'-'+semester;
    Year = year1+'-'+year2;
    Semester = semester;
    return data={
        'YearSemester':YearSemester,
        'Year':Year,
        'Semester':Semester
    }

}

//课程属性部分选中后修改样式
function inputLessonAttrAddCss()
{
    //var obj = windows.event.srcElement;
    $('#inputLessonAttr').css('color','black');
}

//理论、实践、体育 评价表页面
function CheckTime() {
    if ($('#ListenTime').val().match(/^\d\d\d\d-\d\d-\d\d/) == null) {
        $('#ListenTime').val('');
        alert('听课时间格式错误！请重新输入');
    }
}

function checkNum(a)
{
    if (a.value != '')
    {
        if (a.value.match(/^\d{1,4}$/) == null )
        {
            a.value='';
            alert('格式错误！请重新输入');
        }
        else {
            var Num1 = parseInt($('label:contains("人数")').eq(0).next().children().val());
            var Num2 = parseInt($('label:contains("人数")').eq(1).next().children().val());
            var Num3 = parseInt($('label:contains("人数")').eq(2).next().children().val());
            var Num4 = parseInt($('label:contains("人数")').eq(3).next().children().val());


            if ( isNaN(Num1)===false  && isNaN(Num2)===false )
            {
                if (Num1 < Num2)
                {
                    a.value='';
                    alert('请输入正确的人数');
                }
            }

            if ( !isNaN(Num2) && !isNaN(Num3))
                if (Num2 < Num3)
                {
                    a.value='';
                    alert('请输入正确的应到人数或实到人数');
                }
            if ( !isNaN(Num2) && !isNaN(Num4))
                if (Num2 < Num4)
                {
                    a.value='';
                    alert('请输入正确的应到人数或实到人数');
                }

        }
    }
}
function ok(obj)
{
    $('#LessonTime').val($(obj).text());
    $('#LessonTimeStyle').css("display","none");
}

//增加课程节次
function AddLessonTime(LessonTime_X,LessonTime_Y,lessonTime)
{
    //1、LessonTime-suggest的位置调整
    $('#LessonTime-suggest').css("position","absolute");
    $('#LessonTime-suggest').css("left",LessonTime_X +"px");
    $('#LessonTime-suggest').css("top", LessonTime_Y + $('#LessonTime').height()+10+"px");
    $('#LessonTime-suggest').width(($('#LessonTime').width()+10));

    //2、增加Lesson_time 的内容
    var reg = /\d{4}/g;
    lessonTimeArr = lessonTime.match(reg);
    console.log(lessonTimeArr);//0102,0304
    if (lessonTimeArr.length>=1)
    {
        $("#LessonTime-suggest").append('<div class="LessonTime-result active1">单节</div>');
        $("#LessonTime-suggest").append('<div class="LessonTime-result">双节</div>');
    }

    if (lessonTimeArr.length>=2)
    {
        $("#LessonTime-suggest").append('<div class="LessonTime-result">三节</div>');
        $("#LessonTime-suggest").append('<div class="LessonTime-result">半天</div>');

    }
//                $('.LessonTime-result').removeClass('active1');

//        3、当课程或者课时被点击的时候，默认显示单节的情况
    $('.LessonTime-result').eq(0).addClass('active1');
    position_boxDown();
    appendLessonTime1(lessonTimeArr);

    //具体内容的定位

    $('.LessonTime-result').eq(0).click(function (){
        $('.box_down').children().remove();
        $('.LessonTime-result').removeClass('active1');
        $(this).addClass('active1');
        position_boxDown();
        appendLessonTime1(lessonTimeArr);
    });
    $('.LessonTime-result').eq(1).click(function (){
        $('.box_down').children().remove();
        $('.LessonTime-result').removeClass('active1');
        $(this).addClass('active1');
        position_boxDown();
        appendLessonTime2(lessonTimeArr,lessonTime);
    });
    $('.LessonTime-result').eq(2).click(function (){
        $('.box_down').children().remove();
        $('.LessonTime-result').removeClass('active1');
        $(this).addClass('active1');
        console.log(lessonTime);
        console.log(lessonTimeArr);

        appendLessonTime3(lessonTime);
    });
    $('.LessonTime-result').eq(3).click(function (){
        $('.box_down').children().remove();
        $('.LessonTime-result').removeClass('active1');
        $(this).addClass('active1');
        appendLessonTime4(lessonTime);
    });

//bowdown 类的坐标计算
    function position_boxDown()
    {
        $('.box_down').css("left",LessonTime_X +"px");
        $('.box_down').css("top", LessonTime_Y + $('#LessonTime').height()+ $('#LessonTime-suggest').height()+"px");
        $('.box_down').width($('#LessonTime').width()+10);
        $('.box_down').children().remove();

    }
}
//课时被点击时：
function appendLessonTime1(lessonTimeArr)
{
    for (var i=0;i<lessonTimeArr.length;i++)
    {
        if (lessonTimeArr[i]=='0102')
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第1节</ul>");
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第2节</ul>");
        }
        if (lessonTimeArr[i]=='0304')
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第3节</ul>");
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第4节</ul>");
        }
        if (lessonTimeArr[i]=='0506')
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第5节</ul>");
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第6节</ul>");
        }
        if (lessonTimeArr[i]=='0708')
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第7节</ul>");
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第8节</ul>");
        }
        if (lessonTimeArr[i].indexOf('0910')>=0)
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第9节</ul>");
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第10节</ul>");
        }
    }
}

//课程被点击时：
function appendLessonTime2(lessonTimeArr,lessonTime)
{
    for (var i=0;i<lessonTimeArr.length;i++)
    {
        if (lessonTimeArr[i]=='0102')
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第1-2节</ul>");
        }

        if (lessonTimeArr[i]=='0304')
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第3-4节</ul>");
        }

        if (lessonTimeArr[i]=='0506')
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第5-6节</ul>");
        }

        if (lessonTimeArr[i]=='0708')
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第7-8节</ul>");
        }

        if (lessonTimeArr[i].indexOf('0910')>=0)
        {
            $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第9-10节</ul>");
        }
    }
    if (lessonTime.indexOf('0203')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第2-3节</ul>");
    }
    if (lessonTime.indexOf('0405')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第4-5节</ul>");
    }
    if (lessonTime.indexOf('0607')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第6-7节</ul>");
    }
    if (lessonTime.indexOf('0809')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第8-9节</ul>");
    }
}

//三节被点击时：
function appendLessonTime3(lessonTime)
{
    if (lessonTime.indexOf('010203')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第1-3节</ul>");
    }
    if (lessonTime.indexOf('020304')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第2-4节</ul>");
    }
    if (lessonTime.indexOf('030405')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第3-5节</ul>");
    }
    if (lessonTime.indexOf('040506')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第4-6节</ul>");
    }
    if (lessonTime.indexOf('050607')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第5-7节</ul>");
    }
    if (lessonTime.indexOf('060708')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第6-8节</ul>");
    }
    if (lessonTime.indexOf('070809')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第7-9节</ul>");
    }
    if (lessonTime.indexOf('080910')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第8-10节</ul>");
    }
}

//半天被点击时：
function appendLessonTime4(lessonTime)
{
    if (lessonTime.indexOf('01020304')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第1-4节</ul>");
    }
    if (lessonTime.indexOf('05060708')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第5-8节</ul>");
    }
    if (lessonTime.indexOf('0910')>=0)
    {
        $('.box_down').append("<ul class='index_detail' onclick='ok(this)'>第9-10节</ul>");
    }
}


//课表  听课节数转换
function change_lessontime(lessonTime)
{
    switch(lessonTime)
    {
        case 1:lessonTime='0102';break;
        case 2:lessonTime='0304';break;
        case 3:lessonTime='0506';break;
        case 4:lessonTime='0708';break;
        case 5:lessonTime='0910';break;
    }
    return lessonTime;
}
//日期选择函数

function chooseDate(date_arr)
{
    $("#ListenTime").datetimepicker({
        language: 'zh-CN',
        format: 'yyyy-mm-dd',
        minView:'month',
        autoclose:true,
        endDate:new Date(),
        // daysOfWeekDisabled:date_arr
    })
        .datetimepicker('setDaysOfWeekDisabled',date_arr);
}

//管理员的督导选择函数
function chooseSupervisor()
{
    //输入框的操作事宜
    var timeoutObj;
    $('#SearchBar')
        .bind('click',function (ev){
        var Super_name = $('#SearchBar').val();

            $.ajax({
                type: "get",
                async: false,
                timeout:500,
                url: "/GetSupervisorName",
                data:{supervisor:Super_name},
                success: function (result) {
                    console.log(result);
                    var html='';
                    for (var i=0;i<result.length;i++)
                    {
                        if (result[i]['name'].indexOf('组')>=0 || result[i]['name'].indexOf('学院')>=0 || result[i]['name'].indexOf('负责人')>= 0)
                            i++;
                        else
                            html+='<li>'+result[i]['user_id']+' '+result[i]['name']+'</li>';
                    }
                    $('#search_result').html(html);
                    //阻止事件冒泡
                    var oEvent = ev || event;
                    oEvent.stopPropagation();
                    $('#search-suggest').show().css({
                        //top:$('#SearchBar').offset().top+$('#SearchBar').height(),
                        //left:$('#SearchBar').offset().left,
                        position:'absolute',
                        height:'220px',
                        overflow:'auto'
                    });
                }
            });
            $(document).bind('click',function(){
                $('#search-suggest').hide();
            });
            $('#search_result').delegate('li','click',function(){
                SearchValueID.value=$(this).text().split(" ")[0];
                SearchValue.value=$(this).text().split(" ")[1];
            });

    })
        .bind('input propertychange',function (ev){
            if(timeoutObj)
            {
                clearTimeout(timeoutObj);
            }
            timeoutObj = setTimeout(function(){
                var Super_name = $('#SearchBar').val();
                $.ajax({
                    type: "get",
                    async: false,
                    url: "/GetSupervisorName",
                    data:{supervisor:Super_name},
                    success: function (result) {
                        console.log(result);
                        var html='';
                        for (var i=0;i<result.length;i++)
                        {
                            if (result[i]['name'].indexOf('组')>=0 || result[i]['name'].indexOf('学院')>=0 || result[i]['name'].indexOf('负责人')>= 0)
                                i++;
                            else
                                html+='<li>'+result[i]['user_id']+' '+result[i]['name']+'</li>';
                        }
                        $('#search_result').html(html);
                        //阻止事件冒泡
                        var oEvent = ev || event;
                        oEvent.stopPropagation();
                        $('#search-suggest').show().css({
                            //top:$('#SearchBar').offset().top+$('#SearchBar').height(),
                            //left:$('#SearchBar').offset().left,
                            position:'absolute',
                            height:'220px',
                            overflow:'auto'
                        });
                    }
                });
                $(document).bind('click',function(){
                    $('#search-suggest').hide();
                });
                $('#search_result').delegate('li','click',function(){
                    SearchValueID.value=$(this).text().split(" ")[0];
                    SearchValue.value=$(this).text().split(" ")[1];
                });
            },500);
})
        .blur(function(){
            var Super_name = $('#SearchBar').val();
            $.ajax({
                type: "get",
                async: false,
                url: "/GetSupervisorIDbyName",
                data:{supervisor:Super_name},
                success: function (result) {
                    if (result.length != 0)
                    {
                        SearchValueID.value=result[0]['user_id'];
                    }
                    else {
                        $("#SearchBarID").val('');
                        //alert('督导姓名错误！');
                    }
                }
            });
    });
}


function LessonTimeChange(WebLessonTime)
{
    var LessonTime = null;
    var flag = WebLessonTime.match(/\-/g);
    if (flag == null)//第1节课
    {
        LessonTime = WebLessonTime.match(/(\d+)/)[0];
        return LessonTime;
    }
    else {//第1-2节课
        LessonTime = [];

        flag1 = parseInt(WebLessonTime.split('-')[0].match(/\d/g));
        flag2 = parseInt(WebLessonTime.split('-')[1].match(/\d+/g));

        for (var i=0;i<=flag2-flag1;i++)
        {
            LessonTime.push(flag1+i);
        }
        return LessonTime;
    }

}

function TableHeadData()
{
    var Headlist = [];

    obj1={
        key:'章节目录',
        value:  $('#inputChapter').val()
    };
    obj2={
        key:'课程名称',
        value:  $('#LessonName').val()
    };
    obj3={
        key:'任课教师',
        value:  $('#Teacher').val()
    };
    obj4={
        key:'上课班级',
        value:  $('#LessonClass').val()
    };
    obj5={
        key:'上课地点',
        value:  $('#LessonRoom').val()
    };
    obj6={
        key:'听课时间',
        value: $('#ListenTime').val()
    };
    obj7={
        key:'督导姓名',
        value:  $('#SearchBar').val()
    };
    obj8={
        key:'课程属性',
        value:$('#inputLessonAttr').val()
    };
    obj9={
        key:'督导id',
        value:  $('#SearchBarID').val()
    };
    if($('#LessonTime').val().match(/\d\-\d/)!=null)//听课节次5-7节
    {
        obj10={
            key:'听课节次',
            value1: LessonTimeChange($('#LessonTime').val()),//1:5, 2:6, 3:7
            value:$('#LessonTime').val().match(/\d+\-\d+/)[0]//5-7
        };
    }
    else{//第1节  第11节
        obj10={
            key:'听课节次',
            value1: $('#LessonTime').val().match(/(\d+)/)[0],//1 11
            value:$('#LessonTime').val().match(/(\d+)/)[0]//尽可能多的匹配数字 1  11
        };
    }
    obj11={
        key:'教师ID',
        value:$('#TeacherID').val()
    };
    Headlist.push(obj1);
    Headlist.push(obj2);
    Headlist.push(obj3);
    Headlist.push(obj4);
    Headlist.push(obj5);
    Headlist.push(obj6);
    Headlist.push(obj7);
    Headlist.push(obj8);
    Headlist.push(obj9);
    Headlist.push(obj10);
    Headlist.push(obj11);

    return Headlist;
}

function checkNeceHead_Input(LessonState)
{
    function checkStuNum()
    {
        var Num1 = parseInt($('label:contains("人数")').eq(0).next().children().val());
        var Num2 = parseInt($('label:contains("人数")').eq(1).next().children().val());
        var Num3 = parseInt($('label:contains("人数")').eq(2).next().children().val());
        var Num4 = parseInt($('label:contains("人数")').eq(3).next().children().val());
        if ( isNaN(Num1)  || isNaN(Num2) || isNaN(Num3) || isNaN(Num4) )
            return -1;
        else
            return 1;
    }
    var flagC =0;
    //验证必填信息部分
    if($('#inputChapter').val() == '')
    {
        if(LessonState=='已完成')//已完成的话弹出提示框
        {
            flagC = 1;
        }else{
            flagC = 2;//待提交
        }
        alert('请输入章节目录！');
        return flagC;
    }
    if($('#SearchBarID').val() === '' )
    {
        if(LessonState=='已完成')//已完成的话弹出提示框
        {
            flagC = 1;
        }else{
            flagC = 2;//待提交
        }
        alert('请输入督导ID和姓名！');
        return flagC;
    }

    //if($('#inputLessonAttr').val() === null)
    //{
    //    if(LessonState=='已完成')//已完成的话弹出提示框
    //    {
    //        flagC = 1;
    //    }else{
    //        flagC = 2;//待提交
    //    }
    //    alert('请输入课程属性！');
    //}
    if($('#LessonName').val() == '')
    {
        if(LessonState=='已完成')//已完成的话弹出提示框
        {
            flagC = 1;
        }else{
            flagC = 2;//待提交
        }
        alert('请输入课程名称！');
        return flagC;
    }
    if ( $('#ListenTime').val() =='')
    {
        if(LessonState=='已完成')//已完成的话弹出提示框
        {
            flagC = 1;
        }else{
            flagC = 2;
        }
        alert('请输入听课时间！');
        return flagC;
    }
    if ( $('#LessonTime').val() =='')
    {
        if(LessonState=='已完成')//已完成的话弹出提示框
        {
            flagC = 1;
        }else{
            flagC = 2;
        }
        alert('请输入听课节次！');
        return flagC;
    }
    //if (flagC != 0)//保证头部必填信息全部填完
    //{
    //    return flagC;
    //}
    if(LessonState=='已完成'){
        if ($('#front .tab_menu').length != $('#front .current').length)
        {

            flagC = 1;
            if(LessonState=='已完成')//已完成的话弹出提示框
            {
                alert('评价表正面信息未完成!');
            }
        }
        for (var i=0;i<$('#front .grade2 h2').length;i++)
        {
            if($($('#front .grade2 h2')[i])["0"].innerText.indexOf("教师授课情况总体评价")>=0)
            {
                if ( $($('#front .grade2')[i]).find('.current'))
                {
                    if ( $($('#front .grade2')[i]).find('.bar1.current'))
                    {
                        if($($('#front .grade1')[0]).find('.grade2 .bar1.current').length<6)
                        {
                            flagC=1;
                            alert('"教师授课情况总体评价”为“非常满意”的条件不满足！');
                            break;
                        }
                    }
                    else if ($($('#front .grade2')[i]).find('.bar5.current'))
                    {
                        if($($('#front .grade1')[0]).find('.grade2 .bar5.current').length<4)
                        {
                            flagC=1;
                            alert('"教师授课情况总体评价”为“存在明显不足”的条件不满足！');
                            break;
                        }
                    }
                }
            }
        }
    }
    return flagC;
}

function GetBackList1()
{
    var Backlist1 = [];
    $("input:checkbox:checked").each(function(index,element){
        Backlist1.push(element.parentNode.lastChild.data.replace(/(^\s+)|(\s+$)/g,""));
    });

    $("input:radio:checked").each(function(index,element){
        Backlist1.push(element.parentNode.lastChild.data.replace(/(^\s+)|(\s+$)/g,""));
    });
    return Backlist1;
}
function GetBackList2()
{
    var Backlist2 = [];//背面textarea的值
    for (k=0;k<$('p:contains("评述")').length;k++)
    {
        value = $('p:contains("评述")').eq(k).next().val();
        obj = {
            key : k,
            value : value
        };
        Backlist2.push(obj);
    }
    return Backlist2;
}

// $("#year1").val(time-1); $("#year2").val(time); $("#terminal").val("第2学期");
function Get_CurrentYear(year1, year2, terminal)
{
    var MyDate = new Date();
    var time = MyDate.getFullYear();

    if ( (MyDate.getMonth()+1) <8 && (MyDate.getMonth()+1) >= 3)
    {
        year1.val(time-1);
        year2.val(time);
        if(terminal != null)
            terminal.val("第2学期");
    }
    else
    {
        if(terminal != null)
            terminal.val("第1学期");
        if (MyDate.getMonth()+1 < 3)
        {
            year1.val(time-1);
            year2.val(time);
        }
        if(MyDate.getMonth()+1 >= 8)
        {
            year1.val(time);
            year2.val(time+1);
        }
    }

}
function getRootPath(){
    //获取当前网址，如： http://localhost:8083/uimcardprj/share/meun.jsp
    var curWwwPath=window.document.location.href;
    //获取主机地址之后的目录，如： uimcardprj/share/meun.jsp
    var pathName=window.document.location.pathname;
    var pos=curWwwPath.indexOf(pathName);
    //获取主机地址，如： http://localhost:8083
    var localhostPaht=curWwwPath.substring(0,pos);
    //获取带"/"的项目名，如：/uimcardprj
    var projectName=pathName.substring(0,pathName.substr(1).indexOf('/')+1);
    return(localhostPaht+projectName);
}
//$("#calender")  $("#dtBox")
function school_calendar(calendar, dtBox, year1, year2, terminal, year_plus, year_minus)
{
    var MyDate = new Date();
    var CurrentYear = MyDate.getFullYear();
    Get_CurrentYear(year1, year2, terminal);
    dtBox.show();

    //        X 按钮
    $(".dtpicker-close").click(function(){
        dtBox.hide();
    });
    //        取消按钮
    $(".dtpicker-buttonClear").click(function(){
        dtBox.hide();
    });
    //        确定按钮
    $(".dtpicker-buttonSet").click(function(){
        dtBox.hide();
        if (terminal == null)
            calendar.val( year1.val()+"学年 ～ "+year2.val()+"学年");
        else
            calendar.val( year1.val()+"学年 ～ "+year2.val()+"学年："+terminal.val());
    });

    //year1 + 按钮
    year_plus.click(function(){
        CurrentYear++;
        year1.val(CurrentYear);
        year2.val(CurrentYear+1);
    });
    //year1 - 按钮
    year_minus.click(function(){
        CurrentYear--;
        year1.val(CurrentYear);
        year2.val(CurrentYear+1);
    });
    //学期选择的 +、- 按钮
    $(".dtpicker-compButtonEnable").click(function(){
        if (terminal != null)
            if ( terminal.val() == "第1学期")
                terminal.val("第2学期");
            else
                terminal.val("第1学期");
    });
    year1.bind('keyup',function(){
        CurrentYear=year1.val();
        if( CurrentYear.length==4)
        {
            year1.blur(function(){
                //console.log(CurrentYear.match(/^\d{4}$/));
                if ( parseInt(CurrentYear)>=2050 ||  parseInt(CurrentYear)<=1970 || CurrentYear.match(/^\d{4}$/) == null)
                {
                    alert("请输入正确的4位数字！有效范围为1970～2050");
                    CurrentYear = MyDate.getFullYear();
                }
                year1.val(CurrentYear);
                year2.val(parseInt(CurrentYear)+1);

            });
        }
    })



}