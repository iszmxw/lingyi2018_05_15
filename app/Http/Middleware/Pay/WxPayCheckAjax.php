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
            // 企业支付到银行卡接口
            case "pay/wx/pay_bank":
                $res_check = $this->check_pay_bank();
                break;
            // 查询企业支付到银行卡接口
            case "pay/wx/query_bank":
                $res_check = $this->check_query_bank();
                break;
            //企业支付到零钱接口
            case "pay/wx/transfers":
                $res_check = $this->check_transfers();
                break;
            // 查询企业支付到零钱接口
            case "pay/wx/gettransferinfo":
                $res_check = $this->check_gettransferinfo();
                break;
            // 普通红包
            case "pay/wx/sendredpack":
                $res_check = $this->check_sendredpack();
                break;
            // 发送裂变红包
            case "pay/wx/sendgroupredpack":
                $res_check = $this->check_sendgroupredpack();
                break;
            // 红包查询记录
            case "pay/wx/gethbinfo":
                $res_check = $this->check_gethbinfo();
                break;
            // 订单接口
            case "pay/wx/nativeOrder":
            case "pay/wx/jsApiOrder":
                $res_check = $this->check_order();
                break;
            // 关闭订单接口
            case "pay/wx/closeOrder":
                $res_check = $this->check_closeOrder();
                break;
            // 刷卡支付
            case "pay/wx/microOrder":
                $res_check = $this->check_microOrder();
                break;
            // 订单查询接口
            case "pay/wx/orderQuery":
                $res_check = $this->check_orderQuery();
                break;
            // 退款接口
            case "pay/wx/refund":
                $res_check = $this->check_refund();
                break;
            // 退款订单查询
            case "pay/wx/refundQuery":
                $res_check = $this->check_refundQuery();
                break;
            // 下载对账单
            case "pay/wx/downloadBill":
                $res_check = $this->check_downloadBill();
                break;
        }

        // 判断参数是否传输错误
        if ($res_check !== true) {
            // 接口返回失败
            echo $res_check;
            exit;
        }
        // 条件处理完就进入控制器中
        return $next($request);
    }


    /**
     * 检测企业支付到银行卡接口 数据
     * @return bool|string
     */
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


        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'order_num' => 'required',
            'bank_card_num' => 'required',
            'bank_code' => 'required',
            'order_money' => 'required',
            'remark' => 'required',
            'ip_address' => 'required',
        ];
        // 提示消息
        $message = [
            "activity_name.required" => "activity_name 必须填写",
            "order_num.required" => "order_num 必须填写",
            "order_money.required" => "order_money 必须填写",
            "ip_address.required" => "ip_address 必须填写",
            "openid.required" => "openid 必须填写",
            "remark.required" => "remark 必须填写",
            "wishing.required" => "wishing 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);


    }

    /**
     * 检测查询企业支付到银行卡接口 数据
     * @return bool|string
     */
    public function check_query_bank()
    {
        // 企业发放到银行卡查询
//        $data["order_num"] = "152e4b79e81e33edc4b843c077c82d24";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        echo $this->query_bank($data);

        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'order_num' => 'required',
        ];
        // 提示消息
        $message = [
            "order_num.required" => "order_num 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);

    }

    /**
     * 检测企业支付到零钱接口 数据
     * @return bool|string
     */
    public function check_transfers()
    {
        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'activity_name' => 'required',
            'order_num' => 'required',
            'order_money' => 'required',
            'ip_address' => 'required',
            'openid' => 'required',
            'remark' => 'required',
            'wishing' => 'required',
        ];
        // 提示消息
        $message = [
            "activity_name.required" => "activity_name 必须填写",
            "order_num.required" => "order_num 必须填写",
            "order_money.required" => "order_money 必须填写",
            "ip_address.required" => "ip_address 必须填写",
            "openid.required" => "openid 必须填写",
            "remark.required" => "remark 必须填写",
            "wishing.required" => "wishing 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 检测查询企业支付到零钱接口 数据
     * @return bool|string
     */
    public function check_gettransferinfo()
    {
        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'activity_name' => 'required',
            'order_num' => 'required',
            'order_money' => 'required',
            'ip_address' => 'required',
            'openid' => 'required',
            'remark' => 'required',
            'wishing' => 'required',
        ];
        // 提示消息
        $message = [
            "activity_name.required" => "activity_name 必须填写",
            "order_num.required" => "order_num 必须填写",
            "order_money.required" => "order_money 必须填写",
            "ip_address.required" => "ip_address 必须填写",
            "openid.required" => "openid 必须填写",
            "remark.required" => "remark 必须填写",
            "wishing.required" => "wishing 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }


    /**
     * 检测普通红包 数据
     * @return bool|string
     */
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


        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'activity_name' => 'required',
            'order_num' => 'required',
            'order_money' => 'required',
            'ip_address' => 'required',
            'openid' => 'required',
            'remark' => 'required',
            'wishing' => 'required',
        ];
        // 提示消息
        $message = [
            "activity_name.required" => "activity_name 必须填写",
            "order_num.required" => "order_num 必须填写",
            "order_money.required" => "order_money 必须填写",
            "ip_address.required" => "ip_address 必须填写",
            "openid.required" => "openid 必须填写",
            "remark.required" => "remark 必须填写",
            "wishing.required" => "wishing 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 检测发送裂变红包 数据
     * @return bool|string
     */
    public function check_sendgroupredpack()
    {
        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'activity_name' => 'required',
            'order_num' => 'required',
            'order_money' => 'required',
            'ip_address' => 'required',
            'openid' => 'required',
            'remark' => 'required',
            'wishing' => 'required',
        ];
        // 提示消息
        $message = [
            "activity_name.required" => "activity_name 必须填写",
            "order_num.required" => "order_num 必须填写",
            "order_money.required" => "order_money 必须填写",
            "ip_address.required" => "ip_address 必须填写",
            "openid.required" => "openid 必须填写",
            "remark.required" => "remark 必须填写",
            "wishing.required" => "wishing 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 检测红包查询记录 数据
     * @return bool|string
     */
    public function check_gethbinfo()
    {
        // 查询红包
//        $data["order_num"] = "33d5540a1185917e72ff8bbb6d9d";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        echo $this->gethbinfo($data);

        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'order_num' => 'required',
        ];
        // 提示消息
        $message = [
            "order_num.required" => "order_num 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 检测下单接口 数据
     * @return bool|string
     */
    public function check_order()
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


        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'desc' => 'required',
            'order_num' => 'required',
            'order_money' => 'required',
            'ip_address' => 'required',
            'trade_type' => 'required',
            'openid' => 'required',
            'product_id' => 'required',
        ];
        // 提示消息
        $message = [
            "desc.required" => "desc 必须填写",
            "order_num.required" => "order_num 必须填写",
            "order_money.required" => "order_money 必须填写",
            "ip_address.required" => "ip_address 必须填写",
            "trade_type.required" => "trade_type 必须填写",
            "openid.required" => "openid 必须填写",
            "product_id.required" => "product_id 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 检测关闭订单接口 数据
     * @return bool|string
     */
    public function check_closeOrder()
    {
        // 关闭订单
//        $data["order_num"] = 1503376371;
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->closeOrder($data);
//        echo $res;

        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'order_num' => 'required',
        ];
        // 提示消息
        $message = [
            "order_num.required" => "order_num 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 检测刷卡支付 数据
     * @return bool|string
     */
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


        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'desc' => 'required',
            'order_num' => 'required',
            'order_money' => 'required',
            'ip_address' => 'required',
            'auth_code' => 'required',
        ];
        // 提示消息
        $message = [
            "desc.required" => "desc 必须填写",
            "order_num.required" => "order_num 必须填写",
            "order_money.required" => "order_money 必须填写",
            "ip_address.required" => "ip_address 必须填写",
            "auth_code.required" => "auth_code 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 检测订单查询接口 数据
     * @return bool|string
     */
    public function check_orderQuery()
    {
        // 订单查询
//        $data["order_num_type"] = 'out_trade_no';
//        $data["order_num"] = '150337637120180509095053';
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->orderQuery($data);
//        echo $res;

        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'order_num_type' => 'required',
            'order_num' => 'required',
        ];
        // 提示消息
        $message = [
            "order_num_type.required" => "order_num_type 必须填写",
            "order_num.required" => "order_num 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 检测退款接口 数据
     * @return bool|string
     */
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


        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'order_num_type' => 'required',
            'order_num' => 'required',
            'refund_num' => 'required',
            'order_money' => 'required',
            'refund_money' => 'required',
            'refund_reason' => 'required',
        ];
        // 提示消息
        $message = [
            "order_num_type.required" => "order_num_type 必须填写",
            "order_num.required" => "order_num 必须填写",
            "refund_num.required" => "refund_num 必须填写",
            "order_money.required" => "order_money 必须填写",
            "refund_money.required" => "refund_money 必须填写",
            "refund_reason.required" => "refund_reason 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 检测退款订单查询 数据
     * @return bool|string
     */
    public function check_refundQuery()
    {
//        // 退款查询接口
//        $reqData["order_num_type"] = "out_refund_no";
//        $reqData["order_num"] = "1003022622018050853721122351525761650";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->refundQuery($reqData);
//        echo $res;

        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'order_num_type' => 'required',
            'order_num' => 'required',
        ];
        // 提示消息
        $message = [
            "order_num_type.required" => "order_num_type 必须填写",
            "order_num.required" => "order_num 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }


    /**
     * 检测下载对账单数据
     * @return bool|string
     */
    public function check_downloadBill()
    {
        // 获取数据
        $post_data = request()->post();
        // 规则
        $rule = [
            'bill_date' => 'required',
            'bill_type' => 'required',
        ];
        // 提示消息
        $message = [
            "bill_date.required" => "bill_date 必须填写",
            "bill_type.required" => "bill_type 必须填写",
        ];
        // 返回验证结果
        return $this->validate($post_data, $rule, $message);
    }

    /**
     * 验证器
     * @param $data
     * @param $rule
     * @param array $message
     * @return bool|string
     */
    private function validate($data, $rule, $message = [])
    {
        $validate = \Validator::make($data, $rule, $message);
        if (!$validate->passes()) {
            $error_msg = $validate->errors();
            $error_arr = json_decode(json_encode($error_msg, JSON_UNESCAPED_UNICODE), true);
            foreach ($error_arr as $val) {
                $error_msg = $val[0];
            }
            $res["return_code"] = 0;
            $res["return_msg"] = $error_msg;
            return json_encode($res, JSON_UNESCAPED_UNICODE);
        }
        return true;
    }

}