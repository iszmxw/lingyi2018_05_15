<?php

/**
 *   店铺概况模块，包括：
 *   店铺首页，操作日志，登录日志
 */

namespace App\Http\Controllers\Fansmanage;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\LoginLog;
use App\Models\OperationLog;
use App\Models\Organization;
use App\Models\OrganizationAssets;
use App\Models\Program;
use App\Services\ZeroneRedis\ZeroneRedis;
use Illuminate\Http\Request;
use Session;

class ShopController extends Controller
{
    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function display(Request $request)
    {
        // 中间件产生的管理员数据参数
        $admin_data = $request->get('admin_data');
        // 中间件产生的菜单数据参数
        $menu_data = $request->get('menu_data');
        // 中间件产生的子菜单数据参数
        $son_menu_data = $request->get('son_menu_data');
        // 获取当前的页面路由
        $route_name = $request->path();

        // 组织id
        $organization_id = $admin_data['organization_id'];
        // 判断是否为超级管理员
        if ($admin_data['is_super'] == 1 && $admin_data['organization_id'] == 0) {
            // 获取组织名
            $organization_name = $request->organization_name;
            // 获取粉丝管理类型数据
            $where = ['type' => '3'];
            // 查询分店
            $listOrg = Organization::getOrganizationAndAccount($organization_name, $where, 20, 'id', 'ASC');
            // 渲染页面
            return view('Fansmanage/Shop/select_shop', ['listOrg' => $listOrg]);
        } else {
            $where = [['organization_id', $organization_id]];
            // 获取负责人id
            $account_id = Account::getPluck([['organization_id', $organization_id], ['parent_id', 1]], 'id');
            // 如果不是分公司负责人 只允许看自己的登入记录
            if ($account_id != $admin_data['id']) {
                $where[] = ['account_id', $admin_data['id']];
            }
            // 登录记录列表
            $login_log_list = LoginLog::getList($where, 5, 'id');
            // 操作记录列表
            $operation_log_list = OperationLog::getList($where, 5, 'id');
            // 查询分公司人数
            $acc_num = Account::where([['organization_id', $organization_id]])->count();
            // 查询分公司附属商务个数
            $org_num = Organization::where([['parent_id', $organization_id]])->count();
            //查询当前商户用的哪套程序
            $program_id = OrganizationAssets::getPluck(["organization_id" => $admin_data["organization_id"]], 'program_id')->first();
            $program = Program::getOne(['id' => $program_id]);//获取当前粉丝管理系统能使用的资产程序
            //查询商户信息
            $company_info = Organization::getOne(['id' => $admin_data["organization_id"]]);
            // 获取商户的基本信息
            $shop_simple_info = Organization::getShopSimpleInfo($admin_data["organization_id"]);
            // 获取营收情况的数据
            $revenue_info = Organization::getRevenueInfo($admin_data["organization_id"]);
            // 渲染页面
            return view('Fansmanage/Shop/index', ['shop_simple_info' => $shop_simple_info, 'revenue_info' => $revenue_info, 'login_log_list' => $login_log_list, 'company_info' => $company_info, 'program' => $program, 'operation_log_list' => $operation_log_list, 'acc_num' => $acc_num, 'org_num' => $org_num, 'admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
        }
    }

    /**
     * 超级管理员选择商户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function select_shop(Request $request)
    {
        // 中间件产生的管理员数据参数
        $admin_this = $request->get('admin_data');
        // 用户ID
        $account_id = $request->input('account_id');
        // 根据账号查询
        $account_info = Account::getOneAccount([['id', $account_id]]);

        if (!empty($account_info)) {
            // 重新生成缓存的登录信息
            $admin_data = [
                // 用户ID
                'id' => $account_info->id,
                // 用户账号
                'account' => $account_info->account,
                // 组织ID
                'organization_id' => $account_info->organization_id,
                // 是否超级管理员
                'is_super' => '1',
                // 上级ID
                'parent_id' => $account_info->parent_id,
                // 上级树
                'parent_tree' => $account_info->parent_tree,
                // 账号在组织中的深度
                'deepth' => $account_info->deepth,
                // 绑定手机号
                'mobile' => $account_info->mobile,
                // 安全密码-超级管理员
                'safe_password' => $admin_this['safe_password'],
                // 用户状态
                'account_status' => $account_info->status,
            ];
            // 存储登录session_id为当前用户ID
            Session::put('fansmanage_account_id', encrypt(1));

            // 构造用户缓存数据
            if (!empty($account_info->account_info->realname)) {
                $admin_data['realname'] = $account_info->account_info->realname;
            } else {
                $admin_data['realname'] = '未设置';
            }

            if (!empty($account_info->account_roles) && $account_info->account_roles->count() != 0) {
                foreach ($account_info->account_roles as $key => $val) {
                    $account_info->role = $val;
                }
                $admin_data['role_name'] = $account_info->role->role_name;
            } else {
                $admin_data['role_name'] = '角色未设置';
            }

            // 生成账号数据的Redis缓存
            ZeroneRedis::create_fansmanage_account_cache(1, $admin_data);
            // 生成对应账号的系统菜单
            ZeroneRedis::create_fansmanage_menu_cache(1);
            // 返回提示
            return response()->json(['data' => '操作成功', 'status' => '1']);
        } else {
            return response()->json(['data' => '操作失败', 'status' => '0']);
        }
    }

    /**
     * 超级管理员选择店铺
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function switch_status(Request $request)
    {
        $admin_data = $request->get('admin_data');                //中间件产生的管理员数据参数
        $admin_data['organization_id'] = 0;
        ZeroneRedis::create_fansmanage_account_cache(1, $admin_data);//清空所选组织
        return redirect('fansmanage');
    }

    /**
     * 操作日志页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function operation_log(Request $request)
    {
        // 中间件产生的管理员数据参数
        $admin_data = $request->get('admin_data');
        // 中间件产生的菜单数据参数
        $menu_data = $request->get('menu_data');
        // 中间件产生的子菜单数据参数
        $son_menu_data = $request->get('son_menu_data');
        // 获取当前的页面路由
        $route_name = $request->path();
        // 通过登录页账号查询
        $account = $admin_data['account'];
        // 查询时间开始
        $time_st = $request->input('time_st');
        // 查询时间结束
        $time_nd = $request->input('time_nd');
        // 实例化时间格式
        $time_st_format = $time_nd_format = 0;

        // 筛选条件
        if (!empty($time_st) && !empty($time_nd)) {
            // 开始时间转时间戳
            $time_st_format = strtotime($time_st . ' 00:00:00');
            // 结束时间转时间戳
            $time_nd_format = strtotime($time_nd . ' 23:59:59');
        }
        // 处理筛选条件
        $search_data = ['time_st' => $time_st, 'time_nd' => $time_nd];
        // 获取操作记录列表
        $list = OperationLog::getUnionPaginate($account, $time_st_format, $time_nd_format, 10, 'id');
        // 渲染页面
        return view('Fansmanage/Shop/operation_log', ['list' => $list, 'search_data' => $search_data, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //我的登入记录
    public function login_log(Request $request)
    {
        // 中间件产生的管理员数据参数
        $admin_data = $request->get('admin_data');
        // 中间件产生的菜单数据参数
        $menu_data = $request->get('menu_data');
        // 中间件产生的子菜单数据参数
        $son_menu_data = $request->get('son_menu_data');
        // 获取当前的页面路由
        $route_name = $request->path();

        // 通过登录页账号查询
        $account = $admin_data['account'];
        // 查询时间开始
        $time_st = $request->input('time_st');
        // 查询时间结束
        $time_nd = $request->input('time_nd');

        // 实例化时间格式
        $time_st_format = $time_nd_format = 0;
        if (!empty($time_st) && !empty($time_nd)) {
            // 开始时间转时间戳
            $time_st_format = strtotime($time_st . ' 00:00:00');
            // 结束时间转时间戳
            $time_nd_format = strtotime($time_nd . ' 23:59:59');
        }
        // 处理筛选条件
        $search_data = ['time_st' => $time_st, 'time_nd' => $time_nd];
        // 获取登录记录列表
        $list = LoginLog::getUnionPaginate($account, $time_st_format, $time_nd_format, 15, 'id');
        // 渲染页面
        return view('Fansmanage/Shop/login_log', ['list' => $list, 'search_data' => $search_data, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }


    /**
     * 退出登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function quit(Request $request)
    {
        // 清空session 数据
        Session::put('fansmanage_account_id', '');
        return redirect('fansmanage/login');
    }
}