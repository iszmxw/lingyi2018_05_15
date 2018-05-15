<?php
namespace App\Http\Controllers\Agent;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountInfo;
use App\Models\Module;
use App\Models\OperationLog;
use App\Models\OrganizationAgentinfo;
use App\Models\ProgramModuleNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
class PersonalController extends Controller{

    //个人 信息

    public function account_info(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        if($admin_data['is_super'] == 2){//如果是超级管理员
            $user = Account::getOne([['id',1]]);
        }else{
            $user = Account::getOne([['id',$admin_data['id']]]);
        }
        $account_id = Account::getPluck([['organization_id',$admin_data['organization_id']],['deepth',1]],'id');
        if($account_id == $admin_data['id']) {
            $module_node_list = Module::getListProgram(2, [], 0, 'id');//获取当前系统的所有模块和节点
        }else{
            $account_node_list = ProgramModuleNode::getAccountModuleNodes(2,$admin_data['id']);//获取当前用户具有权限的节点
            $modules = [];
            $nodes = [];
            $module_node_list = [];
            //过滤重复选出的节点和模块
            foreach($account_node_list as $key=>$val){
                $modules[$val->module_id] = $val->module_name;
                $nodes[$val->module_id][$val->node_id] = $val->node_name;
            }
            //遍历，整理为合适的格式
            foreach($modules as $key=>$val){
                $module = ['id'=>$key,'module_name'=>$val];
                foreach($nodes[$key] as $k=>$v){
                    $module['program_nodes'][] = array('id'=>$k,'node_name'=>$v);
                }
                $module_node_list[] = $module;
                unset($module);
            }
        }

        return view('Agent/Personal/account_info',['user'=>$user,'module_node_list'=>$module_node_list,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);

    }
    //修改个人信息提交
    public function account_info_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $mobile = $request->input('mobile');//手机号码
        $realname = $request->input('realname');//真实姓名
        $id = $request->input('id');//用户id
        $organization_id = $request->input('organization_id');//用户id
        if($admin_data['is_super'] == 2){
            $oneAcc = Account::getOne([['id',1]]);
        }else{
            $oneAcc = Account::getOne([['id',$id]]);
        }
        DB::beginTransaction();
        try {
            if($oneAcc['mobile']!=$mobile){
                if(Account::checkRowExists([['mobile',$mobile],['organization_id',$organization_id]])){//判断手机号在分公司存不存在
                    return response()->json(['data' => '手机号已存在', 'status' => '0']);
                }
                if($admin_data['is_super'] != 2) {
                    if(Account::checkRowExists([['organization_id','0'],[ 'mobile',$mobile ]])) {//判断手机号码是否超级管理员手机号码
                        return response()->json(['data' => '手机号码已存在', 'status' => '0']);
                    }
                    OrganizationAgentinfo::editOrganizationAgentinfo([['agent_id', $organization_id]], ['agent_owner_mobile' => $mobile]);//修改分公司表分公司手机号码
                }
                Account::editAccount(['organization_id'=>$organization_id],['mobile'=>$mobile]);//修改用户管理员信息表 手机号

            }
            if($oneAcc['account_info']['realname'] != $realname){
                if($admin_data['is_super'] != 2) {
                    OrganizationAgentinfo::editOrganizationAgentinfo([['agent_id', $organization_id]], ['agent_owner' => $realname]);//修改分公司用户信息表 用户姓名
                }
                AccountInfo::editAccountInfo([['account_id',$id]],['realname'=>$realname]);//修改用户管理员信息表 用户名
            }
            $admin_data['realname'] = $realname;
            $admin_data['mobile'] = $mobile;
            if($admin_data['is_super'] != 2){
                \ZeroneRedis::create_agent_account_cache($admin_data['id'],$admin_data);//生成账号数据的Redis缓存-分公司
                OperationLog::addOperationLog('2',$organization_id,$id,$route_name,'修改了个人信息');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '个人信息修改失败，请检查', 'status' => '0']);
        }

