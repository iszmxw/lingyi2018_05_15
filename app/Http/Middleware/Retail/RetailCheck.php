<?php
/**
 * 检测是否登录的中间件
 */

namespace App\Http\Middleware\Retail;

use App\Models\Account;
use App\Models\Program;
use Closure;
use Session;
use Illuminate\Support\Facades\Redis;

class RetailCheck
{
    public function handle($request, Closure $next)
    {
        $route_name = $request->path();     //获取当前的页面路由
        switch ($route_name) {
            /*****登录页,如果已经登录则不需要再次登录*****/
            case "retail/login":                                //登录页,如果已经登录则不需要再次登录
                $sess_key = Session::get('retail_account_id');  //获取用户登录存储的SessionId
                if (!empty($sess_key)) {                         //如果不为空跳转到选择零售店铺组织页面
                    return redirect('retail');
                }
                break;
            /****仅检测是否登录及是否具有权限****/
            case "retail":                             //后台首页
            case "retail/retail_list":                 //所有零售店铺列表
            case "retail/retail_switch":               //退出切换店铺
            case "retail/account/profile":             //账号信息
            case "retail/account/safe_password":       //安全密码设置
            case "retail/account/password":            //密码设置

            case "retail/category/category_add":       //添加商品分类
            case "retail/category/category_list":      //商品分类列表
            case "retail/goods/goods_add":             //添加商品
            case "retail/goods/goods_edit":            //编辑商品
            case "retail/goods/goods_list":            //商品列表

            case "retail/subordinate/subordinate_add": //添加下级人员
            case "retail/subordinate/subordinate_list"://下级人员列表

            case "retail/order/order_spot":             //订单管理-现场订单
            case "retail/order/order_spot_detail":      //订单管理-现场订单详情

            case "retail/dispatch/dispatch_add":      //运费模板-添加运费模板
            case "retail/dispatch/dispatch_list":     //运费模板-运费模板列表
            case "retail/dispatch/dispatch_edit":     //运费模板-编辑运费模板

            case "retail/billing/purchase_goods":     //从供应商进货管理
            case "retail/billing/loss_goods":         //进销存管理--报损开单管理
            case "retail/billing/check_goods":        //进销存管理--盘点核对开单管理
            case "retail/billing/stock_list":         //进销存管理--库存列表

            case "retail/invoicing/purchase_goods":     //进销存管理--供应商到货开单
            case "retail/invoicing/return_goods":       //进销存管理--供应商退货开单
            case "retail/invoicing/loss_goods":         //进销存管理--报损开单
            case "retail/invoicing/check_goods":        //进销存管理--盘点核对开单

            case "retail/supplier/supplier_add":        //进销存管理--添加供应商
            case "retail/supplier/supplier_list":        //进销存管理--供应商列表

            case "retail/user/user_list":               //用户管理-粉丝用户管理

            case "retail/paysetting/payconfig":        //支付设置-收款信息设置
            case "retail/paysetting/shengpay_add":     //支付设置-添加终端机器号信息
            case "retail/paysetting/shengpay_list":    //支付设置-终端机器号信息列表
                $re = $this->checkLoginAndRule($request);//判断是否登录
                return self::format_response($re, $next);
                break;
        }
        return $next($request);
    }

    //检测是否admin或是否有权限
    public function checkLoginAndRule($request)
    {
        $re = $this->checkIsLogin($request);            //判断是否登录
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

    //部分页面检测用户是否admin，否则检测是否有权限


    public function checkIsLogin($request)
    {
        $sess_key = Session::get('retail_account_id');              //获取用户登录存储的SessionId
        if (!empty($sess_key)) {
            $sess_key = Session::get('retail_account_id');          //获取管理员ID
            $sess_key = decrypt($sess_key);                         //解密管理员ID
            Redis::connect('zeo');                                  //连接到我的缓存服务器
            $admin_data = Redis::get('retail_system_admin_data_' . $sess_key);//获取管理员信息
            $menu_data = Redis::get('zerone_system_menu_10_' . $sess_key);
            $son_menu_data = Redis::get('zerone_system_son_menu_10_' . $sess_key);
            $admin_data = unserialize($admin_data);                 //解序列我的信息
            $menu_data = unserialize($menu_data);                  //解序列一级菜单
            $son_menu_data = unserialize($son_menu_data);          //解序列子菜单
            $request->attributes->add(['admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]); //添加参数
            return self::res(1, $request);                     //把参数传递到下一个中间件
        } else {                                                      //如果为空跳转到登录页面
            return self::res(0, redirect('retail/login'));
        }
    }

    /**
     * 1、普通页面检测用户是否登录
     * 2、工厂方法返回结果
     * 3、格式化返回值
     */
    //1、普通页面检测用户是否登录
    public static function res($status, $response)
    {
        return ['status' => $status, 'response' => $response];
    }

    //2、工厂方法返回结果

    public function checkHasRule($request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        if ($admin_data['id'] <> 1 && $admin_data['is_super'] <> 1) {
            $route_name = $request->path();//获取当前的页面路由

            //查询用户所具备的所有节点的路由
            $account_info = Account::getOne([['id', $admin_data['id']]]);
            $account_routes = [];
            foreach ($account_info->nodes as $key => $val) {
                $account_routes[] = $val->route_name;
            }
            //查询该程序下所有节点的路由
            $program_info = Program::getOne([['id', 10]]);
            $program_routes = [];
            foreach ($program_info->nodes as $key => $val) {
                $program_routes[] = $val->route_name;
            }

            //计算数组差集，获取用户所没有的权限
            $unset_routes = array_diff($program_routes, $account_routes);
            //如果跳转的路由不在该程序的所有节点中。则报错
            if (!in_array($route_name, $program_routes) && !in_array($route_name, config('app.retail_route_except'))) {
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

    //3、格式化返回值

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