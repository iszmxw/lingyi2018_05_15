<?php

use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::any('/', function () {
    echo "你好世界";
});
/***************************框架学习整理资料部分***************************/
Route::get('zeott', 'Tooling\TestController@test');
Route::group(['prefix' => 'study'], function () {
    //Sql基本使用
    Route::group(['prefix' => 'basic'], function () {
        Route::get('ins', 'Study\SqlBasicController@insertDb');//测试SQL基本使用插入数据
        Route::get('sel', 'Study\SqlBasicController@selectDb');//测试SQL基本使用查询数据
        Route::get('upd', 'Study\SqlBasicController@updateDb');//测试SQL基本使用更新数据
        Route::get('del', 'Study\SqlBasicController@deleteDb');//测试SQL基本使用删除数据
        Route::get('state', 'Study\SqlBasicController@statementDb');//测试SQL基本使用与一般数据库语句
        Route::get('trans', 'Study\SqlBasicController@transactionDb');//测试数据库事务运作
    });
    //查询构造器的使用
    Route::group(['prefix' => 'builder'], function () {
        Route::get('getall', 'Study\QueryBuliderController@select_all');//查询构造器，查询所有数据
        Route::get('getfirst', 'Study\QueryBuliderController@select_first');//查询构造器，查询单条数据
        Route::get('getvalue', 'Study\QueryBuliderController@select_value');//查询构造器，查询单条数据的单个值
        Route::get('getpluck', 'Study\QueryBuliderController@select_pluck');//查询构造器，查询单条数据的单个值
        Route::get('count', 'Study\QueryBuliderController@select_count');//总行数
        Route::get('max', 'Study\QueryBuliderController@select_max');//列最大值
        Route::get('min', 'Study\QueryBuliderController@select_min');//列最小值
        Route::get('avg', 'Study\QueryBuliderController@select_avg');//列平均值
        Route::get('sum', 'Study\QueryBuliderController@select_sum');//列总和
        Route::get('column', 'Study\QueryBuliderController@select_column');//查询指定的多个列的值
        Route::get('column2', 'Study\QueryBuliderController@select_column2');//查询指定的多个列的值
        Route::get('distinct', 'Study\QueryBuliderController@select_distinct');//过滤查询结果中的重复值
        Route::get('join', 'Study\QueryBuliderController@select_join');//关联查询
        Route::get('union', 'Study\QueryBuliderController@select_union');//联合查询
        Route::get('where', 'Study\QueryBuliderController@select_where');//子语句
        Route::get('orwhere', 'Study\QueryBuliderController@select_orwhere');//or查询
        Route::get('between', 'Study\QueryBuliderController@select_between');//between,not between语句
        Route::get('in', 'Study\QueryBuliderController@select_in');//between,not between语句
        Route::get('cc', 'Study\QueryBuliderController@select_cc');//whereColumn 对比两个字段的值
        Route::get('gjwhere', 'Study\QueryBuliderController@select_gjwhere');//whereColumn 对比两个字段的值
        Route::get('exists', 'Study\QueryBuliderController@select_exists');//exist 使用select语句作为where查询 与in使用方法雷士
        Route::get('orderby', 'Study\QueryBuliderController@select_orderby');//正常排序,随机排序
        Route::get('groupby', 'Study\QueryBuliderController@select_groupby');//分组
        Route::get('having', 'Study\QueryBuliderController@select_having');//having查询
        Route::get('skip', 'Study\QueryBuliderController@select_skip');//offect,limit的两种用法
        Route::get('when', 'Study\QueryBuliderController@select_when');//带参数的条件语句when
        Route::get('ins', 'Study\QueryBuliderController@insertDB');//插入单条数据,插入多条数据，插入数据并获取ID
        Route::get('update', 'Study\QueryBuliderController@updateDB');//更新数据，字段自增自减，字段自增自减同时更新数据
        Route::get('delete', 'Study\QueryBuliderController@deleteDB');//删除数据
        Route::get('truncate', 'Study\QueryBuliderController@truncateDB');//清空数据,自增ID改为0，慎用
        Route::get('paginate', 'Study\QueryBuliderController@paginateDB');//清空数据,自增ID改为0，慎用
    });

    //ORM的使用
    Route::group(['prefix' => 'ormstudy'], function () {
        Route::get('getall', 'Study\OrmStudyController@getAll');//获取列表数据
        Route::get('getlist', 'Study\OrmStudyController@getList');//获取多条数据
        Route::get('getone', 'Study\OrmStudyController@getOne');//获取单条数据
        Route::get('getpage', 'Study\OrmStudyController@getPage');//获取分页
        Route::get('inssave', 'Study\OrmStudyController@ins_save');//插入或更新
        Route::get('update', 'Study\OrmStudyController@do_update');//批量更新
        Route::get('delete', 'Study\OrmStudyController@do_delete');//删除的几种方式
        Route::get('oneone', 'Study\OrmStudyController@one_one');//ORM关联一对一的关系
        Route::get('onemany', 'Study\OrmStudyController@one_many');//ORM关联一对多的关系
        Route::get('manymany', 'Study\OrmStudyController@many_many');//ORM关联多对多的关系
        Route::get('onethroughmany', 'Study\OrmStudyController@one_through_many');//ORM远程一对多的关系
        Route::get('ormsearch', 'Study\OrmStudyController@orm_search');
    });
});
/***************************框架学习整理资料部分**************************/

/***********************程序管理系统*********************/
Route::group(['prefix' => 'tooling'], function () {
    Route::get('/', 'Tooling\SystemController@dashboard')->middleware('ToolingCheck');//系统首页
    Route::get('quit', 'Tooling\SystemController@quit');//退出系统

    //系统管理组(功能只有超级管理员能用)
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('account_add', 'Tooling\SystemController@account_add')->middleware('ToolingCheck');//添加账号路由
        Route::get('account_list', 'Tooling\SystemController@account_list')->middleware('ToolingCheck');//账号列表路由
        Route::get('operation_log', 'Tooling\SystemController@operation_log_list')->middleware('ToolingCheck');//所有操作记录
        Route::get('login_log', 'Tooling\SystemController@login_log_list')->middleware('ToolingCheck');//所有登录记录
    });

    //个人中心组
    Route::group(['prefix' => 'personal'], function () {
        Route::get('password_edit', 'Tooling\PersonalController@password_edit')->middleware('ToolingCheck');//修改密码路由
        Route::get('operation_log', 'Tooling\PersonalController@operation_log_list')->middleware('ToolingCheck');//我的操作记录
        Route::get('login_log', 'Tooling\PersonalController@login_log_list')->middleware('ToolingCheck');//所有登录记录
    });

    //功能模块组
    Route::group(['prefix' => 'module'], function () {
        Route::get('module_add', 'Tooling\ModuleController@module_add')->middleware('ToolingCheck');//修改密码路由
        Route::get('module_list', 'Tooling\ModuleController@module_list')->middleware('ToolingCheck');//修改密码路由
    });

    //节点管理组
    Route::group(['prefix' => 'node'], function () {
        Route::get('node_add', 'Tooling\NodeController@node_add')->middleware('ToolingCheck');//修改密码路由
        Route::get('node_list', 'Tooling\NodeController@node_list')->middleware('ToolingCheck');//修改密码路由
    });

    //程序管理组
    Route::group(['prefix' => 'program'], function () {
        Route::get('program_add', 'Tooling\ProgramController@program_add')->middleware('ToolingCheck');//修改密码路由
        Route::get('program_list', 'Tooling\ProgramController@program_list')->middleware('ToolingCheck');//修改密码路由
        Route::get('menu_list', 'Tooling\ProgramController@menu_list')->middleware('ToolingCheck');//程序菜单路由
        Route::get('package_add', 'Tooling\ProgramController@package_add')->middleware('ToolingCheck');//添加配套路由
        Route::get('package_list', 'Tooling\ProgramController@package_list')->middleware('ToolingCheck');//配套列表路由
    });

    //登录页面组
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', 'Tooling\LoginController@display')->middleware('ToolingCheck');//登录页面路由
        Route::get('captcha/{tmp}', 'Tooling\LoginController@captcha');//验证码路由
    });

    Route::group(['prefix' => 'ajax'], function () {
        Route::post('checklogin', 'Tooling\LoginController@checkLogin')->middleware('ToolingCheckAjax');//提交登录数据


        Route::post('account_add_check', 'Tooling\SystemController@account_add_check')->middleware('ToolingCheckAjax');//提交增加账号数据
        Route::post('account_edit', 'Tooling\SystemController@account_edit')->middleware('ToolingCheckAjax');//获取账号数据并编辑
        Route::post('account_edit_check', 'Tooling\SystemController@account_edit_check')->middleware('ToolingCheckAjax');//提交编辑账号数据
        Route::post('account_lock', 'Tooling\SystemController@account_lock')->middleware('ToolingCheckAjax');//提交编辑账号数据;


        Route::post('password_edit_check', 'Tooling\PersonalController@password_edit_check')->middleware('ToolingCheckAjax');//修改密码


        Route::post('node_add_check', 'Tooling\NodeController@node_add_check')->middleware('ToolingCheckAjax');//提交节点数据
        Route::post('node_edit', 'Tooling\NodeController@node_edit')->middleware('ToolingCheckAjax');//获取节点数据并编辑
        Route::post('node_edit_check', 'Tooling\NodeController@node_edit_check')->middleware('ToolingCheckAjax');//检测编辑节点数据


        Route::post('module_add_check', 'Tooling\ModuleController@module_add_check')->middleware('ToolingCheckAjax');//提交功能模块数据
        Route::post('module_edit', 'Tooling\ModuleController@module_edit')->middleware('ToolingCheckAjax');//获取功能模块数据并提交
        Route::post('module_edit_check', 'Tooling\ModuleController@module_edit_check')->middleware('ToolingCheckAjax');


        Route::post('program_add_check', 'Tooling\ProgramController@program_add_check')->middleware('ToolingCheckAjax');//提交功能模块数据
        Route::post('program_parents_node', 'Tooling\ProgramController@program_parents_node')->middleware('ToolingCheckAjax');//获取上级程序ID
        Route::post('program_edit', 'Tooling\ProgramController@program_edit')->middleware('ToolingCheckAjax');//获取功能模块数据并提交
        Route::post('program_edit_check', 'Tooling\ProgramController@program_edit_check')->middleware('ToolingCheckAjax');//获取功能模块数据并提交


        Route::post('menu_add', 'Tooling\ProgramController@menu_add')->middleware('ToolingCheckAjax');//获取菜单添加页面
        Route::post('menu_second_get', 'Tooling\ProgramController@menu_second_get')->middleware('ToolingCheckAjax');//获取二级菜单
        Route::post('menu_add_check', 'Tooling\ProgramController@menu_add_check')->middleware('ToolingCheckAjax');//获取添加菜单数据并提交
        Route::post('menu_edit', 'Tooling\ProgramController@menu_edit')->middleware('ToolingCheckAjax');//获取菜单编辑页面
        Route::post('menu_edit_check', 'Tooling\ProgramController@menu_edit_check')->middleware('ToolingCheckAjax');//获取编辑菜单数据并提交
        Route::post('menu_edit_displayorder', 'Tooling\ProgramController@menu_edit_displayorder')->middleware('ToolingCheckAjax');//获取编辑菜单数据并提交

        Route::post('package_add_check', 'Tooling\ProgramController@package_add_check')->middleware('ToolingCheckAjax');//获取编辑菜单数据并提交
        Route::post('package_edit', 'Tooling\ProgramController@package_edit')->middleware('ToolingCheckAjax');//获取程序套餐编辑页面
        Route::post('package_edit_check', 'Tooling\ProgramController@package_edit_check')->middleware('ToolingCheckAjax');//获取编辑套餐数据并提交


        Route::post('node_delete', 'Tooling\NodeController@node_delete')->middleware('ToolingCheckAjax');//软删除节点
        Route::post('node_remove', 'Tooling\NodeController@node_remove')->middleware('ToolingCheckAjax');//软删除节点


        Route::post('module_delete', 'Tooling\ModuleController@module_delete')->middleware('ToolingCheckAjax');//软删除模块
        Route::post('module_remove', 'Tooling\ModuleController@module_remove')->middleware('ToolingCheckAjax');//硬删除模块


        Route::post('program_delete', 'Tooling\ProgramController@program_delete')->middleware('ToolingCheckAjax');//软删除程序
        Route::post('program_remove', 'Tooling\ProgramController@program_remove')->middleware('ToolingCheckAjax');//硬删除程序


        Route::post('menu_delete', 'Tooling\ProgramController@menu_delete')->middleware('ToolingCheckAjax');//软删除菜单
        Route::post('menu_remove', 'Tooling\ProgramController@menu_remove')->middleware('ToolingCheckAjax');//硬删除菜单


        Route::post('package_delete', 'Tooling\ProgramController@package_delete')->middleware('ToolingCheckAjax');//软删除套餐
        Route::post('package_remove', 'Tooling\ProgramController@package_remove')->middleware('ToolingCheckAjax');//硬删除套餐
    });
});
/********************程序管理系统*************************/

