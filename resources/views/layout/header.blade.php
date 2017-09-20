<style>
    @media (min-width: 10px){
        #change_user{
            position:absolute;
            margin-left:53% ;
        }
        #navbar{
            position: absolute; margin-left: 88%
        }
        #natop{
            position: absolute;
            width: 1913px;
        }
    }

    @media screen and (min-width: 768px){
        #change_user{
            position:absolute;
            margin-left:53% ;
            margin-top:15px;
        }
        #navbar{
            position: absolute; margin-left: 90%
        }
        #natop{
            position: fixed;
            width: auto;
        }
        #welcomeText{
            margin-left: -80px;
        }

    }
    @media screen and (min-width: 1400px){
        #change_user{
            position:absolute;margin-left:70% ;margin-top:15px;
        }
        #navbar{
            position: absolute; margin-left: 93%
        }
        #welcomeText{
            margin-left: 10px;
        }
    }
    @media screen and (min-width: 1900px){
        #change_user{
            position:absolute;
            margin-left:75% ;
            margin-top:15px;
        }
        #navbar{
            position: absolute; margin-left: 95%
        }
        #welcomeText{
            margin-left: 20px;
        }
    }
    #change_user{
        margin-top:15px;
    }
    #welcomeText{
        color: white;font-size: 22px;
        padding-top: 15px;
        float: left;
    }
    #welcomeText:hover,.navbar-brand:hover,.nav a:hover{
        text-decoration: none;
        background: none!important;
    }
    .navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:hover, .navbar-inverse .navbar-nav>.open>a:focus{
        background: none!important;
        /*background-color:#333!important;*/
    }
    .user-menu li:hover{
        background-color: #333;
    }
    #id_title{
        color: white;font-size: 18px;
    }
</style>
    <nav id="natop" class="navbar navbar-inverse navbar-fixed-top" style="background: linear-gradient(to right,#0a1d2f, #091929)!important;background: -moz-linear-gradient(left,#0a1d2f, #091929)!important;background: -webkit-linear-gradient(left,#0a1d2f, #091929)!important;">
    <div class="container-fluid" style="height: 60px;">
        <div class="navbar-header" >

            <a class="navbar-brand" href="#" style="color: white;font-size: 22px; margin-top: 3px;">
                <img src="{{asset('assets/images/logo.png')}}" alt="" style="width: 8%;margin-right:10px;">教评中心业务平台
            </a>
            <a id="welcomeText" style="">欢迎您，{{Auth::User()->name}}老师</a>

            <div id="change_user"  style="float: right;">
                <span id="id_title">切换用户身份：</span>
                <span id="level" style="color: #fff;display: none;">{{session('role')}}</span>
                <span id="user-level" style="color: #CDCDCD;"></span>
            </div>

            <div id="navbar" class="navbar-collapse" style="float:right;">
                <ul class="nav navbar-nav navbar-right" style="margin-top: 6px;">
                    <li class="light-blue">
                        <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                            <img class="nav-user-photo" src="{{asset('assets/avatars/avatar2.png')}}">
                            <input id="id" value=" {{ Auth::user()->id }}" style="display: none"/>
                            <i class="icon-caret-down"></i>
                        </a>
                        <ul class="user-menu pull-right dropdown-menu dropdown-caret dropdown-close " style="background: #FFF">
                            <li>
                                <a href="/userManage">
                                    <i class="icon-user"></i>
                                    修改信息
                                </a>
                            </li>
                            <li>
                                <a href="/changePass">
                                    <i class="icon-key"></i>
                                    修改密码
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="/logout">
                                    <i class="icon-off"></i>
                                    退出
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</nav>

    <script>
//        alert($('#level').html());
        var MyDate = new Date();
        var CurrentYear = MyDate.getFullYear();
        var terminal=null;
        if ( (MyDate.getMonth()+1) <8 && (MyDate.getMonth()+1) >= 3)
        {
            terminal="第2学期";
            year1 = parseInt(CurrentYear)-1;
            year2 = parseInt(CurrentYear);
        }
        else
        {
            terminal="第1学期";

            if (MyDate.getMonth()+1 < 3)
            {
                year1=parseInt(CurrentYear)-1;
                year2=parseInt(CurrentYear);
            }
            if(MyDate.getMonth()+1 >= 8)
            {
                year1=parseInt(CurrentYear);
                year2=parseInt(CurrentYear)+1;
            }
        }
        terminal=terminal.match(/\d+/g);

        $.ajax({
            type:"GET",
            url:"/GetStatus",
            data:{id:$('#id').val().replace(/(^\s*)|(\s*$)/g, ""),year:year1+'-'+year2+'-'+terminal},
            success:function(result){
//                console.log(result);
                for (i=0;i<result.length;i++)
                {
//                    url = 'admin/user/switch/start/'+result[0]['id'];
//                    $('#menu-dropdown').append('<li><a href="'+"admin/user/switch/start/"+result[i]['id']+'">'+result[i]["level"]+'</a></li>');
                    $('#user-level').append('' +
                            '<a style="margin-right:6px;padding:6px 10px;" href="'+"/user/switch/start/"+result[i]['name']+'">'+result[i]["name"]+'</a>');


                }
                if (result.length==1)
                {
                    $('#change_user').hide();
                }
                else {
                    $('#change_user').show();
                    for(k=0; k<$('#user-level a').length;k++){
                        if($('#user-level a').eq(k).text()==($('#level').html()))
                        {
                            $('#user-level a').eq(k).css({'color':'#428bca','background':'#EDEDED'});
                        }
                    }
                }
            }
        });

    </script>
