<?php

namespace App\Services\ZeroneRedis;

use Illuminate\Support\Facades\Redis;
use App\Models\ProgramMenu;
use App\Models\Program;
use App\Models\Account;

class ZeroneRedis
{
    //内部方法，生成账号数据Redis缓存
    /*
     * key_id  - 目前以用户ID作为Redis的key的关键字
     * admin_data - 需要缓存的数据
     */
    public static function create_account_cache($key_id, $admin_data)
    {
        $admin_data = serialize($admin_data);//序列化数组数据
        Redis::connection('zeo');//连接到我的redis服务器
        $data_key = 'zerone_system_admin_data_' . $key_id;
        Redis::set($data_key, $admin_data);
    }

    /*
     * 分公司平台
     */
    public static function create_agent_account_cache($key_id, $admin_data)
    {
        $admin_data = serialize($admin_data);//序列化数组数据
        Redis::connection('zeo');//连接到我的redis服务器-商户平台使用
        $data_key = 'agent_system_admin_data_' . $key_id;
        Redis::set($data_key, $admin_data);
    }

    /*
     * 总店平台
     */
    public static function create_catering_account_cache($key_id, $admin_data)
    {
        $admin_data = serialize($admin_data);//序列化数组数据
        Redis::connection('zeo');//连接到我的redis服务器-商户平台使用
        $data_key = 'catering_system_admin_data_' . $key_id;
        Redis::set($data_key, $admin_data);
    }

    /*
     * 粉丝管理平台
     */
    public static function create_fansmanage_account_cache($key_id, $admin_data)
    {
        $admin_data = serialize($admin_data);//序列化数组数据
        Redis::connection('zeo');//连接到我的redis服务器-商户平台使用
        $data_key = 'fansmanage_system_admin_data_' . $key_id;
        Redis::set($data_key, $admin_data);
    }

    /*
     * 餐饮分店平台简版
     */
    public static function create_catering_branch_account_cache($key_id, $admin_data)
    {
        $admin_data = serialize($admin_data);//序列化数组数据
        Redis::connection('zeo');//连接到我的redis服务器-餐饮分店平台使用
        $data_key = 'catering_branch_system_admin_data_' . $key_id;
        Redis::set($data_key, $admin_data);
    }

    /*
     * 餐饮分店平台
     */
    public static function create_branch_account_cache($key_id, $admin_data)
    {
        $admin_data = serialize($admin_data);//序列化数组数据
        Redis::connection('zeo');//连接到我的redis服务器-餐饮分店平台使用
        $data_key = 'branch_system_admin_data_' . $key_id;
        Redis::set($data_key, $admin_data);
    }

    /*
     * 零售版店铺管理系统
     */
    public static function create_retail_account_cache($key_id, $admin_data)
    {
        $admin_data = serialize($admin_data);//序列化数组数据
        Redis::connection('zeo');//连接到我的redis服务器-餐饮分店平台使用
        $data_key = 'retail_system_admin_data_' . $key_id;
        Redis::set($data_key, $admin_data);
    }

    /*
     * 简版店铺管理系统（重写的新版简版）
     */
    public static function create_simple_account_cache($key_id, $admin_data)
    {
        $admin_data = serialize($admin_data);//序列化数组数据
        Redis::connection('zeo');//连接到我的redis服务器-简版店铺管理系统使用
        $data_key = 'simple_system_admin_data_' . $key_id;
        Redis::set($data_key, $admin_data);
    }

    /*
     * 简版店铺管理系统（重写的新版简版）---购物车缓存
     */
    public static function create_shopping_cart($key_id, $admin_data)
    {
        $admin_data = serialize($admin_data);//序列化数组数据
        Redis::connection('zeo');//连接到我的redis服务器-简版店铺管理系统使用
        Redis::set($key_id, $admin_data);
    }