/**********************零壹管理系统*********************/
Route::group(['prefix' => 'zerone'], function () {

    //登录页面组
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', 'Zerone\LoginController@display')->middleware('ZeroneCheck');//登录页面路由
        Route::get('captcha/{tmp}', 'Zerone\LoginController@captcha');               //验证码路由
    });

    Route::get('/', 'Zerone\DashboardController@display')->middleware('ZeroneCheck');//系统首页
    Route::get('quit', 'Zerone\DashboardController@quit');                            //退出系统

    //系统管理分组
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('warzone', 'Zerone\DashboardController@warzone')->middleware('ZeroneCheck');            //战区管理展示
        Route::get('structure', 'Zerone\DashboardController@structure')->middleware('ZeroneCheck');        //人员结构
        Route::get('operation_log', 'Zerone\DashboardController@operation_log')->middleware('ZeroneCheck');//所有操作记录
        Route::get('login_log', 'Zerone\DashboardController@login_log')->middleware('ZeroneCheck');        //所有登录记录
    });

    //个人中心组
    Route::group(['prefix' => 'personal'], function () {
        Route::get('/', 'Zerone\PersonalController@display')->middleware('ZeroneCheck');                   //个人资料
        Route::get('password_edit', 'Zerone\PersonalController@password_edit')->middleware('ZeroneCheck'); //登录密码修改
        Route::get('safe_password', 'Zerone\PersonalController@safe_password')->middleware('ZeroneCheck'); //安全密码设置
        Route::get('operation_log', 'Zerone\PersonalController@operation_log')->middleware('ZeroneCheck'); //我的操作日志
        Route::get('login_log', 'Zerone\PersonalController@login_log')->middleware('ZeroneCheck');         //我的登录日志
    });

    //权限角色组
    Route::group(['prefix' => 'role'], function () {
        Route::get('role_add', 'Zerone\RoleController@role_add')->middleware('ZeroneCheck');               //添加权限角色
        Route::get('role_list', 'Zerone\RoleController@role_list')->middleware('ZeroneCheck');             //权限角色列表
    });

    //下级人员权限组
    Route::group(['prefix' => 'subordinate'], function () {
        Route::get('subordinate_add', 'Zerone\SubordinateController@subordinate_add')->middleware('ZeroneCheck');            //添加下级人员
        Route::get('subordinate_list', 'Zerone\SubordinateController@subordinate_list')->middleware('ZeroneCheck');          //下级人员列表
        Route::get('subordinate_structure', 'Zerone\SubordinateController@subordinate_structure')->middleware('ZeroneCheck');//下级人员结构
    });

    //分公司管理
    Route::group(['prefix' => 'agent'], function () {
        Route::get('agent_examinelist', 'Zerone\AgentController@agent_examinelist')->middleware('ZeroneCheck');//分公司审核列表
        Route::get('agent_add', 'Zerone\AgentController@agent_add')->middleware('ZeroneCheck');                //添加分公司
        Route::get('agent_list', 'Zerone\AgentController@agent_list')->middleware('ZeroneCheck');              //分公司列表
        Route::get('agent_structure', 'Zerone\AgentController@agent_structure')->middleware('ZeroneCheck');    //分公司人员架构
        Route::get('agent_program', 'Zerone\AgentController@agent_program')->middleware('ZeroneCheck');        //分公司程序管理
        Route::get('agent_fansmanage', 'Zerone\AgentController@agent_fansmanage')->middleware('ZeroneCheck');  //分公司商户划拨
    });
    //商户管理
    Route::group(['prefix' => 'fansmanage'], function () {
        Route::get('fansmanage_add', 'Zerone\FansmanageController@fansmanage_add')->middleware('ZeroneCheck');                //添加商户
        Route::get('fansmanage_examinelist', 'Zerone\FansmanageController@fansmanage_examinelist')->middleware('ZeroneCheck');//商户审核列表
        Route::get('fansmanage_list', 'Zerone\FansmanageController@fansmanage_list')->middleware('ZeroneCheck');              //商户列表
        Route::get('fansmanage_structure', 'Zerone\FansmanageController@fansmanage_structure')->middleware('ZeroneCheck');    //商户店铺架构
        Route::get('fansmanage_program', 'Zerone\FansmanageController@fansmanage_program')->middleware('ZeroneCheck');        //商户程序管理
        Route::get('fansmanage_store', 'Zerone\FansmanageController@fansmanage_store')->middleware('ZeroneCheck');            //商户划拨管理
    });

    //支付审核
    Route::group(['prefix' => 'paysetting'], function () {
        Route::get('payconfig', 'Zerone\PaysettingController@payconfig')->middleware('ZeroneCheck');                    //收款信息审核
        Route::get('shengpay', 'Zerone\PaysettingController@shengpay')->middleware('ZeroneCheck');                      //pos终端号审核
    });


    //异步提交数据组
    Route::group(['prefix' => 'ajax'], function () {
        Route::post('login_check', 'Zerone\LoginController@login_check')->middleware('ZeroneCheckAjax');//提交登录数据

        //系统管理
        Route::post('warzone_add', 'Zerone\DashboardController@warzone_add')->middleware('ZeroneCheckAjax');//战区管理编辑战区
        Route::post('warzone_add_check', 'Zerone\DashboardController@warzone_add_check')->middleware('ZeroneCheckAjax');//战区管理编辑战区
        Route::post('warzone_delete', 'Zerone\DashboardController@warzone_delete')->middleware('ZeroneCheckAjax');//战区管理去确认删除战区
        Route::post('warzone_delete_confirm', 'Zerone\DashboardController@warzone_delete_confirm')->middleware('ZeroneCheckAjax');//战区管理删除战区弹出框
        Route::post('warzone_edit', 'Zerone\DashboardController@warzone_edit')->middleware('ZeroneCheckAjax');//战区管理编辑战区
        Route::post('warzone_edit_check', 'Zerone\DashboardController@warzone_edit_check')->middleware('ZeroneCheckAjax');//战区管理编辑战区

        //个人中心
        Route::post('personal_edit_check', 'Zerone\PersonalController@personal_edit_check')->middleware('ZeroneCheckAjax');//修改个人信息功能提交
        Route::post('password_edit_check', 'Zerone\PersonalController@password_edit_check')->middleware('ZeroneCheckAjax');//修改密码功能提交
        Route::post('safe_password_edit_check', 'Zerone\PersonalController@safe_password_edit_check')->middleware('ZeroneCheckAjax');//修改密码功能提交


        //下级管理
        Route::post('role_add_check', 'Zerone\RoleController@role_add_check')->middleware('ZeroneCheckAjax');//提交添加权限角色数据
        Route::post('role_edit', 'Zerone\RoleController@role_edit')->middleware('ZeroneCheckAjax');//编辑权限角色弹出框
        Route::post('role_edit_check', 'Zerone\RoleController@role_edit_check')->middleware('ZeroneCheckAjax');//提交编辑权限角色数据
        Route::post('role_delete_comfirm', 'Zerone\RoleController@role_delete_comfirm');//删除权限角色弹出安全密码框
        Route::post('role_delete', 'Zerone\RoleController@role_delete')->middleware('ZeroneCheckAjax');//删除权限角色弹出安全密码框

        Route::post('subordinate_add_check', 'Zerone\SubordinateController@subordinate_add_check')->middleware('ZeroneCheckAjax');//添加下级人员数据提交
        Route::post('subordinate_edit', 'Zerone\SubordinateController@subordinate_edit')->middleware('ZeroneCheckAjax');//编辑下级人员弹出框
        Route::post('subordinate_edit_check', 'Zerone\SubordinateController@subordinate_edit_check')->middleware('ZeroneCheckAjax');//提交编辑下级人员数据提交
        Route::post('subordinate_lock_confirm', 'Zerone\SubordinateController@subordinate_lock_confirm')->middleware('ZeroneCheckAjax');//冻结下级人员安全密码输入框
        Route::post('subordinate_lock', 'Zerone\SubordinateController@subordinate_lock')->middleware('ZeroneCheckAjax');//提交冻结下级人员数据
        Route::post('subordinate_authorize', 'Zerone\SubordinateController@subordinate_authorize')->middleware('ZeroneCheckAjax');//下级人员授权管理弹出框
        Route::post('subordinate_authorize_check', 'Zerone\SubordinateController@subordinate_authorize_check')->middleware('ZeroneCheckAjax');//下级人员授权管理弹出框
        Route::post('subordinate_delete_confirm', 'Zerone\SubordinateController@subordinate_delete_confirm')->middleware('ZeroneCheckAjax');//删除下级人员安全密码输入框

        Route::post('quick_rule', 'Zerone\SubordinateController@quick_rule')->middleware('ZeroneCheckAjax');//添加下级人员快速授权
        Route::post('selected_rule', 'Zerone\SubordinateController@selected_rule')->middleware('ZeroneCheckAjax');//下级人员已经选中的权限


        //分公司管理
        Route::post('agent_examine', 'Zerone\AgentController@agent_examine')->middleware('ZeroneCheckAjax');//分公司审核页面显示
        Route::post('agent_examine_check', 'Zerone\AgentController@agent_examine_check')->middleware('ZeroneCheckAjax');//分公司审核数据提交
        Route::post('agent_add_check', 'Zerone\AgentController@agent_add_check')->middleware('ZeroneCheckAjax');//提交编辑参数设置
        Route::post('agent_list_edit', 'Zerone\AgentController@agent_list_edit')->middleware('ZeroneCheckAjax');//分公司编辑显示页面
        Route::post('agent_list_edit_check', 'Zerone\AgentController@agent_list_edit_check')->middleware('ZeroneCheckAjax');//分公司编辑数据提交
        Route::post('agent_list_lock', 'Zerone\AgentController@agent_list_lock')->middleware('ZeroneCheckAjax');//分公司冻结显示页面
        Route::post('agent_list_lock_check', 'Zerone\AgentController@agent_list_lock_check')->middleware('ZeroneCheckAjax');//分公司冻结提交功能
        Route::post('agent_assets', 'Zerone\AgentController@agent_assets')->middleware('ZeroneCheckAjax');//分公司程序管理划入划出显示页面
        Route::post('agent_assets_check', 'Zerone\AgentController@agent_assets_check')->middleware('ZeroneCheckAjax');//分公司程序管理划入数据提交
        Route::post('agent_fansmanage_add', 'Zerone\AgentController@agent_fansmanage_add')->middleware('ZeroneCheckAjax');//商户划拨管理-商户划入归属
        Route::post('agent_fansmanage_add_check', 'Zerone\AgentController@agent_fansmanage_add_check')->middleware('ZeroneCheckAjax');//商户划拨管理-商户划入归属功能提交
        Route::post('agent_fansmanage_draw', 'Zerone\AgentController@agent_fansmanage_draw')->middleware('ZeroneCheckAjax');//商户划拨管理-商户划出归属
        Route::post('agent_fansmanage_draw_check', 'Zerone\AgentController@agent_fansmanage_draw_check')->middleware('ZeroneCheckAjax');//商户划拨管理-商户划出归属功能提交


        //商户管理
        Route::post('fansmanage_examine', 'Zerone\FansmanageController@fansmanage_examine')->middleware('ZeroneCheckAjax');//商户审核页面显示
        Route::post('fansmanage_examine_check', 'Zerone\FansmanageController@fansmanage_examine_check')->middleware('ZeroneCheckAjax');//商户审核提交数据
        Route::post('fansmanage_add_check', 'Zerone\FansmanageController@fansmanage_add_check')->middleware('ZeroneCheckAjax');//商户申请提交编辑参数设置
        Route::post('fansmanage_list_edit', 'Zerone\FansmanageController@fansmanage_list_edit')->middleware('ZeroneCheckAjax');//商户编辑页面显示
        Route::post('fansmanage_list_edit_check', 'Zerone\FansmanageController@fansmanage_list_edit_check')->middleware('ZeroneCheckAjax');//商户编辑数据提交
        Route::post('fansmanage_list_lock', 'Zerone\FansmanageController@fansmanage_list_lock')->middleware('ZeroneCheckAjax');//商户冻结页面显示
        Route::post('fansmanage_list_lock_check', 'Zerone\FansmanageController@fansmanage_list_lock_check')->middleware('ZeroneCheckAjax');//商户冻结数据提交
        Route::post('fansmanage_assets', 'Zerone\FansmanageController@fansmanage_assets')->middleware('ZeroneCheckAjax');//商户程序管理划入划出显示页面
        Route::post('fansmanage_assets_check', 'Zerone\FansmanageController@fansmanage_assets_check')->middleware('ZeroneCheckAjax');//商户程序管理划入数据提交
        Route::post('fansmanage_store_add', 'Zerone\FansmanageController@fansmanage_store_add')->middleware('ZeroneCheckAjax');//商户店铺管理划入页面显示
        Route::post('fansmanage_store_add_check', 'Zerone\FansmanageController@fansmanage_store_add_check')->middleware('ZeroneCheckAjax');//商户店铺管理划入数据提交
        Route::post('fansmanage_store_draw', 'Zerone\FansmanageController@fansmanage_store_draw')->middleware('ZeroneCheckAjax');//商户店铺管理划出页面显示
        Route::post('fansmanage_store_draw_check', 'Zerone\FansmanageController@fansmanage_store_draw_check')->middleware('ZeroneCheckAjax');//商户店铺管理划出数据提交

        //支付审核
        Route::post('payconfig_apply', 'Zerone\PaysettingController@payconfig_apply')->middleware('ZeroneCheckAjax');            //付款信息ajax显示
        Route::post('payconfig_apply_check', 'Zerone\PaysettingController@payconfig_apply_check')->middleware('ZeroneCheckAjax');//付款信息功能提交
        Route::post('payconfig_type', 'Zerone\PaysettingController@payconfig_type')->middleware('ZeroneCheckAjax');              //店铺到款状态

        Route::post('shengpay_apply', 'Zerone\PaysettingController@shengpay_apply')->middleware('ZeroneCheckAjax');            //终端号审核ajax显示
        Route::post('shengpay_apply_check', 'Zerone\PaysettingController@shengpay_apply_check')->middleware('ZeroneCheckAjax');//终端号审核功能提交

    });
});
/********************零壹管理系统*************************/


