<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/login', 'Auth\AuthController@getLogin');
Route::get('/logout', 'Auth\AuthController@getLogout');
Route::get('captcha/makecode','Auth\CapchaController@makecode');
Route::get('captcha/getcode','Auth\CapchaController@getcode');

Route::get('/login', 'Auth\AuthController@login');
Route::get('/GetUserRoles', 'SupervisorController@GetUserRoles');
//Route::auth();

// 发送密码重置链接路由
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// 密码重置路由
Route::get('password/reset', 'Auth\PasswordController@getReset');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::group(['middleware' => 'auth'], function () {


    Route::get('/aaa', function(){
        return view('particle.Theory');
    });

    //1、学院、组 督导总人数+关注课程数目+关注课程完成数目+已完成听课数目

    Route::get('/index', 'IndexController@index');//the view of index
    Route::get('/Schoolindex', 'IndexController@Schoolindex');//学校级 + 大组长
    Route::get('/Unitindex', 'IndexController@Unitindex');//学校级 + 大组长
    Route::get('/Groupindex', 'IndexController@Groupindex');//学校级 + 大组长
//2、登录首页中的饼状图，返回值为完成与未完成的数据数组
    //校级+大组长
    Route::get('/NecessaryState', 'IndexController@NecessaryState');
    //院级登录首页中的饼状图，返回值为完成与未完成的数据数组
    Route::get('/UnitNecessaryState', 'IndexController@UnitNecessaryState');
    //小组长登录首页中的饼状图，返回值为完成与未完成的数据数组
    Route::get('/GroupNecessaryState', 'IndexController@GroupNecessaryState');

    //3、登录首页中的柱状图，返回值为每个学院的听课情况的数据数组
    Route::get('/EvaluatedInUnit', 'IndexController@EvaluatedInUnit');
    //4|登录首页中的柱状图，返回值为校级、院级、小组的兼职、专职人员数目
    Route::get('/TimeSupervisorNumber', 'IndexController@TimeSupervisorNumber');


    //5、用户状态切换
    Route::get( 'GetStatus', 'Auth\UserController@GetStatus' );
    Route::get( '/user/switch/start/{name}', 'Auth\UserController@user_switch_start' );
    Route::get('/SupervisorNum', 'IndexController@SupervisorNum');//学校级 + 大组长

//更新列表
    Route::get('/GetNewList', 'IndexController@GetNewList');//校级+大组长
    Route::get('/GetUnitNewList', 'IndexController@GetUnitNewList');
    Route::get('/GetGroupNewList', 'IndexController@GetGroupNewList');


    //评价管理
    //学期关注课程
    Route::get('/NecessaryTask', 'EvaluationController@NecessaryTask');//必听任务视图
    Route::get('/GetNecessaryTask', 'EvaluationController@GetNecessaryTask');//获取必听任务
    Route::post('/DeleteNecessaryTask', 'EvaluationController@DeleteNecessaryTask');//删除必听任务
    Route::get('/DeleteNecessaryTask', 'EvaluationController@NecessaryTask');//删除必听任务

    Route::get('/AddNecessaryTask', 'EvaluationController@AddNecessaryTask');//增加必听任务





    //关注课程完成情况
    Route::get('/Evaluation', 'EvaluationController@Evaluation');//督导完成情况页面
    //1、校级+大组长
    Route::get('/UnEvaluated', 'EvaluationController@UnEvaluated');//督导未完成必听任务情况
    Route::get('/Evaluated', 'EvaluationController@Evaluated');//督导完成必听任务情况
    Route::get('/Saved', 'EvaluationController@Saved');//督导保存必听任务情况

    //2、小组长
    Route::get('/GroupUnEvaluated', 'EvaluationController@GroupUnEvaluated');//督导未完成必听任务情况
    Route::get('/GroupEvaluated', 'EvaluationController@GroupEvaluated');//督导完成必听任务情况
    Route::get('/GroupSaved', 'EvaluationController@GroupSaved');//督导完成必听任务情况

    //3、院级
    Route::get('/UnitUnEvaluated', 'EvaluationController@UnitUnEvaluated');//督导未完成必听任务情况
    Route::get('/UnitEvaluated', 'EvaluationController@UnitEvaluated');//督导完成必听任务情况
    Route::get('/UnitSaved', 'EvaluationController@UnitSaved');//督导完成必听任务情况



    Route::get('/GetSupervisorName', 'SupervisorController@GetSupervisorName');//所有督导的姓名
    Route::get('/GetSupervisorIDbyName', 'SupervisorController@GetSupervisorIDbyName');//所有督导的姓名
    Route::get('/GetUnitSupervisorName', 'SupervisorController@GetUnitSupervisorName');//本院督导的姓名
    Route::get('/GetGroupSupervisorName', 'SupervisorController@GetGroupSupervisorName');//本组督导的姓名
    Route::get('/UnitName', 'Help\HelpController@UnitName');//校级+大组长姓名提示框（学院方式）

    // 评价结果

    Route::get('/EverEvaluated', 'EverEvaluationController@EverEvaluated');//每个督导完成听课情况视图页面
    /**
     * 已完成评价课程
     */
    //校级+大组长
    Route::get('/GetAllEveryEvaluated', 'EverEvaluationController@GetAllEveryEvaluated');//校级督导完成听课情况
    //院级
    Route::get('/GetUnitEveryEvaluated', 'EverEvaluationController@GetUnitEveryEvaluated');//学院每个督导完成听课情况
    //小组长
    Route::get('/GetGroupEveryEvaluated', 'EverEvaluationController@GetGroupEveryEvaluated');//小组每个督导完成听课情况
    //督导
    Route::get('/GetEveryEvaluated', 'EverEvaluationController@GetEveryEvaluated');//每个督导完成听课情况
    /**
     * 已保存待提交评价课程
     */
    //校级+大组长
    Route::get('/GetAllEverySaved', 'EverEvaluationController@GetAllEverySaved');//校级督导完成听课情况
    //院级
    Route::get('/GetUnitEverySaved', 'EverEvaluationController@GetUnitEverySaved');//学院每个督导完成听课情况
    //小组长
    Route::get('/GetGroupEverySaved', 'EverEvaluationController@GetGroupEverySaved');//小组每个督导完成听课情况
    //督导
    Route::get('/GetEverySaved', 'EverEvaluationController@GetEverySaved');//每个督导完成听课情况

    //校级打回
    Route::get('/ResetEvaluationContent', 'EverEvaluationController@ResetEvaluationContent');//校级打回每个督导完成听课情况

    Route::get('/SubmitEvaluationContent', 'EverEvaluationController@SubmitEvaluationContent');//每个督导自行提交听课情况

    Route::get('/DelEvaluationContent', 'EverEvaluationController@DelEvaluationContent');//每个督导自行撤销



/*restructure begin*/
    //lesson search
    Route::get('/GetLessonArr', 'Help\LessonSearchController@GetLessonArr');//搜索框的课程表
    Route::get('/GetLessonArrTNLN', 'Help\LessonSearchController@GetLessonArrTNLN');//增加必听任务的时候，搜索框的课程表
    Route::get('/GetLessonArrThe', 'Help\LessonSearchController@GetLessonArrThe');//查询理论课程的时候，搜索框的课程表
    Route::get('/GetLessonArrPra', 'Help\LessonSearchController@GetLessonArrPra');//查询实践课程的时候，搜索框的课程表
    Route::get('/GetLessonArrPhy', 'Help\LessonSearchController@GetLessonArrPhy');//查询体育课程的时候，搜索框的课程表


    Route::get('/ResetPass', 'SupervisorController@ResetPass');//reset the password
    Route::get('/DeleteSupInfo', 'SupervisorController@DeleteSupInfo');//delete the supervisor_time
    //supervisor management
    Route::get('/SupervisorInfo', 'SupervisorController@SupervisorInfo');//the view of supervisor management
    Route::post('/ChangeSupervisorInfo', 'SupervisorController@ChangeSupervisorInfo');//change/add the supervisor's information
    Route::get('/ChangeSupervisorInfo', 'SupervisorController@SupervisorInfo');//if method is get, redirect the view SupervisorInfo
    Route::get('/GetSupervisorInfo', 'SupervisorController@GetSupervisorInfo');//specific supervisor's information showing in detail
    Route::get('/GetAllSupervisorInfo', 'SupervisorController@GetAllSupervisorInfo');//all supervisor's information showing in table
    Route::get('/GetBigGroupSupervisorInfo', 'SupervisorController@GetBigGroupSupervisorInfo');//all supervisor's information showing in table
    Route::get('/GetUnitSupervisorInfo', 'SupervisorController@GetUnitSupervisorInfo');//unit supervisor's information showing in table
    Route::get('/GetGroupSupervisorInfo', 'SupervisorController@GetGroupSupervisorInfo');//group supervisor's information showing in table
    Route::get('/RenewContacts', 'SupervisorController@RenewContacts');//增加督导信息

    //Unit responsibility management
    Route::get('/UnitUserManage', 'UnitUserController@UnitUserManage');//the view of Unit user
    Route::get('/GetSpecificUnitInfo', 'UnitUserController@GetSpecificUnitInfo');//specific supervisor's information showing in detail
    Route::get('/GetUnitUserInfo', 'UnitUserController@GetUnitUserInfo');//all unit users' information showing in table
    Route::post('/ChangeUnitUserInfo', 'UnitUserController@ChangeUnitUserInfo');//改变院级具体信息

    //teacher management
    Route::get('/teacherManage', 'TeacherUserController@teacherManage');//the view of Teacher user
    Route::get('/teacherInfo', 'TeacherUserController@teacherInfo');//the view of Teacher user

    //LessonTable
    Route::get('/LessonTable', 'LessonTable\LessonTableController@LessonTable');//学院教师课表视图页面
    Route::get('/LessonTeacher', 'LessonTable\LessonTableController@LessonTeacher');//各学院教师名单
    Route::get('/Lesson', 'LessonTable\LessonTableController@Lesson');//各学院教师课表

    //用户管理
//修改信息
    Route::get('/userManage', 'Auth\UserController@userManage');//使用者具体信息
    Route::post('/userManage','Auth\UserController@getUserInfo');
//修改密码
    Route::get('/changePass', 'Auth\UserController@changePass');//修改用户密码页面//使用者密码判断是否为初始密码，以及修改密码页面
    Route::post('/changePass', 'Auth\UserController@SubmitPass');//修改用户密码
/*restructure end*/


    //评价内容管理
    Route::get('/TheoryEvaluationTableView', 'Auth\HomeController@TheoryEvaluationTableView');//理论评价表视图
    Route::get('/PracticeEvaluationTableView', 'Auth\HomeController@PracticeEvaluationTableView');//实践评价表视图
    Route::get('/PhysicalEvaluationTableView', 'Auth\HomeController@PhysicalEvaluationTableView');//体育评价表视图

    //评价表正反面内容获取（编辑填充）
    Route::get('/TheoryEvaluationEdit', 'Auth\HomeController@TheoryEvaluationEdit');//理论评价表正反面内容获取
    Route::get('/PhysicalEvaluationEdit', 'Auth\HomeController@PhysicalEvaluationEdit');//体育评价表正反面内容获取
    Route::get('/PracticeEvaluationEdit', 'Auth\HomeController@PracticeEvaluationEdit');//实践评价表正反面内容获取
    //通过web端填写评价表写入数据库
    Route::any('/DBTheoryEvaluationTable', 'EvaluationController@DBTheoryEvaluationTable');//填写理论评价表的内容
    Route::any('/DBPracticeEvaluationTable', 'EvaluationController@DBPracticeEvaluationTable');//管理员填写实践评价表
    Route::any('/DBPhysicalEvaluationTable', 'EvaluationController@DBPhysicalEvaluationTable');//管理员填写体育评价表



    Route::any('/CreateFrontTheoryEvalTable', 'Auth\HomeController@CreateFrontTheoryEvalTable');//动态创建评价表:正面
    Route::any('/CreateBackTheoryEvalTable', 'Auth\HomeController@CreateBackTheoryEvalTable');//动态创建评价表:正面
    Route::any('/CreateFrontPracticeEvalTable', 'Auth\HomeController@CreateFrontPracticeEvalTable');//动态创建评价表:正面
    Route::any('/CreateBackPracticeEvalTable', 'Auth\HomeController@CreateBackPracticeEvalTable');//动态创建评价表:正面
    Route::any('/CreateFrontPhysicalEvalTable', 'Auth\HomeController@CreateFrontPhysicalEvalTable');//动态创建评价表:正面
    Route::any('/CreateBackPhysicalEvalTable', 'Auth\HomeController@CreateBackPhysicalEvalTable');//动态创建评价表:正面

    Route::any('/CreateEvalBackTable', 'Auth\HomeController@CreateEvalBackTable');//动态创建评价表：背面


    Route::get('/EvaluationContent', 'Auth\HomeController@EvaluationContent');//获取正面、背面评价详情
    Route::get('/EvaluationCheckBox', 'Auth\HomeController@EvaluationCheckBox');//获取正面、背面评价单选和多选的属性

    Route::get('/UpdateEvaluation_Migration', 'Auth\HomeController@UpdateEvaluation_Migration');//获取更新信息
    Route::get('/GetFrontValueTable', 'Auth\HomeController@GetFrontValueTable');//正面的评价项
    Route::get('/GetBackValueTable', 'Auth\HomeController@GetBackValueTable');//背面的评价项




    Route::get('/GetLessonTime', 'Help\HelpController@GetLessonTime');//理论课、实践课 每门课程的课程节次
    Route::get('/GetLessonTimeBylistendate', 'Help\HelpController@GetLessonTimeBylistendate');//理论课、实践课 每门课程的课程节次




    //所有的excel操作
    Route::post('/excel/ImportLesson','ExcelController@ImportLesson');
    Route::get('/excel/NecessaryTaskExport','ExcelController@NecessaryTaskExport');
    Route::get('/excel/EvaluatedExport','ExcelController@EvaluatedExport');
    Route::get('/excel/EvaluatedGroupExport','ExcelController@EvaluatedGroupExport');
    Route::get('/excel/EvaluatedUnitExport','ExcelController@EvaluatedUnitExport');
    Route::get('/excel/EvaluatedPersonExport','ExcelController@EvaluatedPersonExport');

    Route::get('/excel/StaticExport','ExcelController@StaticExport');//督导听课统计情况导出（大组长、校级）
    Route::get('/excel/StaticGroupExport','ExcelController@StaticGroupExport');//督导听课统计情况导出（小组长）
    //export the activity
    Route::get('/activity/excel/ActivityExport','ExcelController@ActivityExport');

    //export the teachers of activity
    Route::get('/activity/excel/teacherExport','ExcelController@teacherExport');

    //import the information of teacher
    Route::post('/excel/ImportTeacher','ExcelController@ImportTeacher');

//    Route::post('/excel/NecessaryTaskImport','ExcelController@NecessaryTaskImport');
    Route::post('/excel/NecessaryTaskImport','ExcelController@NecessaryTaskImportByName');


    //评价统计
    Route::get('/Statistics','StatisticController@StatisticsView');
    Route::get('/GetEvalutedLessonArr','StatisticController@GetEvalutedLessonArr');
    //听课数据统计  返回view
    Route::get('/TheoryStatistics','LessonDataStatisticController@TheoryStatisticsView');
    Route::get('/PracticeStatistics','LessonDataStatisticController@PracticeStatisticsView');
    Route::get('/PhysicalStatistics','LessonDataStatisticController@PhysicalStatisticsView');

    //理论课评价统计函数
    Route::get('/TheoryStatisticsData','LessonDataStatisticController@TheoryStatisticsData');

    //实践课评价统计函数
    Route::get('/PracticeStatisticsData','LessonDataStatisticController@PracticeStatisticsData');

    //体育课评价统计函数
    Route::get('/PhysicalStatisticsData','LessonDataStatisticController@PhysicalStatisticsData');

    //本学年本学期已完成评价课程的所有课程名
    Route::get('/GetEvalutedLessonContent','StatisticController@GetEvalutedLessonContent');
    //本学年本学期已完成评价课程的课程的评价内容
    Route::get('/GroupEvaluationInfo','StatisticController@GroupEvaluationInfo');




    //评价体系修改
    Route::get('/UpdateTheoryView', 'UpdateEvalutionController@UpdateTheoryView');//理论评价表视图
    Route::get('/UpdatePracticeView', 'UpdateEvalutionController@UpdatePracticeView');//实践评价表视图
    Route::get('/UpdatePhysicalView', 'UpdateEvalutionController@UpdatePhysicalView');//体育评价表视图
    //通过web端填写评价表写入数据库
    Route::any('/DBFrontTheoryEvaluationTable', 'EvaluationController@TheoryFrontEvaluationTableToDB');//填写理论评价表的正面内容
    Route::any('/DBBackTheoryEvaluationTable', 'EvaluationController@TheoryBackEvaluationTableToDB');//填写理论评价表的背面内容
    Route::any('/DBFrontPracticeEvaluationTable', 'EvaluationController@PracticeFrontEvaluationTableToDB');//填写理论评价表的正面内容
    Route::any('/DBBackPracticeEvaluationTable', 'EvaluationController@PracticeBackEvaluationTableToDB');//填写理论评价表的正面内容
    Route::any('/DBFrontPhysicalEvaluationTable', 'EvaluationController@PhysicalFrontEvaluationTableToDB');//填写理论评价表的正面内容
    Route::any('/DBBackPhysicalEvaluationTable', 'EvaluationController@PhysicalBackEvaluationTableToDB');//填写理论评价表的正面内容


    //    教师活动系统

    //修改密码
    Route::get('/TeacherChangePass', 'Auth\UserController@TeacherChangePass');//咨询系统的修改密码页面
    Route::post('/TeacherChangePass', 'Auth\UserController@SubmitTeacherPass');//修改用户密码
    //修改信息
    Route::get('/TeacherUserManage', 'Auth\UserController@TeacherUserManage');//咨询系统的使用者具体信息
    Route::post('/TeacherUserManage','Auth\UserController@getTeacherUserInfo');

    Route::group(['namespace'=>'Activity'],function(){
        //普通教师端
        Route::get('/activity/index','ActivityController@index');
        //showing the attended activities with array (teacher)
        Route::get('/activity/index-suv','ActivityController@index1');
        //showing the attended activities with array (supervisor)
        Route::get('/activity/teacher/attend/{flag}','ActivityController@ShowAttendActivities');

        //showing the activities with array (teacher)
        Route::get('/activity/teacher/show/{userId}','ActivityController@ShowTeacherActivities');

        //enrolling the activities  (teacher)
        Route::get('/activity/teacher/enroll','ActivityController@EnrollActivities');

        //canceling the activities (teacher)
        Route::get('/activity/teacher/cancel','ActivityController@CancelActivities');


        //管理员端
        //the view of adding the activities
        Route::get('/activity/modify/','ActivityController@modify');

        //the view of adding the special activities
        Route::get('/activity/modify/{timeflag}','ActivityController@show');
        //showing the activities with array
        Route::get('/activity/show/{timeflag}','ActivityController@ShowActivities');

        //submit the request of adding the activities
        Route::post('/activity/admin/create','ActivityController@create');
        Route::get('/activity/admin/create','ActivityController@modify');
        //submit the request of adding the activities
        Route::post('/activity/admin/change','ActivityController@change');
        Route::get('/activity/admin/change','ActivityController@modify');
        //submit the request of adding the teacher
        Route::post('/activity/admin/addteacher','ActivityController@addteacher');
        Route::get('/activity/admin/addteacher','ActivityController@modify');
        //delete the request of adding the activities
        Route::post('/activity/admin/delete','ActivityController@deleteActivity');
        Route::get('/activity/admin/delete','ActivityController@modify');
        //activate the request of adding the activities
//        Route::get('/activity/admin/activate','ActivityController@activate');
        //get the teacher wants to attend the activities
        Route::get('/activity/admin/attendTeacher','ActivityController@attendTeacher');
        //show the teachers who attend this activity
        Route::get('/activity/admin/Teachers','ActivityController@showTeachers');
        //submit the request of adding the teacher fin_state
        Route::post('/activity/admin/TeachersState','ActivityController@TeachersStateChange');


    });
    //教师咨询系统
    Route::group(['namespace'=>'Consult'],function(){
        //the view of teacher consult
        Route::get('/consult/index','ConsultController@index');
        //the view of supervior consult
        Route::get('/consult/index-suv','ConsultController@index1');
        //post the consult request
        Route::post('/consult/post','ConsultController@storePost');
        Route::get('/consult/post','ConsultController@index');
        //post the consult request(supervior)
        Route::post('/consult/post1','ConsultController@storePost1');
        Route::get('/consult/post1','ConsultController@index1');
        //view the consult history
        Route::get('/consult/ConsultHistory/{userId}','ConsultController@consultHistory');



        //管理员端
        //the view of adding the consult content
        Route::get('/consult/modify/','ConsultController@modify');
        //the view of adding the consult content
        Route::get('/consult/ConsultContent','ConsultController@getContent');

        //submit the request of adding the activities
        Route::post('/consult/admin/create','ConsultController@create');
        Route::get('/consult/admin/create','ConsultController@modify');
        //delete the request of adding the activities
        Route::post('/consult/admin/delete','ConsultController@deleteConsult');
        Route::get('/consult/admin/delete','ConsultController@modify');
        //the view of adjusting the consult content
        Route::get('/consult/adjust','AdjustController@index');

        //finish the work of coordination
        Route::post('/consult/coordinate','ConsultController@StoreCoordination');


        //the data of done/undo
        Route::get('/consult/adjust/{action}','AdjustController@ConsultResult');
        //the data of done/undo
        Route::get('/consult/admin/coordinate','AdjustController@ConsultCoordinate');
    });
    //督导对教师的评价
    Route::group(['namespace'=>'TeachEva'],function(){
        //the view of teacher evaluation
        Route::get('/teachEvaluation/index','TeachEvaluationController@index');
        //the view of teacher evaluation(supervior)
        Route::get('/teachEvaluation/index-suv','TeachEvaluationController@index1');
        //the data of teacher evaluation
        Route::get('/teachEvaluation/evaluationData','TeachEvaluationController@evaluationData');
    });

    Route::group(['namespace'=>'wechat'],function(){
        //评价内容管理 微信端链接
        Route::get('/weixinTheoryEvaluationTableView', 'WechatHomeController@weixinTheoryEvaluationTableView');//理论评价表视图
        Route::get('/weixinPracticeEvaluationTableView', 'WechatHomeController@weixinPracticeEvaluationTableView');//实践评价表视图
        Route::get('/weixinPhysicalEvaluationTableView', 'WechatHomeController@weixinPhysicalEvaluationTableView');//体育评价表视图

        //修改密码 微信端
        Route::get('/weixinChangePass', 'WechatUserController@weixinChangePass');//咨询系统的修改密码页面
        Route::post('/weixinChangePass', 'WechatUserController@SubmitTeacherPass1');//修改用户密码

        //修改信息 微信端
        Route::get('/weixinUserManage', 'WechatUserController@weixinUserManage');//咨询系统的使用者具体信息
        Route::post('/weixinUserManage','WechatUserController@getTeacherUserInfo1');
    });

});


Route::post('/client/login','ClientController@login');
Route::POST('/client/GetLessonArrThe','ClientController@GetLessonArrThe');
Route::POST('/client/GetLessonArrPra','ClientController@GetLessonArrPra');
Route::POST('/client/GetLessonArrPhy','ClientController@GetLessonArrPhy');
Route::post('/client/TheoryEvaluation','ClientController@TheoryEvaluation');
Route::post('/client/PracticeEvaluation','ClientController@PracticeEvaluation');
Route::post('/client/PhysicalEvaluation','ClientController@PhysicalEvaluation');
Route::get('/client/testStr','ClientController@testStr');


Route::get('/weixin/testStr','WeixinController@testStr');