    //内部方法，生成对应程序及账号的菜单
    /*
     * id - 用户的ID
     */
    public static function create_menu_cache($id, $program_id)
    {
        $menu = ProgramMenu::getList([['parent_id', 0], ['program_id', $program_id]], 0, 'displayorder', 'asc')->toArray();//获取零壹管理系统的一级菜单
        if ($id <> 1) {
            //查询用户所具备的所有节点的路由
            $account_info = Account::getOne([['id', $id]]);
            $account_routes = [];
            foreach ($account_info->nodes as $key => $val) {
                $account_routes[] = $val->route_name;
            }
            //查询该程序下所有节点的路由
            $program_info = Program::getOne([['id', $program_id]]);
            $program_routes = [];
            foreach ($program_info->nodes as $key => $val) {
                $program_routes[] = $val->route_name;
            }
            //获取用户所没有的权限
            $unset_routes = array_diff($program_routes, $account_routes);
            foreach ($menu as $key => $val) {
                $sm = ProgramMenu::son_menu($val['id'])->toArray();//获取子菜单列表
                foreach ($sm as $k => $v) {
                    //判断子菜单的路由是否在程序的所有路由中或是否在无需判断的过滤路由中，不在的话，取消菜单
                    if (!in_array($v['menu_route'], $program_routes) && !in_array($v['menu_route'], config('app.zerone_route_except'))) {
                        unset($sm[$k]);
                    } //循环判断用户是否具有子菜单权限,不具备的话，取消菜单
                    elseif (in_array($v['menu_route'], $unset_routes)) {
                        unset($sm[$k]);
                    }
                }
                if (count($sm) < 1) {//
                    unset($menu[$key]);
                } else {
                    $son_menu[$val['id']] = $sm;
                }
                unset($sm);//销毁用于判断的变量
            }

        } else {
            $son_menu = [];
            foreach ($menu as $key => $val) {//获取一级菜单下的子菜单
                $son_menu[$val['id']] = ProgramMenu::son_menu($val['id'])->toArray();
            }
        }
        $menu = serialize($menu);
        if (!empty($son_menu)) {
            $son_menu = serialize($son_menu);
        }
        Redis::connection('zeo');//连接到我的redis服务器
        $menu_key = 'zerone_system_menu_' . $program_id . '_' . $id;  //一级菜单的Redis主键。
        $son_menu_key = 'zerone_system_son_menu_' . $program_id . '_' . $id;//子菜单的Redis主键
        Redis::set($menu_key, $menu);
        if (!empty($son_menu)) {
            Redis::set($son_menu_key, $son_menu);
        }
    }
    //
    //内部方法，生成对应程序及账号的菜单
    /*
     * id - 用户的ID
     */

    public static function create_agent_menu_cache($id)
    {
        $menu = ProgramMenu::getList([['parent_id', 0], ['program_id', '2']], 0, 'displayorder', 'asc')->toArray();//获取零壹管理系统的一级菜单
        $account_info = Account::getOne([['id', $id]]);
        /******************仍然需要修改*******************/
        if ($id <> 1 && $account_info['parent_id'] <> 1) {
            //查询用户所具备的所有节点的路由

            $account_routes = [];
            foreach ($account_info->nodes as $key => $val) {
                $account_routes[] = $val->route_name;
            }
            //查询该程序下所有节点的路由
            $program_info = Program::getOne([['id', 2]]);
            $program_routes = [];
            foreach ($program_info->nodes as $key => $val) {
                $program_routes[] = $val->route_name;
            }
            //获取用户所没有的权限
            $unset_routes = array_diff($program_routes, $account_routes);
            foreach ($menu as $key => $val) {
                $sm = ProgramMenu::son_menu($val['id'])->toArray();//获取子菜单列表

                foreach ($sm as $k => $v) {
                    //判断子菜单的路由是否在程序的所有路由中或是否在无需判断的过滤路由中，不在的话，取消菜单
                    if (!in_array($v['menu_route'], $program_routes) && !in_array($v['menu_route'], config('app.zerone_route_except'))) {
                        unset($sm[$k]);
                    } //循环判断用户是否具有子菜单权限,不具备的话，取消菜单
                    elseif (in_array($v['menu_route'], $unset_routes)) {
                        unset($sm[$k]);
                    }

                }
                if (count($sm) < 1) {//
                    unset($menu[$key]);
                } else {
                    $son_menu[$val['id']] = $sm;
                }
                unset($sm);//销毁用于判断的变量
            }
            /**
             * 未完成，这里准备查询用户权限。
             */
        } else {
            $son_menu = [];
            foreach ($menu as $key => $val) {//获取一级菜单下的子菜单
                $son_menu[$val['id']] = ProgramMenu::son_menu($val['id'])->toArray();
            }
        }
        $menu = serialize($menu);
        $son_menu = serialize($son_menu);
        Redis::connection('proxy');//连接到我的redis服务器
        $menu_key = 'proxy_system_menu_' . $id;  //一级菜单的Redis主键。
        $son_menu_key = 'proxy_system_son_menu_' . $id;//子菜单的Redis主键
        Redis::set($menu_key, $menu);
        Redis::set($son_menu_key, $son_menu);
    }