/**********************分公司管理系统*********************/
Route::group(['prefix' => 'agent'], function () {

    //登录页面组
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', 'Agent\LoginController@display')->middleware('AgentCheck');//登录页面路由
        Route::get('captcha/{tmp}', 'Agent\LoginController@captcha');//验证码路由
    });


    Route::get('/', 'Agent\SystemController@display')->middleware('AgentCheck');//系统首页
    Route::get('switch_status', 'Agent\SystemController@switch_status')->middleware('AgentCheck');//超级管理员切换分公司
    Route::get('quit', 'Agent\SystemController@quit');//退出系统

    //系统管理分组
    Route::group(['prefix' => 'system'], function () {
        Route::post('select_agent', 'Agent\SystemController@select_agent')->middleware('AgentCheck');//超级管理员选择登入的分公司
        Route::get('agent_info', 'Agent\SystemController@agent_info')->middleware('AgentCheck');//分公司信息设置
        Route::get('agent_structure', 'Agent\SystemController@agent_structure')->middleware('AgentCheck');//分公司人员结构
        Route::get('operationlog', 'Agent\SystemController@operationlog')->middleware('AgentCheck');//操作日志
        Route::get('loginlog', 'Agent\SystemController@loginlog')->middleware('AgentCheck');//登录日志
    });
    //个人信息分组
    Route::group(['prefix' => 'personal'], function () {
        Route::get('account_info', 'Agent\PersonalController@account_info')->middleware('AgentCheck');//个人信息修改
        Route::get('safe_password', 'Agent\PersonalController@safe_password')->middleware('AgentCheck');//安全密码修改
        Route::get('password', 'Agent\PersonalController@password')->middleware('AgentCheck');//登入密码修改
    });

    //系统资产管理
    Route::group(['prefix' => 'program'], function () {
        Route::get('program_list', 'Agent\ProgramController@program_list')->middleware('AgentCheck');//资产
        Route::get('program_log', 'Agent\ProgramController@program_log')->middleware('AgentCheck');//权限角色列表
    });

    //下辖商户管理
    Route::group(['prefix' => 'fansmanage'], function () {
        Route::get('fansmanage_register', 'Agent\FansmanageController@fansmanage_register')->middleware('AgentCheck');//商户注册列表
        Route::get('fansmanage_list', 'Agent\FansmanageController@fansmanage_list')->middleware('AgentCheck');//商户列表
        Route::get('fansmanage_structure', 'Agent\FansmanageController@fansmanage_structure')->middleware('AgentCheck');//店铺结构
        Route::get('fansmanage_program', 'Agent\FansmanageController@fansmanage_program')->middleware('AgentCheck');//程序划拨
    });

    //异步提交数据组
    Route::group(['prefix' => 'ajax'], function () {
        Route::post('login_check', 'Agent\LoginController@login_check')->middleware('AgentCheckAjax');//提交登录数据
        Route::post('agent_info_check', 'Agent\SystemController@agent_info_check')->middleware('AgentCheckAjax');//提交公司信息修改

        Route::post('account_info_check', 'Agent\PersonalController@account_info_check')->middleware('AgentCheckAjax');//个人信息修改
        Route::post('safe_password_check', 'Agent\PersonalController@safe_password_check')->middleware('AgentCheckAjax');//安全密码设置
        Route::post('password_check', 'Agent\PersonalController@password_check')->middleware('AgentCheckAjax');//登入密码修改

        Route::post('fansmanage_assets', 'Agent\FansmanageController@fansmanage_assets')->middleware('AgentCheckAjax');//程序划入划出显示页面
        Route::post('fansmanage_assets_check', 'Agent\FansmanageController@fansmanage_assets_check')->middleware('AgentCheckAjax');//程序划入划出功能提交
    });
});
/********************分公司管理系统*************************/

