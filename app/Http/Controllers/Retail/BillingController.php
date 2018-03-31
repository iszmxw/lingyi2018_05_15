<?php
/**
 * 零售版店铺
 * 进出开单管理
 **/

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\OperationLog;
use App\Models\RetailCategory;
use App\Models\Organization;
use App\Models\RetailCheckOrder;
use App\Models\RetailGoods;
use App\Models\RetailLossOrder;
use App\Models\RetailPurchaseOrder;
use App\Models\RetailStock;
use App\Models\RetailStockLog;
use App\Models\RetailSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class BillingController extends Controller
{
    //采购商品订单管理页面
    public function purchase_goods(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $ordersn = $request->get('ordersn');                //订单编号
        $fansmanage_id = Organization::getPluck(['id'=>$admin_data['organization_id']],'parent_id')->first();         //获取粉丝管理平台的组织id
        $search_data = [
            'ordersn' => $ordersn,
        ];
        $where = [
            'retail_id' => $admin_data['organization_id'],
            'fansmanage_id' => $fansmanage_id,
        ];
        $list = RetailPurchaseOrder::getPaginage($where,$search_data,'10','created_at','DESC'); //订单信息
        return view('Retail/Billing/purchase_goods',['ordersn'=>$ordersn,'list'=>$list,'admin_data'=>$admin_data,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data,'route_name'=>$route_name]);
    }

    //报损商品订单管理页面
    public function loss_goods(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $ordersn = $request->get('ordersn');                //订单编号
        $fansmanage_id = Organization::getPluck(['id'=>$admin_data['organization_id']],'parent_id')->first();         //获取粉丝管理平台的组织id
        $search_data = [
            'ordersn' => $ordersn,
        ];
        $where = [
            'retail_id' => $admin_data['organization_id'],
            'fansmanage_id' => $fansmanage_id,
        ];
        $list = RetailLossOrder::getPaginage($where,$search_data,'10','created_at','DESC'); //订单信息
        return view('Retail/Billing/loss_goods',['ordersn'=>$ordersn,'list'=>$list,'admin_data'=>$admin_data,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data,'route_name'=>$route_name]);
    }

    //盘点商品订单管理页面
    public function check_goods(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $ordersn = $request->get('ordersn');                //订单编号
        $fansmanage_id = Organization::getPluck(['id'=>$admin_data['organization_id']],'parent_id')->first();         //获取粉丝管理平台的组织id
        $search_data = [
            'ordersn' => $ordersn,
        ];
        $where = [
            'retail_id' => $admin_data['organization_id'],
            'fansmanage_id' => $fansmanage_id,
        ];
        $list = RetailCheckOrder::getPaginage($where,$search_data,'10','created_at','DESC'); //订单信息
        return view('Retail/Billing/check_goods',['ordersn'=>$ordersn,'list'=>$list,'admin_data'=>$admin_data,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data,'route_name'=>$route_name]);
    }

    //审核订单页面
    public function purchase_list_confirm(Request $request)
    {
        $order_id = $request->get('id');        //会员标签id
        $status = $request->status;                 //冻结或者解锁
        $order = RetailPurchaseOrder::getOne(['id'=>$order_id])->first();    //获取订单信息
        return view('Retail/Billing/purchase_list_confirm', ['order' => $order,'status' => $status]);
    }

    //审核订单页面
    public function loss_list_confirm(Request $request)
    {
        $order_id = $request->get('id');        //会员标签id
        $status = $request->status;                 //冻结或者解锁
        $order = RetailLossOrder::getOne(['id'=>$order_id])->first();    //获取订单信息
        return view('Retail/Billing/loss_list_confirm', ['order' => $order,'status' => $status]);
    }

    //审核订单页面
    public function check_list_confirm(Request $request)
    {
        $order_id = $request->get('id');        //会员标签id
        $status = $request->status;                 //冻结或者解锁
        $order = RetailCheckOrder::getOne(['id'=>$order_id])->first();    //获取订单信息
        return view('Retail/Billing/check_list_confirm', ['order' => $order,'status' => $status]);
    }

    //审核订单页面
    public function order_list_details(Request $request)
    {
        $order_id = $request->get('id');        //订单ID
        $type = $request->get('type');          //订单类型type（1和2为从供应商进货退货开单类型，3为报损订单类型，4为盘点订单类型）
        if ($type == 1 || $type == 2){
            $order = RetailPurchaseOrder::getOne(['id'=>$order_id])->first();    //获取订单信息
        }elseif($type == 3){
            $order = RetailLossOrder::getOne(['id'=>$order_id])->first();       //获取订单信息
        }elseif($type == 4){
            $order = RetailCheckOrder::getOne(['id'=>$order_id])->first();       //获取订单信息
        }
        return view('Retail/Billing/order_list_details', ['order' => $order]);
    }

    //进货出货审核订单确认
    public function purchase_list_confirm_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $order_id = $request->get('order_id');        //会员标签id
        $status = $request->get('status');            //接收订单当前状态
        if ($status == 0){
            DB::beginTransaction();
            try {
                RetailPurchaseOrder::editOrder(['id'=>$order_id],['status'=>'1']);
                //添加操作日志
                if ($admin_data['is_super'] == 1){//超级管理员审核订单操作记录
                    OperationLog::addOperationLog('1','1','1',$route_name,'在零售管理系统审核了供应商订单！');//保存操作记录
                }else{//零售店铺本人操作记录
                    OperationLog::addOperationLog('10',$admin_data['organization_id'],$admin_data['id'],$route_name, '审核了供应商订单！');//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '审核供应商订单失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '审核供应商订单成功', 'status' => '1']);
        }
    }

    //报损订单审核确认
    public function loss_list_confirm_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $order_id = $request->get('order_id');        //会员标签id
        $status = $request->get('status');            //接收订单当前状态
        if ($status == 0){
            DB::beginTransaction();
            try {
                RetailLossOrder::editOrder(['id'=>$order_id],['status'=>'1']);
                //添加操作日志
                if ($admin_data['is_super'] == 1){//超级管理员审核订单操作记录
                    OperationLog::addOperationLog('1','1','1',$route_name,'在零售管理系统审核了报损订单！');//保存操作记录
                }else{//零售店铺本人操作记录
                    OperationLog::addOperationLog('10',$admin_data['organization_id'],$admin_data['id'],$route_name, '审核了报损订单！');//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '审核报损订单失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '审核报损订单成功', 'status' => '1']);
        }
    }

    //盘点订单审核确认
    public function check_list_confirm_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $order_id = $request->get('order_id');        //会员标签id
        $status = $request->get('status');            //接收订单当前状态
        if ($status == 0){
            DB::beginTransaction();
            try {
                RetailCheckOrder::editOrder(['id'=>$order_id],['status'=>'1']);
                //添加操作日志
                if ($admin_data['is_super'] == 1){//超级管理员审核订单操作记录
                    OperationLog::addOperationLog('1','1','1',$route_name,'在零售管理系统审核了盘点订单！');//保存操作记录
                }else{//零售店铺本人操作记录
                    OperationLog::addOperationLog('10',$admin_data['organization_id'],$admin_data['id'],$route_name, '审核了盘点订单！');//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '审核盘点订单失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '审核盘点订单成功', 'status' => '1']);
        }
    }


    //库存查询
    public function stock_list(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $goods_name = $request->get('goods_name');         //获取供应商名称
        $search_data = ['goods_name' => $goods_name];          //处理搜索参数
        $fansmanage_id = Organization::getPluck(['id'=>$admin_data['organization_id']],'parent_id')->first();    //获取粉丝管理平台的组织id
        $where = [
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $admin_data['organization_id'],
        ];
        $stock_list = RetailGoods::getPaginage($where,$search_data,'10','displayorder','ASC'); //查询商品信息
        foreach ($stock_list as $key=>$val){
            $stock_data = [
                'fansmanage_id' => $fansmanage_id,
                'retail_id' => $admin_data['organization_id'],
                'category_id' => $val->category_id,
                'goods_id' => $val->id,
                'stock' => $val->stock,
            ];
            RetailStock::addStock($stock_data);
        }

        return  view('Retail/Billing/stock_list',['stock_list'=>$stock_list,'search_data'=>$search_data,'admin_data'=>$admin_data,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data,'route_name'=>$route_name]);
    }


}

?>