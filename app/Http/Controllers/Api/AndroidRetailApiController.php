<?php
/**
 * Android接口
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountInfo;
use App\Models\Organization;
use App\Models\RetailCategory;
use App\Models\RetailConfig;
use App\Models\RetailGoods;
use App\Models\RetailGoodsThumb;
use App\Models\RetailOrder;
use App\Models\RetailOrderGoods;
use App\Models\RetailShengpay;
use App\Models\RetailShengpayTerminal;
use App\Models\RetailStock;
use App\Models\RetailStockLog;
use App\Models\User;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class AndroidRetailApiController extends Controller
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
        //检查该账号是否被冻结
        if ($data->status == '0') {
            return response()->json(['msg' => '对不起该账号已经被冻结！', 'status' => '0', 'data' => '']);
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
//        // 查询pos商户号
//        $shengpay = RetailShengpay::getOne([['retail_id', $data['organization_id']], ['sft_pos_num', $sft_pos_num]]);
//        if (empty($shengpay)) {
//            return response()->json(['msg' => 'pos商户号不存在', 'status' => '0', 'data' => '']);
//        }
//        if ($shengpay->status != '1') {
//            return response()->json(['msg' => 'pos商户号没通过审核', 'status' => '0', 'data' => '']);
//        }
//        // 查询pos机终端号
//        $terminal = RetailShengpayTerminal::getOne([['retail_id', $data['organization_id']], ['terminal_num', $terminal_num]]);
//        if (empty($terminal)) {
//            return response()->json(['msg' => 'pos机终端号不存在', 'status' => '0', 'data' => '']);
//        }
//        if ($terminal->status != '1') {
//            return response()->json(['msg' => 'pos机终端号没通过审核', 'status' => '0', 'data' => '']);
//        }
        // 店铺名称
        $organization_name = Organization::getPluck([['id', $data['organization_id']]], 'organization_name');
        //用户昵称
        $account_realname = AccountInfo::getPluck([['account_id', $data['id']]], 'realname');
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
        $categorylist = RetailCategory::getList([['retail_id', $organization_id]], '0', 'displayorder', 'asc', ['id', 'name', 'displayorder']);
        if (empty($categorylist->toArray())) {
            return response()->json(['status' => '0', 'msg' => '没有分类', 'data' => '']);
        }
        foreach ($categorylist as $key => $value) {
            if (!RetailGoods::checkRowExists([['category_id', $value['id']]], 'id')) {
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
        // 店铺id
        $organization_id = $request->organization_id;
        // 关键字
        $keyword = $request->keyword;
        // 条码
        $scan_code = $request->scan_code;
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
        // 根据账号进行查询
        $data = Account::where([['id', $account_id]])->first();
        if ($data->status == '0') {
            return response()->json(['msg' => '对不起该账号，就在刚刚被冻结啦，请联系管理员！', 'status' => '0', 'data' => '']);
        }
        // 备注
        $remarks = $request->remarks;
        // 折扣比率
        $discount = $request->discount;
        if (empty($discount)) {
            // 原价
            $discount = 10;
        }
        // 商品数组
        $goodsdata = json_decode($request->goodsdata, TRUE);
        $order_price = 0;
        foreach ($goodsdata as $key => $value) {
            foreach ($value as $k => $v) {
                // 查询商品是否下架
                $goods_status = RetailGoods::getPluck(['id' => $v['id']], 'status')->first();
                if ($goods_status == '0') {
                    return response()->json(['msg' => '对不起就在刚刚部分商品被下架了，请返回首页重新选购！', 'status' => '0', 'data' => '']);
                }
                $order_price += $v['price'] * $v['num'];
            }
        }
        $fansmanage_id = Organization::getPluck([['id', $organization_id]], 'parent_id');
        // 查询订单今天的数量
        $num = RetailOrder::where([['retail_id', $organization_id], ['ordersn', 'LIKE', '%' . date("Ymd", time()) . '%']])->count();
        $num += 1;
        $sort = 100000 + $num;
        // 订单号
        $ordersn = 'LS' . date("Ymd", time()) . '_' . $organization_id . '_' . $sort;
        // 折扣价
        $discount_price = round($order_price * $discount / 10, 2);
        $orderData = [
            'ordersn' => $ordersn,
            'order_price' => $order_price,
            'remarks' => $remarks,
            'fansmanage_id' => $fansmanage_id,
            'retail_id' => $organization_id,
            'user_id' => $user_id,
            'operator_id' => $account_id,
            'discount_price' => $discount_price,
            'discount' => $discount,
            'status' => '0',
        ];
        DB::beginTransaction();
        try {
            // 添加入订单表
            $order_id = RetailOrder::addRetailOrder($orderData);
            foreach ($goodsdata as $key => $value) {
                foreach ($value as $k => $v) {
                    // 查询商品库存数量
                    $onedata = RetailGoods::getOne([['id', $v['id']]]);
                    // 商品图片一张
                    $thumb = RetailGoodsThumb::getPluck([['goods_id', $v['id']]], 'thumb')->first();
                    $data = [
                        'order_id' => $order_id,
                        'goods_id' => $v['id'],
                        'title' => $onedata['name'],
                        'thumb' => $thumb,
                        'details' => $onedata['details'],
                        'total' => $v['num'],
                        'price' => $v['price'],
                    ];
                    // 添加商品快照
                    RetailOrderGoods::addOrderGoods($data);
                }
            }
            // 查询是下单减库存/付款减库存
            $power = RetailConfig::getPluck([['retail_id', $organization_id], ['cfg_name', 'change_stock_role']], 'cfg_value');
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
        $order = RetailOrder::getOne([['id', $order_id]]);
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
            RetailOrder::editRetailOrder([['id', $order_id]], ['status' => '-1']);
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

        $where[] = ['retail_id', $organization_id];
        if ($status) {
            if ($status != '-1') {
                $status = preg_match('/(^[0-9]*$)/', $status, $a) ? $a[1] : 0;
                $status = (string)$status;
            }
            $where[] = ['status', $status];
        }
        $orderlist = RetailOrder::getRetailList($where, '20', 'id', 'DESC', ['id', 'ordersn', 'order_price', 'status', 'discount_price', 'payment_price', 'discount', 'created_at']);
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
            'total_amount' => round($total_amount, 2),
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
        // 粉丝账号
        $user_account = User::getPluck([['id', $order['user_id']]], 'account');
        // 操作人员账号
        $operator_account = Account::getPluck([['id', $order['operator_id']]], 'account');
        // 用户昵称
        $account_realname = AccountInfo::getPluck([['account_id', $order['operator_id']]], 'realname');
        // 订单商品列表
        $goodsdata = $order['retail_order_goods'];
        // 定义为数组
        $ordergoods = [];
        foreach ($goodsdata as $key => $value) {
            // 商品id
            $ordergoods[$key]['goods_id'] = $value['goods_id'];
            // 商品名字
            $ordergoods[$key]['title'] = $value['title'];
            // 商品图片
            $ordergoods[$key]['thumb'] = $value['thumb'];
            // 商品描述
            $ordergoods[$key]['details'] = $value['details'];
            // 商品数量
            $ordergoods[$key]['total'] = $value['total'];
            // 商品价格
            $ordergoods[$key]['price'] = $value['price'];
        }
        $orderdata = [
            // 订单id
            'id' => $order['id'],
            // 订单编号
            'ordersn' => $order['ordersn'],
            // 订单价格
            'order_price' => $order['order_price'],
            // 订单备注
            'remarks' => $order['remarks'],
            // 粉丝id
            'user_id' => $order['user_id'],
            // 粉丝账号
            'user_account' => $user_account,
            // 支付公司
            'payment_company' => $order['payment_company'],
            // 订单状态
            'status' => $order['status'],
            // 支付方式
            'paytype' => $order['paytype'],
            // 操作人id
            'operator_id' => $order['operator_id'],
            // 店铺ID
            'retail_id' => $order['retail_id'],
            // 操作人账号
            'operator_account' => $operator_account,
            // 操作人员昵称
            'realname' => $account_realname,
            // 折扣价
            'discount_price' => $order['discount_price'],
            // 实收价格
            'payment_price' => $order['payment_price'],
            // 折扣比率
            'discount' => $order['discount'],
            // 添加时间
            'created_at' => $order['created_at'],
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
        $order = RetailOrder::getOne([['id', $order_id]]);
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
            // 实收
            $payment_price = round($order['order_price'] * $order['discount'] / 10, 2);

            RetailOrder::editRetailOrder([['id', $order_id]], ['paytype' => $paytype, 'payment_price' => $payment_price, 'status' => '1']);//修改订单状态
            DB::commit();//提交事务
        } catch (\Exception $e) {
            DB::rollBack();//事件回滚
            return response()->json(['msg' => '现金付款失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '现金付款成功', 'data' => ['order_id' => $order_id, 'payment_price' => $payment_price, 'price' => $order['order_price']]]);
    }

    /**
     * 其他支付方式接口
     */
    public function other_payment(Request $request)
    {
        // 订单id
        $order_id = $request->order_id;
        $order = RetailOrder::getOne([['id', $order_id]]);
        if ($order['order_status'] != '0') {
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
            $payment_price = round($order['order_price'] * $order['discount'] / 10, 2);
            // 修改订单状态
            RetailOrder::editRetailOrder([['id', $order_id]], ['paytype' => $paytype, 'status' => '1', 'payment_price' => $payment_price, 'payment_company' => $payment_company]);
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '付款失败', 'status' => '0', 'data' => '']);
        }
        return response()->json(['status' => '1', 'msg' => '现金付款成功', 'data' => ['order_id' => $order_id, 'payment_price' => $payment_price]]);
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
     * 签入二维码
     */
    public function code(Request $request)
    {

        $value = 'www.baidu.com';                  //二维码内容

        $errorCorrectionLevel = 'L';    //容错级别
        $matrixPointSize = 5;           //生成图片大小

        //生成二维码图片
        $filename = 'qrcode/'.microtime().'.png';
        QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);

        $QR = $filename;                //已经生成的原始二维码图片文件


        $QR = imagecreatefromstring(file_get_contents($QR));

        //输出图片
        imagepng($QR, 'qrcode.png');
        imagedestroy($QR);
        return '<img src="qrcode.png" alt="使用微信扫描支付">';
    }


    /**
     * 减库存
     * @order_id 订单id
     * @status 1表示减库存，-1表示加库存
     */
    private function reduce_stock($order_id, $status)
    {
        // 订单详情
        $data = RetailOrder::getOne([['id', $order_id]]);
        // 查询是否可零库存开单
        $config = RetailConfig::getPluck([['retail_id', $data['retail_id']], ['cfg_name', 'allow_zero_stock']], 'cfg_value');
        DB::beginTransaction();
        try {
            if ($status == '1') {
                // 订单快照中的商品
                $goodsdata = RetailOrderGoods::where([['order_id', $order_id]])->get();
                foreach ($goodsdata as $key => $value) {
                    // 商品详情
                    $goods = RetailGoods::getOne([['id', $value['goods_id']]]);
                    // 如果不允许零库存开单
                    if ($config != '1') {
                        // 库存小于0 打回
                        if ($goods['stock'] - $value['total'] < 0) {
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
                        // 销售出库类型
                        'type' => '6',
                        'status' => '1',
                    ];
                    // 商品操作记录
                    RetailStockLog::addStockLog($stock_data);
                    $re = RetailStock::getOneRetailStock([['retail_id', $data['retail_id']], ['goods_id', $value['goods_id']]]);
                    $simple_stock = $re['stock'] - $value['total'];
                    RetailStock::editStock([['id', $re['id']]], ['stock' => $simple_stock]);
                    // 修改stock_status为1表示该订单的库存状态已经减去
                    RetailOrder::editRetailOrder(['id' => $order_id], ['stock_status' => '1']);
                }
            } else {
                // 订单快照中的商品
                $goodsdata = RetailOrderGoods::where([['order_id', $order_id]])->get();
                foreach ($goodsdata as $key => $value) {
                    // 商品剩下的库存
                    $stock = RetailGoods::getPluck([['id', $value['goods_id']]], 'stock');
                    $stock = $stock + $value['total'];
                    // 修改商品库存
                    RetailGoods::editRetailGoods([['id', $value['goods_id']]], ['stock' => $stock]);
                    $stock_data = [
                        'fansmanage_id' => $data['fansmanage_id'],
                        'retail_id' => $data['retail_id'],
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
                    RetailStockLog::addStockLog($stock_data);
                    $re = RetailStock::getOneRetailStock([['retail_id', $data['retail_id']], ['goods_id', $value['goods_id']]]);
                    $simple_stock = $re['stock'] + $value['total'];
                    RetailStock::editStock([['id', $re['id']]], ['stock' => $simple_stock]);
                    // 修改stock_status为-1表示该订单的库存状态已经退回
                    RetailOrder::editRetailOrder(['id' => $order_id], ['stock_status' => '-1']);
                }
            }
            // 提交事务
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            // 事件回滚
            DB::rollBack();
            return response()->json(['msg' => '提交失败', 'status' => '0', 'data' => '']);
        }
        return 'ok';
    }

}

?>