/**********************粉丝管理系统*********************/
Route::group(['prefix' => 'fansmanage'], function () {

    Route::get('/test', 'Fansmanage\TestController@test');
    Route::get('/test2', 'Fansmanage\TestController@test2');


    //登录页面组
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', 'Fansmanage\LoginController@display')->middleware('FansmanageCheck');                           //登录页面路由
        Route::get('captcha/{tmp}', 'Fansmanage\LoginController@captcha');                                              //验证码路由
    });

    //切换公众号粉丝平台

    //系统首页
    Route::get('/', 'Fansmanage\ShopController@display')->middleware('FansmanageCheck');
    //超级管理员切换分公司
    Route::get('switch_status', 'Fansmanage\ShopController@switch_status')->middleware('FansmanageCheck');
    //退出系统
    Route::get('quit', 'Fansmanage\ShopController@quit');
    //超级管理员选择登入的分公司
    Route::post('select_shop', 'Fansmanage\ShopController@select_shop')->middleware('FansmanageCheck');
    //操作日记
    Route::get('operation_log', 'Fansmanage\ShopController@operation_log')->middleware('FansmanageCheck');
    //登入日记
    Route::get('login_log', 'Fansmanage\ShopController@login_log')->middleware('FansmanageCheck');


    //账号中心
    Route::group(['prefix' => 'account'], function () {
        Route::get('profile', 'Fansmanage\AccountController@profile')->middleware('FansmanageCheck');                   //账号信息
        Route::get('password', 'Fansmanage\AccountController@password')->middleware('FansmanageCheck');                 //登入密码修改
        Route::get('safe_password', 'Fansmanage\AccountController@safe_password')->middleware('FansmanageCheck');       //安全密码设置
        Route::get('message_setting', 'Fansmanage\AccountController@message_setting')->middleware('FansmanageCheck');   //消息推送设置
    });


    //公众号管理
    Route::group(['prefix' => 'api'], function () {
        Route::get('store_auth', 'Fansmanage\ApiController@store_auth')->middleware('FansmanageCheck');                 //公众号管理
        Route::get('material_image', 'Fansmanage\ApiController@material_image')->middleware('FansmanageCheck');         //图片素材列表


        Route::get('material_article', 'Fansmanage\ApiController@material_article')->middleware('FansmanageCheck');     //图文素材列表
        Route::get('material_article_add', 'Fansmanage\ApiController@material_article_add')->middleware('FansmanageCheck');//编辑素材列表
        Route::get('material_articles_add', 'Fansmanage\ApiController@material_articles_add')->middleware('FansmanageCheck');//添加多条图文
        Route::get('material_article_edit', 'Fansmanage\ApiController@material_article_edit')->middleware('FansmanageCheck');//编辑单条图文
        Route::get('material_articles_edit', 'Fansmanage\ApiController@material_articles_edit')->middleware('FansmanageCheck');//编辑多条图文
    });

    //公众号管理--消息管理
    Route::group(['prefix' => 'message'], function () {
        Route::get('auto_reply', 'Fansmanage\MessageController@auto_reply')->middleware('FansmanageCheck');//关键词自动回复
        Route::get('subscribe_reply', 'Fansmanage\MessageController@subscribe_reply')->middleware('FansmanageCheck');//关注事件自动回复
        Route::get('default_reply', 'Fansmanage\MessageController@default_reply')->middleware('FansmanageCheck');//默认回复
    });


    //公众号管理--菜单管理
    Route::group(['prefix' => 'wechatmenu'], function () {
        Route::get('defined_menu', 'Fansmanage\WechatmenuController@defined_menu')->middleware('FansmanageCheck');//自定义菜单管理页面
        Route::get('conditional_menu', 'Fansmanage\WechatmenuController@conditional_menu')->middleware('FansmanageCheck');//个性化义菜单管理页面
    });


    //用户管理
    Route::group(['prefix' => 'user'], function () {
        Route::get('user_tag', 'Fansmanage\UserController@user_tag')->middleware('FansmanageCheck');                    //粉丝标签管理
//        Route::get('user_list', 'Fansmanage\UserController@user_list')->middleware('FansmanageCheck');
        //粉丝用户管理
        Route::get('user_list', 'Fansmanage\UserController@user_list')->middleware('FansmanageCheck');                              //粉丝搜索
        Route::post('user_search', 'Fansmanage\UserController@user_list_search')->middleware('FansmanageCheck');                    //粉丝用户管理
//        Route::get('user_timeline', 'Fansmanage\UserController@user_timeline')->middleware('FansmanageCheck');          //粉丝用户足迹
    });

    //支付设置
    Route::group(['prefix' => 'paysetting'], function () {
        Route::get('wechat_setting', 'Fansmanage\PaysettingController@wechat_setting')->middleware('FansmanageCheck');      //微信支付设置
    });

    //总分店管理
    Route::group(['prefix' => 'store'], function () {
        Route::get('store_create', 'Fansmanage\StoreController@store_create')->middleware('FansmanageCheck');            //创建总分店
        Route::get('store_list', 'Fansmanage\StoreController@store_list')->middleware('FansmanageCheck');                //总分店管理
    });


    //异步提交数据组
    Route::group(['prefix' => 'ajax'], function () {

        Route::post('login_check', 'Fansmanage\LoginController@login_check')->middleware('FansmanageCheckAjax');             //提交登录数据

        //账号中心
        Route::post('profile_check', 'Fansmanage\AccountController@profile_check')->middleware('FansmanageCheckAjax');       //提交登录数据
        Route::post('safe_password_check', 'Fansmanage\AccountController@safe_password_check')->middleware('FansmanageCheckAjax');//安全密码数据提交
        Route::post('password_check', 'Fansmanage\AccountController@password_check')->middleware('FansmanageCheckAjax');     //安全密码数据提交

        //公众号管理--图文素材
        Route::post('meterial_image_upload', 'Fansmanage\ApiController@meterial_image_upload')->middleware('FansmanageCheckAjax');//上传图片素材
        Route::post('meterial_image_upload_check', 'Fansmanage\ApiController@meterial_image_upload_check')->middleware('FansmanageCheckAjax');//上传图片素材
        Route::post('material_image_delete_comfirm', 'Fansmanage\ApiController@material_image_delete_comfirm')->middleware('FansmanageCheckAjax');//删除图片素材弹窗
        Route::post('material_image_delete_check', 'Fansmanage\ApiController@material_image_delete_check')->middleware('FansmanageCheckAjax');//检测删除图片素材数据
        Route::post('material_image_select', 'Fansmanage\ApiController@material_image_select')->middleware('FansmanageCheckAjax');//弹出图片选择框
        Route::post('material_article_add_check', 'Fansmanage\ApiController@material_article_add_check')->middleware('FansmanageCheckAjax');//添加单条图文检测
        Route::post('material_articles_add_check', 'Fansmanage\ApiController@material_articles_add_check')->middleware('FansmanageCheckAjax');//添加多条图文检测
        Route::post('material_article_delete_comfirm', 'Fansmanage\ApiController@material_article_delete_comfirm')->middleware('FansmanageCheckAjax');//删除图文ajax显示
        Route::post('material_article_delete_check', 'Fansmanage\ApiController@material_article_delete_check')->middleware('FansmanageCheckAjax');//删除图文功能提交
        Route::post('material_article_edit_check', 'Fansmanage\ApiController@material_article_edit_check')->middleware('FansmanageCheckAjax');//编辑图文ajax显示
        Route::post('material_articles_edit_check', 'Fansmanage\ApiController@material_articles_edit_check')->middleware('FansmanageCheckAjax');//编辑图文功能提交


        //公众号管理--消息管理
        Route::post('auto_reply_add', 'Fansmanage\MessageController@auto_reply_add')->middleware('FansmanageCheckAjax');//添加关键字ajax显示
        Route::post('auto_reply_add_check', 'Fansmanage\MessageController@auto_reply_add_check')->middleware('FansmanageCheckAjax');//添加关键字功能提交
        Route::post('auto_reply_edit_text', 'Fansmanage\MessageController@auto_reply_edit_text')->middleware('FansmanageCheckAjax');//检测自动回复文章ajax显示
        Route::post('auto_reply_edit_text_check', 'Fansmanage\MessageController@auto_reply_edit_text_check')->middleware('FansmanageCheckAjax');//检测自动回复文章数据提交
        Route::post('auto_reply_edit_image', 'Fansmanage\MessageController@auto_reply_edit_image')->middleware('FansmanageCheckAjax');//检测自动回复图片素材ajax显示
        Route::post('auto_reply_edit_image_check', 'Fansmanage\MessageController@auto_reply_edit_image_check')->middleware('FansmanageCheckAjax');//检测自动回复图片素材数据提交
        Route::post('auto_reply_edit_article', 'Fansmanage\MessageController@auto_reply_edit_article')->middleware('FansmanageCheckAjax');//检测自动回复图文素材ajax显示
        Route::post('auto_reply_edit_article_check', 'Fansmanage\MessageController@auto_reply_edit_article_check')->middleware('FansmanageCheckAjax');//检测自动回复图文素材数据提交
        Route::post('auto_reply_edit', 'Fansmanage\MessageController@auto_reply_edit')->middleware('FansmanageCheckAjax');//编辑自动回复关键字ajax显示
        Route::post('auto_reply_edit_check', 'Fansmanage\MessageController@auto_reply_edit_check')->middleware('FansmanageCheckAjax');//编辑自动回复关键字功能提交
        Route::post('auto_reply_delete_confirm', 'Fansmanage\MessageController@auto_reply_delete_confirm')->middleware('FansmanageCheckAjax');//删除关键字ajax显示
        Route::post('auto_reply_delete_check', 'Fansmanage\MessageController@auto_reply_delete_check')->middleware('FansmanageCheckAjax');//删除关键字功能提交

        Route::post('subscribe_reply_text_edit', 'Fansmanage\MessageController@subscribe_reply_text_edit')->middleware('FansmanageCheckAjax');//关注后自动回复文字ajax显示
        Route::post('subscribe_reply_text_edit_check', 'Fansmanage\MessageController@subscribe_reply_text_edit_check')->middleware('FansmanageCheckAjax');//关注后自动回复文字功能提交
        Route::post('subscribe_reply_image_edit', 'Fansmanage\MessageController@subscribe_reply_image_edit')->middleware('FansmanageCheckAjax');//关注后图片回复ajax显示
        Route::post('subscribe_reply_image_edit_check', 'Fansmanage\MessageController@subscribe_reply_image_edit_check')->middleware('FansmanageCheckAjax');//关注后图片回复功能提交
        Route::post('subscribe_reply_article_edit', 'Fansmanage\MessageController@subscribe_reply_article_edit')->middleware('FansmanageCheckAjax');//关注后自动回复文本素材ajax显示
        Route::post('subscribe_reply_article_edit_check', 'Fansmanage\MessageController@subscribe_reply_article_edit_check')->middleware('FansmanageCheckAjax');//关注后自动回复文本素材功能提交

        Route::post('default_reply_text_edit', 'Fansmanage\MessageController@default_reply_text_edit')->middleware('FansmanageCheckAjax');//默认回复文字回复ajax显示
        Route::post('default_reply_text_edit_check', 'Fansmanage\MessageController@default_reply_text_edit_check')->middleware('FansmanageCheckAjax');//默认回复文字回复功能提交
        Route::post('default_reply_image_edit', 'Fansmanage\MessageController@default_reply_image_edit')->middleware('FansmanageCheckAjax');//默认回复图片素材ajax显示
        Route::post('default_reply_image_edit_check', 'Fansmanage\MessageController@default_reply_image_edit_check')->middleware('FansmanageCheckAjax');//默认回复图片素材功能提交
        Route::post('default_reply_article_edit', 'Fansmanage\MessageController@default_reply_article_edit')->middleware('FansmanageCheckAjax');//默认回复图文素材ajax显示
        Route::post('default_reply_article_edit_check', 'Fansmanage\MessageController@default_reply_article_edit_check')->middleware('FansmanageCheckAjax');//默认回复图文素材能提交


        //公众号管理--菜单管理--自定义
        Route::any('defined_menu_get', 'Fansmanage\WechatmenuController@defined_menu_get')->middleware('FansmanageCheckAjax');//获取自定义菜单数据
        Route::any('defined_menu_add', 'Fansmanage\WechatmenuController@defined_menu_add')->middleware('FansmanageCheckAjax');//添加自定义菜单板块
        Route::any('defined_menu_add_check', 'Fansmanage\WechatmenuController@defined_menu_add_check')->middleware('FansmanageCheckAjax');//添加自定义菜单板块
        Route::any('defined_menu_delete', 'Fansmanage\WechatmenuController@defined_menu_delete')->middleware('FansmanageCheckAjax');//添加自定义菜单板块
        Route::any('defined_menu_delete_check', 'Fansmanage\WechatmenuController@defined_menu_delete_check')->middleware('FansmanageCheckAjax');//添加自定义菜单板块
        Route::any('defined_menu_edit', 'Fansmanage\WechatmenuController@defined_menu_edit')->middleware('FansmanageCheckAjax');//编辑自定义菜单板块
        Route::any('defined_menu_edit_check', 'Fansmanage\WechatmenuController@defined_menu_edit_check')->middleware('FansmanageCheckAjax');//编辑自定义菜单板块
        Route::any('wechat_menu_add', 'Fansmanage\WechatmenuController@wechat_menu_add')->middleware('FansmanageCheckAjax');//一键同步到微信菜单
        Route::any('wechat_menu_add_check', 'Fansmanage\WechatmenuController@wechat_menu_add_check')->middleware('FansmanageCheckAjax');//一键同步到微信菜单
        //公众号管理--菜单管理--自定义
        Route::any('conditional_menu_list', 'Fansmanage\WechatmenuController@conditional_menu_list')->middleware('FansmanageCheckAjax');//显示上级菜单
        Route::any('conditional_menu_get', 'Fansmanage\WechatmenuController@conditional_menu_get')->middleware('FansmanageCheckAjax');//获取个性化菜单数据
        Route::any('conditional_menu_add', 'Fansmanage\WechatmenuController@conditional_menu_add')->middleware('FansmanageCheckAjax');//添加个性化菜单板块
        Route::any('conditional_menu_add_check', 'Fansmanage\WechatmenuController@conditional_menu_add_check')->middleware('FansmanageCheckAjax');//添加个性化菜单功能提交
        Route::any('conditional_menu_edit', 'Fansmanage\WechatmenuController@conditional_menu_edit')->middleware('FansmanageCheckAjax');//修改个性化菜单ajax显示
        Route::any('conditional_menu_edit_check', 'Fansmanage\WechatmenuController@conditional_menu_edit_check')->middleware('FansmanageCheckAjax');//修改个性化菜单功能提交
        Route::any('conditional_menu_delete', 'Fansmanage\WechatmenuController@conditional_menu_delete')->middleware('FansmanageCheckAjax');//删除个性化菜单板块
        Route::any('conditional_menu_delete_check', 'Fansmanage\WechatmenuController@conditional_menu_delete_check')->middleware('FansmanageCheckAjax');//删除个性化菜单板块

        Route::any('wechat_conditional_menu_add', 'Fansmanage\WechatmenuController@wechat_conditional_menu_add')->middleware('FansmanageCheckAjax');//一键同步到微信菜单
        Route::any('wechat_conditional_menu_add_check', 'Fansmanage\WechatmenuController@wechat_conditional_menu_add_check')->middleware('FansmanageCheckAjax');//一键同步到微信菜单


        //用户管理
        Route::post('label_add', 'Fansmanage\UserController@label_add')->middleware('FansmanageCheckAjax');                  //添加会员标签ajax显示页面
        Route::post('label_add_check', 'Fansmanage\UserController@label_add_check')->middleware('FansmanageCheckAjax');      //添加会员标签功能提交
        Route::post('label_edit', 'Fansmanage\UserController@label_edit')->middleware('FansmanageCheckAjax');                //编辑会员标签功能提交
        Route::post('label_edit_check', 'Fansmanage\UserController@label_edit_check')->middleware('FansmanageCheckAjax');    //编辑会员标签功能提交
        Route::post('label_delete', 'Fansmanage\UserController@label_delete')->middleware('FansmanageCheckAjax');            //删除会员标签功能提交
        Route::post('label_delete_check', 'Fansmanage\UserController@label_delete_check')->middleware('FansmanageCheckAjax');//删除会员标签功能提交
        Route::post('label_wechat', 'Fansmanage\UserController@label_wechat')->middleware('FansmanageCheckAjax');            //微信同步会员标签
        Route::post('label_wechat_check', 'Fansmanage\UserController@label_wechat_check')->middleware('FansmanageCheckAjax');//微信同步会员标签功能提交

        Route::post('store_label_add_check', 'Fansmanage\UserController@store_label_add_check')->middleware('FansmanageCheckAjax');   //粉丝会员标签功能提交
        Route::post('user_list_edit', 'Fansmanage\UserController@user_list_edit')->middleware('FansmanageCheckAjax');                 //列表编辑ajax显示
        Route::post('user_list_edit_check', 'Fansmanage\UserController@user_list_edit_check')->middleware('FansmanageCheckAjax');     //列表编辑功能提交
        Route::post('user_list_lock', 'Fansmanage\UserController@user_list_lock')->middleware('FansmanageCheckAjax');                 //列表冻结ajax显示
        Route::post('user_list_lock_check', 'Fansmanage\UserController@user_list_lock_check')->middleware('FansmanageCheckAjax');     //列表冻结功能提交
        Route::post('user_list_wallet', 'Fansmanage\UserController@user_list_wallet')->middleware('FansmanageCheckAjax');             //列表粉丝钱包ajax显示

        //总店管理
        Route::post('store_create_check', 'Fansmanage\StoreController@store_create_check')->middleware('FansmanageCheckAjax');//店铺添加功能提交

        // 店铺信息修改
        Route::post('fansmanage_edit_check', 'Fansmanage\StoreController@fansmanage_edit_check')->middleware('FansmanageCheckAjax');

        // 微信支付信息提交
        Route::post('editPayInfo', 'Fansmanage\PaysettingController@editPayInfo')->middleware('FansmanageCheckAjax');
    });
});
/**********************粉丝管理系统*********************/

