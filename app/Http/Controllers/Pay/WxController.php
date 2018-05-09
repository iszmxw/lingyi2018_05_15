<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8
 * Time: 17:09
 */

namespace App\Http\Controllers\Pay;


use App\Http\Controllers\Api\WechatController;
use App\Http\Controllers\Controller;
use App\Models\XhoLog;
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
    // 通知地址
    private $notify_url = "http://develop.01nnt.com/pay/sft/test14";
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
        $data["desc"] = "商品-xho-test";
        $data["order_num"] = md5(time());
        $data["order_money"] = 0.01;
        $data["ip_address"] = "120.78.140.10";
        $data["trade_type"] = "JSAPI";
        $data["openid"] = "oK2HF1Sy1qdRQyqg69pPN5-rirrg";
        $data["product_id"] = md5(time());
        $res = $this->unifiedOrder($data);
        echo $res;
    }

    public function test14()
    {

    }

    public function test15()
    {
        $wechat = new WechatController();
        $res = $wechat->getSignPackage();
        dd($res);
        $a_k = "9_ka-c5NS_a0XMaeRCcyD5sPPG5ydczvqs5p-8HWh3ei09RwuxW5qEx4eT0wkdwl_KOSKztRj6l2zGNEAcZWhKu1rN0jaeiw2IYD-PTw9kcXrZuuHHEJUyA9UXcJ8WG5IDNpD2gfdpGnDNikADHYIhADAULV";

        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$a_k}&type=jsapi";
        $res = file_get_contents($url);
        $res = json_decode($res, true);
        $ticket = $res["ticket"];


        var_dump($ticket);

// 设置得到签名的参数
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        var_dump($url);

        $timestamp = time();
        $nonceStr = substr(md5(time()), 0, 16);
// 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket={$ticket}&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array("appId" => "wx3fb8f4754008e524", "nonceStr" => $nonceStr, "timestamp" => $timestamp, "url" => $url, "rawString" => $string, "signature" => $signature);


        $data["desc"] = "商品-xho-test";
        $data["order_num"] = md5(time());
        $data["order_money"] = 0.01;
        $data["ip_address"] = "120.78.140.10";
        $data["trade_type"] = "JSAPI";
        $data["openid"] = "oK2HF1Sy1qdRQyqg69pPN5-rirrg";
        $data["product_id"] = md5(time());
        $res = $this->unifiedOrder($data);
        $res = json_decode($res,true);

        return view("Fansmanange/Test/test", ["signPackage" => $signPackage,"wxpay"=>$res]);
    }

    public function demo()
    {

        // 订单查询
//        $data["order_num_type"] = 'out_trade_no';
//        $data["order_num"] = '150337637120180509095053';
//        $res = $this->orderQuery($data);
//        echo $res;

//        // 退款查询接口
//        $reqData["order_num_type"] = "out_refund_no";
//        $reqData["order_num"] = "1003022622018050853721122351525761650";
//        $res = $this->refundQuery($reqData);
//        echo $res;

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
//        $res = $this->refund($data);
//        echo $res;
    }


    public function unifiedOrder($param = [])
    {
        // 商品信息
        $data["body"] = $param["desc"];
        // 订单号
        $data["out_trade_no"] = $param["order_num"];
        // 金额
        $data["total_fee"] = $param["order_money"] * 100;
        // ip 地址
        $data["spbill_create_ip"] = $param["ip_address"];
        // 交易类型
        $data["trade_type"] = $param["trade_type"];
        // 通知地址
        $data["notify_url"] = $this->notify_url;
        // openid (JSAPI : 公众号支付必填)
        $data["openid"] = $param["openid"];
        // 商品ID (NATIVE : 扫码模式必填)
        $data["product_id"] = $param["product_id"];


        $res = $this->wechat->unifiedOrder($data);
        dump($res);
        exit;
        return $this->resDispose($res);
    }


    /**
     * 订单查询接口
     * order_num_type 有两个值：
     *          transaction_id(微信订单号) 和 out_trade_no(商户订单号)
     * @param array $param
     * @return string
     */
    public function orderQuery($param = [])
    {
        // 查询订单类型，和相对应的订单号
        $data[$param["order_num_type"]] = $param["order_num"];
        $res = $this->wechat->orderQuery($data);
        return $this->resDispose($res);
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
        // 通知地址
        $data["notify_url"] = $this->notify_url;

        $res = $this->wechat->refund($data);
        return $this->resDispose($res);
    }


    /**
     * 退款订单查询
     * order_num_type 有四个值：
     *          transaction_id(微信订单号) 和 out_trade_no(商户订单号)
     *          out_refund_no(商户退款单号) 和 refund_id(微信退款单号)
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
        // 判断接口返回结果
        if ($param["return_code"] == "SUCCESS") {
            // 判断提交是否成功
            if ($param["result_code"] == "FAIL") {
                $res["return_code"] = 0;
                $res["return_msg"] = $param["err_code_des"];
            } else {
                $res["data"] = $this->dataDispose($param);
                $res["return_code"] = 1;
                $res["return_msg"] = "SUCCESS";
            }
        } else {
            $res["return_code"] = 0;
            $res["return_msg"] = $param["return_msg"];
        }
        // 返回 json 数据
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 数据格式处理
     * @param $param
     * @return mixed
     */
    public function dataDispose($param)
    {
        // 金额处理
        $total_type = ["total_fee", "cash_fee"];
        foreach ($total_type as $val) {
            if (array_key_exists($val, $param)) {
                $param[$val] = $param[$val] / 100;
            }
        }
        return $param;
    }
}