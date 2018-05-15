<?php
namespace App\Http\Controllers\Agent;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountInfo;
use App\Models\LoginLog;
use App\Models\OperationLog;
use App\Models\Organization;
use App\Models\OrganizationAgentinfo;
use App\Models\OrganizationRole;
use App\Models\Warzone;
use App\Services\ZeroneRedis\ZeroneRedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
class SystemController extends Controller{
    //添加分公司
    public function display(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = $admin_data['organization_id'];//分公司id
        if($admin_data['is_super'] == 1 && $admin_data['organization_id'] == 0 ){
            $list = Organization::getPaginage([['program_id','2']],20,'id');
            foreach($list as $key=>$value){
                $list[$key]['warzone'] = Warzone::getPluck([['id', $value['warzoneAgent']['zone_id']]],'zone_name')->first();
            }
            //选择分公司分公司页面数据渲染
            return view('Agent/System/select_agent',['list'=>$list]);
        }else{
            $where = [['organization_id',$organization_id]];
            $account_id = Account::getPluck([['organization_id',$organization_id],['parent_id',1]],'id');//获取负责人id
            if($account_id != $admin_data['id']) {//如果不是分公司负责人 只允许看自己的登入记录
                $where[] = ['account_id',$admin_data['id']];
            }
            $login_log_list = LoginLog::getList($where,10,'id');//登录记录
            $operation_log_list = OperationLog::getList($where,10,'id');//操作记录
            $acc_num = Account::where([['organization_id',$organization_id]])->count();//查询分公司人数
            $org_num = Organization::where([['parent_id',$organization_id]])->count();//查询分公司附属商务个数
            return view('Agent/System/index',['login_log_list'=>$login_log_list,'operation_log_list'=>$operation_log_list,'acc_num'=>$acc_num,'org_num'=>$org_num,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
        }
    }
    //超级管理员选择分公司操作
    public function select_agent(Request $request){
        $admin_this = $request->get('admin_data');//中间件产生的管理员数据参数
        $organization_id = $request->input('organization_id');//中间件产生的管理员数据参数
        $account_info = Account::getOneAccount([['organization_id',$organization_id],['deepth','1']]);//根据账号查询
        if(!empty($account_info)){
            //重新生成缓存的登录信息
            $admin_data = [
                'id'=>$account_info->id,    //用户ID
                'account'=>$account_info->account,//用户账号
                'organization_id'=>$account_info->organization_id,//组织ID
                'is_super'=>'1',//是否超级管理员
                'parent_id'=>$account_info->parent_id,//上级ID
                'parent_tree'=>$account_info->parent_tree,//上级树
                'deepth'=>$account_info->deepth,//账号在组织中的深度
                'mobile'=>$account_info->mobile,//绑定手机号
                'safe_password'=>$admin_this['safe_password'],//安全密码-超级管理员
                'account_status'=>$account_info->status,//用户状态
            ];
            Session::put('agent_account_id',encrypt(1));//存储登录session_id为当前用户ID
            //构造用户缓存数据
            if(!empty( $account_info->account_info->realname)) {
                $admin_data['realname'] = $account_info->account_info->realname;
            }else{
                $admin_data['realname'] = '未设置';
            }
            if(!empty($account_info->account_roles) && $account_info->account_roles->count() != 0) {
                foreach ($account_info->account_roles as $key => $val) {
                    $account_info->role = $val;
                }
                $admin_data['role_name'] = $account_info->role->role_name;
            }else{
                $admin_data['role_name'] = '角色未设置';
            }
            ZeroneRedis::create_agent_account_cache(1,$admin_data);//生成账号数据的Redis缓存
            ZeroneRedis::create_menu_cache(1,2);//生成对应账号的系统菜单
            return response()->json(['data' => '操作成功', 'status' => '1']);
        }else{
            return response()->json(['data' => '操作失败', 'status' => '0']);
        }
    }

    //超级管理员选择分公司
    public function switch_status(Request $request){
        $admin_data = $request->get('admin_data');                //中间件产生的管理员数据参数
        $admin_data['organization_id'] = 0;
        ZeroneRedis::create_agent_account_cache(1, $admin_data);//清空所选组织
        return redirect('agent');
    }

    //公司信息设置页面
    public function agent_info(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = $admin_data['organization_id'];//分公司id
        $data = Organization::getOneAgent([['id',$organization_id]]);
        $warzone = Warzone::getOne([['id', $data['warzoneAgent']['zone_id']]]);
        return view('Agent/System/agent_info',['warzone'=>$warzone,'data'=>$data,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    //公司信息设置修改保存
    public function agent_info_check(Request $request){

        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = $request->input('organization_id');//分公司id
        $realname = $request->input('realname');//负责人
        $organization_name = $request->input('organization_name');//分公司名称
        $idcard = $request->input('idcard');//负责人身份证
        $mobile = $request->input('mobile');//负责人手机号

        DB::beginTransaction();
        try{
            $agent= Organization::getOneAgent([['id',$organization_id]]);
            $acc = Account::getOne([['id',$admin_data['id']]]);
            $account_id = $acc['id'];
            if($agent['organization_name']!=$organization_name){
                Organization::editOrganization([['id',$organization_id]], ['organization_name'=>$organization_name]);//修改分公司表分公司名称
            }
            if($agent['mobile']!=$mobile){
                OrganizationAgentinfo::editOrganizationAgentinfo([['agent_id',$organization_id]], ['agent_owner_mobile'=>$mobile]);//修改分公司表分公司手机号码
                Account::editAccount(['organization_id'=>$organization_id],['mobile'=>$mobile]);//修改用户管理员信息表 手机号
                $admin_data['mobile'] = $mobile;
            }

            if($agent['organizationAgentinfo']['agent_owner'] != $realname){
                OrganizationAgentinfo::editOrganizationAgentinfo([['agent_id',$organization_id]],['agent_owner'=>$realname]);//修改分公司用户信息表 用户姓名
                AccountInfo::editAccountInfo([['account_id',$account_id]],['realname'=>$realname]);//修改用户管理员信息表 用户名
                $admin_data['realname'] = $realname;
            }

            if($agent['organizationAgentinfo']['agent_owner_idcard'] != $idcard){
                AccountInfo::editAccountInfo([['account_id',$account_id]],['idcard'=>$idcard]);//修改用户管理员信息表 身份证号
                OrganizationAgentinfo::editOrganizationAgentinfo([['agent_id',$organization_id]],['agent_owner_idcard'=>$idcard]);//修改分公司信息表 身份证号
            }

            if($admin_data['is_super'] != 2) {
                OperationLog::addOperationLog('2', $organization_id, $account_id, $route_name, '修改了分公司信息：' . $agent['organization_name']);//保存操作记录
            }
            DB::commit();//提交事务
        }catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '修改失败', 'status' => '0']);
        }
        if($acc['realname'] != $realname || $agent['mobile']!=$mobile){
            if($admin_data['is_super'] == 2) {
                \ZeroneRedis::create_agent_account_cache(1, $admin_data);//生成账号数据的Redis缓存
            }else{
                \ZeroneRedis::create_agent_account_cache($account_id, $admin_data);//生成账号数据的Redis缓存
            }
        }
        return response()->json(['data' => '修改成功', 'status' => '1']);

    }
    //公司人员结构
    public function agent_structure(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = $admin_data['organization_id'];//当前组织ID，零壹管理平台组织只能为1
        $oneAcc = Account::getOne([['organization_id',$organization_id],['deepth',1]]);//查找分公司对应的负责人信息
        $parent_tree = $oneAcc['parent_tree'];//组织树
        //获取重Admin开始的的所有人员
        $list = Account::getList([['organization_id',$organization_id],['parent_tree','like','%'.$parent_tree.$oneAcc['id'].',%']],0,'id','asc')->toArray();
        //根据获取的人员组成结构树
        $structure = $this->create_structure($list,$oneAcc['id']);
        return view('Agent/System/agent_structure',['oneAcc'=>$oneAcc,'structure'=>$structure,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    private function create_structure($list,$id){
        $structure = '';
        foreach($list as $key=>$val){
            if($val['parent_id'] == $id) {
                $structure .= '<ol class="dd-list"><li class="dd-item" data-id="' . $val['id'] . '">' ;
                $structure .= '<div class="dd-handle">';
                $structure .= '<span class="pull-right">创建时间：'.date('Y-m-d,H:i:s',$val['created_at']).'</span>';
                $structure .= '<span class="label label-info"><i class="icon-user"></i></span>';
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
    //操作日记
    public function operationlog(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由

        $account = $request->input('account');//通过登录页账号查询
        $where = [['operation_log.organization_id',$admin_data['organization_id']]];
        if(!empty($account)){
            $where[] = ['account.account','like','%'.$account.'%'];
        }
        $search_data = ['account'=>$account];
        $list = OperationLog::getAgentPaginate($where,10,'id');
        $roles = [];
        foreach($list as $key=>$val){
            $roles[$val->id] = OrganizationRole::getLogsRoleName($val->account_id);
        }
        return view('Agent/System/operationlog',['list'=>$list,'roles'=>$roles,'search_data'=>$search_data,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    //登录日记
    public function loginlog(Request $request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $account = $request->input('account');//通过登录页账号查询
        $where = [['login_log.organization_id',$admin_data['organization_id']]];
        if(!empty($account)){
            $where[] = ['account.account','like','%'.$account.'%'];
        }
        $search_data = ['account'=>$account];
        $list = LoginLog::getAgentPaginate($where,15,'id');
        return view('Agent/System/loginlog',['list'=>$list,'search_data'=>$search_data,'admin_data'=>$admin_data,'route_name'=>$route_name,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);
    }
    //退出登录
    public function quit(Request $request){
        Session::put('agent_account_id','');
        return redirect('agent/login');
    }
}
?>