/**********************零售版店铺管理系统*********************/
Route::group(['prefix' => 'retail'], function () {

    //登录页面组
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', 'Retail\LoginController@display')->middleware('RetailCheck');//登录页面路由
        Route::get('captcha/{tmp}', 'Retail\LoginController@captcha');//验证码路由
    });

    Route::get('/', 'Retail\DisplayController@display')->middleware('RetailCheck');//分店首页
    Route::get('quit', 'Retail\LoginController@quit');                                                 //退出系统
    Route::get('retail_list', 'Retail\DisplayController@retail_list')->middleware('RetailCheck');      //分店列表
    Route::get('retail_switch', 'Retail\DisplayController@retail_switch')->middleware('RetailCheck'); //超级管理员退出当前店铺

    //账户中心
    Route::group(['prefix' => 'account'], function () {
        Route::get('profile', 'Retail\AccountController@profile')->middleware('RetailCheck'); //账号中心-账户信息
        Route::get('safe_password', 'Retail\AccountController@safe_password')->middleware('RetailCheck');//安全密码
        Route::get('password', 'Retail\AccountController@password')->middleware('RetailCheck');          //登录密码页面
    });

    //栏目管理
    Route::group(['prefix' => 'category'], function () {
        Route::get('category_add', 'Retail\CategoryController@category_add')->middleware('RetailCheck');   //商品管理-添加商品分类
        Route::get('category_list', 'Retail\CategoryController@category_list')->middleware('RetailCheck'); //商品管理-商品分类列表
    });

    //商品管理
    Route::group(['prefix' => 'goods'], function () {
        Route::get('goods_add', 'Retail\GoodsController@goods_add')->middleware('RetailCheck');         //商品管理-添加商品
        Route::get('goods_edit', 'Retail\GoodsController@goods_edit')->middleware('RetailCheck');       //商品管理-编辑商品
        Route::get('goods_list', 'Retail\GoodsController@goods_list')->middleware('RetailCheck');       //商品管理-商品列表
    });

    //订单管理
    Route::group(['prefix' => 'order'], function () {
        Route::get('order_spot', 'Retail\OrderController@order_spot')->middleware('RetailCheck');                       //订单管理-现场订单
        Route::get('order_spot_detail', 'Retail\OrderController@order_spot_detail')->middleware('RetailCheck');         //订单管理-现场订单详情
        Route::get('order_takeout', 'Retail\OrderController@order_takeout')->middleware('RetailCheck');                 //订单管理-外卖订单
        Route::get('order_takeout_detail', 'Retail\OrderController@order_takeout_detail')->middleware('RetailCheck');   //订单管理-外卖订单详情
        Route::get('order_appointment', 'Retail\OrderController@order_appointment')->middleware('RetailCheck');         //预约管理
    });

    //运费模板管理
    Route::group(['prefix' => 'dispatch'], function () {
        Route::get('dispatch_add', 'Retail\DispatchController@dispatch_add')->middleware('RetailCheck');   //添加运费模板                    //订单管理-现场订单
        Route::get('dispatch_list', 'Retail\DispatchController@dispatch_list')->middleware('RetailCheck'); //运费模板列表                              //订单管理-现场订单
        Route::get('dispatch_edit', 'Retail\DispatchController@dispatch_edit')->middleware('RetailCheck'); //运费模板列表                              //订单管理-现场订单
    });


    //进销存开单处理
    Route::group(['prefix' => 'supplier'], function () {
        Route::get('supplier_add', 'Retail\SupplierController@supplier_add')->middleware('RetailCheck');        //添加供应商
        Route::get('supplier_list', 'Retail\SupplierController@supplier_list')->middleware('RetailCheck');      //供应商列表
    });

    //进销存供应商管理
    Route::group(['prefix' => 'invoicing'], function () {
        Route::get('purchase_goods', 'Retail\InvoicingController@purchase_goods')->middleware('RetailCheck');    //从供应商进货开单
        Route::get('return_goods', 'Retail\InvoicingController@return_goods')->middleware('RetailCheck');        //从供应商退货开单
        Route::get('loss_goods', 'Retail\InvoicingController@loss_goods')->middleware('RetailCheck');           //报损开单
        Route::get('check_goods', 'Retail\InvoicingController@check_goods')->middleware('RetailCheck');        //盘点开单
    });

    //进出开单管理
    Route::group(['prefix' => 'billing'], function () {
        Route::get('purchase_goods', 'Retail\BillingController@purchase_goods')->middleware('RetailCheck');    //从供应商进货开单管理
        Route::get('return_goods', 'Retail\BillingController@return_goods')->middleware('RetailCheck');        //从供应商退货开单管理
        Route::get('loss_goods', 'Retail\BillingController@loss_goods')->middleware('RetailCheck');           //报损开单管理
        Route::get('check_goods', 'Retail\BillingController@check_goods')->middleware('RetailCheck');        //盘点开单管理
        Route::get('stock_list', 'Retail\BillingController@stock_list')->middleware('RetailCheck');         //库存查询
    });

    //用户管理
    Route::group(['prefix' => 'user'], function () {
        Route::get('user_list', 'Retail\UserController@user_list')->middleware('RetailCheck');          //用户管理-粉丝用户管理
    });

    //支付设置
    Route::group(['prefix' => 'paysetting'], function () {
        Route::get('payconfig', 'Retail\PaysettingController@payconfig')->middleware('RetailCheck');           //收款信息设置
        Route::get('shengpay_add', 'Retail\PaysettingController@shengpay_add')->middleware('RetailCheck');     //添加终端机器号信息
        Route::get('shengpay_list', 'Retail\PaysettingController@shengpay_list')->middleware('RetailCheck');   //终端机器号列表
    });


    //下属管理--添加组
    Route::group(['prefix' => 'subordinate'], function () {
        Route::get('subordinate_add', 'Retail\SubordinateController@subordinate_add')->middleware('RetailCheck');    //添加下级人员
        Route::get('subordinate_list', 'Retail\SubordinateController@subordinate_list')->middleware('RetailCheck');  //下级人员列表
    });

    //异步提交数据组
    Route::group(['prefix' => 'ajax'], function () {
        Route::post('login_check', 'Retail\LoginController@login_check')->middleware('RetailCheckAjax');//提交登录数据
        Route::post('retail_select', 'Retail\DisplayController@retail_select')->middleware('RetailCheckAjax');//提交选择分店数据
        Route::post('store_edit_check', 'Retail\DisplayController@store_edit_check')->middleware('RetailCheckAjax');;    //分店店铺信息编辑弹窗
        Route::post('profile_edit_check', 'Retail\AccountController@profile_edit_check')->middleware('RetailCheckAjax');//个人账号信息修改
        Route::post('safe_password_edit_check', 'Retail\AccountController@safe_password_edit_check')->middleware('RetailCheckAjax');//安全密码设置检测
        Route::post('password_edit_check', 'Retail\AccountController@password_edit_check')->middleware('RetailCheckAjax');          //密码检测


        Route::post('subordinate_add_check', 'Retail\SubordinateController@subordinate_add_check')->middleware('RetailCheckAjax');  //下属添加检测
        Route::post('subordinate_edit', 'Retail\SubordinateController@subordinate_edit')->middleware('RetailCheckAjax');            //下属信息编辑页面
        Route::post('subordinate_edit_check', 'Retail\SubordinateController@subordinate_edit_check')->middleware('RetailCheckAjax');//下属信息编辑检测
        Route::post('subordinate_lock', 'Retail\SubordinateController@subordinate_lock')->middleware('RetailCheckAjax');            //下属冻结检测
        Route::post('subordinate_delete', 'Retail\SubordinateController@subordinate_delete')->middleware('RetailCheckAjax');        //下属删除检测
        Route::post('subordinate_lock', 'Retail\SubordinateController@subordinate_lock')->middleware('RetailCheckAjax');            //下属冻结页面
        Route::post('subordinate_lock_check', 'Retail\SubordinateController@subordinate_lock_check')->middleware('RetailCheckAjax');//下属冻结检测


        Route::post('goods_list', 'Retail\InvoicingController@goods_list')->middleware('RetailCheckAjax');                       //开单异步加载部分（商品列表）
        Route::post('supplier_add_check', 'Retail\SupplierController@supplier_add_check')->middleware('RetailCheckAjax');          //供应商添加检测
        Route::post('supplier_delete', 'Retail\SupplierController@supplier_delete')->middleware('RetailCheckAjax');               //删除供应商弹窗
        Route::post('supplier_delete_check', 'Retail\SupplierController@supplier_delete_check')->middleware('RetailCheckAjax');   //删除供应商操作
        Route::post('supplier_edit', 'Retail\SupplierController@supplier_edit')->middleware('RetailCheckAjax');                   //编辑供应商弹窗
        Route::post('supplier_edit_check', 'Retail\SupplierController@supplier_edit_check')->middleware('RetailCheckAjax');       //编辑供应商操作
        Route::post('purchase_goods_check', 'Retail\InvoicingController@purchase_goods_check')->middleware('RetailCheckAjax');   //从供应商进货、退货开单检测
        Route::post('loss_goods_check', 'Retail\InvoicingController@loss_goods_check')->middleware('RetailCheckAjax');          //报损开单检测
        Route::post('check_goods_check', 'Retail\InvoicingController@check_goods_check')->middleware('RetailCheckAjax');        //盘点开单检测
        Route::post('search_company', 'Retail\InvoicingController@search_company')->middleware('RetailCheckAjax');              //搜索供应商检测
        Route::post('select_company', 'Retail\InvoicingController@select_company')->middleware('RetailCheckAjax');              //选择供应商检测


        Route::post('purchase_list_confirm', 'Retail\BillingController@purchase_list_confirm')->middleware('RetailCheckAjax');           //进货退货审核订单弹出页面
        Route::post('purchase_list_confirm_check', 'Retail\BillingController@purchase_list_confirm_check')->middleware('RetailCheckAjax');//进货退货审核订单操作
        Route::post('loss_list_confirm', 'Retail\BillingController@loss_list_confirm')->middleware('RetailCheckAjax');               //报损审核订单弹出页面
        Route::post('loss_list_confirm_check', 'Retail\BillingController@loss_list_confirm_check')->middleware('RetailCheckAjax');   //报损审核订单操作
        Route::post('check_list_confirm', 'Retail\BillingController@check_list_confirm')->middleware('RetailCheckAjax');             //盘点审核订单弹出页面
        Route::post('check_list_confirm_check', 'Retail\BillingController@check_list_confirm_check')->middleware('RetailCheckAjax');   //盘点审核订单操作
        Route::post('order_list_details', 'Retail\BillingController@order_list_details')->middleware('RetailCheckAjax');             //订单详细信息列表

        Route::post('category_add_check', 'Retail\CategoryController@category_add_check')->middleware('RetailCheckAjax');          //栏目添加检测
        Route::post('category_delete', 'Retail\CategoryController@category_delete')->middleware('RetailCheckAjax');          //栏目添加检测
        Route::post('category_delete_check', 'Retail\CategoryController@category_delete_check')->middleware('RetailCheckAjax');          //栏目添加检测
        Route::post('category_edit', 'Retail\CategoryController@category_edit')->middleware('RetailCheckAjax');                    //栏目编辑页面
        Route::post('category_edit_check', 'Retail\CategoryController@category_edit_check')->middleware('RetailCheckAjax');        //栏目编辑检测
        Route::post('goods_add_check', 'Retail\GoodsController@goods_add_check')->middleware('RetailCheckAjax');                   //商品添加检测

        Route::post('goods_delete', 'Retail\GoodsController@goods_delete')->middleware('RetailCheckAjax');                         //商品删除弹窗
        Route::post('goods_status', 'Retail\GoodsController@goods_status')->middleware('RetailCheckAjax');                         //商品状态修改弹窗
        Route::post('goods_delete_check', 'Retail\GoodsController@goods_delete_check')->middleware('RetailCheckAjax');             //商品删除检测
        Route::post('goods_status_check', 'Retail\GoodsController@goods_status_check')->middleware('RetailCheckAjax');             //商品状态修改检测
        Route::post('thumb_edit_displayorder', 'Retail\GoodsController@thumb_edit_displayorder')->middleware('RetailCheckAjax');   //商品图片删除弹窗
        Route::post('goods_thumb_delete', 'Retail\GoodsController@goods_thumb_delete')->middleware('RetailCheckAjax');              //商品图片删除弹窗
        Route::post('goods_thumb_delete_check', 'Retail\GoodsController@goods_thumb_delete_check')->middleware('RetailCheckAjax');  //商品图片删除检测
        Route::post('goods_edit_check', 'Retail\GoodsController@goods_edit_check')->middleware('RetailCheckAjax');                 //商品编辑检测
        Route::post('order_status', 'Retail\OrderController@order_status')->middleware('RetailCheckAjax');                         //修改订单状态弹窗
        Route::post('order_status_check', 'Retail\OrderController@order_status_check')->middleware('RetailCheckAjax');             //修改订单状态检测
        Route::post('order_status_paytype', 'Retail\OrderController@order_status_paytype')->middleware('RetailCheckAjax');                         //修改订单状态弹窗
        Route::post('order_status_paytype_check', 'Retail\OrderController@order_status_paytype_check')->middleware('RetailCheckAjax');             //修改订单状态检测
        Route::any('goods_thumb', 'Retail\GoodsController@goods_thumb')->middleware('RetailCheckAjax');                           //商品规格异步加载页面
        Route::post('upload_thumb_check', 'Retail\GoodsController@upload_thumb_check')->middleware('RetailCheckAjax');             //上传文件检测
        Route::post('dispatch_add_check', 'Retail\DispatchController@dispatch_add_check')->middleware('RetailCheckAjax');             //运费模板添加
        Route::post('dispatch_province_add_check', 'Retail\DispatchController@dispatch_province_add_check')->middleware('RetailCheckAjax');  //运费模板省份添加
        Route::post('dispatch_province_edit_check', 'Retail\DispatchController@dispatch_province_edit_check')->middleware('RetailCheckAjax');  //运费模信息编辑
        Route::post('dispatch_province_delete_check', 'Retail\DispatchController@dispatch_province_delete_check')->middleware('RetailCheckAjax');  //运费模信息删除
        Route::post('dispatch_list_lock', 'Retail\DispatchController@dispatch_list_lock')->middleware('RetailCheckAjax');                 //列表启用运费模板ajax显示
        Route::post('dispatch_list_lock_check', 'Retail\DispatchController@dispatch_list_lock_check')->middleware('RetailCheckAjax');                 //列表启用运费模板ajax显示
        Route::post('dispatch_list_delete', 'Retail\DispatchController@dispatch_list_delete')->middleware('RetailCheckAjax');                 //列表启用运费模板ajax显示
        Route::post('dispatch_list_delete_check', 'Retail\DispatchController@dispatch_list_delete_check')->middleware('RetailCheckAjax');                 //列表启用运费模板ajax显示

        Route::post('user_list_edit', 'Retail\UserController@user_list_edit')->middleware('RetailCheckAjax');                 //列表编辑ajax显示
        Route::post('user_list_edit_check', 'Retail\UserController@user_list_edit_check')->middleware('RetailCheckAjax');     //列表编辑功能提交
        Route::post('user_list_lock', 'Retail\UserController@user_list_lock')->middleware('RetailCheckAjax');                 //列表冻结ajax显示
        Route::post('user_list_lock_check', 'Retail\UserController@user_list_lock_check')->middleware('RetailCheckAjax');     //列表冻结功能提交
        Route::post('user_list_wallet', 'Retail\UserController@user_list_wallet')->middleware('RetailCheckAjax');             //列表粉丝钱包ajax显示


        //支付设置
        Route::post('payconfig_check', 'Retail\PaysettingController@payconfig_check')->middleware('RetailCheckAjax');         //收款信息功能提交
        Route::post('payconfig_edit', 'Retail\PaysettingController@payconfig_edit')->middleware('RetailCheckAjax');           //收款信息编辑ajax
        Route::post('payconfig_edit_check', 'Retail\PaysettingController@payconfig_edit_check')->middleware('RetailCheckAjax');//收款信息功能提交
        Route::post('payconfig_apply', 'Retail\PaysettingController@payconfig_apply')->middleware('RetailCheckAjax');         //收款信息功能重新申请
        Route::post('payconfig_apply_check', 'Retail\PaysettingController@payconfig_apply_check')->middleware('RetailCheckAjax');//收款信息功能重新申请功能提交
        Route::post('payconfig_delete', 'Retail\PaysettingController@payconfig_delete')->middleware('RetailCheckAjax');       //收款信息功能解除绑定ajax显示
        Route::post('payconfig_delete_check', 'Retail\PaysettingController@payconfig_delete_check')->middleware('RetailCheckAjax');//收款信息功能解除绑定功能提交
        Route::post('shengpay_add_check', 'Retail\PaysettingController@shengpay_add_check')->middleware('RetailCheckAjax');   //添加终端机器号功能提交
        Route::post('shengpay_edit', 'Retail\PaysettingController@shengpay_edit')->middleware('RetailCheckAjax');             //编辑终端机器号Ajax显示
        Route::post('shengpay_edit_check', 'Retail\PaysettingController@shengpay_edit_check')->middleware('RetailCheckAjax'); //编辑终端机器号功能提交
        Route::post('shengpay_apply', 'Retail\PaysettingController@shengpay_apply')->middleware('RetailCheckAjax');           //终端机器号重新申请Ajax
        Route::post('shengpay_apply_check', 'Retail\PaysettingController@shengpay_apply_check')->middleware('RetailCheckAjax');//终端机器号重新申请功能提交
        Route::post('shengpay_delete', 'Retail\PaysettingController@shengpay_delete')->middleware('RetailCheckAjax');         //终端机器号解除绑定ajax
        Route::post('shengpay_delete_check', 'Retail\PaysettingController@shengpay_delete_check')->middleware('RetailCheckAjax');//终端机器号解除绑定功能提交

    });
});
/**********************零售版店铺管理系统*********************/


