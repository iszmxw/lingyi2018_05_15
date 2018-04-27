<?php
/**
 * Android接口
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountInfo;
use App\Models\Organization;
use App\Models\SimpleCategory;
use App\Models\SimpleConfig;
use App\Models\SimpleGoods;
use App\Models\SimpleGoodsThumb;
use App\Models\SimpleOrder;
use App\Models\SimpleOrderGoods;
use App\Models\SimpleStock;
use App\Models\SimpleStockLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class AndroidSimpleApiController extends Controller
{
    /**
     * 登入检测
     */
    public function login(Request $request)
    {
        // 登入账号
        $account = $request->account;
        // 登入密码
        $password = $request->password;

        // 根据账号进行查询
        $data = Account::where([['account', $account]])->orWhere([['mobile', $account]])->first();
        if (empty($data)) {
            return response()->json(['msg' => '用户不存在', 'status' => '0', 'data' => '']);
        }
        if ($data->status == '0') {
            return response()->json(['msg' => '对不起该账号已经被冻结！', 'status' => '0', 'data' => '']);
        }
        // 获取加密盐
        $key = config("app.simple_encrypt_key");
        // 加密密码第一重
        $encrypted = md5($password);
        // 加密密码第二重
        $encryptPwd = md5("lingyikeji" . $encrypted . $key);
        if ($encryptPwd != $data['password']) {
            return response()->json(['msg' => '密码不正确', 'status' => '0', 'data' => '']);
        }
        // 店铺名称
        $organization_name = Organization::getPluck([['id', $data['organization_id']]], 'organization_name');
        //用户昵称
        $account_realname = AccountInfo::getPluck([['account_id', $data['id']]], 'realname')->first();
        // 数据返回
        $data = ['status' => '1', 'msg' => '登陆成功', 'data' => ['account_id' => $data['id'], 'account' => $data['account'], 'realname' => $account_realname, 'organization_id' => $data['organization_id'], 'uuid' => $data['uuid'], 'organization_name' => $organization_name]];

        return response()->json($data);
    }

    /**
     * 商品分类接口
     */
    public function goodscategory(Request $request)
    {
        // 店铺id
        $organization_id = $request->organization_id;
        $categorylist = SimpleCategory::getList([['simple_id', $organization_id]], '0', 'displayorder', 'asc', ['id', 'name', 'displayorder']);
        if (empty($categorylist->toArray())) {
            return response()->json(['status' => '0', 'msg' => '没有分类', 'data' => '']);
        }
        foreach ($categorylist as $key => $value) {
            if (!SimpleGoods::checkRowExists([['category_id', $value['id']]])) {
                unset($categorylist[$key]);
            };
        }
        $categorylist = array_values($categorylist->toArray());
        return response()->json(['status' => '1', 'msg' => '获取分类成功', 'data' => ['categorylist' => $categorylist]]);
    }

    /**
     * 商品列表接口
     */
    public function goodslist(Request $request)
    {
        $organization_id = $request->organization_id;//店铺id
        $keyword = $request->keyword;//关键字
        $scan_code = $request->scan_code;//条码
        $where = [['simple_id', $organization_id], ['status', '1']];
        if ($keyword) {
            $where[] = ['name', 'LIKE', '%' . $keyword . '%'];
        }
        if ($scan_code) {
            $where[] = ['barcode', $scan_code];
        }
        $goodslist = SimpleGoods::getList($where, '0', 'displayorder', 'asc', ['id', 'name', 'category_id', 'details', 'price', 'stock']);
        if (empty($goodslist->toArray())) {
            return response()->json(['status' => '0', 'msg' => '没有商品', 'data' => '']);
        }
        foreach ($goodslist as $key => $value) {
            $goodslist[$key]['category_name'] = SimpleCategory::getPluck([['id', $value['category_id']]], 'name');
            $goodslist[$key]['thumb'] = SimpleGoodsThumb::where([['goods_id', $value['id']]])->select('thumb')->get();
        }
        $data = ['status' => '1', 'msg' => '获取商品成功', 'data' => ['goodslist' => $goodslist]];
        return response()->json($data);
    }

    /**
     * 订单提交接口
     */
    public function order_check(Request $request)
    {
        // 店铺id
        $organization_id = $request->organization_id;
        // 用户id 散客为0
        $user_id = $request->user_id;
        if (empty($user_id)) {
            $user_id = 0;
        }
        // 操作员id
        $account_id = $request->account_id;

        // 备注
        $remarks = $request->remarks;

        // 商品数组
        $goodsdata = json_decode($request->goodsdata, TRUE);
        $order_price = 0;
        foreach ($goodsdata as $key => $value) {
            foreach ($value as $k => $v) {
                // 查询商品是否下架
                $goods_status = SimpleGoods::getPluck(['id' => $v['id']], 'status');
                if ($goods_status == '0') {
                    return response()->json(['msg' => '对不起就在刚刚部分商品被下架了，请返回首页重新选购！', 'status' => '0', 'data' => '']);
                }
                $order_price += $v['price'] * $v['num'];
            }
        }
        $fansmanage_id = Organization::getPluck([['id', $organization_id]], 'parent_id');
        // 查询订单今天的数量
        $num = SimpleOrder::where([['simple_id', $organization_id], ['ordersn', 'LIKE', '%' . date("Ymd", time()) . '%']])->count();
        $num += 1;
        $sort = 100000 + $num;
        // 订单号
        $ordersn = 'LS' . date("Ymd", time()) . '_' . $organization_id . '_' . $sort;
        // 数据处理
        $orderData = [
            'ordersn' => $ordersn,
            'order_price' => $order_price,
            'remarks' => $remarks,
            'fansmanage_id' => $fansmanage_id,
            'simple_id' => $organization_id,
            'user_id' => $user_id,
            'operator_id' => $account_id,
            'status' => '0',
        ];
        DB::beginTransaction();
        try {
            // 添加入订单表
            $order_id = SimpleOrder::addSimpleOrder($orderData);
            foreach ($goodsdata as $key => $value) {
                foreach ($value as $k => $v) {
                    // 查询商品库存数量
                    $onedata = SimpleGoods::getOne([['id', $v['id']]]);
                    $thumb = SimpleGoodsThumb::getPluck([['goods_id', $v['id']]], 'thumb')->first();//商品图片一张
                    $data = [
                        'order_id' => $order_id,
                        'goods_id' => $v['id'],
                        'title' => $onedata['name'],
                        'thumb' => $thumb,
                        'details' => $onedata['details'],
                        'total' => $v['num'],
                        'price' => $v['price'],
                    ];
                    SimpleOrderGoods::addOrderGoods($data);//添加商品快照
                }
            }
            // 查询是下单减库存/付款减库存
            $power = SimpleConfig::getPluck([['simple_id', $organization_id], ['cfg_name', 'change_stock_role']], 'cfg_value');
            // 说明下单减库存
            if ($power != '1') {
                // 减库存
                $re = $this->reduce_stock($order_id, '1');
                if ($re != 'ok') {
                    return $re;
                }
            }
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '提交订单失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '提交订单成功', 'data' => ['order_id' => $order_id]]);
    }

//account_id=76&organization_id=5&timestamp=1522485361983&token=e71eaa006f6854ca6c86380a7e94e853&goodsdata={"data":[{"id":1,"num":"1","price":"10.00"},{"id":2,"num":"1","price":"12.00"}]}


    /**
     * 取消订单接口
     */
    public function cancel_order(Request $request)
    {
        // 订单id
        $order_id = $request->order_id;
        // 订单详情
        $order = SimpleOrder::getOne([['id', $order_id]]);
        if ($order['status'] != '0') {
            return response()->json(['msg' => '订单状态不是待付款，不能取消', 'status' => '0', 'data' => '']);
        }
        DB::beginTransaction();
        try {
            // 说明该订单的库存还未退回，这里的判断是为了防止用户频繁切换下单减库存，付款减库存设置的检测
            if ($order['stock_status'] == '1') {
                // 归还库存
                $re = $this->reduce_stock($order_id, '-1');
                if ($re != 'ok') {
                    return response()->json(['msg' => '取消订单失败', 'status' => '0', 'data' => '']);
                }
            }
            // 修改订单状态为取消
            SimpleOrder::editSimpleOrder([['id', $order_id]], ['status' => '-1']);
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '取消订单失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '取消订单成功', 'data' => ['order_id' => $order_id]]);
    }

    /**
     * 订单列表接口
     */
    public function order_list(Request $request)
    {
        // 店铺
        $organization_id = $request->organization_id;
        // 订单状态
        $status = $request->status;

        $where[] = ['simple_id', $organization_id];
        if ($status) {
            if ($status != '-1') {
                $status = preg_match('/(^[0-9]*$)/', $status, $a) ? $a[1] : 0;
                $status = (string)$status;
            }
            $where[] = ['status', $status];
        }
        $orderlist = SimpleOrder::getListPaginate($where, '20', 'id', 'DESC', ['id', 'ordersn', 'order_price', 'status', 'created_at']);
        if ($orderlist->toArray()) {
            // 订单数量
            $total_num = count($orderlist);
            $total_amount = 0;
            foreach ($orderlist as $key => $value) {
                // 订单总价格
                $total_amount += $value['order_price'];
            }
        } else {
            return response()->json(['status' => '0', 'msg' => '没有订单', 'data' => '']);
        }
        unset($orderlist->first_page_url);
        $data = [
            'orderlist' => $orderlist,
            'total_num' => $total_num,
            'total_amount' => round($total_amount,2),
        ];
        return response()->json(['status' => '1', 'msg' => '订单列表查询成功', 'data' => $data]);
    }

    /**
     * 订单详情接口
     */
    public function order_detail(Request $request)
    {
        // 店铺
        $organization_id = $request->organization_id;
        // 订单id
        $order_id = $request->order_id;
        // 订单详情
        $order = SimpleOrder::getOne([['id', $order_id], ['simple_id', $organization_id]]);
        if (empty($order)) {
            return response()->json(['status' => '0', 'msg' => '不存在订单', 'data' => '']);
        }
        $order = $order->toArray();
        $user_account = User::getPluck([['id', $order['user_id']]], 'account');//粉丝账号
        $operator_account = Account::getPluck([['id', $order['operator_id']]], 'account');//操作人员账号
        //用户昵称
        $account_realname = AccountInfo::getPluck([['account_id', $order['operator_id']]], 'realname')->first();
        $goodsdata = $order['simple_order_goods'];//订单商品列表
        foreach ($goodsdata as $key => $value) {
            $ordergoods[$key]['goods_id'] = $value['goods_id']; //商品id
            $ordergoods[$key]['title'] = $value['title']; //商品名字
            $ordergoods[$key]['thumb'] = $value['thumb']; //商品图片
            $ordergoods[$key]['details'] = $value['details'];//商品描述
            $ordergoods[$key]['total'] = $value['total']; //商品数量
            $ordergoods[$key]['price'] = $value['price']; //商品价格
        }
        //防止值为null
        if (empty($order['remarks'])) {
            $order['remarks'] = '';
        }
        if (empty($order['user_account'])) {
            $order['user_account'] = '';
        }
        if (empty($order['payment_company'])) {
            $order['payment_company'] = '';
        }
        if (empty($order['paytype'])) {
            $order['paytype'] = '';
        }
        $orderdata = [
            'id' => $order['id'], //订单id
            'ordersn' => $order['ordersn'],//订单编号
            'order_price' => $order['order_price'],//订单价格
            'remarks' => $order['remarks'],//订单备注
            'user_id' => $order['user_id'],//粉丝id
            'user_account' => $user_account,//粉丝账号
            'payment_company' => $order['payment_company'],//支付公司
            'status' => $order['status'],//订单状态
            'paytype' => $order['paytype'],//支付方式
            'operator_id' => $order['operator_id'],//操作人id
            'simple_id' => $order['simple_id'],//店铺ID
            'operator_account' => $operator_account,//操作人账号
            'realname' => $account_realname,//操作人员昵称
            'created_at' => $order['created_at'],//添加时间
        ];
        $data = [
            'orderdata' => $orderdata,
            'ordergoods' => $ordergoods,
        ];
        return response()->json(['status' => '1', 'msg' => '订单详情查询成功', 'data' => $data]);
    }


    /**
     * 现金支付接口
     */
    public function cash_payment(Request $request)
    {
        // 订单id
        $order_id = $request->order_id;
        // 订单详情
        $order = SimpleOrder::getOne([['id', $order_id]]);
        if ($order['status'] != '0') {
            return response()->json(['msg' => '订单不是待付款，不能操作', 'status' => '0', 'data' => '']);
        }
        // 支付方式
        $paytype = $request->paytype;
        DB::beginTransaction();
        try {
            // 说明该订单的库存还未减去，这里的判断是为了防止用户频繁切换下单减库存，付款减库存设置的检测
            if ($order['stock_status'] != '1') {
                // 减库存
                $re = $this->reduce_stock($order_id, '1');
                if ($re != 'ok') {
                    return response()->json(['msg' => '提交订单失败', 'status' => '0', 'data' => '']);
                }
            }
            // 修改订单状态
            SimpleOrder::editSimpleOrder([['id', $order_id]], ['paytype' => $paytype, 'status' => '1']);
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '现金付款失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '现金付款成功', 'data' => ['order_id' => $order_id, 'price' => $order['order_price']]]);
    }

    /**
     * 其他支付方式接口
     */
    public function other_payment(Request $request)
    {
        // 订单id
        $order_id = $request->order_id;
        // 订单详情
        $order = SimpleOrder::getOne([['id', $order_id]]);
        if ($order['status'] != '0') {
            return response()->json(['msg' => '订单不是待付款，不能操作', 'status' => '0', 'data' => '']);
        }
        // 支付方式
        $paytype = $request->paytype;
        // 支付公司名字
        $payment_company = $request->payment_company;
        DB::beginTransaction();
        try {
            // 说明该订单的库存还未减去，这里的判断是为了防止用户频繁切换下单减库存，付款减库存设置的检测
            if ($order['stock_status'] != '1') {
                // 减库存
                $re = $this->reduce_stock($order_id, '1');
                if ($re != 'ok') {
                    return response()->json(['msg' => '提交订单失败', 'status' => '0', 'data' => '']);
                }
            }
            // 修改订单状态
            SimpleOrder::editSimpleOrder([['id', $order_id]], ['paytype' => $paytype, 'status' => '1', 'payment_company' => $payment_company]);
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '付款失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '现金付款成功', 'data' => ['order_id' => $order_id]]);
    }


    /**
     * 开启/关闭零库存开单接口
     */
    public function allow_zero_stock(Request $request)
    {
        // 开启或关闭值
        $cfg_value = $request->cfg_value;
        // 店铺
        $organization_id = $request->organization_id;
        // 查看店铺allow_zero_stock值是否存在
        $re = SimpleConfig::getOne([['simple_id', $organization_id], ['cfg_name', 'allow_zero_stock']]);
        // 如果存在
        if (!empty($re)) {
            // 如果状态一致
            if ($cfg_value == $re['cfg_value']) {
                return response()->json(['msg' => '状态一致，无效操作', 'status' => '0', 'data' => '']);
            }
            // 修改状态值
            SimpleConfig::editSimpleConfig([['id', $re['id']]], ['cfg_value' => $cfg_value]);
        } else {
            // 添加配置项
            SimpleConfig::addSimpleConfig(['simple_id' => $organization_id, 'cfg_name' => 'allow_zero_stock', 'cfg_value' => $cfg_value]);
        }
        return response()->json(['status' => '1', 'msg' => '设置成功', 'data' => ['vfg_value' => $cfg_value, 'cfg_name' => 'allow_zero_stock']]);
    }

    /**
     * 下单减库存/付款减库存接口
     */
    public function change_stock_role(Request $request)
    {
        // 开启或关闭值
        $cfg_value = $request->cfg_value;
        // 店铺id
        $organization_id = $request->organization_id;
        // 查看店铺change_stock_role值是否存在
        $re = SimpleConfig::getOne([['simple_id', $organization_id], ['cfg_name', 'change_stock_role']]);
        // 如果存在
        if (!empty($re)) {
            // 如果状态一致
            if ($cfg_value == $re['cfg_value']) {
                return response()->json(['msg' => '状态一致，无效操作', 'status' => '0', 'data' => '']);
            }
            // 修改状态值
            SimpleConfig::editSimpleConfig([['id', $re['id']]], ['cfg_value' => $cfg_value]);
        } else {
            // 添加配置项
            SimpleConfig::addSimpleConfig(['simple_id' => $organization_id, 'cfg_name' => 'change_stock_role', 'cfg_value' => $cfg_value]);
        }
        return response()->json(['status' => '1', 'msg' => '设置成功', 'data' => ['vfg_value' => $cfg_value, 'cfg_name' => 'change_stock_role']]);
    }

    /**
     * 查询店铺设置
     */
    public function stock_cfg(Request $request)
    {
        // 店铺id
        $organization_id = $request->organization_id;
        // 查看店铺配设置项
        $re = SimpleConfig::getList([['simple_id', $organization_id]], 0, 'id');
        if (empty($re->toArray())) {
            return response()->json(['status' => '0', 'msg' => '该店铺没有设置配置项', 'data' => '']);
        }
        foreach ($re as $key => $value) {
            $cfglist[$key] = [
                'id' => $value['id'],
                'cfg_name' => $value['cfg_name'],
                'cfg_value' => $value['cfg_value'],
            ];
        }
        return response()->json(['status' => '1', 'msg' => '查询成功', 'data' => ['cfglist' => $cfglist]]);
    }

    /**
     * 减库存
     * @order_id 订单id
     * @status 1表示减库存，-1表示加库存
     */
    private function reduce_stock($order_id, $status)
    {
        // 订单详情
        $data = SimpleOrder::getOne([['id', $order_id]]);
        // 查询是否可零库存开单
        $config = SimpleConfig::getPluck([['simple_id', $data['simple_id']], ['cfg_name', 'allow_zero_stock']], 'cfg_value');
        DB::beginTransaction();
        try {
            if ($status == '1') {
                // 订单快照中的商品
                $goodsdata = SimpleOrderGoods::where([['order_id', $order_id]])->get();
                foreach ($goodsdata as $key => $value) {
                    // 商品详情
                    $goods = SimpleGoods::getOne([['id', $value['goods_id']]]);
                    // 如果不允许零库存开单
                    if ($config != '1') {
                        // 库存小于0 打回
                        if ($goods['stock'] - $value['total'] < 0) {
                            return response()->json(['msg' => '商品' . $goods['name'] . '库存不足', 'status' => '0', 'data' => '']);
                        }
                    }
                    $stock = $goods['stock'] - $value['total'];
                    SimpleGoods::editSimpleGoods([['id', $value['goods_id']]], ['stock' => $stock]);//修改商品库存
                    $stock_data = [
                        'fansmanage_id' => $data['fansmanage_id'],
                        'simple_id' => $data['simple_id'],
                        'goods_id' => $value['goods_id'],
                        'amount' => $value['total'],
                        'ordersn' => $data['ordersn'],
                        'operator_id' => $data['operator_id'],
                        'remark' => $data['remarks'],
                        // 销售出库类型
                        'type' => '6',
                        'status' => '1',
                    ];
                    // 商品操作记录
                    SimpleStockLog::addStockLog($stock_data);
                    $re = SimpleStock::getOneSimpleStock([['simple_id', $data['simple_id']], ['goods_id', $value['goods_id']]]);
                    $simple_stock = $re['stock'] - $value['total'];
                    SimpleStock::editStock([['id', $re['id']]], ['stock' => $simple_stock]);
                    // 修改stock_status为1表示该订单的库存状态已经减去
                    SimpleOrder::editSimpleOrder(['id' => $order_id], ['stock_status' => '1']);
                }
            } else {
                // 订单快照中的商品
                $goodsdata = SimpleOrderGoods::where([['order_id', $order_id]])->get();
                foreach ($goodsdata as $key => $value) {
                    // 商品剩下的库存
                    $stock = SimpleGoods::getPluck([['id', $value['goods_id']]], 'stock');
                    $stock = $stock + $value['total'];
                    // 修改商品库存
                    SimpleGoods::editSimpleGoods([['id', $value['goods_id']]], ['stock' => $stock]);
                    $stock_data = [
                        'fansmanage_id' => $data['fansmanage_id'],
                        'simple_id' => $data['simple_id'],
                        'goods_id' => $value['goods_id'],
                        'amount' => $value['total'],
                        'ordersn' => $data['ordersn'],
                        'operator_id' => $data['operator_id'],
                        'remark' => $data['remarks'],
                        // 退货入库类型
                        'type' => '7',
                        'status' => '1',
                    ];
                    // 商品操作记录
                    SimpleStockLog::addStockLog($stock_data);
                    $re = SimpleStock::getOneSimpleStock([['simple_id', $data['simple_id']], ['goods_id', $value['goods_id']]]);
                    $simple_stock = $re['stock'] + $value['total'];
                    SimpleStock::editStock([['id', $re['id']]], ['stock' => $simple_stock]);
                    // 修改stock_status为-1表示该订单的库存状态已经退回
                    SimpleOrder::editSimpleOrder(['id' => $order_id], ['stock_status' => '-1']);
                }
            }
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '提交失败', 'status' => '0', 'data' => '']);
        }
        return 'ok';
    }

}

?>