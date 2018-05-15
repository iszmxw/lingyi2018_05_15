<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Organization;
use App\Models\OrganizationAssets;
use App\Models\OrganizationAssetsallocation;
use App\Models\OrganizationFansmanageapply;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class FansmanageController extends Controller
{
    //商户注册列表
    public function fansmanage_register(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $fansmanage_name = $request->input('fansmanage_name');
        $fansmanage_owner_mobile = $request->input('fansmanage_owner_mobile');
        $search_data = ['fansmanage_name' => $fansmanage_name, 'fansmanage_owner_mobile' => $fansmanage_owner_mobile];
        $where = [['agent_id', $admin_data['organization_id']]];
        if (!empty($fansmanage_name)) {
            $where[] = ['fansmanage_name', 'like', '%' . $fansmanage_name . '%'];
        }
        if (!empty($fansmanage_owner_mobile)) {
            $where[] = ['fansmanage_owner_mobile', $fansmanage_owner_mobile];
        }
        $list = Organizationfansmanageapply::getPaginage($where, '15', 'id');
        return view('Agent/Fansmanage/fansmanage_register', ['list' => $list, 'search_data' => $search_data, 'admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
    }

    //商户列表
    public function fansmanage_list(Request $request)
    {
        $organization_name = $request->input('organization_name');
        $fansmanage_owner_mobile = $request->input('fansmanage_owner_mobile'); // 接收查询条件
        $search_data = ['organization_name' => $organization_name, 'fansmanage_owner_mobile' => $fansmanage_owner_mobile]; //返回查询默认参数
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization = $admin_data['organization_id'];
        if (!empty($organization_name)) {
            $where = [['parent_id', $organization], ['program_id', 3], ['organization_name', 'like', '%' . $organization_name . '%']]; //页面商户名称查询
        }
//        if(!empty($fansmanage_owner_mobile)){
//            $where = [['parent_id', $organization], ['program_id', 3],['fansmanage_owner_mobile', 'like', '%' . $fansmanage_owner_mobile . '%']]; //电话号码查询
//        }

        if (empty($where)) {
            $where = [['parent_id', $organization], ['program_id', 3]];
        }
        $list = Organization::getPaginagefansmanage($where, 10, 'id'); //数据库条件查询商户
        return view('Agent/Fansmanage/fansmanage_list', ['list' => $list, 'search_data' => $search_data, 'admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
    }

    //店铺结构
    public function fansmanage_structure(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = $request->input('organization_id');//当前组织ID，零壹管理平台组织只能为1
        $list = Organization::getList([['parent_id', $organization_id]]);
        foreach ($list as $key => $value) {
            $oneAcc = Account::getOne([['organization_id', $value['id']], ['deepth', 1]]);//查找分公司对应的负责人信息
            $list[$key]['oneAcc'] = $oneAcc;
            $parent_tree = $oneAcc['parent_tree'];//组织树
            //获取重Admin开始的的所有人员
            $accList = Account::getList([['organization_id', $value['id']], ['parent_tree', 'like', '%' . $parent_tree . $oneAcc['id'] . ',%']], 0, 'id', 'asc')->toArray();
            //根据获取的人员组成结构树
            $list[$key]['structure'] = $this->create_structure($accList, $oneAcc['id']);
        }
        return view('Agent/Fansmanage/fansmanage_structure', ['list' => $list, 'admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
    }

    private function create_structure($list, $id)
    {
        $structure = '';
        foreach ($list as $key => $val) {
            if ($val['parent_id'] == $id) {
                $structure .= '<ol class="dd-list"><li class="dd-item" data-id="' . $val['id'] . '">';
                $structure .= '<div class="dd-handle">';
                $structure .= '<span class="pull-right">创建时间：' . date('Y-m-d,H:i:s', $val['created_at']) . '</span>';
                $structure .= '<span class="label label-info"><i class="icon-user"></i></span>';
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

    //程序划拨
    public function fansmanage_program(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');//中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');//中间件产生的管理员数据参数
        $route_name = $request->path();//获取当前的页面路由
        $organization_id = $request->input('organization_id');//分公司id
        $oneFansmanage = Organization::getOneFansmanage([['id', $organization_id]]);
        $data = Program::getOne([['id', $oneFansmanage['asset_id']]]);
        $re = OrganizationAssets::getOne([['organization_id', $organization_id], ['program_id', $oneFansmanage['asset_id']]]);
        $data['program_balance'] = $re['program_balance'];
        $data['program_used_num'] = $re['program_used_num'];

        return view('Agent/Fansmanage/fansmanage_program', ['oneFansmanage' => $oneFansmanage, 'data' => $data, 'admin_data' => $admin_data, 'route_name' => $route_name, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data]);
    }

    //程序划拨
    public function fansmanage_assets(Request $request)
    {
        $organization_id = $request->input('organization_id');//分公司id
        $program_id = $request->input('program_id');//套餐id
        $status = $request->input('status');//状态
        $oneFansmanage = Organization::getOneFansmanage([['id', $organization_id]]);
        $oneProgram = Program::getOne([['id', $program_id]]);
        return view('Agent/Fansmanage/fansmanage_assets', ['oneFansmanage' => $oneFansmanage, 'oneProgram' => $oneProgram, 'status' => $status]);
    }

    //商户资产页面划入js显示
    public function fansmanage_assets_check(Request $request)
    {
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        $to_organization_id = $admin_data['organization_id'];
        $operator_id = $admin_data['id'];

        $organization_id = $request->input('organization_id');//商户id
        $program_id = $request->input('program_id');//程序id
        $number = $request->input('num');//数量
        $status = $request->input('status');//判断划入或者划出
        DB::beginTransaction();
        try {
            $re = OrganizationAssets::getOne([['organization_id', $organization_id], ['program_id', $program_id]]);//查询商户程序系统数量
            $oneAgent = OrganizationAssets::getOne([['organization_id', $admin_data['organization_id']], ['program_id', $program_id]]);//查询分公司套餐系统数量
            $id = $re['id'];
            if ($status == '1') {//划入
                if ($oneAgent['program_balance'] < $number) {//数量不足
                    return response()->json(['data' => '数量不足', 'status' => '0']);
                }
                if (empty($re)) {
                    OrganizationAssets::addAssets(['organization_id' => $organization_id, 'program_id' => $program_id, 'program_balance' => $number, 'program_used_num' => '0']);//新添加商户系统数量
                } else {
                    $num = $re['program_balance'] + $number;
                    OrganizationAssets::editAssets([['id', $id]], ['program_balance' => $num]);//商户原来的基础上加上系统数量
                }
                $agentNum = $oneAgent['program_balance'] - $number;//剩余数量
                $agentUseNum = $oneAgent['program_used_num'] + $number;//使用数量
                OrganizationAssets::editAssets([['id', $oneAgent['id']]], ['program_balance' => $agentNum, 'program_used_num' => $agentUseNum]);//修改分公司系统数量
            } else {//划出
                if (empty($re)) {
                    return response()->json(['data' => '商户系统数量不足划出', 'status' => '0']);
                } else {
                    if ($re['program_balance'] >= $number) {//划出数量小于或等于剩余数量
                        $num = $re['program_balance'] - $number;
                        OrganizationAssets::editAssets([['id', $id]], ['program_balance' => $num]);
                    } else {
                        return response()->json(['data' => '商户系统数量不足划出', 'status' => '0']);
                    }
                }
                $agentNum = $oneAgent['program_balance'] + $number;//剩余数量
                $agentUseNum = $oneAgent['program_used_num'] - $number;//使用数量
                OrganizationAssets::editAssets([['id', $oneAgent['id']]], ['program_balance' => $agentNum, 'program_used_num' => $agentUseNum]);//修改分公司系统数量
            }
            $data = ['operator_id' => $operator_id, 'fr_organization_id ' => $organization_id, 'to_organization_id' => $to_organization_id, 'program_id' => $program_id, 'status' => $status, 'number' => $number];
            //添加操作日志
            OrganizationAssetsallocation::addOrganizationAssetsallocation($data); //保存操作记录
            DB::commit();//提交事务
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '操作失败', 'status' => '0']);
        }
        return response()->json(['data' => '操作成功', 'status' => '1']);
    }
}

?>