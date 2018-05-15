<?php
/**
 *新版本登录界面
 *
 **/

namespace App\Http\Controllers\Zerone;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Organization;
use App\Models\Province;
use App\Models\Warzone;
use App\Models\LoginLog;
use App\Models\OperationLog;
use App\Models\WarzoneProvince;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Models\Statistics;
use App\Models\OrganizationRole;

class DashboardController extends Controller
{

    //查询系统管理人员数
    public function account($type,$account)
    {
        $organization_id = Organization::getList(['type'=>$type]);
        foreach ($organization_id as $key=>$val){
            $account += Account::getList(['organization_id'=>$val->id],0,'id','DESC')->count();
        }
        return $account;
    }
    /**
     * 系统管理首页
     */
    public function display(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        //零壹管理系统--管理人员
        $zerone_account = Account::getList(['organization_id'=>'1'],0,'id','DESC')->count();
        //分公司管理系统--管理人员
        $agent_account = $this->account('2','0');
        //商户管理系统--管理人员
        $company_account = $this->account('3','0');
        //所有业务系统--管理人员
        $store_account = $this->account('4','0');
        //分公司数量
        $agent_num = Organization::getList(['type'=>'2'])->count();
        //商户数量
        $company_num = Organization::getList(['type'=>'3'])->count();
        //店铺数量
        $store_num = Organization::getList(['type'=>'4'])->count();

        Statistics::editStatistics(['id'=>'1'],['item_value'=>$zerone_account]);
        Statistics::editStatistics(['id'=>'2'],['item_value'=>$agent_account]);
        Statistics::editStatistics(['id'=>'3'],['item_value'=>$company_account]);
        Statistics::editStatistics(['id'=>'4'],['item_value'=>$store_account]);
        Statistics::editStatistics(['id'=>'5'],['item_value'=>$agent_num]);
        Statistics::editStatistics(['id'=>'6'],['item_value'=>$company_num]);
        Statistics::editStatistics(['id'=>'7'],['item_value'=>$store_num]);

        $list = Statistics::pluck('item_value')->toArray();//所有数据
        $zerone = [
            'system_personnel' => $list['0'],       //零壹管理系统人员数量
            'service_providers' => $list['1'],      //分公司系统人员数量
            'merchant_system' => $list['2'],        //商户系统人员数量
            'all_system_personnel' => $list['3'],   //所有业务系统人员数量
            'service_provider' => $list['4'],       //分公司数量
            'merchant' => $list['5'],               //商户数量
            'shop' => $list['6']                    //店铺数量
        ];
        $where = [];
        if ($admin_data['id'] <> 1) {   //不是超级管理员的时候，只查询自己相关的数据【后期考虑转为查询自己及自己管理的下级人员的所有操作记录】
            $where = [['account_id', $admin_data['id']]];
        }
        $login_log_list = LoginLog::getList($where, 10, 'id');//登录记录
        $operation_log_list = OperationLog::getList($where, 10, 'id');//操作记录
        return view('Zerone/Dashboard/display', ['admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'login_log_list' => $login_log_list, 'operation_log_list' => $operation_log_list, 'zerone' => $zerone]);
    }

    /**
     * 战区管理首页
     */
    public function warzone(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $zone_name = $request->input('zone_name');//搜索时输入的战区名称
        $search_data['zone_name'] = $zone_name;//分页参数
        $warzone = Warzone::getPaginage([['zone_name', 'like', '%' . $zone_name . '%']], 15, 'id');//战区列表
        return view('Zerone/Dashboard/warzone', ['zone_name' => $zone_name, 'search_data' => $search_data, 'warzone' => $warzone, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    /**
     * 战区管理编辑弹出
     */
    public function warzone_edit(Request $request)
    {
        $zone_id = $request->input('id');//获取战区id
        $zone_info = Warzone::getOne(['id' => $zone_id]);//当前战区信息
        $province = Province::getList([], 0, 'id', 'asc');
        foreach ($zone_info->province as $key => $val) {
            $selected_province[] = $val->id;
        }
        return view('Zerone/Dashboard/warzone_edit', ['zone_info' => $zone_info, 'province' => $province, 'selected_province' => $selected_province]);
    }

    /**
     * 战区管理编辑数据提交
     */
    public function warzone_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $zone_name = $request->input('zone_name');//战区名称
        $province_id = $request->input('province_id');//包含省份ID（array）
        $zone_id = $request->input('zone_id');//战区ID
        if (empty($province_id)) {
            return response()->json(['data' => '选择战区包含省份！', 'status' => '1']);
        }
        DB::beginTransaction();
        try {
            Warzone::WarzoneEdit([['id', $zone_id]], ['zone_name' => $zone_name]);//修改战区名称
            foreach ($province_id as $key => $val) {
                $vo = WarzoneProvince::getOne([['zone_id', $zone_id], ['province_id', $val]]);//查询数据是否存在
                if (is_null($vo)) {//不存在生成插入数据
                    WarzoneProvince::WarzoneProvinceAdd(['zone_id' => $zone_id, 'province_id' => $val]);
                } else {//存在数据则跳过
                    continue;
                }
            }
            WarzoneProvince::where('zone_id', $zone_id)->whereNotIn('province_id', $province_id)->forceDelete();//删除原本拥有但本次未选中的数据
            //添加操作日志
            OperationLog::addOperationLog('1', $admin_data['organization_id'], $admin_data['id'], $route_name, '编辑了战区：' . $zone_name);//保存操作记录
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '编辑战区失败，请检查！', 'status' => '0']);
        }
        return response()->json(['data' => '编辑战区成功', 'status' => '1']);
    }

    /**
     * 战区管理添加战区弹出
     */
    public function warzone_add(Request $request)
    {
        $province = Province::getList([], 0, 'id', 'asc');//获取所有战区可选省份
        return view('Zerone/Dashboard/warzone_add', ['province' => $province]);
    }

    /**
     * 战区管理添加数据提交
     */
    public function warzone_add_check(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $zone_name = $request->input('zone_name');//战区名称
        $province_id = $request->input('province_id');//包含省份ID（array）
        DB::beginTransaction();
        try {
            $zone_id = Warzone::WarzoneAdd($zone_name);//添加战区名称并且返回添加的id
            foreach ($province_id as $key => $val) {
                WarzoneProvince::WarzoneProvinceAdd(['zone_id' => $zone_id, 'province_id' => $val]);
            }
            //添加操作日志
            OperationLog::addOperationLog('1', $admin_data['organization_id'], $admin_data['id'], $route_name, '添加了战区：' . $zone_name);//保存操作记录
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '添加战区失败，请检查！', 'status' => '0']);
        }
        return response()->json(['data' => '添加战区成功！', 'status' => '1']);
    }

