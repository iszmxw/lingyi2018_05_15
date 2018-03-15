<?php
/**
 * 零售版店铺
 * 栏目管理
 **/

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\OperationLog;
use App\Models\CateringCategory;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class CategoryController extends Controller
{
    //添加商品分类页面
    public function category_add(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        return view('Retail/Category/category_add',['admin_data'=>$admin_data,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data,'route_name'=>$route_name]);
    }

    //添加商品分类操作
    public function category_add_check(Request $request)
    {
        $admin_data = $request->get('admin_data');      //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $category_name = $request->get('category_name');    //栏目名称
        $category_sort = $request->get('category_sort');    //栏目排序
        if (empty($category_sort)){
            $category_sort = '0';
        }
        $fansmanage_id = Organization::getPluck(['id'=>$admin_data['organization_id']],'parent_id')->first();
        $category_data = [
            'name' => $category_name,
            'created_by' => $admin_data['id'],
            'displayorder' => $category_sort,
            'fansmanage_id' => $fansmanage_id,
            'restaurant_id' => $admin_data['organization_id'],
        ];
        DB::beginTransaction();
        try {
            CateringCategory::addCategory($category_data);
            //添加操作日志
            if ($admin_data['is_super'] == 1){//超级管理员操作商户的记录
                OperationLog::addOperationLog('1','1','1',$route_name,'在餐饮分店管理系统添加了栏目分类！');//保存操作记录
            }else{//商户本人操作记录
                OperationLog::addOperationLog('5',$admin_data['organization_id'],$admin_data['id'],$route_name, '添加了栏目分类！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '添加分类失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '添加分类信息成功', 'status' => '1']);
    }

    //商品分类列表
    public function category_list(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的管理员数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $category_name = $request->get('name');
        $where = [
            'restaurant_id' => $admin_data['organization_id'],
        ];
        $category = CateringCategory::getPaginage($where,$category_name,'10','displayorder','DESC');
        return view('Retail/Category/category_list',['category_name'=>$category_name,'category'=>$category,'admin_data'=>$admin_data,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data,'route_name'=>$route_name]);
    }

    //商品分类删除弹窗
    public function category_delete(Request $request)
    {
        $category_id = $request->get('id');              //分类栏目的id
        return view('Retail/Category/category_delete',['category_id'=>$category_id]);
    }

    //商品分类删除弹窗
    public function category_delete_check(Request $request)
    {
        $admin_data = $request->get('admin_data');           //中间件产生的管理员数据参数
        $route_name = $request->path();                          //获取当前的页面路由
        $category_id = $request->get('category_id');        //获取分类栏目ID
        DB::beginTransaction();
        try {
            CateringCategory::select_delete($category_id);
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员操作商户的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在餐饮分店管理系统删除了商品分类！');//保存操作记录
            } else {//分店本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '删除了商品分类！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '删除分类失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '删除分类信息成功', 'status' => '1']);
    }

    //商品分类编辑页面
    public function category_edit(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $category_id = $request->get('id');
        $where = [
            'restaurant_id' => $admin_data['organization_id'],
            'id' => $category_id,
        ];
        $category = CateringCategory::getOne($where);
        return view('Retail/Category/category_edit',['category'=>$category,'admin_data'=>$admin_data]);
    }

    //商品分类编辑提交
    public function category_edit_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                     //获取当前的页面路由
        $name = $request->get('category_name');                      //栏目名称
        $displayorder = $request->get('displayorder');      //栏目排序
        $category_id = $request->get('category_id');         //栏目id
        if (empty($displayorder)){
            $displayorder = '0';
        }
        $where = [
            'restaurant_id' => $admin_data['organization_id'],
            'id' => $category_id,
        ];
        $category_data = [
            'name' => $name,
            'displayorder' => $displayorder,
        ];
        DB::beginTransaction();
        try {
            CateringCategory::editCategory($where,$category_data);
            //添加操作日志
            if ($admin_data['is_super'] == 1){//超级管理员操作商户的记录
                OperationLog::addOperationLog('1','1','1',$route_name,'在餐饮分店管理系统修改了栏目分类！');//保存操作记录
            }else{//商户本人操作记录
                OperationLog::addOperationLog('5',$admin_data['organization_id'],$admin_data['id'],$route_name, '修改了栏目分类！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => '修改分类失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => '修改分类信息成功', 'status' => '1']);
    }
}

?>