        return response()->json(['data' => '个人信息修改成功', 'status' => '1']);

    }
    //修改登入密码显示页面
    public function password(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $id = $admin_data['id'];
        if($admin_data['is_super'] == 2){
            $oneAcc = Account::getOne([['id',1]]);
        }else{
            $oneAcc = Account::getOne([['id',$id]]);
        }
        return view('Agent/Personal/password',['oneAcc'=>$oneAcc,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);

    }

    //修改登入密码功能提交
    public function password_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $id = $request->input('id'); //获取修改登入密码的id
        $account = Account::getOne([['id',$id]]);//获取用户信息

        $old_password = $request->input('old_password'); //原密码
        $password = $request->input('password');//新密码
        if($admin_data['is_super'] == 2){
            $key = config("app.zerone_encrypt_key");//获取加密盐
        }else{
            $key = config("app.agent_encrypt_key");//获取加密盐
        }

        $encrypted = md5($password);//加密密码第一重---新密码
        $encryptPwd = md5("lingyikeji".$encrypted.$key);//加密密码第二重---新密码
        $old_encrypted = md5($old_password);//加密密码第一重---原密码
        $old_encryptPwd = md5("lingyikeji".$old_encrypted.$key);//加密码第二重---原密码

        if ($account['password'] == $old_encryptPwd){
            DB::beginTransaction();
            try {
                Account::editAccount([['id',$id ]],['password' => $encryptPwd]);
                if($admin_data['is_super'] != 2){
                    OperationLog::addOperationLog('2',$admin_data['organization_id'],$id,$route_name,'修改了登录密码');//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '修改登录密码失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '登录密码修改成功！', 'status' => '1']);
        }else{
            return response()->json(['data' => '原密码不正确！', 'status' => '0']);
        }

    }


    //修改安全密码
    public function safe_password(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $id = $admin_data['id'];
        if($admin_data['is_super'] == 2){
            $oneAcc = Account::getOne([['id',1]]);
        }else{
            $oneAcc = Account::getOne([['id',$id]]);
        }
        return view('Agent/Personal/safe_password',['oneAcc'=>$oneAcc,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);

    }

    //修改安全密码提交
    public function safe_password_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $is_editing = $request->input('is_editing');    //是否修改安全密码
        $old_safe_password = $request->input('old_safe_password');    //原安全密码
        $safe_password = $request->input('safe_password');  //新安全密码

        if($admin_data['is_super'] ==2){
            $key = config("app.zerone_safe_encrypt_key");//获取加密盐
        }else{
            $key = config("app.agent_safe_encrypt_key");//获取加密盐
        }
        $encrypted = md5($safe_password);//加密安全密码第一重
        $encryptPwd = md5("lingyikeji".$encrypted.$key);//加密安全密码第二重

        $old_encrypted = md5($old_safe_password);//加密原安全密码第一重
        $old_encryptPwd = md5("lingyikeji".$old_encrypted.$key);//加密原安全密码第二重
        if ($is_editing == '-1'){
            DB::beginTransaction();
            try {
                $admin_data['safe_password'] = $encryptPwd;
                if($admin_data['is_super'] == 2){
                    Account::editAccount([['id',1]],['safe_password' => $encryptPwd]);
                    \ZeroneRedis::create_agent_account_cache(1, $admin_data);//生成账号数据的Redis缓存
                }else{
                    Account::editAccount([['id',$admin_data['id']]],['safe_password' => $encryptPwd]);
                    OperationLog::addOperationLog('2',$admin_data['organization_id'],$admin_data['id'],$route_name,'设置了安全密码');//保存操作记录
                    \ZeroneRedis::create_agent_account_cache($admin_data['id'], $admin_data);//生成账号数据的Redis缓存
                }
                DB::commit();
            } catch (\Exception $e) {
                dd($e);
                DB::rollBack();//事件回滚
                return response()->json(['data' => '设置安全密码失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '安全密码设置成功', 'status' => '1']);
        }else{//修改安全密码
            if ($admin_data['safe_password'] == $old_encryptPwd){
                DB::beginTransaction();
                try {
                    $admin_data['safe_password'] = $encryptPwd;
                    if($admin_data['is_super'] == 2){
                        Account::editAccount([['id',1]],['safe_password' => $encryptPwd]);
                        \ZeroneRedis::create_agent_account_cache(1, $admin_data);//生成账号数据的Redis缓存

                    }else{
                        Account::editAccount([['id',$admin_data['id']]],['safe_password' => $encryptPwd]);
                        OperationLog::addOperationLog('2',$admin_data['organization_id'],$admin_data['id'],$route_name,'修改了安全密码');//保存操作记录
                        \ZeroneRedis::create_agent_account_cache($admin_data['id'], $admin_data);//生成账号数据的Redis缓存

                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();//事件回滚
                    return response()->json(['data' => '安全密码修改失败，请检查', 'status' => '0']);
                }
                return response()->json(['data' => '安全密码修改成功！', 'status' => '1']);
            }else{
                return response()->json(['data' => '原安全密码不正确！', 'status' => '0']);
            }
        }
    }



}
?>