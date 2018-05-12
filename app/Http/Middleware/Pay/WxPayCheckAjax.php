<?php
/**
 * 检测中间件
 */

namespace App\Http\Middleware\Pay;

use App\Models\Account;
use App\Models\Program;
use Closure;
use Session;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Request;

class WxPayCheckAjax
{
    public function handle($request, Closure $next)
    {
        // 获取当前的页面路由
        $route_name = $request->path();
        $res_check = true;
        switch ($route_name) {
            case "pay/wx/pay_bank":
                $res_check = $this->check_pay_bank();
                break;
            case "pay/wx/query_bank":
                $res_check = $this->check_query_bank();
                break;
            case "pay/wx/transfers":
                $res_check = $this->check_transfers();
                break;
            case "pay/wx/gettransferinfo":
                $res_check = $this->check_gettransferinfo();
                break;
            case "pay/wx/sendredpack":
                $res_check = $this->check_sendredpack();
                break;
            case "pay/wx/sendgroupredpack":
                $res_check = $this->check_sendgroupredpack();
                break;
            case "pay/wx/gethbinfo":
                $res_check = $this->check_gethbinfo();
                break;
            case "pay/wx/nativeOrder":
                $res_check = $this->check_nativeOrder();
                break;
            case "pay/wx/jsApiOrder":
                $res_check = $this->check_jsApiOrder();
                break;
            case "pay/wx/unifiedOrder":
                $res_check = $this->check_unifiedOrder();
                break;
            case "pay/wx/closeOrder":
                $res_check = $this->check_closeOrder();
                break;
            case "pay/wx/microOrder":
                $res_check = $this->check_microOrder();
                break;
            case "pay/wx/orderQuery":
                $res_check = $this->check_orderQuery();
                break;
            case "pay/wx/refund":
                $res_check = $this->check_refund();
                break;
            case "pay/wx/refundQuery":
                $res_check = $this->check_refundQuery();
                break;
            case "pay/wx/downloadBill":
                $res_check = $this->check_downloadBill();
                break;
        }
        var_dump($res_check);
        exit;

        // 判断参数是否传输错误
        if ($res_check == false) {
            // 接口返回失败
            $res["return_code"] = 0;
            $res["return_msg"] = "参数错误";
            return json_encode($res, JSON_UNESCAPED_UNICODE);
        }
        // 条件处理完就进入控制器中
        return $next($request);
    }


    public function check_pay_bank()
    {
        //        // 商户订单号
//        $data["order_num"] = md5(time());
//        // 用户openid
//        $data["bank_card_num"] = "6214837873289338";
//        // 收款用户姓名
//        $data["bank_card_name"] = "郑旭宏";
//        $data["bank_code"] = "1001";
//        // 金额
//        $data["order_money"] = 0.01;
//        // 企业付款描述信息
//        $data["remark"] = "还钱";
//        // ip 地址
//        $data["ip_address"] = "120.78.140.10";
//
//        echo $data["order_num"];
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//
//        echo $this->pay_bank($data);

    }

    public function check_query_bank()
    {
        // 企业发放到银行卡查询
//        $data["order_num"] = "152e4b79e81e33edc4b843c077c82d24";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        echo $this->query_bank($data);

    }

    public function check_transfers()
    {

    }

    public function check_gettransferinfo()
    {

    }


    public function check_sendredpack()
    {
        //发放普通红包
//        // 活动名称
//        $data["activity_name"] = "zzzz";
//        // 发放ip地址
//        $data["ip_address"] = "120.78.140.10";
//        // 订单号
//        $data["order_num"] = substr(md5(time()), 0, 28);
////        $data["order_num"] = "6530cb44b093892f9e14d442472b";
//        // 发送的openid
//        $data["openid"] = "oK2HF1Sy1qdRQyqg69pPN5-rirrg";
//        // 备注
//        $data["remark"] = "ganjinqiang";
//        // 金额
//        $data["order_money"] = "1";
//        // 祝福语
//        $data["wishing"] = "gongxi";
//
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->sendredpack($data);
//        echo $res;
    }

    public function check_sendgroupredpack()
    {

    }

