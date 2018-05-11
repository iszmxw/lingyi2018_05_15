<?php
/**
 * 零售版店铺
 * 进销存管理-进出管理
 **/

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\OperationLog;
use App\Models\Organization;
use App\Models\RetailCategory;
use App\Models\RetailSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    //零售进销存管理--供应商添加
    public function supplier_add(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $where = [
            'retail_id' => $admin_data['organization_id'],
        ];
        $category = RetailCategory::getList($where, '0', 'displayorder', 'DESC');   //栏目
        return view('Retail/Supplier/supplier_add', ['category' => $category, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //零售进销存管理--供应商添加数据操作
    public function supplier_add_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $company_name = $request->get('company_name');      //公司名称
        $contactname = $request->get('contactname');        //联系人姓名
        $contactmobile = $request->get('contactmobile');    //联系人电话
        $displayorder = $request->get('displayorder');      //排序
        if (empty($displayorder)) {
            $displayorder = '0';
        }
        if (RetailSupplier::checkRowExists(['company_name' => $company_name])) {
            return response()->json(['data' => '供应商名称已存在，请检查', 'status' => '0']);
        }
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');
        $supplier_data = [
            'company_name' => $company_name,
            'contactname' => $contactname,
            'contactmobile' => $contactmobile,
            'displayorder' => $displayorder,
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $admin_data['organization_id'],
        ];
        DB::beginTransaction();
        try {
            RetailSupplier::addSupplier($supplier_data);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员添加零售店铺供应商的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统添加了供应商！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '添加了供应商！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '添加供应商失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '添加供应商成功', 'status' => '1']);
    }

    //零售进销存管理--供应商列表
    public function supplier_list(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $company_name = $request->get('company_name');      //获取供应商名称
        $search_data = ['company_name' => $company_name];         //搜索参数
        $where[] = ['retail_id', $admin_data['organization_id']];
        if (!empty($company_name)) {
            $where[] = ['company_name', 'like', '%' . $company_name . '%'];
        }
        $supplier = RetailSupplier::getPaginage($where, '10', 'displayorder', 'DESC');   //供应商信息
        return view('Retail/Supplier/supplier_list', ['supplier' => $supplier, 'search_data' => $search_data, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //供应商编辑弹窗
    public function supplier_edit(Request $request)
    {
        $supplier_id = $request->get('id');
        $supplier = RetailSupplier::getOne(['id' => $supplier_id]);
        return view('Retail/Supplier/supplier_edit', ['supplier' => $supplier]);
    }

    //供应商删除弹窗
    public function supplier_delete(Request $request)
    {
        $supplier_id = $request->get('id');
        return view('Retail/Supplier/supplier_delete', ['supplier_id' => $supplier_id]);
    }

    //供应商删除操作
    public function supplier_delete_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $supplier_id = $request->get('supplier_id');
        DB::beginTransaction();
        try {
            RetailSupplier::select_delete($supplier_id);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员删除零售店铺供应商的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统删除了供应商！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '删除了供应商！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '删除供应商失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '删除供应商成功', 'status' => '1']);
    }

    //供应商编辑操作
    public function supplier_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $supplier_id = $request->get('supplier_id');        //接收供应商id
        $company_name = $request->get('company_name');        //接收供应商名称
        $contactname = $request->get('contactname');        //接收供应商联系人姓名
        $contactmobile = $request->get('contactmobile');    //接收供应商联系人手机号码
        $supplier_data = [
            'company_name' => $company_name,
            'contactname' => $contactname,
            'contactmobile' => $contactmobile,
        ];
        DB::beginTransaction();
        try {
            RetailSupplier::editSupplier(['id' => $supplier_id], $supplier_data);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员修改零售店铺供应商的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统修改了供应商信息！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '修改了供应商信息！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '修改供应商失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '修改供应商成功', 'status' => '1']);
    }
}

?>