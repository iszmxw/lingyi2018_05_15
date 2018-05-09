<?php
/**
 * 零售版店铺
 * 进出开单管理
 **/

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\OperationLog;
use App\Models\Organization;
use App\Models\RetailCheckOrder;
use App\Models\RetailGoods;
use App\Models\RetailLossOrder;
use App\Models\RetailPurchaseOrder;
use App\Models\RetailStock;
use App\Models\RetailStockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $search_data = [
            'ordersn' => $ordersn,
        ];
        $where = [
            'retail_id' => $admin_data['organization_id']
        ];
        $list = RetailPurchaseOrder::getPaginage($where, $search_data, '10', 'created_at', 'DESC'); //订单信息
        return view('Retail/Billing/purchase_goods', ['ordersn' => $ordersn, 'list' => $list, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //报损商品订单管理页面
    public function loss_goods(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $ordersn = $request->get('ordersn');                //订单编号
        $search_data = [
            'ordersn' => $ordersn,
        ];
        $where = [
            'retail_id' => $admin_data['organization_id']
        ];
        $list = RetailLossOrder::getPaginage($where, $search_data, '10', 'created_at', 'DESC'); //订单信息
        return view('Retail/Billing/loss_goods', ['ordersn' => $ordersn, 'list' => $list, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //盘点商品订单管理页面
    public function check_goods(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $ordersn = $request->get('ordersn');                //订单编号
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');         //获取粉丝管理平台的组织id
        $search_data = [
            'ordersn' => $ordersn,
        ];
        $where = [
            'retail_id' => $admin_data['organization_id'],
            'fansmanage_id' => $fansmanage_id,
        ];
        $list = RetailCheckOrder::getPaginage($where, $search_data, '10', 'created_at', 'DESC'); //订单信息
        return view('Retail/Billing/check_goods', ['ordersn' => $ordersn, 'list' => $list, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //审核进出货订单页面
    public function purchase_list_confirm(Request $request)
    {
        $order_id = $request->get('id');        //会员标签id
        $status = $request->status;                 //冻结或者解锁
        $order = RetailPurchaseOrder::getOne(['id' => $order_id])->first();    //获取订单信息
        return view('Retail/Billing/purchase_list_confirm', ['order' => $order, 'status' => $status]);
    }

    //审核报损订单页面
    public function loss_list_confirm(Request $request)
    {
        $order_id = $request->get('id');        //会员标签id
        $status = $request->status;                 //冻结或者解锁
        $order = RetailLossOrder::getOne(['id' => $order_id])->first();    //获取订单信息
        return view('Retail/Billing/loss_list_confirm', ['order' => $order, 'status' => $status]);
    }

    //审核盘点订单页面
    public function check_list_confirm(Request $request)
    {
        $order_id = $request->get('id');        //会员标签id
        $status = $request->status;                 //冻结或者解锁
        $order = RetailCheckOrder::getOne(['id' => $order_id])->first();    //获取订单信息
        return view('Retail/Billing/check_list_confirm', ['order' => $order, 'status' => $status]);
    }

    //订单列表详情
    public function order_list_details(Request $request)
    {
        $order_id = $request->get('id');        //订单ID
        $type = $request->get('type');          //订单类型type（1和2为从供应商进货退货开单类型，3为报损订单类型，4为盘点订单类型）
        if ($type == 1 || $type == 2) {
            $order = RetailPurchaseOrder::getOne(['id' => $order_id])->first();    //获取订单信息
        } elseif ($type == 3) {
            $order = RetailLossOrder::getOne(['id' => $order_id])->first();       //获取订单信息
        } elseif ($type == 4) {
            $order = RetailCheckOrder::getOne(['id' => $order_id])->first();       //获取订单信息
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
        $order = RetailPurchaseOrder::getOne(['id' => $order_id])->first();    //获取订单信息
        $order_goods = $order->RetailPurchaseOrderGoods;    //订单对应的商品
        $type = $order->type;                               //订单类型
        if ($status == 0) {
            DB::beginTransaction();
            try {
                $this->edit_stock($order_goods, $type);
                //添加库存操作记录日志
                foreach ($order_goods as $key => $val) {
                    $stock_data = [
                        'fansmanage_id' => $order->fansmanage_id,
                        'retail_id' => $order->retail_id,
                        'goods_id' => $val->goods_id,
                        'amount' => $val->total,
                        'ordersn' => $order->ordersn,
                        'operator_id' => $order->operator_id,
                        'remark' => $order->remarks,
                        'type' => $type,
                        'status' => '1',
                    ];
                    RetailStockLog::addStockLog($stock_data);
                }
                RetailPurchaseOrder::editOrder(['id' => $order_id], ['status' => '1']);
                //添加操作日志
                if ($admin_data['is_super'] == 1) {//超级管理员审核订单操作记录
                    OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统审核了供应商订单！');//保存操作记录
                } else {//零售店铺本人操作记录
                    OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '审核了供应商订单！');//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                dd($e);
                DB::rollBack();//事件回滚
                return response()->json(['data' => '审核供应商订单失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '审核供应商订单成功', 'status' => '1']);
        }
    }

    //报损订单审核确认

    /**
     * 修改库存处理
     * 进货后处理商品库存
     * 1、处理商品信息的库存
     * 2、处理库存表的库存
     * 需要的参数：
     * $order_goods（订单商品）
     * $type（订单类型）
     **/
    public static function edit_stock($order_goods, $type)
    {
        foreach ($order_goods as $key => $val) {
            $old_stock = RetailGoods::getPluck(['id' => $val->goods_id], 'stock')->first(); //查询原来商品的库存
            if ($type == 1) {                    //加库存：type=1、进货类型
                $new_stock = $old_stock + $val->total;    //新的库存
            } elseif ($type == 2 || $type == 3) {  //减库存：type=2|type=3、退货类型|报损类型
                $new_stock = $old_stock - $val->total;    //新的库存
            } elseif ($type == 4) {                //更新覆盖库存：type=4 盘点类型
                $new_stock = $val->total;               //新的库存
            }
            //1、更新商品信息中的库存
            RetailGoods::editRetailGoods(['id' => $val->goods_id], ['stock' => $new_stock]);
            //2、更新库存表的库存
            RetailStock::editStock(['goods_id' => $val->goods_id], ['stock' => $new_stock]);
        }
    }

    //盘点订单审核确认

    public function loss_list_confirm_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $order_id = $request->get('order_id');        //会员标签id
        $status = $request->get('status');            //接收订单当前状态

        $order = RetailLossOrder::getOne(['id' => $order_id])->first();    //获取订单信息
        $order_goods = $order->RetailLossOrderGoods;    //订单对应的商品
        $type = $order->type;                               //订单类型
        if ($status == 0) {
            DB::beginTransaction();
            try {
                $this->edit_stock($order_goods, $type);
                //添加库存操作记录日志
                foreach ($order_goods as $key => $val) {
                    $stock_data = [
                        'fansmanage_id' => $order->fansmanage_id,
                        'retail_id' => $order->retail_id,
                        'goods_id' => $val->goods_id,
                        'amount' => $val->total,
                        'ordersn' => $order->ordersn,
                        'operator_id' => $order->operator_id,
                        'remark' => $order->remarks,
                        'type' => $type,
                        'status' => '1',
                    ];
                    RetailStockLog::addStockLog($stock_data);
                }
                RetailLossOrder::editOrder(['id' => $order_id], ['status' => '1']);
                //添加操作日志
                if ($admin_data['is_super'] == 1) {//超级管理员审核订单操作记录
                    OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统审核了报损订单！');//保存操作记录
                } else {//零售店铺本人操作记录
                    OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '审核了报损订单！');//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '审核报损订单失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '审核报损订单成功', 'status' => '1']);
        }
    }


    //库存查询

    public function check_list_confirm_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $order_id = $request->get('order_id');        //会员标签id
        $status = $request->get('status');            //接收订单当前状态
        $order = RetailCheckOrder::getOne(['id' => $order_id])->first();    //获取订单信息
        $order_goods = $order->RetailCheckOrderGoods;    //订单对应的商品
        $type = $order->type;                               //订单类型
        if ($status == 0) {
            DB::beginTransaction();
            try {
                $this->edit_stock($order_goods, $type);
                //添加库存操作记录日志
                foreach ($order_goods as $key => $val) {
                    $stock_data = [
                        'fansmanage_id' => $order->fansmanage_id,
                        'retail_id' => $order->retail_id,
                        'goods_id' => $val->goods_id,
                        'amount' => $val->total,
                        'ordersn' => $order->ordersn,
                        'operator_id' => $order->operator_id,
                        'remark' => $order->remarks,
                        'type' => $type,
                        'status' => '1',
                    ];
                    RetailStockLog::addStockLog($stock_data);
                }
                RetailCheckOrder::editOrder(['id' => $order_id], ['status' => '1']);
                //添加操作日志
                if ($admin_data['is_super'] == 1) {//超级管理员审核订单操作记录
                    OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统审核了盘点订单！');//保存操作记录
                } else {//零售店铺本人操作记录
                    OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '审核了盘点订单！');//保存操作记录
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();//事件回滚
                return response()->json(['data' => '审核盘点订单失败，请检查', 'status' => '0']);
            }
            return response()->json(['data' => '审核盘点订单成功', 'status' => '1']);
        }
    }

    public function stock_list(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $goods_name = $request->get('goods_name');         //获取供应商名称
        $stock_list = [];
        if ($goods_name != null) {
            $where = [['retail_id', $admin_data['organization_id']], ['name', 'like', '%' . $goods_name . '%']];
            $goods = RetailGoods::getList($where, 0, 'created_at', 'DESC');
            foreach ($goods as $key => $val) {
                $stock_list[] = RetailStock::getOne(['goods_id' => $val->id]);
            }
        } else {
            $where = [['retail_id', $admin_data['organization_id']]];
            $stock_list = RetailStock::getPaginage($where, '10', 'created_at', 'ASC'); //查询商品信息
        }
        return view('Retail/Billing/stock_list', ['stock_list' => $stock_list, 'goods_name' => $goods_name, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }
}

?>