    public function check_gethbinfo()
    {
        // 查询红包
//        $data["order_num"] = "33d5540a1185917e72ff8bbb6d9d";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        echo $this->gethbinfo($data);

    }

    public function check_nativeOrder()
    {
//        // native 下单
//        $data["desc"] = "商品-xho-test";
//        $data["order_num"] = md5(time());
//        $data["order_money"] = 0.01;
//        $data["ip_address"] = "120.78.140.10";
//        $data["trade_type"] = "NATIVE";
//        $data["openid"] = "oK2HF1Sy1qdRQyqg69pPN5-rirrg";
//        $data["product_id"] = md5(time());
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->nativeOrder($data);
//        echo "<img src='$res'>";

    }

    public function check_jsApiOrder()
    {
//        // jsapi 下单
//        $wechat = new WechatController();
//        $wechat->getSignPackage();
//        $signPackage = request()->get("zerone_jssdk_info");
//        $data["desc"] = "商品-xho-test";
//        $data["order_num"] = md5(time());
//        $data["order_money"] = 0.1;
//        $data["ip_address"] = "120.78.140.10";
//        $data["trade_type"] = "JSAPI";
//        $data["openid"] = "oK2HF1Sy1qdRQyqg69pPN5-rirrg";
//        $data["product_id"] = md5(time());
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->jsApiOrder($data);
//        $res = json_decode($res,true);
//        return view("Fansmanage/Test/test", ["signPackage" => $signPackage, "wxpay" => $res["data"]]);

    }

    public function check_unifiedOrder()
    {

    }

    public function check_closeOrder()
    {
        // 关闭订单
//        $data["order_num"] = 1503376371;
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->closeOrder($data);
//        echo $res;
    }

    public function check_microOrder()
    {
        // 刷卡支付
//        $data["desc"] = "商品-xho-test";
//        $data["order_num"] = md5(time());
//        $data["order_money"] = 0.1;
//        $data["ip_address"] = "120.78.140.10";
//        $data["auth_code"] = "135463544838356441";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        echo $this->microOrder($data);

    }

    public function check_orderQuery()
    {
        // 订单查询
//        $data["order_num_type"] = 'out_trade_no';
//        $data["order_num"] = '150337637120180509095053';
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->orderQuery($data);
//        echo $res;

    }

    public function check_refund()
    {
//        // 退款接口
//        $data["order_num_type"] = 'out_trade_no';
//        $data["order_num"] = '150337637120180509095053';
//        // 商户退款单号
//        $data["refund_num"] = md5(time());
//        // 订单金额
//        $data["order_money"] = 0.1;
//        // 退款金额
//        $data["refund_money"] = 0.01;
//        // 退款原因
//        $data["refund_reason"] = "不想买了";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->refund($data);
//        echo $res;

    }

    public function check_refundQuery()
    {
//        // 退款查询接口
//        $reqData["order_num_type"] = "out_refund_no";
//        $reqData["order_num"] = "1003022622018050853721122351525761650";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->refundQuery($reqData);
//        echo $res;

    }

    /**
     * 检测下载对账单数据
     * @return bool
     */
    public function check_downloadBill()
    {
        $post_data = request()->post();


//        // 必填数组
//        $param = ["bill_date", "bill_type"];
//        foreach ($param as $val) {
//            if (!array_key_exists($val, $post_data)) {
//                return false;
//            }
//        }
//        return true;


        $rule = [
            'bill_date' => 'required',
            'bill_type' => 'required',
        ];

        $message = [
            "bill_date.required" => "bill_date 必须填写",
            "bill_type.required" => "bill_type 必须填写",
            "appsecret.required" => "appsecret 必须填写",
            "mchid.required" => "mchid 必须填写",
            "api_key.required" => "api_key 必须填写",
        ];

        $validate = \Validator::make($post_data, $rule, $message);

        if (!$validate->passes()) {
            $error_msg = $validate->errors();
            $res = json_encode($error_msg, JSON_UNESCAPED_UNICODE);
            $res = json_decode($res, true);
            var_dump($res);
            exit;
            foreach ($res as $val) {
                $error_msg = $val[0];
            }
            return response()->json(['data' => $error_msg, 'status' => '0']);
        }

    }

}