/**********************简单的*********************/
Route::group(['prefix' => 'simple'], function () {
    //登录页面组
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', 'Simple\LoginController@display')->middleware('SimpleCheck');//登录页面路由
        Route::get('captcha/{tmp}', 'Simple\LoginController@captcha');//验证码路由
    });

    Route::get('/', 'Simple\DisplayController@display')->middleware('SimpleCheck');//分店首页
    Route::get('quit', 'Simple\LoginController@quit');                                                 //退出系统
    Route::get('simple_list', 'Simple\DisplayController@simple_list')->middleware('SimpleCheck');      //分店列表
    Route::get('simple_switch', 'Simple\DisplayController@simple_switch')->middleware('SimpleCheck'); //超级管理员退出当前店铺

    //账户中心
    Route::group(['prefix' => 'account'], function () {
        Route::get('profile', 'Simple\AccountController@profile')->middleware('SimpleCheck'); //账号中心-账户信息
        Route::get('safe_password', 'Simple\AccountController@safe_password')->middleware('SimpleCheck');//安全密码
        Route::get('password', 'Simple\AccountController@password')->middleware('SimpleCheck');          //登录密码页面
    });

    //栏目管理
    Route::group(['prefix' => 'category'], function () {
        Route::get('category_add', 'Simple\CategoryController@category_add')->middleware('SimpleCheck');   //商品管理-添加商品分类
        Route::get('category_list', 'Simple\CategoryController@category_list')->middleware('SimpleCheck'); //商品管理-商品分类列表
    });

    //商品管理
    Route::group(['prefix' => 'goods'], function () {
        Route::get('goods_add', 'Simple\GoodsController@goods_add')->middleware('SimpleCheck');         //商品管理-添加商品
        Route::get('goods_edit', 'Simple\GoodsController@goods_edit')->middleware('SimpleCheck');       //商品管理-编辑商品
        Route::get('goods_list', 'Simple\GoodsController@goods_list')->middleware('SimpleCheck');       //商品管理-商品列表
    });

    //订单管理
    Route::group(['prefix' => 'order'], function () {
        Route::get('order_spot', 'Simple\OrderController@order_spot')->middleware('SimpleCheck');                       //订单管理-现场订单
        Route::get('order_spot_detail', 'Simple\OrderController@order_spot_detail')->middleware('SimpleCheck');         //订单管理-现场订单详情
        Route::get('order_takeout', 'Simple\OrderController@order_takeout')->middleware('SimpleCheck');                 //订单管理-外卖订单
        Route::get('order_takeout_detail', 'Simple\OrderController@order_takeout_detail')->middleware('SimpleCheck');   //订单管理-外卖订单详情
        Route::get('order_appointment', 'Simple\OrderController@order_appointment')->middleware('SimpleCheck');         //预约管理
    });


    //进销存开单处理
    Route::group(['prefix' => 'supplier'], function () {
        Route::get('supplier_add', 'Simple\SupplierController@supplier_add')->middleware('SimpleCheck');        //添加供应商
        Route::get('supplier_list', 'Simple\SupplierController@supplier_list')->middleware('SimpleCheck');      //供应商列表
    });

    //进销存供应商管理
    Route::group(['prefix' => 'invoicing'], function () {
        Route::get('purchase_goods', 'Simple\InvoicingController@purchase_goods')->middleware('SimpleCheck');    //从供应商进货开单
        Route::get('return_goods', 'Simple\InvoicingController@return_goods')->middleware('SimpleCheck');        //从供应商退货开单
        Route::get('loss_goods', 'Simple\InvoicingController@loss_goods')->middleware('SimpleCheck');           //报损开单
        Route::get('check_goods', 'Simple\InvoicingController@check_goods')->middleware('SimpleCheck');        //盘点开单
    });

    //进出开单管理
    Route::group(['prefix' => 'billing'], function () {
        Route::get('purchase_goods', 'Simple\BillingController@purchase_goods')->middleware('SimpleCheck');    //从供应商进货开单管理
        Route::get('return_goods', 'Simple\BillingController@return_goods')->middleware('SimpleCheck');        //从供应商退货开单管理
        Route::get('loss_goods', 'Simple\BillingController@loss_goods')->middleware('SimpleCheck');           //报损开单管理
        Route::get('check_goods', 'Simple\BillingController@check_goods')->middleware('SimpleCheck');        //盘点开单管理
        Route::get('stock_list', 'Simple\BillingController@stock_list')->middleware('SimpleCheck');         //库存查询
    });


    //下属管理--添加组
    Route::group(['prefix' => 'subordinate'], function () {
        Route::get('subordinate_add', 'Simple\SubordinateController@subordinate_add')->middleware('SimpleCheck');    //添加下级人员
        Route::get('subordinate_list', 'Simple\SubordinateController@subordinate_list')->middleware('SimpleCheck');  //下级人员列表
    });

    //异步提交数据组
    Route::group(['prefix' => 'ajax'], function () {
        Route::post('login_check', 'Simple\LoginController@login_check')->middleware('SimpleCheckAjax');//提交登录数据
        Route::post('simple_select', 'Simple\DisplayController@simple_select')->middleware('SimpleCheckAjax');//提交选择分店数据
        Route::post('store_edit_check', 'Simple\DisplayController@store_edit_check')->middleware('SimpleCheckAjax');;    //分店店铺信息编辑弹窗
        Route::post('profile_edit_check', 'Simple\AccountController@profile_edit_check')->middleware('SimpleCheckAjax');//个人账号信息修改
        Route::post('safe_password_edit_check', 'Simple\AccountController@safe_password_edit_check')->middleware('SimpleCheckAjax');//安全密码设置检测
        Route::post('password_edit_check', 'Simple\AccountController@password_edit_check')->middleware('SimpleCheckAjax');          //密码检测


        Route::post('subordinate_add_check', 'Simple\SubordinateController@subordinate_add_check')->middleware('SimpleCheckAjax');  //下属添加检测
        Route::post('subordinate_edit', 'Simple\SubordinateController@subordinate_edit')->middleware('SimpleCheckAjax');            //下属信息编辑页面
        Route::post('subordinate_edit_check', 'Simple\SubordinateController@subordinate_edit_check')->middleware('SimpleCheckAjax');//下属信息编辑检测
        Route::post('subordinate_lock', 'Simple\SubordinateController@subordinate_lock')->middleware('SimpleCheckAjax');            //下属冻结检测
        Route::post('subordinate_delete', 'Simple\SubordinateController@subordinate_delete')->middleware('SimpleCheckAjax');        //下属删除检测
        Route::post('subordinate_lock', 'Simple\SubordinateController@subordinate_lock')->middleware('SimpleCheckAjax');            //下属冻结页面
        Route::post('subordinate_lock_check', 'Simple\SubordinateController@subordinate_lock_check')->middleware('SimpleCheckAjax');//下属冻结检测


        Route::post('goods_list', 'Simple\InvoicingController@goods_list')->middleware('SimpleCheckAjax');                       //开单异步加载部分（商品列表）
        Route::post('supplier_add_check', 'Simple\SupplierController@supplier_add_check')->middleware('SimpleCheckAjax');          //供应商添加检测
        Route::post('supplier_delete', 'Simple\SupplierController@supplier_delete')->middleware('SimpleCheckAjax');               //删除供应商弹窗
        Route::post('supplier_delete_check', 'Simple\SupplierController@supplier_delete_check')->middleware('SimpleCheckAjax');   //删除供应商操作
        Route::post('supplier_edit', 'Simple\SupplierController@supplier_edit')->middleware('SimpleCheckAjax');                   //编辑供应商弹窗
        Route::post('supplier_edit_check', 'Simple\SupplierController@supplier_edit_check')->middleware('SimpleCheckAjax');       //编辑供应商操作
        Route::post('purchase_goods_check', 'Simple\InvoicingController@purchase_goods_check')->middleware('SimpleCheckAjax');   //从供应商进货、退货开单检测
        Route::post('loss_goods_check', 'Simple\InvoicingController@loss_goods_check')->middleware('SimpleCheckAjax');          //报损开单检测
        Route::post('check_goods_check', 'Simple\InvoicingController@check_goods_check')->middleware('SimpleCheckAjax');        //盘点开单检测
        Route::post('search_company', 'Simple\InvoicingController@search_company')->middleware('SimpleCheckAjax');              //搜索供应商检测
        Route::post('select_company', 'Simple\InvoicingController@select_company')->middleware('SimpleCheckAjax');              //选择供应商检测


        Route::post('purchase_list_confirm', 'Simple\BillingController@purchase_list_confirm')->middleware('SimpleCheckAjax');           //进货退货审核订单弹出页面
        Route::post('purchase_list_confirm_check', 'Simple\BillingController@purchase_list_confirm_check')->middleware('SimpleCheckAjax');//进货退货审核订单操作
        Route::post('loss_list_confirm', 'Simple\BillingController@loss_list_confirm')->middleware('SimpleCheckAjax');               //报损审核订单弹出页面
        Route::post('loss_list_confirm_check', 'Simple\BillingController@loss_list_confirm_check')->middleware('SimpleCheckAjax');   //报损审核订单操作
        Route::post('check_list_confirm', 'Simple\BillingController@check_list_confirm')->middleware('SimpleCheckAjax');             //盘点审核订单弹出页面
        Route::post('check_list_confirm_check', 'Simple\BillingController@check_list_confirm_check')->middleware('SimpleCheckAjax');   //盘点审核订单操作
        Route::post('order_list_details', 'Simple\BillingController@order_list_details')->middleware('SimpleCheckAjax');             //订单详细信息列表

        Route::post('category_add_check', 'Simple\CategoryController@category_add_check')->middleware('SimpleCheckAjax');          //栏目添加检测
        Route::post('category_delete', 'Simple\CategoryController@category_delete')->middleware('SimpleCheckAjax');          //栏目添加检测
        Route::post('category_delete_check', 'Simple\CategoryController@category_delete_check')->middleware('SimpleCheckAjax');          //栏目添加检测
        Route::post('category_edit', 'Simple\CategoryController@category_edit')->middleware('SimpleCheckAjax');                    //栏目编辑页面
        Route::post('category_edit_check', 'Simple\CategoryController@category_edit_check')->middleware('SimpleCheckAjax');        //栏目编辑检测
        Route::post('goods_add_check', 'Simple\GoodsController@goods_add_check')->middleware('SimpleCheckAjax');                   //商品添加检测

        Route::post('goods_delete', 'Simple\GoodsController@goods_delete')->middleware('SimpleCheckAjax');                         //商品删除弹窗
        Route::post('goods_status', 'Simple\GoodsController@goods_status')->middleware('SimpleCheckAjax');                         //商品状态修改弹窗
        Route::post('goods_delete_check', 'Simple\GoodsController@goods_delete_check')->middleware('SimpleCheckAjax');             //商品删除检测
        Route::post('goods_status_check', 'Simple\GoodsController@goods_status_check')->middleware('SimpleCheckAjax');             //商品状态修改检测
        Route::post('thumb_edit_displayorder', 'Simple\GoodsController@thumb_edit_displayorder')->middleware('SimpleCheckAjax');   //商品图片删除弹窗
        Route::post('goods_thumb_delete', 'Simple\GoodsController@goods_thumb_delete')->middleware('SimpleCheckAjax');              //商品图片删除弹窗
        Route::post('goods_thumb_delete_check', 'Simple\GoodsController@goods_thumb_delete_check')->middleware('SimpleCheckAjax');  //商品图片删除检测
        Route::post('goods_edit_check', 'Simple\GoodsController@goods_edit_check')->middleware('SimpleCheckAjax');                 //商品编辑检测
        Route::post('order_status', 'Simple\OrderController@order_status')->middleware('SimpleCheckAjax');                         //修改订单状态弹窗
        Route::post('order_status_check', 'Simple\OrderController@order_status_check')->middleware('SimpleCheckAjax');             //修改订单状态检测
        Route::post('order_status_paytype', 'Simple\OrderController@order_status_paytype')->middleware('SimpleCheckAjax');                         //修改订单状态弹窗
        Route::post('order_status_paytype_check', 'Simple\OrderController@order_status_paytype_check')->middleware('SimpleCheckAjax');             //修改订单状态检测
        Route::any('goods_thumb', 'Simple\GoodsController@goods_thumb')->middleware('SimpleCheckAjax');                           //商品规格异步加载页面
        Route::post('upload_thumb_check', 'Simple\GoodsController@upload_thumb_check')->middleware('SimpleCheckAjax');             //上传文件检测

    });
});
/**********************简单的*********************/


