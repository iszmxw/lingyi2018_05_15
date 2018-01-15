<?php
namespace App\Http\Controllers\Zerone;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrganizationRole;
use App\Models\Module;
use App\Models\ProgramModuleNode;
use App\Models\Account;
use App\Models\AccountNode;
use App\Models\AccountInfo;
use App\Models\RoleAccount;
use App\Models\OperationLog;
use Session;
class SubordinateController extends Controller{
    //添加下级人员
    public function subordinate_add(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        //获取当前用户添加的权限角色
        $role_list = OrganizationRole::getList([['program_id',1],['created_by',$admin_data['id']]],0,'id');
        return view('Zerone/Subordinate/subordinate_add',['role_list'=>$role_list,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }

    //快速授权功能
    public function quick_rule(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $account_id = $admin_data['id'];//当前登陆账号ID
        $role_id = $request->input('role_id');
        if($account_id == 1) {//如果是超级管理员
            $module_node_list = Module::getListProgram(1, [], 0, 'id');//获取当前系统的所有模块和节点
        }else{
            $account_node_list = ProgramModuleNode::getAccountModuleNodes(1,$admin_data['id']);//获取当前用户具有权限的节点

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
        $selected_nodes = [];//选中的节点
        $selected_modules = [];//选中的模块
        if($role_id <> '0'){
            $node_list = ProgramModuleNode::getRoleModuleNodes(1,$role_id);//获取当前角色拥有权限的模块和节点
            foreach($node_list as $key=>$val){
                $selected_modules[] = $val->module_id;
                $selected_nodes[] = $val->node_id;
            }
        }
        return view('Zerone/Subordinate/quick_rule',['module_node_list'=>$module_node_list,'selected_nodes'=>$selected_nodes,'selected_modules'=>$selected_modules]);
    }

    //添加下级人员数据提交
    public function subordinate_add_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $account = $request->input('account');//用户账号
        $password = $request->input('password');//登陆密码
        $realname = $request->input('realname');//用户真实姓名
        $mobile = $request->input('mobile');//用户手机号码
        $role_id = $request->input('role_id');//用户角色ID
        $module_node_ids = $request->input('module_node_ids');//用户权限节点

        $key = config("app.zerone_encrypt_key");//获取加密盐
        $encrypted = md5($password);//加密密码第一重
        $encryptPwd = md5("lingyikeji".$encrypted.$key);//加密密码第二重

        $parent_id = $admin_data['id'];//上级ID是当前用户ID
        $parent_tree = $admin_data['parent_tree'].','.$parent_id;//树是上级的树拼接上级的ID；
        $deepth = $admin_data['deepth']+1;
        $organization_id = 1;//当前零壹管理平台就只有一个组织。

        if(Account::checkRowExists([[ 'account',$account ]])){//判断零壹管理平台中 ，判断组织中账号是否存在
            return response()->json(['data' => '账号已存在', 'status' => '0']);
        }elseif(Account::checkRowExists([['organization_id',$organization_id],[ 'mobile',$mobile ]])) {//判断零壹管理平台中，判断组织中手机号码是否存在；
            return response()->json(['data' => '手机号码已存在', 'status' => '0']);
        }elseif(Account::checkRowExists([['organization_id','0'],[ 'mobile',$mobile ]])) {//判断手机号码是否超级管理员手机号码
            return response()->json(['data' => '手机号码已存在', 'status' => '0']);
        }else {
            DB::beginTransaction();
            try {
                //添加用户
                $account_id=Account::addAccount(['organization_id'=>$organization_id, 'parent_id'=>$parent_id, 'parent_tree'=>$parent_tree, 'deepth'=>$deepth, 'account'=>$account, 'password'=>$encryptPwd,'mobile'=>$mobile]);
                //添加用户个人信息
                AccountInfo::addAccountInfo(['account_id'=>$account_id,'realname'=>$realname]);
                //添加用户角色关系
                RoleAccount::addRoleAccount(['account_id'=>$account_id,'role_id'=>$role_id]);
                //添加用户权限节点关系
                foreach($module_node_ids as $key=>$val){
                    AccountNode::addAccountNode(['account_id'=>$account_id,'node_id'=>$val]);
                }
                //添加操作日志
                OperationLog::addOperationLog('1',$admin_data['organization_id'],$admin_data['id'],$route_name,'添加了下级人员：'.$account);//保存操作记录
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '添加了下级人员失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '添加下级人员成功', 'status' => '1']);
        }
    }

    //下级人员列表
    public function subordinate_list(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $account = $request->input('account');
        $search_data = ['account'=>$account];
        $organization_id = 1;//零壹管理平台只有一个组织
        $parent_tree = $admin_data['parent_tree'].','.$admin_data['id'];
        $list = Account::getPaginage([['organization_id',$organization_id],['parent_tree','like','%'.$parent_tree.'%'],[ 'account','like','%'.$account.'%' ]],15,'id');
        return view('Zerone/Subordinate/subordinate_list',['list'=>$list,'search_data'=>$search_data,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }

    //编辑下级人员
    public function subordinate_edit(Request $request){
        $id = $request->input('id');
        $info = Account::getOne([['id',$id]]);
        return view('Zerone/Subordinate/subordinate_edit',['info'=>$info]);
    }

    //编辑下级人员数据提交
    public function subordinate_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $id = $request->input('id');//要编辑的人员的ID
        $account = $request->input('account');
        $password = $request->input('password');//登陆密码
        $realname = $request->input('realname');//真实姓名
        $mobile = $request->input('mobile');//手机号码
        $organization_id = 1;
        if (!empty($password)) {
            $key = config("app.zerone_encrypt_key");//获取加密盐
            $encrypted = md5($password);//加密密码第一重
            $encryptPwd = md5("lingyikeji" . $encrypted . $key);//加密密码第二重
        }
       if(Account::checkRowExists([['id','<>',$id],['organization_id',$organization_id],[ 'mobile',$mobile ]])) {//判断零壹管理平台中，判断组织中手机号码是否存在；
            return response()->json(['data' => '手机号码已存在', 'status' => '0']);
        }elseif(Account::checkRowExists([['id','<>',$id],['organization_id','0'],[ 'mobile',$mobile ]])) {//判断手机号码是否超级管理员手机号码
            return response()->json(['data' => '手机号码已存在', 'status' => '0']);
        }else {
            DB::beginTransaction();
            try {
                //编辑用户
                $data['mobile'] = $mobile;
                if (!empty($password)) {
                    $data['password'] = $encryptPwd;
                }
                Account::editAccount([[ 'id',$id]],$data);
                if(AccountInfo::checkRowExists([['account_id',$id]])) {
                    AccountInfo::editAccountInfo([['account_id', $id]], ['realname' => $realname]);
                }else{
                    AccountInfo::addAccountInfo(['account_id'=>$id,'realname'=>$realname]);
                }
                //添加操作日志
                OperationLog::addOperationLog('1',$admin_data['organization_id'],$admin_data['id'],$route_name,'编辑了下级人员：'.$account);//保存操作记录
                DB::commit();
            } catch (\Exception $e) {
                dump($e);
                DB::rollBack();//事件回滚
                return response()->json(['data' => '编辑下级人员失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '编辑下级人员成功', 'status' => '1']);
        }
    }

    //下级人员授权管理
    public function subordinate_authorize(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $id = $request->input('id');
        $info = Account::getOne([['id',$id]]);
        foreach($info->account_roles as $key=>$val){
            $info->account_role = $val->id;
        }
        $role_list = OrganizationRole::getList([['program_id',1],['created_by',$admin_data['id']]],0,'id');
        return view('Zerone/Subordinate/subordinate_authorize',['info'=>$info,'role_list'=>$role_list]);
    }

    //获取下级人员当前已经选取的节点
    public function selected_rule(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $id = $request->input('id');
        if($admin_data['id'] == 1) {//如果是超级管理员
            $module_node_list = Module::getListProgram(1, [], 0, 'id');//获取当前系统的所有模块和节点
        }else{
            $account_node_list = ProgramModuleNode::getAccountModuleNodes(1,$admin_data['id']);//获取当前用户具有权限的节点
            dump($account_node_list);
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
        $selected_nodes = [];//选中的节点
        $selected_modules = [];//选中的模块
        $selected_node_list = ProgramModuleNode::getAccountModuleNodes(1,$id);//获取要操作的用户有的节点
        foreach($selected_node_list as $key=>$val){
            $selected_modules[] = $val->module_id;
            $selected_nodes[] = $val->node_id;
        }
        return view('Zerone/Subordinate/selected_rule',['module_node_list'=>$module_node_list,'selected_nodes'=>$selected_nodes,'selected_modules'=>$selected_modules]);
    }


    public function subordinate_authorize_check(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $id = $request->input('id');
        $role_id = $request->input('role_id');
        $account = $request->input('account');
        $module_node_ids = $request->input('module_node_ids');
        DB::beginTransaction();
        try {
            //修改账号与角色间的关系
            if(RoleAccount::checkRowExists([['account_id',$id ]])) {
                RoleAccount::editRoleAccount([['account_id', $id]], ['role_id' => $role_id]);//修改账号角色关系
            }else{
                RoleAccount::addRoleAccount(['role_id' => $role_id,'account_id'=>$id]);//添加账号角色关系
            }
            foreach($module_node_ids as $key=>$val){
                $vo = AccountNode::getOne([['account_id',$id],['node_id',$val]]);//查询是否存在数据
                if(is_null($vo)) {//不存在生成插入数据
                    AccountNode::addAccountNode(['account_id' => $id, 'node_id' => $val]);
                }else{//存在数据则跳过
                    continue;
                }
            }
            AccountNode::where('account_id', $id)->whereNotIn('node_id', $module_node_ids)->forceDelete();
            //添加操作日志
            OperationLog::addOperationLog('1',$admin_data['organization_id'],$admin_data['id'],$route_name,'编辑了下级人员的授权：'.$account);//保存操作记录
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '编辑下级人员授权失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '编辑下级人员授权成功', 'status' => '1']);
    }

    //输入安全密码判断是否能冻结的页面
    public function subordinate_lock_confirm(Request $request){
        $id = $request->input('id');//要操作的用户的ID
        $account = $request->input('account');//要操作的管理员的账号,用于记录
        $status = $request->input('status');//当前用户的状态
        return view('Zerone/Subordinate/subordinate_lock_confirm',['id'=>$id,'account'=>$account,'status'=>$status]);
    }
    //冻结解冻下级人员
    public function subordinate_lock(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $id = $request->input('id');//要操作的用户的ID
        $account = $request->input('account');//要操作的用户的账号,用于记录
        $status = $request->input('status');//当前用户的状态
        DB::beginTransaction();
        try{
            if($status==1) {
                Account::editAccount([['id',$id]],['status'=>'0']);
                OperationLog::addOperationLog('1',$admin_data['organization_id'],$admin_data['id'],$route_name,'冻结了下级人员：'.$account);//保存操作记录
            }else{
                Account::editAccount([['id',$id]],['status'=>'1']);
                OperationLog::addOperationLog('1',$admin_data['organization_id'],$admin_data['id'],$route_name,'解冻了下级人员：'.$account);//保存操作记录
            }
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '操作失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '操作成功', 'status' => '1']);
    }

    //删除下级人员确定
    public function subordinate_delete_confirm(Request $request){
        $id = $request->input('id');//要操作的用户的ID
        $account = $request->input('account');//要操作的管理员的账号,用于记录
        return view('Zerone/Subordinate/subordinate_delete_confirm',['id'=>$id,'account'=>$account]);
    }

    //删除下级人员
    public function subordinate_delete(Request $request){
        echo "这里是删除下级人员";
    }

    //下级人员结构
    public function subordinate_structure(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = 1;//当前组织ID，零壹管理平台组织只能为1
        $list = Account::getList([['organization_id',$organization_id],['parent_tree','like','%'.$admin_data['parent_tree'].','.$admin_data['id'].'%']],0,'id','asc')->toArray();
        $structure = $this->create_structure($list,$admin_data['id']);
        return view('Zerone/Subordinate/subordinate_structure',['structure'=>$structure ,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    /*
     * 递归生成人员结构的方法
     * $list - 结构所有人员的无序列表
     * $id - 上级ID
     */
    private function create_structure($list,$id){
        $structure = '';
        foreach($list as $key=>$val){
            if($val['parent_id'] == $id) {
                unset($list[$key]);
                $val['sonlist'] = $this->create_structure($list, $val['id']);
                //$arr[] = $val;
                $structure .= '<ol class="dd-list"><li class="dd-item" data-id="' . $val['id'] . '">' ;
                $structure .= '<div class="dd-handle">';
                $structure .= '<span class="pull-right">创建时间：'.date('Y-m-d,H:i:s',$val['created_at']).'</span>';
                $structure .= '<span class="label label-info"><i class="fa fa-user"></i></span>';
                $structure .=  $val['account']. '-'.$val['account_info']['realname'];
                if(!empty($val['account_roles'])){
                    $structure.='【'.$val['account_roles'][0]['role_name'].'】';
                }
                $structure .= '</div>';
                $son_menu = $this->create_structure($list, $val['id']);
                if (!empty($son_menu)) {
                    $structure .=  $son_menu;
                }
                $structure .= '</li></ol>';
            }
        }
        return $structure;
    }
}
?>