    //内部方法，生成总店系统账号的菜单
    /*
     * id - 用户的ID
     */
    public static function create_catering_menu_cache($id)
    {
        $menu = ProgramMenu::getList([['parent_id', 0], ['program_id', '4']], 0, 'id', 'asc');//获取商户系统的一级菜单

        $son_menu = [];
        foreach ($menu as $key => $val) {//获取一级菜单下的子菜单
            $son_menu[$val->id] = ProgramMenu::son_menu($val->id);
        }
        if ($id <> 1) {
            /**
             * 未完成，这里准备查询用户权限。
             */
        }
        $menu = serialize($menu);
        $son_menu = serialize($son_menu);
        Redis::connection('catering');//连接到我的redis服务器——商户平台使用
        $menu_key = 'catering_system_menu_' . $id;  //一级菜单的Redis主键。
        $son_menu_key = 'catering_system_son_menu_' . $id;//子菜单的Redis主键
        Redis::set($menu_key, $menu);
        Redis::set($son_menu_key, $son_menu);
    }

    //内部方法，生成粉丝管理系统账号的菜单
    public static function create_fansmanage_menu_cache($id)
    {
        $menu = ProgramMenu::getList([['parent_id', 0], ['program_id', '3']], 0, 'id', 'asc');//获取粉丝管理系统的一级菜单

        $son_menu = [];
        foreach ($menu as $key => $val) {//获取一级菜单下的子菜单
            $son_menu[$val->id] = ProgramMenu::son_menu($val->id);
        }
        if ($id <> 1) {
            /**
             * 未完成，这里准备查询用户权限。
             */
        }
        $menu = serialize($menu);
        $son_menu = serialize($son_menu);
        Redis::connection('fansmanage');//连接到我的redis服务器——商户平台使用
        $menu_key = 'fansmanage_system_menu_' . $id;  //一级菜单的Redis主键。
        $son_menu_key = 'fansmanage_system_son_menu_' . $id;//子菜单的Redis主键
        Redis::set($menu_key, $menu);
        Redis::set($son_menu_key, $son_menu);
    }

    //内部方法，生成餐饮分店系统账号的菜单
    /*
     * id - 用户的ID
     */
    public static function create_branch_menu_cache($id)
    {
        $menu = ProgramMenu::getList([['parent_id', 0], ['program_id', '5']], 0, 'id', 'asc');//获取分店管理平台系统的一级菜单
        $son_menu = [];
        foreach ($menu as $key => $val) {//获取一级菜单下的子菜单
            $son_menu[$val->id] = ProgramMenu::son_menu($val->id);
        }
        if ($id <> 1) {
            /**
             * 未完成，这里准备查询用户权限。
             */
        }
        $menu = serialize($menu);
        $son_menu = serialize($son_menu);
        Redis::connection('branch');//连接到我的redis服务器——商户平台使用
        $menu_key = 'branch_system_menu_' . $id;  //一级菜单的Redis主键。
        $son_menu_key = 'branch_system_son_menu_' . $id;//子菜单的Redis主键
        Redis::set($menu_key, $menu);
        Redis::set($son_menu_key, $son_menu);
    }

    //内部方法，生成餐饮分店系统账号的菜单
    /*
     * id - 用户的ID
     */
    public static function create_retail_menu_cache($id)
    {
        $menu = ProgramMenu::getList([['parent_id', 0], ['program_id', '9']], 0, 'id', 'asc');//获取分店管理平台系统的一级菜单
        $son_menu = [];
        foreach ($menu as $key => $val) {//获取一级菜单下的子菜单
            $son_menu[$val->id] = ProgramMenu::son_menu($val->id);
        }
        if ($id <> 1) {
            /**
             * 未完成，这里准备查询用户权限。
             */
        }
        $menu = serialize($menu);
        $son_menu = serialize($son_menu);
        Redis::connection('retail');//连接到我的redis零售——商户平台使用
        $menu_key = 'retail_system_menu_' . $id;  //一级菜单的Redis主键。
        $son_menu_key = 'retail_system_son_menu_' . $id;//子菜单的Redis主键
        Redis::set($menu_key, $menu);
        Redis::set($son_menu_key, $son_menu);
    }

    //内部方法，生成餐饮分店系统账号的菜单
    /*
     * id - 用户的ID
     */
    public static function create_simple_menu_cache($id)
    {
        $menu = ProgramMenu::getList([['parent_id', 0], ['program_id', '9']], 0, 'id', 'asc');//获取分店管理平台系统的一级菜单
        $son_menu = [];
        foreach ($menu as $key => $val) {//获取一级菜单下的子菜单
            $son_menu[$val->id] = ProgramMenu::son_menu($val->id);
        }
        if ($id <> 1) {
            /**
             * 未完成，这里准备查询用户权限。
             */
        }
        $menu = serialize($menu);
        $son_menu = serialize($son_menu);
        Redis::connection('simple');//连接到我的redis零售——商户平台使用
        $menu_key = 'simple_system_menu_' . $id;  //一级菜单的Redis主键。
        $son_menu_key = 'simple_system_son_menu_' . $id;//子菜单的Redis主键
        Redis::set($menu_key, $menu);
        Redis::set($son_menu_key, $son_menu);
    }
}

?>