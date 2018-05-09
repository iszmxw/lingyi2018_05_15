<?php
/**
 * 零售管理系统
 * 下属管理
 **/

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountInfo;
use App\Models\OperationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubordinateController extends Controller
{
    //下属添加
    public function subordinate_add(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        return view('Retail/Subordinate/subordinate_add', ['admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
    }

    //添加下级人员数据提交
    public function subordinate_add_check(Request $request)
    {
        $admin_data = $request->get('admin_data');  //中间件产生的管理员数据参数
        $route_name = $request->path();                 //获取当前的页面路由

        $password = $request->input('password');    //登录密码
        $realname = $request->input('realname');    //用户真实姓名
        $mobile = $request->input('mobile');        //用户手机号码

        $key = config("app.retail_encrypt_key");    //获取加密盐（零售系统加密盐）
        $encrypted = md5($password);                    //加密密码第一重
        $encryptPwd = md5("lingyikeji" . $encrypted . $key);//加密密码第二重

        $parent_id = $admin_data['id'];//上级ID是当前用户ID
        $parent_tree = $admin_data['parent_tree'] . $parent_id . ',';//树是上级的树拼接上级的ID；
        $deepth = $admin_data['deepth'] + 1;
        $organization_id = $admin_data['organization_id'];//当前平台组织id

        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        if (Account::checkRowExists([['uuid', $uuid]])) {
            return response()->json(['data' => 'uuid重复，请重新操作！', 'status' => '0']);
        }
        $account = Account::max('account');
        $account = $account + 1;
        if (Account::checkRowExists([['account', $account]])) {//判断零壹管理平台中 ，判断组织中账号是否存在
            return response()->json(['data' => '账号生成错误，请重试', 'status' => '0']);
        } elseif (Account::checkRowExists([['organization_id', $organization_id], ['mobile', $mobile]])) {//判断零壹管理平台中，判断组织中手机号码是否存在；
            return response()->json(['data' => '手机号码已存在', 'status' => '0']);
        } elseif (Account::checkRowExists([['organization_id', '0'], ['mobile', $mobile]])) {//判断手机号码是否超级管理员手机号码
            return response()->json(['data' => '手机号码已存在', 'status' => '0']);
        } else {
            DB::beginTransaction();
            try {
                //添加用户
                $account_id = Account::addAccount(['organization_id' => $organization_id, 'parent_id' => $parent_id, 'parent_tree' => $parent_tree, 'deepth' => $deepth, 'account' => $account, 'password' => $encryptPwd, 'mobile' => $mobile, 'uuid' => $uuid]);
                //添加用户个人信息
                AccountInfo::addAccountInfo(['account_id' => $account_id, 'realname' => $realname]);
                if ($admin_data['is_super'] == 1) {
                    //添加操作日志
                    OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售店铺系统添加了操作员：' . $account);//保存操作记录
                } else {
                    //添加操作日志
                    OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '添加了操作员：' . $account);//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '添加操作员失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '添加操作员成功，账号是：' . $account, 'status' => '1']);
        }
    }

    //下属列表
    public function subordinate_list(Request $request)
    {
        $admin_data = $request->get('admin_data');      //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');        //中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();                     //获取当前的页面路由
        $account = $request->input('account');
        $search_data = ['account' => $account];
        $organization_id = $admin_data['organization_id'];//零壹管理平台只有一个组织
        $parent_tree = $admin_data['parent_tree'] . $admin_data['id'] . ',';
        $list = Account::getPaginage([['organization_id', $organization_id], ['parent_tree', 'like', '%' . $parent_tree . '%'], ['account', 'like', '%' . $account . '%']], 15, 'id');
        return view('Retail/Subordinate/subordinate_list', ['list' => $list, 'search_data' => $search_data, 'admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
    }

    //编辑下级人员
    public function subordinate_edit(Request $request)
    {
        $id = $request->input('id');
        $info = Account::getOne([['id', $id]]);
        return view('Retail/Subordinate/subordinate_edit', ['info' => $info]);
    }

    //编辑下级人员数据提交
    public function subordinate_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');  //中间件产生的管理员数据参数
        $route_name = $request->path();                 //获取当前的页面路由
        $id = $request->input('id');                //要编辑的人员的ID
        $account = $request->input('account');
        $password = $request->input('password');    //登录密码
        $realname = $request->input('realname');    //真实姓名
        $mobile = $request->input('mobile');        //手机号码
        $organization_id = $admin_data['organization_id'];
        if (!empty($password)) {
            $key = config("app.retail_encrypt_key");//获取加密盐
            $encrypted = md5($password);                //加密密码第一重
            $encryptPwd = md5("lingyikeji" . $encrypted . $key);//加密密码第二重
            $data['password'] = $encryptPwd;
        }
        if (Account::checkRowExists([['id', '<>', $id], ['organization_id', $organization_id], ['mobile', $mobile]])) {//判断零壹管理平台中，判断组织中手机号码是否存在；
            return response()->json(['data' => '手机号码已存在', 'status' => '0']);
        } elseif (Account::checkRowExists([['id', '<>', $id], ['organization_id', '0'], ['mobile', $mobile]])) {//判断手机号码是否超级管理员手机号码
            return response()->json(['data' => '手机号码已存在', 'status' => '0']);
        } else {
            DB::beginTransaction();
            try {
                //编辑用户
                $data['mobile'] = $mobile;
                Account::editAccount([['id', $id]], $data);
                if (AccountInfo::checkRowExists([['account_id', $id]])) {
                    AccountInfo::editAccountInfo([['account_id', $id]], ['realname' => $realname]);
                } else {
                    AccountInfo::addAccountInfo(['account_id' => $id, 'realname' => $realname]);
                }
                if ($admin_data['is_super'] == 2) {
                    //添加操作日志
                    OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统编辑了操作员：' . $account);//保存操作记录
                } else {
                    //添加操作日志
                    OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '编辑了操作员：' . $account);//保存操作记录
                }
                //添加操作日志
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '编辑操作员失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '编辑操作员成功', 'status' => '1']);
        }
    }

    //输入安全密码判断是否能冻结的页面
    public function subordinate_lock(Request $request)
    {
        $id = $request->input('id');            //要操作的用户的ID
        $account = $request->input('account');  //要操作的管理员的账号,用于记录
        $status = $request->input('status');    //当前用户的状态
        return view('Retail/Subordinate/subordinate_lock', ['id' => $id, 'account' => $account, 'status' => $status]);
    }

    //冻结解冻下级人员
    public function subordinate_lock_check(Request $request)
    {
        $admin_data = $request->get('admin_data');      //中间件产生的管理员数据参数
        $route_name = $request->path();                     //获取当前的页面路由
        $id = $request->input('id');                    //要操作的用户的ID
        $account = $request->input('account');          //要操作的用户的账号,用于记录
        $status = $request->input('status');            //当前用户的状态
        DB::beginTransaction();
        try {
            if ($status == 1) {
                Account::editAccount([['id', $id]], ['status' => '0']);
                if ($admin_data['is_super'] == 1) {
                    //添加操作日志
                    OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统冻结了操作员：' . $account);//保存操作记录
                } else {
                    //添加操作日志
                    OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '冻结了操作员：' . $account);//保存操作记录
                }
            } else {
                Account::editAccount([['id', $id]], ['status' => '1']);
                if ($admin_data['is_super'] == 1) {
                    //添加操作日志
                    OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统解冻了操作员：' . $account);//保存操作记录
                } else {
                    //添加操作日志
                    OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '解冻了操作员：' . $account);//保存操作记录
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '操作失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '操作成功', 'status' => '1']);
    }

    //删除下级人员确定
    public function subordinate_delete(Request $request)
    {
        $id = $request->input('id');            //要操作的用户的ID
        $account = $request->input('account');  //要操作的管理员的账号,用于记录
        return view('Catering/Subordinate/subordinate_delete', ['id' => $id, 'account' => $account]);
    }

    //删除下级人员
    public function subordinate_delete_check(Request $request)
    {
        echo "这里是删除操作员";
    }
}

?>