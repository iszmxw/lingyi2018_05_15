<?php
/**
 * 零售版店铺
 * 栏目分类管理
 **/

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\Dispatch;
use App\Models\DispatchProvince;
use App\Models\OperationLog;
use App\Models\Province;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispatchController extends Controller
{
    //添加运费模板页面
    public function dispatch_add(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        return view('Retail/Dispatch/dispatch_add', ['admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //添加运费模板操作
    public function dispatch_add_check(Request $request)
    {
        $admin_data = $request->get('admin_data');           //中间件产生的管理员数据参数
        $route_name = $request->path();                          //获取当前的页面路由
        $dispatch_name = $request->get('dispatch_name');    //栏目名称
        $displayorder = $request->get('displayorder');      //栏目排序
        $number = date('Ymdhis') . rand(1000, 9999);        //时间加上4为随机数，一共18位数
        $row = Dispatch::checkRowExists(['name' => $dispatch_name]);
        if (empty($dispatch_name)) {
            return response()->json(['data' => '请输入运费模板名称！', 'status' => '0']);
        } elseif ($row) {
            return response()->json(['data' => '运费模板名称重复，请重新输入！', 'status' => '0']);
        }
        if (empty($displayorder)) {
            $displayorder = '0';
        }
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');
        $dispatch_data = [
            'name' => $dispatch_name,
            'number' => $number,
            'displayorder' => $displayorder,
            'fansmanage_id' => $fansmanage_id,
            'store_id' => $admin_data['organization_id'],
        ];
        DB::beginTransaction();
        try {
            Dispatch::addDispatch($dispatch_data);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员添加零售店铺分类的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统添加了运费模板！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '添加了运费模板！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '添加运费模板失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '添加运费模板信息成功', 'status' => '1']);
    }


    //运费模板列表
    public function dispatch_list(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $dispatch_name = $request->get('dispatch_name');    //模板名称
        $search_data = ['dispatch_name' => $dispatch_name];       //搜索参数
        $where[] = ['store_id', $admin_data['organization_id']];
        if (!empty($dispatch_name)) {
            $where[] = ['name', 'like', '%' . $dispatch_name . '%'];
        }
        $list = Dispatch::getPaginage($where, '0', 'displayorder', 'DESC');
        return view('Retail/Dispatch/dispatch_list', ['list' => $list, 'search_data' => $search_data, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //运费模板删除弹窗
    public function dispatch_list_delete(Request $request)
    {
        $id = $request->get('id');
        return view('Retail/Dispatch/dispatch_list_delete', ['id' => $id]);
    }

    //运费模板删除确认操作
    public function dispatch_list_delete_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $id = $request->get('id');
        DB::beginTransaction();
        try {
            Dispatch::select_delete($id);
            DispatchProvince::select_deletes(['dispatch_id' => $id]);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统删除了运费模板！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '删除了运费模板！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '删除运费模板失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '删除运费模板成功', 'status' => '1']);
    }

    //运费模板启用弹窗
    public function dispatch_list_lock(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        return view('Retail/Dispatch/dispatch_list_lock', ['id' => $id, 'status' => $status]);
    }

    //运费模板启用弹窗
    public function dispatch_list_lock_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $id = $request->get('id');                          //获取运费模板id
        $status = $request->get('status');
        if ($status == 0) {
            $status = '1';
            $tips = '启用';
        } else {
            $status = '0';
            $tips = '弃用';
        }
        DB::beginTransaction();
        try {
            Dispatch::editDispatch(['id' => $id], ['status' => $status]);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统' . $tips . '了运费模板！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, $tips . '了运费模板！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => $tips . '运费模板失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => $tips . '运费模板成功', 'status' => '1']);
    }

    //编辑运费模板
    public function dispatch_edit(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $dispatch_id = $request->get('id');                //模板ID
        $dispatch = Dispatch::getOne(['id' => $dispatch_id]);     //运费模板信息
        //运费模板已经设置的省份，列表信息
        $dispatch_province = DispatchProvince::getList(['dispatch_id' => $dispatch_id], 0, 'id', 'ASC');
        $province_name = [];        //初始化已选的省份
        foreach ($dispatch_province as $key => $val) {//遍历处理已选的省份
            $provinces = explode(',', $val->province_id);
            foreach ($provinces as $kk => $vv) {
                $province_name[] = Province::getOne(['id' => $vv])->first()->toArray();
                $province_data[$val->id][$vv] = Province::getOne(['id' => $vv])->first()->toArray();
            }
            $val->province_name = $province_data[$val->id];       //将查询出来的省份存进原有模型
        }
        $province = Province::getList([], 0, 'id', 'ASC')->toArray();  //  查询出所有省份
        //找出已选的省份并删除
        foreach ($province as $k => $v) {
            if (in_array($v, $province_name)) {
                unset($province[$k]);
            }
        }
        return view('Retail/Dispatch/dispatch_edit', ['province' => $province, 'dispatch' => $dispatch, 'dispatch_province' => $dispatch_province, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //运费模板省份添加
    public function dispatch_province_add_check(Request $request)
    {
        $admin_data = $request->get('admin_data');           //中间件产生的管理员数据参数
        $route_name = $request->path();                          //获取当前的页面路由
        $dispatch_id = $request->get('dispatch_id');
        $provinces = $request->get('provinces');
        if (empty($provinces)) {
            return response()->json(['data' => '请选择配送区域', 'status' => '0']);
        }
        $province = implode(',', $provinces);
        $dispatch_province = ['dispatch_id' => $dispatch_id, 'province_id' => $province];
        DB::beginTransaction();
        try {
            DispatchProvince::addDispatchProvince($dispatch_province);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统设置了运费模板！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '设置了运费模板！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '添加运费区域失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '添加运费区域信息成功', 'status' => '1']);
    }

    //运费模板省份编辑
    public function dispatch_province_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');           //中间件产生的管理员数据参数
        $route_name = $request->path();                          //获取当前的页面路由
        $dispatch_name = $request->get('dispatch_name');     //获取运费模板名称
        $displayorder = $request->get('displayorder');      //获取运费模板排序
        $dispatch_id = $request->get('dispatch_id');         //获取运费模板id
        $dispatch_data = $request->get('dispatch_data');      //获取运费模板详细信息
        DB::beginTransaction();
        try {
            foreach ($dispatch_data as $key => $val) {
                DispatchProvince::editDispatchProvince(['id' => $key, 'dispatch_id' => $dispatch_id], ['first_weight' => $val['first_weight'], 'additional_weight' => $val['additional_weight'], 'freight' => $val['freight'], 'renewal' => $val['renewal']]);
            }
            Dispatch::editDispatch(['id' => $dispatch_id], ['name' => $dispatch_name, 'displayorder' => $displayorder]);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统修改了运费模板！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '修改了运费模板！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '修改运费区域信息失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '修改运费区域信息成功', 'status' => '1']);
    }

    //运费模板省份删除
    public function dispatch_province_delete_check(Request $request)
    {
        $admin_data = $request->get('admin_data');           //中间件产生的管理员数据参数
        $route_name = $request->path();                          //获取当前的页面路由
        $province_id = $request->get('province_id');        //获取运费模板身份关系id
        DB::beginTransaction();
        try {
            DispatchProvince::select_delete($province_id);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统删除了运费模板所包含的部分省份！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '删除了运费模板所包含的部分省份！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '删除相关省份信息失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '删除相关省份信息成功', 'status' => '1']);
    }

}

?>