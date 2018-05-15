<?php
/**
 * 简版店铺管理系统账户中心
 * program_id：12；
 */

namespace App\Http\Controllers\Simple;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountInfo;
use App\Models\Module;
use App\Models\OperationLog;
use App\Models\ProgramModuleNode;
use App\Services\ZeroneRedis\ZeroneRedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    //账号信息修改页面
    public function profile(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $user = Account::getOne(['id' => $admin_data['id']]);   //查询当前用户信息
        $account_id = $admin_data['id'];                        //当前登录账号ID
        if ($account_id == 1) {                                 //如果是超级管理员
            $module_node_list = Module::getListProgram(12, [], 0, 'id');            //获取当前系统的所有模块和节点
        } else {
            $account_node_list = ProgramModuleNode::getAccountModuleNodes(12, $admin_data['id']);//获取当前用户具有权限的节点
            $modules = [];
            $nodes = [];
            $module_node_list = [];
            //过滤重复选出的节点和模块
            foreach ($account_node_list as $key => $val) {
                $modules[$val->module_id] = $val->module_name;
                $nodes[$val->module_id][$val->node_id] = $val->node_name;
            }
            //遍历，整理为合适的格式
            foreach ($modules as $key => $val) {
                $module = ['id' => $key, 'module_name' => $val];
                foreach ($nodes[$key] as $k => $v) {
                    $module['program_nodes'][] = array('id' => $k, 'node_name' => $v);
                }
                $module_node_list[] = $module;
                unset($module);
            }
        }
        return view('Simple/Account/profile', ['module_node_list' => $module_node_list, 'user' => $user, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //个人账号信息修改处理
    public function profile_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');  //中间件产生的管理员数据参数
        $route_name = $request->path();                 //获取当前的页面路由
        $organization_id = $admin_data['organization_id'];
        $realname = $request->input('realname');    //真实姓名
        $mobile = $request->input('mobile');        //手机号码
        $oneAcc = Account::getOne([['id', $admin_data['id']]]);
        $id = AccountInfo::checkRowExists([['account_id', $admin_data['id']]]);//查询是否存在该数据false/true
        DB::beginTransaction();
        try {
            if ($oneAcc['mobile'] != $mobile) {
                if (Account::checkRowExists([['mobile', $mobile], ['organization_id', $organization_id]])) {//判断手机号在分公司存不存在
                    return response()->json(['data' => '手机号已存在', 'status' => '0']);
                }
                if ($admin_data['is_super'] != 1) {
                    if (Account::checkRowExists([['organization_id', '0'], ['mobile', $mobile]])) {//判断手机号码是否超级管理员手机号码
                        return response()->json(['data' => '手机号码已存在', 'status' => '0']);
                    }
                }
                Account::editAccount([['id', $admin_data['id']]], ['mobile' => $mobile]);//修改用户手机号
            }
            if ($id) {//判断是否存在该数据
                AccountInfo::editAccountInfo([['account_id', $admin_data['id']]], ['realname' => $realname]);
            } else {
                $admininfo = ['account_id' => $admin_data['id'], 'realname' => $realname];
                AccountInfo::addAccountInfo($admininfo);
            }
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员操作简版店铺的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在简版店铺管理系统修改了店铺的个人账号信息！');//保存操作记录
            } else {//简版店铺本人操作记录
                OperationLog::addOperationLog('12', $admin_data['organization_id'], $admin_data['id'], $route_name, '修改了个人账号信息');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '修改个人账号信息失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '修改个人账号信息成功', 'status' => '1']);
    }

    //安全密码设置页面
    public function safe_password(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $account = Account::getOne(['id' => '1']);                //获取超级管理员账号
        return view('Simple/Account/safe_password', ['account' => $account, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //安全密码修改设置处理
    public function safe_password_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');                  //中间件产生的管理员数据参数
        $route_name = $request->path();                                 //获取当前的页面路由
        $is_editing = $request->input('is_editing');                //是否修改安全密码
        $old_safe_password = $request->input('old_safe_password');  //原安全密码
        $safe_password = $request->input('safe_password');          //新安全密码
        $account = Account::getOne(['id' => '1']);                        //查询超级管理员的安全密码
        if ($admin_data['is_super'] == 1) {                              //如果是超级管理员获取零壹安全密码加密盐
            $safe_password_check = $account['safe_password'];
            $key = config("app.zerone_safe_encrypt_key");           //获取加安全密码密盐（零壹平台专用）
        } else {
            $safe_password_check = $admin_data['safe_password'];
            $key = config("app.simple_safe_encrypt_key");           //获取安全密码加密盐（简版店铺专用）
        }
        //原安全密码处理
        $old_encrypted = md5($old_safe_password);                   //加密原安全密码第一重
        $old_encryptPwd = md5("lingyikeji" . $old_encrypted . $key);//加密原安全密码第二重
        //新安全密码处理
        $encrypted = md5($safe_password);                   //加密安全密码第一重
        $encryptPwd = md5("lingyikeji" . $encrypted . $key);//加密安全密码第二重
        if ($is_editing == '-1') {//设置安全密码
            DB::beginTransaction();
            try {
                //添加操作日志
                if ($admin_data['is_super'] == 1) {//超级管理员操作简版店铺的记录
                    Account::editAccount([['id', '1']], ['safe_password' => $encryptPwd]);                        //设置超级管理员安全密码
                    OperationLog::addOperationLog('1', '1', '1', $route_name, '在简版店铺管理系统设置了自己的安全密码！');//保存操作记录
                } else {//简版店铺本人操作记录
                    Account::editAccount([['id', $admin_data['id']]], ['safe_password' => $encryptPwd]); //设置简版店铺安全密码
                    OperationLog::addOperationLog('12', $admin_data['organization_id'], $admin_data['id'], $route_name, '设置了安全密码');//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '设置安全密码失败，请检查', 'status' => '0']);
            }
            $admin_data['safe_password'] = $encryptPwd;
            ZeroneRedis::create_simple_account_cache($admin_data['id'], $admin_data);//生成账号数据的Redis缓存
            return response()->json(['data' => '安全密码设置成功', 'status' => '1']);
        } else {//修改安全密码
            if ($safe_password_check == $old_encryptPwd) {
                DB::beginTransaction();
                try {
                    //添加操作日志
                    if ($admin_data['is_super'] == 1) {//超级管理员操作简版店铺的记录
                        Account::editAccount([['id', '1']], ['safe_password' => $encryptPwd]);                        //修改超级管理员安全密码
                        OperationLog::addOperationLog('1', '1', '1', $route_name, '在分店管理系统修改了自己的安全密码！');    //保存操作记录
                    } else {//商户本人操作记录
                        Account::editAccount([['id', $admin_data['id']]], ['safe_password' => $encryptPwd]);          //设置当前简版店铺的安全密码
                        OperationLog::addOperationLog('12', $admin_data['organization_id'], $admin_data['id'], $route_name, '修改了安全密码');//保存操作记录
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();//事件回滚
                    return response()->json(['data' => '安全密码修改失败，请检查', 'status' => '0']);
                }
                $admin_data['safe_password'] = $encryptPwd;
                ZeroneRedis::create_simple_account_cache($admin_data['id'], $admin_data);//生成账号数据的Redis缓存
                return response()->json(['data' => '安全密码修改成功！', 'status' => '1']);
            } else {
                return response()->json(['data' => '原安全密码不正确！', 'status' => '0']);
            }
        }
    }

    //登录密码页面
    public function password(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $account = Account::getOne(['id' => '1']);                //获取超级管理员账号
        if (empty($admin_data['safe_password'])) {
            return redirect('simple/account/safe_password');
        } else {
            return view('Simple/Account/password', ['account' => $account, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
        }
    }

    //登录密码修改处理
    public function password_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');  //中间件产生的管理员数据参数
        $route_name = $request->path();                 //获取当前的页面路由
        $password = $request->input('password');
        $new_password = $request->input('new_password');
        if ($admin_data['is_super'] == 1) {              //如果是超级管理员获取零壹加密盐
            $account = Account::getOne([['id', '1']]);
            $key = config("app.zerone_encrypt_key");//获取加密盐（零壹平台专用）
        } else {
            $account = Account::getOne([['id', $admin_data['id']]]);
            $key = config("app.simple_encrypt_key");//获取加密盐（简版店铺专用）
        }
        $encrypted = md5($password);                        //加密密码第一重
        $encryptPwd = md5("lingyikeji" . $encrypted . $key);//加密密码第二重
        $new_encrypted = md5($new_password);                        //加密新密码第一重
        $new_encryptPwd = md5("lingyikeji" . $new_encrypted . $key);//加密新码第二重
        if ($account['password'] == $encryptPwd) {
            DB::beginTransaction();
            try {
                //添加操作日志
                if ($admin_data['is_super'] == 1) {//超级管理员操作简版店铺的记录
                    Account::editAccount([['id', '1']], ['password' => $new_encryptPwd]);    //修改超级管理员登录密码
                    OperationLog::addOperationLog('1', '1', '1', $route_name, '在餐饮分店管理系统修改了自己的登录密码！');  //保存操作记录
                } else {//简版店铺本人操作记录
                    Account::editAccount([['id', $admin_data['id']]], ['password' => $new_encryptPwd]);      //修改简版店铺登录密码
                    OperationLog::addOperationLog('12', $admin_data['organization_id'], $admin_data['id'], $route_name, '修改了登录密码');//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '修改登录密码失败，请检查', 'status' => '0']);
            }
            if ($admin_data['is_super'] == 1) {
                return response()->json(['data' => '这里修改的是您自己的登录密码！你已经修改了你自己的登录密码！请牢记！', 'status' => '1']);
            } else {
                return response()->json(['data' => '登录密码修改成功！', 'status' => '1']);
            }
        } else {
            return response()->json(['data' => '原密码不正确！', 'status' => '1']);
        }
    }


}

?>