    /**
     * 战区管理删除确认弹出框
     */
    public function warzone_delete_confirm(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $account = $admin_data['account'];//要操作的管理员的账号,用于记录
        $id = $request->input('id');//要操作的战区ID
        $zone_name = $request->input('zone_name');//要操作的战区名称
        return view('Zerone/Dashboard/warzone_delete_confirm', ['id' => $id, 'zone_name' => $zone_name, 'account' => $account]);
    }

    /**
     * 战区管理确认删除
     */
    public function warzone_delete(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $zone_id = $request->input('id');
        $zone_name = $request->input('zone_name');//战区名称
        DB::beginTransaction();
        try {
            Warzone::where('id', $zone_id)->delete();//软删除战区
            WarzoneProvince::where('zone_id', $zone_id)->delete();//软删除战区省份关系
            //添加操作日志
            OperationLog::addOperationLog('1', $admin_data['organization_id'], $admin_data['id'], $route_name, '删除了战区：' . $zone_name);//保存操作记录
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '删除战区失败，请检查！', 'status' => '0']);
        }
        return response()->json(['data' => '删除战区成功！', 'status' => '1']);
    }

    /**
     * 所有操作记录
     */
    public function operation_log(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $account = $request->input('account');//通过登录页账号查询
        $time_st = $request->input('time_st');//查询时间开始
        $time_nd = $request->input('time_nd');//查询时间结束
        $time_st_format = $time_nd_format = 0;//实例化时间格式
        if (!empty($time_st) && !empty($time_nd)) {
            $time_st_format = strtotime($time_st . ' 00:00:00');//开始时间转时间戳
            $time_nd_format = strtotime($time_nd . ' 23:59:59');//结束时间转时间戳
        }
        $search_data = ['account' => $account, 'time_st' => $time_st, 'time_nd' => $time_nd];//分页参数
        $list = OperationLog::getUnionPaginate($account, $time_st_format, $time_nd_format, 10, 'id');//所有操作记录
        $roles = [];
        foreach ($list as $key => $val) {
            $roles[$val->id] = OrganizationRole::getLogsRoleName($val->account_id);
        }
        return view('Zerone/Dashboard/operation_log', ['list' => $list, 'roles' => $roles, 'search_data' => $search_data, 'admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
    }

    /**
     * 所有登录记录
     */
    public function login_log(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $account = $request->input('account');//通过登录页账号查询
        $time_st = $request->input('time_st');//查询时间开始
        $time_nd = $request->input('time_nd');//查询时间结束
        $time_st_format = $time_nd_format = 0;//实例化时间格式
        if (!empty($time_st) && !empty($time_nd)) {
            $time_st_format = strtotime($time_st . ' 00:00:00');//开始时间转时间戳
            $time_nd_format = strtotime($time_nd . ' 23:59:59');//结束时间转时间戳
        }
        $search_data = ['account' => $account, 'time_st' => $time_st, 'time_nd' => $time_nd];//分页参数
        $list = LoginLog::getUnionPaginate($account, $time_st_format, $time_nd_format, 15, 'id');//所有登入记录
        return view('Zerone/Dashboard/login_log', ['list' => $list, 'search_data' => $search_data, 'admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
    }

    /**
     * 系统人员结构
     */
    public function structure(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = 1;//当前组织ID，零壹管理平台组织只能为1
        //获取Admin开始的的所有人员
        $list = Account::getList([['organization_id', $organization_id], ['parent_tree', 'like', '%' . '0,1,' . '%']], 0, 'id', 'asc')->toArray();
        $structure = $this->create_structure($list, 1);//根据获取的人员组成结构树
        return view('Zerone/Dashboard/structure', ['structure' => $structure, 'admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
    }

    /**
     * 递归生成人员结构的方法
     * $list - 结构所有人员的无序列表
     * $id - 上级ID
     */
    private function create_structure($list, $id)
    {
        $structure = '';
        foreach ($list as $key => $val) {
            if ($val['parent_id'] == $id) {
                $structure .= '<ol class="dd-list"><li class="dd-item" data-id="' . $val['id'] . '">';
                $structure .= '<div class="dd-handle">';
                $structure .= '<span class="pull-right">创建时间：' . date('Y-m-d,H:i:s', $val['created_at']) . '</span>';
                $structure .= '<span class="label label-info"><i class="fa fa-user"></i></span>';
                $structure .= $val['account'] . '-' . $val['account_info']['realname'];
                if (!empty($val['account_roles'])) {
                    $structure .= '【' . $val['account_roles'][0]['role_name'] . '】';
                }
                $structure .= '</div>';
                $son_menu = $this->create_structure($list, $val['id']);
                if (!empty($son_menu)) {
                    $structure .= $son_menu;
                }
                $structure .= '</li></ol>';
            }
        }
        return $structure;
    }

    /**
     * 退出登录
     */
    public function quit(Request $request)
    {
        Session::put('zerone_account_id', '');
        return redirect('zerone/login');
    }
}

?>