/**********************微信端*********************/
Route::group(['prefix' => 'zerone'], function () {
    Route::group(['prefix' => 'wechatRetail'], function () {
        Route::any('/', 'Api\WechatController@display')->middleware('WechatCheck'); //首页
        Route::any('goodslist', 'Api\WechatController@goodslist')->middleware('WechatCheck');//商品列表
        Route::any('online_order', 'Api\WechatController@online_order')->middleware('WechatCheck');//线上确认订单
        Route::any('selftake_order', 'Api\WechatController@selftake_order')->middleware('WechatCheck');//自取确认订单
        Route::any('address_add', 'Api\WechatController@address_add')->middleware('WechatCheck');//添加收货地址
        Route::any('address_list', 'Api\WechatController@address_list')->middleware('WechatCheck');//收货地址列表
        Route::any('selftake_add', 'Api\WechatController@selftake_add')->middleware('WechatCheck');//添加自取信息
        Route::any('selftake_edit', 'Api\WechatController@selftake_edit')->middleware('WechatCheck');//编辑自取信息
        Route::any('selftake_list', 'Api\WechatController@selftake_list')->middleware('WechatCheck');//自取信息列表
        Route::any('onlineOrderDetail', 'Api\WechatController@onlineOrderDetail')->middleware('WechatCheck');//线上订单详情
        Route::any('selftakeOrderDetail', 'Api\WechatController@selftakeOrderDetail')->middleware('WechatCheck');//自取订单详情
    });

});
/**********************微信端*********************/

