<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8
 * Time: 17:09
 */

namespace App\Http\Controllers\Pay;


use App\Http\Controllers\Controller;
use WXPay\WXPay;

class WxController extends Controller
{
    // 公众账号ID
    private $appId = "wx3fb8f4754008e524";
    // 公众账号密钥
    private $appSecret = "eff84a38864f33660994eaaa2f258fcf";
    // 商户号
    private $mchId = "1503376371";
    // api 密钥
    private $key = "f1c7979edd28576bfe57e5d36f0a3604";
    // 商户支付证书
    private $certPemPath = "./uploads/pem/1503376371/apiclient_cert.pem";
    // 支付证书私钥
    private $keyPemPath = "./uploads/pem/1503376371/apiclient_key.pem";

    public $wechat;

    public function __construct()
    {
        $wechat = new WXPay(
            $this->appId,
            $this->mchId,     // mch id
            $this->key,
            realpath($this->certPemPath),
            realpath($this->keyPemPath),
            6000
        );
        $this->wechat = $wechat;
    }

    public function test13()
    {
        $data["order_num_type"] = 'out_trade_no';

        $data["order_num"] = '150337637120180509095053';
        // 商户退款单号
        $data["refund_num"] = md5(time());
        // 订单金额
        $data["order_money"] = 0.1;
        // 退款金额
        $data["refund_money"] = 0.01;
        // 退款原因
        $data["refund_reason"] = "不想买了";
        $res = $this->refund($data);
        echo $res;
    }


    public function demo()
    {
        // 退款查询接口
        $reqData["order_num_type"] = "out_refund_no";
        $reqData["order_num"] = "1003022622018050853721122351525761650";
        $res = $this->refundQuery($reqData);
        echo $res;
    }

    public function unifiedOrder()
    {

    }


    public function orderQuery()
    {
        $resp = $this->wechat->orderQuery(array(
            'out_trade_no' => '201610265257070987061763',
            'total_fee' => 1,
            'body' => '腾讯充值中心-QQ会员充值',
            'spbill_create_ip' => '123.12.12.123',
            'trade_type' => 'NATIVE',
            'notify_url' => 'https://www.example.com/wxpay/notify'
        ));
        var_dump($resp);
    }


    /**
     * 退款接口
     * order_num_type 有两个值：
     *          transaction_id(微信订单号) 和 out_trade_no(商户订单号)
     * @param array $param
     * @return string
     */
    public function refund($param = [])
    {
        // 查询订单类型，和相对应的订单号
        $data[$param["order_num_type"]] = $param["order_num"];
        // 商户退款单号
        $data["out_refund_no"] = $param["refund_num"];
        // 订单金额
        $data["total_fee"] = $param["order_money"] * 100;
        // 退款金额
        $data["refund_fee"] = $param["refund_money"] * 100;
        // 退款原因
        $data["refund_desc"] = $param["refund_reason"];
        // 退款通知地址
//        $data["notify_url"] = $param["notify_url"];

        $res = $this->wechat->refund($data);
        return $this->resDispose($res);
    }


    /**
     * 退款订单查询
     *
     * @param array $param
     * @return string
     */
    public function refundQuery($param = [])
    {
        $data[$param["order_num_type"]] = $param["order_num"];
        // 查询接口
        $res = $this->wechat->refundQuery($data);
        return $this->resDispose($res);
    }


    /**
     * 接口返回处理
     * @param $param
     * @return string
     */
    public function resDispose($param)
    {
        if ($param["return_code"] == "SUCCESS") {
            if($param["result_code"] == "FAIL") {
                $res["return_code"] = 0;
                $res["return_msg"] = $param["err_code_des"];
            }else{
                $res["data"] = $param;
                $res["return_code"] = 1;
                $res["return_msg"] = "SUCCESS";
            }
        } else {
            $res["return_code"] = 0;
            $res["return_msg"] = $param["return_msg"];
        }
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }
}