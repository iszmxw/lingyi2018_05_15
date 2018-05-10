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
use WXPay\WXPay;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

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
        $data["order_money"] = 0.1;
        $data["ip_address"] = "120.78.140.10";
        $data["auth_code"] = "135463544838356441";
        echo $this->microOrder($data);
    }

    public function test14()
    {
        $data["bill_date"] = 20180508;
        $data["bill_type"] = "ALL";
        $this->downloadBill($data);

    }

    public function demo()
    {


        // 下载对账单
//        $data["bill_date"] = 20180508;
//        $data["bill_type"] = "ALL";
//        $this->downloadBill($data);

        // 关闭订单
//        $data["order_num"] = 1503376371;
//        $res = $this->closeOrder($data);
//        echo $res;

//        // native 下单
//        $data["desc"] = "商品-xho-test";
//        $data["order_num"] = md5(time());
//        $data["order_money"] = 0.01;
//        $data["ip_address"] = "120.78.140.10";
//        $data["trade_type"] = "NATIVE";
//        $data["openid"] = "oK2HF1Sy1qdRQyqg69pPN5-rirrg";
//        $data["product_id"] = md5(time());
//        $this->nativeOrder($data);
//        echo "<img src='http://develop.01nnt.com/uploads/pay_qr_code.png'>";

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
//        $res = $this->jsApiOrder($data);
//        $res = json_decode($res,true);
//        return view("Fansmanage/Test/test", ["signPackage" => $signPackage, "wxpay" => $res["data"]]);


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


    /**
     * 刷卡支付
     * @param $param
     * @return string
     */
    public function microOrder($param)
    {
        // 商品信息
        $data["body"] = $param["desc"];
        // 订单号
        $data["out_trade_no"] = $param["order_num"];
        // 金额
        $data["total_fee"] = $param["order_money"] * 100;
        // ip 地址
        $data["spbill_create_ip"] = $param["ip_address"];
        // 授权码
        $data["auth_code"] = $param["auth_code"];

        $res = $this->wechat->microPay($data);
        return $this->resDispose($res);
    }


    /**
     * 下载对账单
     * @param array $param
     * @return string
     * @throws \Exception
     */
    public function downloadBill($param = [])
    {
        $data["bill_date"] = $param["bill_date"];
        $data["bill_type"] = $param["bill_type"];
        // 获取数据
        $res = $this->wechat->downloadBill($data);

        // 判断数据返回结果
        if($res["return_code"] != "SUCCESS"){
            return json_encode($res,JSON_UNESCAPED_UNICODE);
        }

        $res = $res["data"];
        // 得到文件名
        $fileName = "./uploads/download.csv";
        file_put_contents($fileName,$res);
        // 告诉浏览器通过附件形式来处理文件
        header( "Content-Disposition:  attachment;  filename=".$fileName);
        // 下载文件大小
        header('Content-Length: ' . filesize($fileName));
        // 读取文件内容
        readfile($fileName);
    }


    /**
     * 扫码下单接口
     * @param $param
     * @return string|void
     */
    public function nativeOrder($param)
    {
        // 统一下单地址
        $res_json = $this->unifiedOrder($param);
        $res = json_decode($res_json, true);

        // 判断接口是否存在问题
        if ($res["return_code"] == 0) {
            return $res_json;
        }
        return $this->qrCode($res["data"]["code_url"]);
    }

    /**
     * jsApi 接口
     * @param $param
     * @return string
     */
    public function jsApiOrder($param)
    {
        // 统一下单地址
        $res_json = $this->unifiedOrder($param);
        $res = json_decode($res_json, true);
        // 判断接口是否存在问题
        if ($res["return_code"] == 0) {
            return $res_json;
        }

        // 时间戳
        $res["data"]["timestamp"] = time();
        // 支付签名
        $paySign = "appId={$res["data"]["appid"]}&nonceStr={$res["data"]["nonce_str"]}&package=prepay_id={$res["data"]["prepay_id"]}&signType=MD5&timeStamp={$res["data"]["timestamp"]}&key={$this->key}";
        // 处理支付签名
        $res["data"]["paySign"] = strtoupper(md5($paySign));
        // 返回数据
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 关闭订单接口
     * @param $param
     * @return string
     */
    public function closeOrder($param)
    {
        // 查询订单类型，和相对应的订单号
        $data["out_trade_no"] = $param["order_num"];
        $res = $this->wechat->closeOrder($data);
        return $this->resDispose($res);
    }

    /**
     * 统一下单接口
     * @param array $param
     * @return string
     */
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
            if (!empty($param["result_code"]) && $param["result_code"] == "FAIL") {
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

    public function qrCode($url)
    {
        // 生成二维码图片
        $qrCode = new QrCode($url);

        // 设置图片大小
        $qrCode->setSize(300);
        // 设置图片格式
        $qrCode->setWriterByName('png');
        // 设置图片边距
        $qrCode->setMargin(10);
        // 设置编码方式
        $qrCode->setEncoding('UTF-8');
        //
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);

        // 设置二维码背部形状颜色
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        // 设置背景颜色
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);

        // 设置字体还有标注文字
//        $qrCode->setLabel('Scan the code', 16, __DIR__.'/../assets/fonts/noto_sans.otf', LabelAlignment::CENTER);
        // 设置logo 图片
//        $qrCode->setLogoPath(__DIR__.'/../assets/images/symfony.png');
        // 设置logo 大小
//        $qrCode->setLogoWidth(150);

        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);

        // 直接输出
//        header('Content-Type: ' . $qrCode->getContentType());
//        return $qrCode->writeString();
        // 保存文件
//        $qrCode->writeFile(__DIR__.'/qrcode.png');

        $qrCode->writeFile("./uploads/pay_qr_code.png");
    }
}