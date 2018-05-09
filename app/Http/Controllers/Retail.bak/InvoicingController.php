<?php
/**
 * 零售版店铺
 * 进销存管理-进出开单
 **/

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\OperationLog;
use App\Models\Organization;
use App\Models\RetailCategory;
use App\Models\RetailCheckOrder;
use App\Models\RetailCheckOrderGoods;
use App\Models\RetailGoods;
use App\Models\RetailLossOrder;
use App\Models\RetailLossOrderGoods;
use App\Models\RetailPurchaseOrder;
use App\Models\RetailPurchaseOrderGoods;
use App\Models\RetailStockLog;
use App\Models\RetailSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicingController extends Controller
{
    //零售进销存开单--供应商到货开单
    public function purchase_goods(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $organization_id = $admin_data['organization_id'];//零壹管理平台只有一个组织
        $parent_tree = $admin_data['parent_tree'] . $admin_data['id'] . ',';
        //查询操作人员信息
        $account = Account::getList([['organization_id', $organization_id], ['parent_tree', 'like', '%' . $parent_tree . '%']], '0', 'id', 'DESC');
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');    //获取粉丝管理平台的组织id
        $where = [
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $admin_data['organization_id'],
        ];
        $category = RetailCategory::getList($where, '0', 'displayorder', 'DESC');   //栏目
        $supplier = RetailSupplier::getList($where, '0', 'displayorder', 'DESC');   //供应商信息

        return view('Retail/Invoicing/purchase_goods', ['supplier' => $supplier, 'account' => $account, 'category' => $category, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //零售进销存开单--退供应商货物开单
    public function return_goods(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $organization_id = $admin_data['organization_id'];//零壹管理平台只有一个组织
        $parent_tree = $admin_data['parent_tree'] . $admin_data['id'] . ',';
        //查询操作人员信息
        $account = Account::getList([['organization_id', $organization_id], ['parent_tree', 'like', '%' . $parent_tree . '%']], '0', 'id', 'DESC');
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');    //获取粉丝管理平台的组织id
        $where = [
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $admin_data['organization_id'],
        ];
        $category = RetailCategory::getList($where, '0', 'displayorder', 'DESC');   //栏目
        $supplier = RetailSupplier::getList($where, '0', 'displayorder', 'DESC');   //供应商信息
        return view('Retail/Invoicing/return_goods', ['supplier' => $supplier, 'account' => $account, 'category' => $category, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }


    //零售进销存开单--供应商搜索
    public function search_company(Request $request)
    {
        $admin_data = $request->get('admin_data');
        $retail_id = $admin_data['organization_id'];
        $company_id = $request->get('company_id');      //供应商ID
        $company_name = $request->get('company_name');      //供应商ID
        $contactmobile = $request->get('contactmobile');      //供应商ID
        $company = RetailSupplier::SearchCompany($retail_id, $company_id, $company_name, $contactmobile);
        if ($company == null) {
            return response()->json(['data' => '商户不存在！请重新选择！', 'status' => '0']);
        } else {
            return view('Retail/Invoicing/select_company', ['company' => $company]);
        }
    }


    //零售进销存开单--报损开单
    public function loss_goods(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $organization_id = $admin_data['organization_id'];//零壹管理平台只有一个组织
        $parent_tree = $admin_data['parent_tree'] . $admin_data['id'] . ',';
        //查询操作人员信息
        $account = Account::getList([['organization_id', $organization_id], ['parent_tree', 'like', '%' . $parent_tree . '%']], '0', 'id', 'DESC');
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');    //获取粉丝管理平台的组织id
        $where = [
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $admin_data['organization_id'],
        ];
        $category = RetailCategory::getList($where, '0', 'displayorder', 'DESC');   //栏目
        return view('Retail/Invoicing/loss_goods', ['account' => $account, 'category' => $category, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }


    //零售进销存开单--盘点开单
    public function check_goods(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $menu_data = $request->get('menu_data');            //中间件产生的菜单数据参数
        $son_menu_data = $request->get('son_menu_data');    //中间件产生的子菜单数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $organization_id = $admin_data['organization_id'];//零壹管理平台只有一个组织
        $parent_tree = $admin_data['parent_tree'] . $admin_data['id'] . ',';
        //查询操作人员信息
        $account = Account::getList([['organization_id', $organization_id], ['parent_tree', 'like', '%' . $parent_tree . '%']], '0', 'id', 'DESC');
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');    //获取粉丝管理平台的组织id
        $where = [
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $admin_data['organization_id'],
        ];
        $category = RetailCategory::getList($where, '0', 'displayorder', 'DESC');   //栏目
        return view('Retail/Invoicing/check_goods', ['account' => $account, 'category' => $category, 'admin_data' => $admin_data, 'menu_data' => $menu_data, 'son_menu_data' => $son_menu_data, 'route_name' => $route_name]);
    }

    //零售进销存开单--商品列表
    public function goods_list(Request $request)
    {
        $admin_data = $request->get('admin_data');      //中间件产生的管理员数据参数
        $category_id = $request->get('category_id');    //栏目分类id
        $goods_name = $request->get('goods_name');      //商品名称
        if (!empty($category_id) && !empty($goods_name)) {
            $where = [['retail_id', $admin_data['organization_id']], ['category_id', $category_id], ['name', 'LIKE', '%' . $goods_name . '%']];
        } elseif (!empty($category_id)) {
            $where = ['retail_id' => $admin_data['organization_id'], 'category_id' => $category_id];
        } elseif (!empty($goods_name)) {
            $where = [['retail_id', $admin_data['organization_id']], ['name', 'LIKE', '%' . $goods_name . '%']];
        } else {
            $where = ['retail_id' => $admin_data['organization_id']];
        }
        $goods = RetailGoods::getList($where, '0', 'id', 'DESC');
        return view('Retail/Invoicing/goods_list', ['goods' => $goods]);
    }


    //从供应商进货、退货开单的数据处理
    public function purchase_goods_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');


        $organization_id = $admin_data['organization_id'];
        $num = RetailPurchaseOrder::where([['retail_id', $organization_id], ['ordersn', 'LIKE', '%' . date("Ymd", time()) . '%']])->count();//查询订单今天的数量
        $num += 1;
        $sort = 100000 + $num;
        $ordersn = 'LS' . date("Ymd", time()) . '_' . $organization_id . '_' . $sort;//订单号

        $type = $request->get('type');          //接收订单类型  1：为进货订单、2为退货订单
        if ($type == 1) {
            $tips = '进货开单';
        } else {
            $tips = '退货开单';
        }
        $orders = $request->get('orders');                  //接收订单信息
        //进货开单订单信息整理
        $order_data = [
            'ordersn' => $ordersn,
            'order_price' => $orders['order_price'],
            'remarks' => '',
            'company_id' => $orders['company_id'],
            'operator_id' => $orders['operator_id'],
            'type' => $type,  //1：为进货订单、2为退货订单
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $admin_data['organization_id'],
        ];
        DB::beginTransaction();
        try {
            $id = RetailPurchaseOrder::addOrder($order_data);
            //进货开单对应商品信息处理
            foreach ($orders['goods'] as $key => $val) {
                $goods = RetailGoods::getOne(['id' => $val['id']]);
                $order_goods_data = [
                    'order_id' => $id,
                    'goods_id' => $val['id'],
                    'total' => $val['number'],
                    'price' => $goods->price,
                    'title' => $goods->name,
                    'thumb' => '',
                    'details' => $goods->details,
                ];
                RetailPurchaseOrderGoods::addOrderGoods($order_goods_data);
            }
            $order = RetailPurchaseOrder::getOne(['id' => $id])->first();    //获取订单信息
            $order_goods = $order->RetailPurchaseOrderGoods;    //订单对应的商品
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
                    'status' => '0',
                ];
                RetailStockLog::addStockLog($stock_data);
            }
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员在零售店进货开单的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统进行了' . $tips . '！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '进行了' . $tips . '！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['data' => $tips . '失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => $tips . '成功,请前往开单管理进行审核确认', 'status' => '1']);
    }


    //报损开单的数据处理
    public function loss_goods_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');

        $organization_id = $admin_data['organization_id'];
        $num = RetailLossOrder::where([['retail_id', $organization_id], ['ordersn', 'LIKE', '%' . date("Ymd", time()) . '%']])->count();//查询订单今天的数量
        $num += 1;
        $sort = 100000 + $num;
        $ordersn = 'LS' . date("Ymd", time()) . '_' . $organization_id . '_' . $sort;//订单号

        $type = $request->get('type');          //接收订单类型  1：为进货订单、2为退货订单
        if ($type == 3) {
            $tips = '报损开单';
        } else {
            $tips = '开单操作(操作类型未知)';
        }
        $orders = $request->get('orders');                  //接收订单信息
        //报损开单订单信息整理
        $order_data = [
            'ordersn' => $ordersn,
            'order_price' => $orders['order_price'],
            'remarks' => '',
            'operator_id' => $orders['operator_id'],
            'type' => $type,  //3为报损开单
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $admin_data['organization_id'],
        ];
        DB::beginTransaction();
        try {
            $id = RetailLossOrder::addOrder($order_data);
            //报损开单对应商品信息处理
            foreach ($orders['goods'] as $key => $val) {
                $goods = RetailGoods::getOne(['id' => $val['id']]);
                $order_goods_data = [
                    'order_id' => $id,
                    'goods_id' => $val['id'],
                    'total' => $val['number'],
                    'price' => $goods->price,
                    'title' => $goods->name,
                    'thumb' => '',
                    'details' => $goods->details,
                ];
                RetailLossOrderGoods::addOrderGoods($order_goods_data);
            }
            $order = RetailLossOrder::getOne(['id' => $id])->first();    //获取订单信息
            $order_goods = $order->RetailLossOrderGoods;    //订单对应的商品
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
                    'status' => '0',
                ];
                RetailStockLog::addStockLog($stock_data);
            }
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员在零售店报损开单的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统进行了' . $tips . '！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '进行了' . $tips . '！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();//事件回滚
            return response()->json(['data' => $tips . '失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => $tips . '成功,请前往开单管理进行审核确认', 'status' => '1']);
    }


    //盘点开单的数据处理
    public function check_goods_check(Request $request)
    {
        $admin_data = $request->get('admin_data');          //中间件产生的管理员数据参数
        $route_name = $request->path();                         //获取当前的页面路由
        $fansmanage_id = Organization::getPluck(['id' => $admin_data['organization_id']], 'parent_id');

        $organization_id = $admin_data['organization_id'];
        $num = RetailCheckOrder::where([['retail_id', $organization_id], ['ordersn', 'LIKE', '%' . date("Ymd", time()) . '%']])->count();//查询订单今天的数量
        $num += 1;
        $sort = 100000 + $num;
        $ordersn = 'LS' . date("Ymd", time()) . '_' . $organization_id . '_' . $sort;//订单号


        $type = $request->get('type');          //接收订单类型  1：为进货订单、2为退货订单
        if ($type == 4) {
            $tips = '盘点操作';
        } else {
            $tips = '开单操作(操作类型未知)';
        }
        $orders = $request->get('orders');                  //接收订单信息
        //报损开单订单信息整理
        $order_data = [
            'ordersn' => $ordersn,
            'order_price' => $orders['order_price'],
            'remarks' => '',
            'operator_id' => $orders['operator_id'],
            'type' => $type,  //4为盘点开单
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $admin_data['organization_id'],
        ];
        DB::beginTransaction();
        try {
            $id = RetailCheckOrder::addOrder($order_data);
            //盘点开单对应商品信息处理
            foreach ($orders['goods'] as $key => $val) {
                $goods = RetailGoods::getOne(['id' => $val['id']]);
                $order_goods_data = [
                    'order_id' => $id,
                    'goods_id' => $val['id'],
                    'total' => $val['number'],
                    'price' => $goods->price,
                    'title' => $goods->name,
                    'thumb' => '',
                    'details' => $goods->details,
                ];
                RetailCheckOrderGoods::addOrderGoods($order_goods_data);
            }
            $order = RetailCheckOrder::getOne(['id' => $id])->first();    //获取订单信息
            $order_goods = $order->RetailCheckOrderGoods;    //订单对应的商品
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
                    'status' => '0',
                ];
                RetailStockLog::addStockLog($stock_data);
            }
            //添加操作日志
            if ($admin_data['is_super'] == 1) {//超级管理员在零售店报盘点单的记录
                OperationLog::addOperationLog('1', '1', '1', $route_name, '在零售管理系统进行了' . $tips . '！');//保存操作记录
            } else {//零售店铺本人操作记录
                OperationLog::addOperationLog('10', $admin_data['organization_id'], $admin_data['id'], $route_name, '进行了' . $tips . '！');//保存操作记录
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();//事件回滚
            return response()->json(['data' => $tips . '失败，请检查', 'status' => '0']);
        }
        return response()->json(['data' => $tips . '成功,请前往开单管理进行审核确认', 'status' => '1']);
    }
}

?>