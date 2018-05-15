<?php
/**
 * 检测是否登录的中间件
 */

namespace App\Http\Middleware\Zerone;

use Closure;
use Session;
use Illuminate\Support\Facades\Redis;
use App\Models\Program;
use App\Models\Account;

class ZeroneCheck
{
    public function handle($request, Closure $next)
    {
        $route_name = $request->path();//获取当前的页面路由
        switch ($route_name) {
            /*****登录页,如果已经登录则不需要再次登录*****/
            // 登录页,如果已经登录则不需要再次登录
            case "zerone/login":
                // 获取用户登录存储的SessionId
                $sess_key = Session::get('zerone_account_id');
                // 如果不为空跳转到首页
                if (!empty($sess_key)) {
                    return redirect('zerone');
                }
                break;

            /****仅检测是否登录及是否具有权限****/

            // 系统管理
            case "zerone":                           //后台首页
            case "zerone/dashboard/operation_log":   //战区管理所有操作记录
            case "zerone/dashboard/login_log":       //战区管理所有登录记录
            case "zerone/dashboard/warzone":         //战区管理首页权限
            case "zerone/dashboard/structure":       //人员结构

                // 个人中心
            case "zerone/personal":                     //个人中心——个人资料
            case "zerone/personal/password_edit":       //个人中心——密码修改
            case "zerone/personal/safe_password":       //个人中心——安全密码设置
            case "zerone/personal/operation_log":       //个人中心——我的操作日志
            case "zerone/personal/login_log":           //个人中心——我的登录日志

                // 下级管理
            case "zerone/role/role_add":                    //添加权限角色
            case "zerone/role/role_list":                   //权限角色列表
            case "zerone/subordinate/subordinate_add":      //添加下级人员
            case "zerone/subordinate/subordinate_list":     //下级人员列表
            case "zerone/subordinate/subordinate_structure"://下级人员列表

                // 分公司管理
            case "zerone/agent/agent_add":                  //添加分公司
            case "zerone/agent/agent_examinelist":          //分公司审核列表
            case "zerone/agent/agent_list":                 //分公司列表
            case "zerone/agent/agent_structure":            //分公司人员架构
            case "zerone/agent/agent_program":              //分公司程序管理
            case "zerone/agent/agent_fansmanage":           //分公司商户划拨

                // 商户管理
            case "zerone/fansmanage/fansmanage_examinelist":  //商户审核列表
            case "zerone/fansmanage/fansmanage_add":          //添加商户
            case "zerone/fansmanage/fansmanage_list":         //商户列表
            case "zerone/fansmanage/fansmanage_structure":    //商户店铺架构
            case "zerone/fansmanage/fansmanage_program":      //商户程序管理
            case "zerone/fansmanage/fansmanage_store":        //商户划拨管理


                //支付审核
            case "zerone/paysetting/payconfig":         //收款信息审核
            case "zerone/paysetting/shengpay":          //pos终端号审核

                $re = $this->checkLoginAndRule($request);//判断是否登录
                return self::format_response($re, $next);
                break;
        }
        return $next($request);
    }

    /**
     * 检测是否admin或是否有权限是否登入
     */
    public function checkLoginAndRule($request)
    {
        $re = $this->checkIsLogin($request);//判断是否登录
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkHasRule($re['response']);//判断用户是否admin或是否有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    /**
     * 部分页面检测用户是否admin，否则检测是否有权限
     */
    public function checkHasRule($request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        if ($admin_data['id'] <> 1) {
            $route_name = $request->path();//获取当前的页面路由

            //查询用户所具备的所有节点的路由
            $account_info = Account::getOne([['id', $admin_data['id']]]);
            $account_routes = [];
            foreach ($account_info->nodes as $key => $val) {
                $account_routes[] = $val->route_name;
            }
            //查询该程序下所有节点的路由
            $program_info = Program::getOne([['id', 1]]);
            $program_routes = [];
            foreach ($program_info->nodes as $key => $val) {
                $program_routes[] = $val->route_name;
            }
            //计算数组差集，获取用户所没有的权限
            $unset_routes = array_diff($program_routes, $account_routes);
            //如果跳转的路由不在该程序的所有节点中。则报错
            if (!in_array($route_name, $program_routes) && !in_array($route_name, config('app.zerone_route_except'))) {
                return self::res(0, response()->json(['data' => '对不起，您不具备权限', 'status' => '0']));
            }
            //如果没有权限，则报错
            if (in_array($route_name, $unset_routes)) {
                return self::res(0, response()->json(['data' => '对不起，您不具备权限', 'status' => '0']));
            }
            return self::res(1, $request);
        } else {
            return self::res(1, $request);
        }
    }


    /**
     * 普通页面检测用户是否登录
     */
    public function checkIsLogin($request)
    {
        //获取用户登录存储的SessionId
        $sess_key = Session::get('zerone_account_id');
        //如果为空跳转到登录页面
        if (empty($sess_key)) {
            return self::res(0, redirect('zerone/login'));
        } else {
            $sess_key = decrypt($sess_key);//解密管理员ID
            Redis::connect('zeo');//连接到我的缓存服务器
            $admin_data = Redis::get('zerone_system_admin_data_' . $sess_key);//获取序列管理员信息
            $menu_data = Redis::get('zerone_system_menu_1_' . $sess_key);//获取序列一级菜单
            $son_menu_data = Redis::get('zerone_system_son_menu_1_' . $sess_key);//获取序列子菜单
            $admin_data = unserialize($admin_data);//解序列我的信息
            $menu_data = unserialize($menu_data);//解序列一级菜单
            $son_menu_data = unserialize($son_menu_data);//解序列子菜单
            $request->attributes->add(['admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);//添加参数
            //把参数传递到下一个中间件
            return self::res(1, $request);
        }
    }

    /**
     * 工厂方法返回结果
     */
    public static function res($status, $response)
    {
        return ['status' => $status, 'response' => $response];
    }


    /**
     * 格式化返回值
     */
    public static function format_response($re, Closure $next)
    {
        if ($re['status'] == '0') {
            return $re['response'];
        } else {
            return $next($re['response']);
        }
    }
}

?>