/*********************接口路由*************************/
Route::group(['prefix' => 'api'], function () {
    //微信通用路由组
    Route::group(['prefix' => 'wechat'], function () {
        Route::any('response/{appid}', 'Fansmanage\WechatController@response');//开放平台控制公众平台回复函数
        Route::any('open', 'Fansmanage\WechatController@open');//接受公众号收授权推送消息
        Route::any('auth', 'Fansmanage\WechatController@auth');//公众号授权链接页面
        Route::any('redirect', 'Fansmanage\WechatController@redirect');//公众号授权回调链接
        Route::any('web_redirect', 'Fansmanage\WechatController@web_redirect');//网页授权回调路由
        Route::any('open_web_redirect', 'Fansmanage\WechatController@open_web_redirect');
        Route::any('pull_authorizer_data', 'Fansmanage\WechatController@pull_authorizer_data');//获取微信平台的授权信息
        Route::any('test', 'Fansmanage\WechatController@test');//测试函数
    });

    //androidRetail-app接口
    Route::group(['prefix' => 'androidRetailApi'], function () {
        Route::post('login', 'Api\AndroidRetailApiController@login')->middleware('AndroidRetailApiCheck');//登入接口
        Route::post('goodscategory', 'Api\AndroidRetailApiController@goodscategory')->middleware('AndroidRetailApiCheck');//商品分类接口
        Route::post('goodslist', 'Api\AndroidRetailApiController@goodslist')->middleware('AndroidRetailApiCheck');//商品列表接口
        Route::post('order_check', 'Api\AndroidRetailApiController@order_check')->middleware('AndroidRetailApiCheck');//订单提交接口
        Route::post('cancel_order', 'Api\AndroidRetailApiController@cancel_order')->middleware('AndroidRetailApiCheck');//取消订单接口
        Route::post('order_list', 'Api\AndroidRetailApiController@order_list')->middleware('AndroidRetailApiCheck');//订单列表接口
        Route::post('order_detail', 'Api\AndroidRetailApiController@order_detail')->middleware('AndroidRetailApiCheck');//订单详情接口
        Route::post('cash_payment', 'Api\AndroidRetailApiController@cash_payment')->middleware('AndroidRetailApiCheck');//现金支付接口
        Route::post('other_payment', 'Api\AndroidRetailApiController@other_payment')->middleware('AndroidRetailApiCheck');//其他支付接口
        Route::post('allow_zero_stock', 'Api\AndroidRetailApiController@allow_zero_stock')->middleware('AndroidRetailApiCheck');//开启/关闭零库存开单接口
        Route::post('change_stock_role', 'Api\AndroidRetailApiController@change_stock_role')->middleware('AndroidRetailApiCheck');//下单减库存/付款减库存接口
        Route::post('stock_cfg', 'Api\AndroidRetailApiController@stock_cfg')->middleware('AndroidRetailApiCheck');//查询店铺设置
        Route::post('code', 'Api\AndroidRetailApiController@code')->middleware('AndroidRetailApiCheck');//签入二维码
    });

    //androidSimple-app简版接口
    Route::group(['prefix' => 'androidSimpleApi'], function () {
        Route::post('login', 'Api\AndroidSimpleApiController@login')->middleware('AndroidSimpleApiCheck');//登入接口
        Route::post('goodscategory', 'Api\AndroidSimpleApiController@goodscategory')->middleware('AndroidSimpleApiCheck');//商品分类接口
        Route::post('goodslist', 'Api\AndroidSimpleApiController@goodslist')->middleware('AndroidSimpleApiCheck');//商品列表接口
        Route::post('order_check', 'Api\AndroidSimpleApiController@order_check')->middleware('AndroidSimpleApiCheck');//订单提交接口
        Route::post('cancel_order', 'Api\AndroidSimpleApiController@cancel_order')->middleware('AndroidSimpleApiCheck');//取消订单接口
        Route::any('order_list', 'Api\AndroidSimpleApiController@order_list')->middleware('AndroidSimpleApiCheck');//订单列表接口
        Route::post('order_detail', 'Api\AndroidSimpleApiController@order_detail')->middleware('AndroidSimpleApiCheck');//订单详情接口
        Route::post('cash_payment', 'Api\AndroidSimpleApiController@cash_payment')->middleware('AndroidSimpleApiCheck');//现金支付接口
        Route::post('other_payment', 'Api\AndroidSimpleApiController@other_payment')->middleware('AndroidSimpleApiCheck');//其他支付接口
        Route::post('allow_zero_stock', 'Api\AndroidSimpleApiController@allow_zero_stock')->middleware('AndroidSimpleApiCheck');//开启/关闭零库存开单接口
        Route::post('change_stock_role', 'Api\AndroidSimpleApiController@change_stock_role')->middleware('AndroidSimpleApiCheck');//下单减库存/付款减库存接口
        Route::post('stock_cfg', 'Api\AndroidSimpleApiController@stock_cfg')->middleware('AndroidSimpleApiCheck');//查询店铺设置
    });

    //wechat接口
    Route::group(['prefix' => 'wechatApi'], function () {
        Route::any('store_list', 'Api\WechatApiController@store_list')->middleware('WechatApiCheck');//店铺列表
        Route::any('category', 'Api\WechatApiController@category')->middleware('WechatApiCheck');//店铺分类接口
        Route::any('goods_list', 'Api\WechatApiController@goods_list')->middleware('WechatApiCheck');//店铺商品接口
        Route::any('shopping_cart_add', 'Api\WechatApiController@shopping_cart_add')->middleware('WechatApiCheck');//购物车添加商品
        Route::any('shopping_cart_reduce', 'Api\WechatApiController@shopping_cart_reduce')->middleware('WechatApiCheck');//购物车减少商品
        Route::any('shopping_cart_list', 'Api\WechatApiController@shopping_cart_list')->middleware('WechatApiCheck');//购物车列表
        Route::any('shopping_cart_empty', 'Api\WechatApiController@shopping_cart_empty')->middleware('WechatApiCheck');//购物车列表
        Route::any('address', 'Api\WechatApiController@address')->middleware('WechatApiCheck');//查询用户默认收货地址信息
        Route::any('selftake', 'Api\WechatApiController@selftake')->middleware('WechatApiCheck');//用户默认取货信息
        Route::any('address_add', 'Api\WechatApiController@address_add')->middleware('WechatApiCheck');//添加收货地址
        Route::any('address_list', 'Api\WechatApiController@address_list')->middleware('WechatApiCheck');//收货地址列表
        Route::any('address_edit', 'Api\WechatApiController@address_edit')->middleware('WechatApiCheck');//编辑用户收货地址
        Route::any('address_delete', 'Api\WechatApiController@address_delete')->middleware('WechatApiCheck');//删除用户收货地址
        Route::any('address_status', 'Api\WechatApiController@address_status')->middleware('WechatApiCheck');//设置为默认收货地址
        Route::any('selftake_add', 'Api\WechatApiController@selftake_add')->middleware('WechatApiCheck');//用户添加取货信息
        Route::any('selftake_list', 'Api\WechatApiController@selftake_list')->middleware('WechatApiCheck');//用户取货信息列表
        Route::any('selftake_edit', 'Api\WechatApiController@selftake_edit')->middleware('WechatApiCheck');//编辑用户取货信息
        Route::any('selftake_delete', 'Api\WechatApiController@selftake_delete')->middleware('WechatApiCheck');//删除用户取货信息
        Route::any('selftake_status', 'Api\WechatApiController@selftake_status')->middleware('WechatApiCheck');//设置默认取货信息
        Route::any('order_submit', 'Api\WechatApiController@order_submit')->middleware('WechatApiCheck');//订单提交
        Route::any('online_order_list', 'Api\WechatApiController@online_order_list')->middleware('WechatApiCheck');//线上订单列表
        Route::any('online_order_detail', 'Api\WechatApiController@online_order_detail')->middleware('WechatApiCheck');//线上订单详情
        Route::any('selftake_order_list', 'Api\WechatApiController@selftake_order_list')->middleware('WechatApiCheck');//自取订单列表
        Route::any('selftake_order_detail', 'Api\WechatApiController@selftake_order_detail')->middleware('WechatApiCheck');//自取订单详情
        Route::any('cancel_online_order', 'Api\WechatApiController@cancel_online_order')->middleware('WechatApiCheck');//取消线上订单
        Route::any('cancel_selftake_order', 'Api\WechatApiController@cancel_selftake_order')->middleware('WechatApiCheck');//取消自取订单
        Route::any('selftake_info', 'Api\WechatApiController@selftake_info')->middleware('WechatApiCheck');//取消自取订单
        Route::any('select_address', 'Api\WechatApiController@select_address')->middleware('WechatApiCheck');//选择收货地址
        Route::any('address_info', 'Api\WechatApiController@address_info')->middleware('WechatApiCheck');//单条收货地址
        Route::any('dispatch_mould', 'Api\WechatApiController@dispatch_mould')->middleware('WechatApiCheck');//查询运费价格
    });


    Route::group(['prefix' => 'authApi'], function () {
        // 零壹服务授权
        Route::any('zerone_auth', 'Api\AuthApiController@getZeroneAuth')->middleware('WechatCheck');
        // 商户公众号授权
        Route::any('shop_auth', 'Api\AuthApiController@getShopAuth')->middleware('WechatCheck');
        // 授权完毕中转站
        Route::any('change_trains', 'Api\AuthApiController@changeTrains')->middleware('WechatCheck');


        // 测试
        Route::any('test11', 'Api\AuthApiController@test11')->middleware('WechatCheck');
        Route::any('test12', 'Api\AuthApiController@test12')->middleware('WechatCheck');

    });

});
/*********************接口路由*************************/


/***************************对接支付接口路由*********************************/
Route::group(['prefix' => 'pay'], function () {
    //盛付通支付通用路由组
    Route::group(['prefix' => 'sft'], function () {
        Route::any('test', 'Pay\SftController@test');//测试函数
        Route::any('notify', 'Pay\SftController@notify');//测试函数
        Route::any('test2', 'Pay\SftController@test2');//测试函数
        Route::any('test3', 'Pay\SftController@test3');//测试函数
        Route::any('test4', 'Pay\SftController@test4');//测试函数
        Route::any('test5', 'Pay\SftController@test5');//测试函数
        Route::any('test6', 'Pay\SftController@test6');//测试函数
        Route::any('test7', 'Pay\SftController@test7');//测试函数
        Route::any('test8', 'Pay\SftController@test8');//测试函数
        Route::any('test9', 'Pay\SftController@test9');//测试函数
        Route::any('test10', 'Pay\SftController@test10');//测试函数
        Route::any('test11', 'Pay\SftController@test11')->middleware("UserCheck");//测试函数
        Route::any('test12', 'Pay\SftController@test12');//测试函数
        Route::any('test13', 'Pay\WxController@test13');//测试函数
    });


    // 微信支付通用路由组
    Route::group(['prefix' => 'wx'], function () {
        // 企业支付到银行卡接口
        Route::post('pay_bank', 'Pay\WxController@pay_bank')->middleware("WxPayCheckAjax");
        // 查询企业支付到银行卡接口
        Route::post('query_bank', 'Pay\WxController@query_bank')->middleware("WxPayCheckAjax");
        // 企业支付到零钱接口
        Route::post('transfers', 'Pay\WxController@transfers')->middleware("WxPayCheckAjax");
        // 查询企业支付到零钱接口
        Route::post('gettransferinfo', 'Pay\WxController@gettransferinfo')->middleware("WxPayCheckAjax");
        // 发送普通红包
        Route::post('sendredpack', 'Pay\WxController@sendredpack')->middleware("WxPayCheckAjax");
        // 发送裂变红包
        Route::post('sendgroupredpack', 'Pay\WxController@sendgroupredpack')->middleware("WxPayCheckAjax");
        // 红包查询记录
        Route::post('gethbinfo', 'Pay\WxController@gethbinfo')->middleware("WxPayCheckAjax");
        // 扫码下订单
        Route::post('nativeOrder', 'Pay\WxController@nativeOrder')->middleware("WxPayCheckAjax");
        // 微信公众号下订单
        Route::post('jsApiOrder', 'Pay\WxController@jsApiOrder')->middleware("WxPayCheckAjax");
//        // 统一下单接口
//        Route::post('unifiedOrder', 'Pay\WxController@unifiedOrder')->$this->middleware("WxPayCheckAjax");
        // 关闭订单接口
        Route::post('closeOrder', 'Pay\WxController@closeOrder')->middleware("WxPayCheckAjax");
        // 刷卡支付
        Route::post('microOrder', 'Pay\WxController@microOrder')->middleware("WxPayCheckAjax");
        // 订单查询接口
        Route::post('orderQuery', 'Pay\WxController@orderQuery')->middleware("WxPayCheckAjax");
        // 退款接口
        Route::post('refund', 'Pay\WxController@refund')->middleware("WxPayCheckAjax");
        // 退款订单查询
        Route::post('refundQuery', 'Pay\WxController@refundQuery')->middleware("WxPayCheckAjax");
        // 下载对账单
        Route::post('downloadBill', 'Pay\WxController@downloadBill')->middleware("WxPayCheckAjax");
        // 获取微信支付支持的银行的信息
        Route::get('getBankInfo', 'Pay\WxController@getBankInfo');

        // 测试地址
        Route::get('demo', 'Pay\WxController@demo');
    });
});
/***************************对接支付接口路由*********************************/