<?php
/**
 * Android接口
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Organization;
use App\Models\RetailCategory;
use App\Models\RetailConfig;
use App\Models\RetailGoods;
use App\Models\RetailGoodsThumb;
use App\Models\RetailOrder;
use App\Models\RetailOrderGoods;
use App\Models\RetailShengpay;
use App\Models\RetailShengpayTerminal;
use App\Models\RetailStockLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class AndroidApiController extends Controller
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
        // 商户号
        $sft_pos_num = $request->sft_pos_num;
        // pos机终端号
        $terminal_num = $request->terminal_num;
        // 根据账号进行查询
        $data = Account::where([['account', $account]])->orWhere([['mobile', $account]])->first();
        if (empty($data)) {
            return response()->json(['msg' => '用户不存在', 'status' => '0', 'data' => '']);
        }
        // 获取加密盐
        $key = config("app.retail_encrypt_key");
        // 加密密码第一重
        $encrypted = md5($password);
        // 加密密码第二重
        $encryptPwd = md5("lingyikeji" . $encrypted . $key);
        if ($encryptPwd != $data['password']) {
            return response()->json(['msg' => '密码不正确', 'status' => '0', 'data' => '']);
        }
        // 查询pos商户号
        $shengpay = RetailShengpay::getOne([['retail_id', $data['organization_id']], ['sft_pos_num', $sft_pos_num]]);
        if (empty($shengpay)) {
            return response()->json(['msg' => 'pos商户号不存在', 'status' => '0', 'data' => '']);
        }
        if ($shengpay->status != '1') {
            return response()->json(['msg' => 'pos商户号没通过审核', 'status' => '0', 'data' => '']);
        }
        // 查询pos机终端号
        $terminal = RetailShengpayTerminal::getOne([['retail_id', $data['organization_id']], ['terminal_num', $terminal_num]]);
        if (empty($terminal)) {
            return response()->json(['msg' => 'pos机终端号不存在', 'status' => '0', 'data' => '']);
        }
        if ($terminal->status != '1') {
            return response()->json(['msg' => 'pos机终端号没通过审核', 'status' => '0', 'data' => '']);
        }
        // 店铺名称
        $organization_name = Organization::getPluck([['id', $data['organization_id']]], 'organization_name');
        // 数据返回
        $data = ['status' => '1', 'msg' => '登陆成功', 'data' => ['account_id' => $data['id'], 'account' => $data['account'], 'organization_id' => $data['organization_id'], 'uuid' => $data['uuid'], 'sft_num' => $shengpay['sft_num'], 'organization_name' => $organization_name]];

        return response()->json($data);
    }

    /**
     * 商品分类接口
     */
    public function goodscategory(Request $request)
    {
        $organization_id = $request->organization_id;//店铺id
        $categorylist = RetailCategory::getList([['retail_id', $organization_id]], '0', 'displayorder', 'asc', ['id', 'name', 'displayorder']);
        if (empty($categorylist->toArray())) {
            return response()->json(['status' => '0', 'msg' => '没有分类', 'data' => '']);
        }
        foreach($categorylist as $key=>$value){
            if(!RetailGoods::checkRowExists([['category_id',$value['id']]])){
              unset($categorylist[$key]);
            };
        }
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
        $where[] = ['retail_id', $organization_id];
        if ($keyword) {
            $where[] = ['name', 'LIKE', '%' . $keyword . '%'];
        }
        if ($scan_code) {
            $where[] = ['barcode', $scan_code];
        }
        $goodslist = RetailGoods::getList($where, '0', 'displayorder', 'asc', ['id', 'name', 'category_id', 'details', 'price', 'stock']);
        if (empty($goodslist->toArray())) {
            return response()->json(['status' => '0', 'msg' => '没有商品', 'data' => '']);
        }
        foreach ($goodslist as $key => $value) {
            $goodslist[$key]['category_name'] = RetailCategory::getPluck([['id', $value['category_id']]], 'name');
            $goodslist[$key]['thumb'] = RetailGoodsThumb::where([['goods_id', $value['id']]])->select('thumb')->get();
        }
        $data = ['status' => '1', 'msg' => '获取商品成功', 'data' => ['goodslist' => $goodslist]];
        return response()->json($data);
    }

    /**
     * 提单提交接口
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


        $goodsdata = json_decode($request->goodsdata, TRUE);//商品数组
        $order_price = 0;
        foreach ($goodsdata as $key => $value) {
            foreach ($value as $k => $v) {
                $order_price += $v['price'] * $v['num'];
            }
        }
        $fansmanage_id = Organization::getPluck([['id', $organization_id]], 'parent_id');
        $num = RetailOrder::where([['retail_id', $organization_id], ['ordersn', 'LIKE', '%' . date("Ymd", time()) . '%']])->count();//查询订单今天的数量
        $num += 1;
        $sort = 100000 + $num;
        $ordersn = 'LS' . date("Ymd", time()) . '_' . $organization_id . '_' . $sort;//订单号
        $orderData = [
            'ordersn' => $ordersn,
            'order_price' => $order_price,
            'remarks' => $remarks,
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $organization_id,
            'user_id' => $user_id,
            'operator_id' => $account_id,
            'status' => '0',
        ];
        DB::beginTransaction();
        try {
            $order_id = RetailOrder::addRetailOrder($orderData);//添加入订单表
            foreach ($goodsdata as $key => $value) {
                foreach ($value as $k => $v) {
                    $onedata = RetailGoods::getOne([['id', $v['id']]]);//查询商品库存数量
                    $thumb = RetailGoodsThumb::getPluck([['goods_id', $v['id']]], 'thumb')->first();//商品图片一张
                    $data = [
                        'order_id' => $order_id,
                        'goods_id' => $v['id'],
                        'title' => $onedata['name'],
                        'thumb' => $thumb,
                        'details' => $onedata['details'],
                        'total' => $v['num'],
                        'price' => $v['price'],
                    ];
                    RetailOrderGoods::addOrderGoods($data);//添加商品快照
                }
            }
            $power = RetailConfig::getPluck([['retail_id', $organization_id], ['cfg_name', 'change_stock_role']], 'cfg_value')->first();//查询是下单减库存/付款减库存
            if ($power != '1') {//说明下单减库存
                $re = $this->reduce_stock($order_id, '1');//减库存
                if ($re != 'ok') {
                    return $re;
                }
            }
            DB::commit();//提交事务
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
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
        $order_id = $request->order_id;//订单id
        $organization_id = $request->organization_id;//店铺
        $power = RetailConfig::getPluck([['retail_id', $organization_id], ['cfg_name', 'change_stock_role']], 'cfg_value')->first();//查询是下单减库存/付款减库存
        DB::beginTransaction();
        try {
            if ($power != '1') {//说明下单减库存 所以要把库存归还
                $re = $this->reduce_stock($order_id, '-1');//加库存
                if ($re != 'ok') {
                    return response()->json(['msg' => '提交订单失败', 'status' => '0', 'data' => '']);
                }
            }
            RetailOrder::editRetailOrder([['id', $order_id]], ['status' => '-1']);
            DB::commit();//提交事务
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
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

        $where[] = ['retail_id', $organization_id];
        if ($status) {
            if($status != '-1'){
                $status = preg_match('/(^[0-9]*$)/',$status,$a)?$a[1]:0;
                $status = (string)$status;
            }
            $where[] = ['status', $status];
        }
        $orderlist = RetailOrder::getList($where, '0', 'id', '', ['id', 'ordersn', 'order_price', 'status', 'created_at']);
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
        $data = [
            'orderlist' => $orderlist,
            'total_num' => $total_num,
            'total_amount' => $total_amount,
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
        $order = RetailOrder::getOne([['id', $order_id], ['retail_id', $organization_id]]);
        if (empty($order)) {
            return response()->json(['status' => '0', 'msg' => '不存在订单', 'data' => '']);
        }
        $order = $order->toArray();
        $user_account = User::getPluck([['id', $order['user_id']]], 'account')->first();//粉丝账号
        $operator_account = Account::getPluck([['id', $order['operator_id']]], 'account')->first();//操作人员账号
        $goodsdata = $order['retail_order_goods'];//订单商品列表
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
            'retail_id' => $order['retail_id'],//店铺ID
            'operator_account' => $operator_account,//操作人账号
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
        $order_id = $request->order_id;//订单id
        $order = RetailOrder::getOne([['id', $order_id]]);
        if ($order['status'] != '0') {
            return response()->json(['msg' => '订单不是代付款，不能操作', 'status' => '0', 'data' => '']);
        }
        $organization_id = $request->organization_id;//店铺
        $paytype = $request->paytype;//支付方式
        $power = RetailConfig::getPluck([['retail_id', $organization_id], ['cfg_name', 'change_stock_role']], 'cfg_value')->first();//查询是下单减库存/付款减库存
        DB::beginTransaction();
        try {
            if ($power == '1') {//说明付款减库存
                $re = $this->reduce_stock($order_id, '1');//减库存
                if ($re != 'ok') {
                    return response()->json(['msg' => '提交订单失败', 'status' => '0', 'data' => '']);
                }
            }
            RetailOrder::editRetailOrder([['id', $order_id]], ['paytype' => $paytype, 'status' => '1']);//修改订单状态
            DB::commit();//提交事务
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['msg' => '现金付款失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '现金付款成功', 'data' => ['order_id' => $order_id, 'price' => $order['order_price']]]);
    }

    /**
     * 其他支付方式接口
     */
    public function other_payment(Request $request)
    {
        $order_id = $request->order_id;//订单id
        $order_status = RetailOrder::getPluck([['id', $order_id]], 'status')->first();
        if ($order_status != '0') {
            return response()->json(['msg' => '订单不是代付款，不能操作', 'status' => '0', 'data' => '']);
        }
        $organization_id = $request->organization_id;//店铺
        $paytype = $request->paytype;//支付方式
        $payment_company = $request->payment_company;//支付公司名字
        $power = RetailConfig::getPluck([['retail_id', $organization_id], ['cfg_name', 'change_stock_role']], 'cfg_value')->first();//查询是下单减库存/付款减库存
        DB::beginTransaction();
        try {
            if ($power == '1') {//说明付款减库存
                $re = $this->reduce_stock($order_id, '1');//减库存
                if ($re != 'ok') {
                    return response()->json(['msg' => '提交订单失败', 'status' => '0', 'data' => '']);
                }
            }
            RetailOrder::editRetailOrder([['id', $order_id]], ['paytype' => $paytype, 'status' => '1', 'payment_company' => $payment_company]);//修改订单状态
            DB::commit();//提交事务
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['msg' => '付款失败', 'status' => '0', 'data' => '']);
        }

        return response()->json(['status' => '1', 'msg' => '现金付款成功', 'data' => ['order_id' => $order_id]]);
    }

    /**
     * 开启/关闭零库存开单接口
     */
    public function allow_zero_stock(Request $request)
    {
        $cfg_value = $request->cfg_value;//开启或关闭值
        $organization_id = $request->organization_id;//店铺

        $re = RetailConfig::getOne([['retail_id', $organization_id], ['cfg_name', 'allow_zero_stock']]);//查看店铺allow_zero_stock值是否存在
        if (!empty($re)) {//如果存在
            if ($cfg_value == $re['cfg_value']) {//如果状态一致
                return response()->json(['msg' => '状态一致，无效操作', 'status' => '0', 'data' => '']);
            }
            RetailConfig::editRetailConfig([['id', $re['id']]], ['cfg_value' => $cfg_value]);//修改状态值
        } else {
            RetailConfig::addRetailConfig(['retail_id' => $organization_id, 'cfg_name' => 'allow_zero_stock', 'cfg_value' => $cfg_value]);//添加配置项
        }
        return response()->json(['status' => '1', 'msg' => '设置成功', 'data' => ['vfg_value' => $cfg_value, 'cfg_name' => 'allow_zero_stock']]);
    }

    /**
     * 下单减库存/付款减库存接口
     */
    public function change_stock_role(Request $request)
    {
        $cfg_value = $request->cfg_value;//开启或关闭值
        $organization_id = $request->organization_id;//店铺

        $re = RetailConfig::getOne([['retail_id', $organization_id], ['cfg_name', 'change_stock_role']]);//查看店铺change_stock_role值是否存在
        if (!empty($re)) {//如果存在
            if ($cfg_value == $re['cfg_value']) {//如果状态一致
                return response()->json(['msg' => '状态一致，无效操作', 'status' => '0', 'data' => '']);
            }
            RetailConfig::editRetailConfig([['id', $re['id']]], ['cfg_value' => $cfg_value]);//修改状态值
        } else {
            RetailConfig::addRetailConfig(['retail_id' => $organization_id, 'cfg_name' => 'change_stock_role', 'cfg_value' => $cfg_value]);//添加配置项
        }
        return response()->json(['status' => '1', 'msg' => '设置成功', 'data' => ['vfg_value' => $cfg_value, 'cfg_name' => 'change_stock_role']]);
    }

    /**
     * 查询店铺设置
     */
    public function stock_cfg(Request $request)
    {
        $organization_id = $request->organization_id;//店铺

        $re = RetailConfig::getList([['retail_id', $organization_id]], 0, 'id');//查看店铺配设置项
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
     * @status 1表示加库存，-1表示加库存
     */
    private function reduce_stock($order_id, $status)
    {
        $data = RetailOrder::getOne([['id', $order_id]]);//订单详情
        $config = RetailConfig::getPluck([['retail_id', $data['retail_id']], ['cfg_name', 'allow_zero_stock']], 'cfg_value')->first();//查询是否可零库存开单
        DB::beginTransaction();
        try {
            if ($status == '1') {
                $goodsdata = RetailOrderGoods::where([['order_id', $order_id]])->get();//订单快照中的商品
                foreach ($goodsdata as $key => $value) {
                    $goods = RetailGoods::getOne([['id', $value['goods_id']]]);//商品详情
                    if ($config != '1') {//如果不允许零库存开单
                        if ($goods['stock'] - $value['total'] < 0) {//库存小于0 打回
                            return response()->json(['msg' => '商品' . $goods['name'] . '库存不足', 'status' => '0', 'data' => '']);
                        }
                    }
                    $stock = $goods['stock'] - $value['total'];
                    RetailGoods::editRetailGoods([['id', $value['goods_id']]], ['stock' => $stock]);//修改商品库存
                    $stock_data = [
                        'fansmanage_id' => $data['fansmanage_id'],
                        'retail_id' => $data['retail_id'],
                        'goods_id' => $value['goods_id'],
                        'amount' => $value['total'],
                        'ordersn' => $data['ordersn'],
                        'operator_id' => $data['operator_id'],
                        'remark' => $data['remarks'],
                        'type' => '6',
                        'status' => '1',
                    ];
                    RetailStockLog::addStockLog($stock_data);//商品操作记录
                }
            } else {
                $goodsdata = RetailOrderGoods::where([['order_id', $order_id]])->get();//订单快照中的商品
                foreach ($goodsdata as $key => $value) {
                    $stock = RetailGoods::getPluck([['id', $value['goods_id']]], 'stock')->first();//商品剩下的库存
                    $stock = $stock + $value['total'];
                    RetailGoods::editRetailGoods([['id', $value['goods_id']]], ['stock' => $stock]);//修改商品库存
                    $stock_data = [
                        'fansmanage_id' => $data['fansmanage_id'],
                        'retail_id' => $data['retail_id'],
                        'goods_id' => $value['goods_id'],
                        'amount' => $value['total'],
                        'ordersn' => $data['ordersn'],
                        'operator_id' => $data['operator_id'],
                        'remark' => $data['remarks'],
                        'type' => '7',
                        'status' => '1',
                    ];
                    RetailStockLog::addStockLog($stock_data);//商品操作记录
                }
            }
            DB::commit();//提交事务
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['msg' => '提交失败', 'status' => '0', 'data' => '']);
        }
        return 'ok';
    }

}

?>