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


Route::group(['middleware' => 'auth'], function () {


    //1、学院、组 督导总人数+关注课程数目+关注课程完成数目+已完成听课数目

    Route::get('/index', 'IndexController@index');//学校级 + 大组长
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
    Route::get('/DeleteNecessaryTask', 'EvaluationController@DeleteNecessaryTask1');//删除必听任务

    Route::get('/AddNecessaryTask', 'EvaluationController@AddNecessaryTask');//增加必听任务
    Route::get('/GetLessonArr', 'EvaluationController@GetLessonArr');//搜索框的课程表
    Route::get('/GetLessonArrTNLN', 'EvaluationController@GetLessonArrTNLN');//增加必听任务的时候，搜索框的课程表
    Route::get('/GetLessonArrThe', 'EvaluationController@GetLessonArrThe');//查询理论课程的时候，搜索框的课程表
    Route::get('/GetLessonArrPra', 'EvaluationController@GetLessonArrPra');//查询实践课程的时候，搜索框的课程表
    Route::get('/GetLessonArrPhy', 'EvaluationController@GetLessonArrPhy');//查询体育课程的时候，搜索框的课程表


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
    Route::get('/GetGroupSupervisorName', 'SupervisorController@GetGroupSupervisorName');//本院督导的姓名
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




    Route::get('/ResetPass', 'SupervisorController@ResetPass');//reset the password

    //supervisor management
    Route::get('/SupervisorInfo', 'SupervisorController@SupervisorInfo');//the view of supervisor management
    Route::post('/ChangeSupervisorInfo', 'SupervisorController@ChangeSupervisorInfo');//change/add the supervisor's information
    Route::get('/ChangeSupervisorInfo', 'SupervisorController@SupervisorInfo');//if method is get, redirect the view SupervisorInfo
    Route::get('/GetSupervisorInfo', 'SupervisorController@Get_SupervisorInfo');//得到督导具体信息



    //Unit responsibility management
    Route::get('/UnitUserManage', 'UnitUserController@UnitUserManage');//the view of Unit user
    Route::get('/GetSpecificUnitInfo', 'UnitUserController@GetSpecificUnitInfo');//get the information of specific Unit user
    Route::get('/GetUnitUserInfo', 'UnitUserController@GetUnitUserInfo');//get the data to fill with table showing all Unit user


    //校级+大组长 ，查看所有的督导信息
    Route::get('/GetAllSupervisorInfo', 'SupervisorController@GetAllSupervisorInfo');//得到所有督导具体信息
    Route::get('/GetBigGroupSupervisorInfo', 'SupervisorController@GetBigGroupSupervisorInfo');//得到大组长所有督导具体信息



    //院级，查看本院所有督导信息
    Route::get('/GetUnitSupervisorInfo', 'SupervisorController@GetUnitSupervisorInfo');
    //小组长，查看本院所有督导信息
    Route::get('/GetGroupSupervisorInfo', 'SupervisorController@GetGroupSupervisorInfo');
    Route::get('/RenewContacts', 'SupervisorController@RenewContacts');//增加督导信息



    Route::post('/ChangeUnitUserInfo', 'SupervisorController@ChangeUnitUserInfo');//改变院级具体信息

    Route::post('/AddSupervisorInfo', 'SupervisorController@Add_SupervisorInfo');//增加督导信息
    Route::get('/AddSupervisorInfo', 'SupervisorController@LessonTable');//增加督导信息


    //评价内容管理
    Route::get('/TheoryEvaluationTableView', 'Auth\HomeController@TheoryEvaluationTableView');//理论评价表视图
    Route::get('/PracticeEvaluationTableView', 'Auth\HomeController@PracticeEvaluationTableView');//实践评价表视图
    Route::get('/PhysicalEvaluationTableView', 'Auth\HomeController@PhysicalEvaluationTableView');//体育评价表视图

    //通过web端填写评价表写入数据库
    Route::any('/DBTheoryFrontEvaluationTable', 'EvaluationController@DBTheoryFrontEvaluationTable');//填写理论评价表的正面内容

    Route::any('/DBPracticeFrontEvaluationTable', 'EvaluationController@DBPracticeFrontEvaluationTable');//管理员填写实践评价表

    Route::any('/DBPhysicalFrontEvaluationTable', 'EvaluationController@DBPhysicalFrontEvaluationTable');//管理员填写体育评价表





    Route::get('/CreateEvalFrontTable', 'Auth\HomeController@CreateEvalFrontTable');//动态创建评价表:正面
    Route::get('/CreateEvalBackTable', 'Auth\HomeController@CreateEvalBackTable');//动态创建评价表：背面

    Route::get('/EvaluationContent', 'Auth\HomeController@EvaluationContent');//获取正面、背面评价详情

    Route::get('/UpdateEvaluation_Migration', 'Auth\HomeController@UpdateEvaluation_Migration');//获取更新信息
    Route::get('/GetFrontValueTable', 'Auth\HomeController@GetFrontValueTable');//正面的评价项
    Route::get('/GetBackValueTable', 'Auth\HomeController@GetBackValueTable');//背面的评价项


    Route::get('/LessonTable', 'LessonTable\LessonTableController@LessonTable');//学院教师课表视图页面
    Route::get('/LessonTeacher', 'LessonTable\LessonTableController@LessonTeacher');//各学院教师名单
    Route::get('/Lesson', 'LessonTable\LessonTableController@Lesson');//各学院教师课表

    Route::get('/GetLessonTime', 'HelpController@GetLessonTime');//理论课、实践课 每门课程的课程节次
    Route::get('/GetLessonTimeBylistendate', 'HelpController@GetLessonTimeBylistendate');//理论课、实践课 每门课程的课程节次




    //所有的excel操作
    Route::post('/excel/ImportLesson','ExcelController@ImportLesson');
    Route::get('/excel/NecessaryTaskExport','ExcelController@NecessaryTaskExport');
    Route::get('/excel/EvaluatedExport','ExcelController@EvaluatedExport');
    Route::get('/excel/EvaluatedGroupExport','ExcelController@EvaluatedGroupExport');
    Route::get('/excel/EvaluatedUnitExport','ExcelController@EvaluatedUnitExport');
    Route::get('/excel/EvaluatedPersonExport','ExcelController@EvaluatedPersonExport');

    Route::get('/excel/StaticExport','ExcelController@StaticExport');//督导听课统计情况导出（大组长、校级）
    Route::get('/excel/StaticGroupExport','ExcelController@StaticGroupExport');//督导听课统计情况导出（小组长）


//    Route::post('/excel/NecessaryTaskImport','ExcelController@NecessaryTaskImport');
    Route::post('/excel/NecessaryTaskImport','ExcelController@NecessaryTaskImportByName');


    //评价统计
    Route::get('/Statistics','StatisticController@StatisticsView');
    Route::get('/GetEvalutedLessonArr','StatisticController@GetEvalutedLessonArr');
    //本学年本学期已完成评价课程的所有课程名
    Route::get('/GetEvalutedLessonContent','StatisticController@GetEvalutedLessonContent');
    //本学年本学期已完成评价课程的课程的评价内容
    Route::get('/GroupEvaluationInfo','StatisticController@GroupEvaluationInfo');

    //督导听课数据统计
    Route::get('/DataStatistics','DataStatisticController@DataStatisticsView');
    Route::get('/TeachTotallyEvaluation','DataStatisticController@TeachTotallyEvaluation');

    //OnePage
    Route::get('/Feature','IndexController@Feature');

    //评价体系修改
    Route::get('/ChangeTheoryView', 'SystemTableChangeController@ChangeTheoryView');//理论评价表视图
    Route::get('/ChangePracticeView', 'SystemTableChangeController@ChangePracticeView');//实践评价表视图
    Route::get('/ChangePhysicalView', 'SystemTableChangeController@ChangePhysicalView');//体育评价表视图


    //    教师活动系统
    Route::group(['namespace'=>'Activity'],function(){
        Route::get('/activity/index','ActivityController@index');
    });
//    教师咨询系统
    Route::group(['namespace'=>'Consult'],function(){
        Route::get('/consult/index','ConsultController@index');
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