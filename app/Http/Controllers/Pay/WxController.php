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
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class WxController extends Controller
{
//    // 公众账号ID
//    private $appId = "wx3fb8f4754008e524";
//    // 公众账号密钥
//    private $appSecret = "eff84a38864f33660994eaaa2f258fcf";
//    // 商户号
//    private $mchId = "1503376371";
//    // api 密钥
//    private $key = "f1c7979edd28576bfe57e5d36f0a3604";
//    // 商户支付证书
//    private $certPemPath = "./uploads/pem/1503376371/apiclient_cert.pem";
//    // 支付证书私钥
//    private $keyPemPath = "./uploads/pem/1503376371/apiclient_key.pem";
//    // 通知地址
//    private $notify_url = "http://develop.01nnt.com/pay/sft/test14";
//    // 商户名称
//    private $mchName = "零壹服务";


    // 公众账号ID
    private $appId = "wx96d9f85643a80012";
    // 公众账号密钥
    private $appSecret = "eff84a38864f33660994eaaa2f258fcf";
    // 商户号
    private $mchId = "1499778612";
    // api 密钥
    private $key = "f1c7979edd28576bfe57e5d36f0a3604";
    // 商户支付证书
    private $certPemPath = "./uploads/pem/1499778612/apiclient_cert.pem";
    // 支付证书私钥
    private $keyPemPath = "./uploads/pem/1499778612/apiclient_key.pem";
    // 通知地址
    private $notify_url = "http://develop.01nnt.com/pay/sft/test14";
    // 商户名称
    private $mchName = "零壹人";

    // 微信支付支持的银行信息
    private $bank_info = [
        ["bank_name" => "工商银行", "bank_code" => "1002"],
        ["bank_name" => "农业银行", "bank_code" => "1005"],
        ["bank_name" => "中国银行", "bank_code" => "1026"],
        ["bank_name" => "建设银行", "bank_code" => "1003"],
        ["bank_name" => "招商银行", "bank_code" => "1001"],
        ["bank_name" => "邮储银行", "bank_code" => "1066"],
        ["bank_name" => "交通银行", "bank_code" => "1020"],
        ["bank_name" => "浦发银行", "bank_code" => "1004"],
        ["bank_name" => "民生银行", "bank_code" => "1006"],
        ["bank_name" => "兴业银行", "bank_code" => "1009"],
        ["bank_name" => "平安银行", "bank_code" => "1010"],
        ["bank_name" => "中信银行", "bank_code" => "1021"],
        ["bank_name" => "华夏银行", "bank_code" => "1025"],
        ["bank_name" => "广发银行", "bank_code" => "1027"],
        ["bank_name" => "光大银行", "bank_code" => "1022"],
        ["bank_name" => "北京银行", "bank_code" => "1032"],
        ["bank_name" => "宁波银行", "bank_code" => "1056"]
    ];

    public function test13()
    {
        // jsapi 下单
        $wechat = new WechatController();
        $wechat->getSignPackage();
        $signPackage = request()->get("zerone_jssdk_info");
        $data["desc"] = "商品-xho-test";
        $data["order_num"] = "2cca586ee1c1ad031972837a2ae59dd9";
        $data["order_money"] = 0.1;
        $data["ip_address"] = "120.78.140.10";
        $data["trade_type"] = "JSAPI";
        $data["openid"] = "oK2HF1Sy1qdRQyqg69pPN5-rirrg";
        $data["product_id"] = "2cca586ee1c1ad031972837a2ae59dd9";
        $data["notify_url"] = "http://develop.01nnt.com/pay/sft/test14";
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        $res = $this->jsApiOrder($data);
        $res = json_decode($res, true);
        return view("Fansmanage/Test/test", ["signPackage" => $signPackage, "wxpay" => $res["data"]]);
    }


    // +----------------------------------------------------------------------
    // | Start - 外部通用方法
    // +----------------------------------------------------------------------

    /**
     * 设置微信公众号信息
     * @param $wx_pay_config
     */
    public function setWxPayConfig($wx_pay_config)
    {
        !empty($wx_pay_config["appId"]) ? $this->appId = $wx_pay_config["appId"] : false;
        !empty($wx_pay_config["appSecret"]) ? $this->appSecret = $wx_pay_config["appSecret"] : false;
        !empty($wx_pay_config["mchId"]) ? $this->mchId = $wx_pay_config["mchId"] : false;
        !empty($wx_pay_config["key"]) ? $this->key = $wx_pay_config["key"] : false;
        !empty($wx_pay_config["certPemPath"]) ? $this->certPemPath = $wx_pay_config["certPemPath"] : false;
        !empty($wx_pay_config["keyPemPath"]) ? $this->keyPemPath = $wx_pay_config["keyPemPath"] : false;
        !empty($wx_pay_config["mchName"]) ? $this->mchName = $wx_pay_config["mchName"] : false;
    }

    /**
     * 获取微信支付支持的银行的信息
     * @return string
     */
    public function getBankInfo()
    {
        return json_encode($this->bank_info, JSON_UNESCAPED_UNICODE);
    }

    // +----------------------------------------------------------------------
    // | End - 外部通用方法
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | Start - 企业支付到零钱/银行
    // +----------------------------------------------------------------------
    /**
     * 企业支付到银行卡接口
     * @return string
     * @throws \Exception
     */
    public function pay_bank()
    {
        $file_name = "./uploads/pay/wechat/public_key/{$this->mchId}/pkcs_8/publicrsa.pem";
        // 如果不存在公钥文件就进行生成
        if (!file_exists(realpath($file_name))) {
            $this->getpublickey();
        }
        // 请求参数处理
        $param = $this->requestDispose();
        // 商户企业付款单号
        $data["partner_trade_no"] = $param["order_num"];
        // 收款方银行卡号
        $data["enc_bank_no"] = $this->rsa_encrypt($param["bank_card_num"], $file_name);
        // 收款方用户名
        $data["enc_true_name"] = $this->rsa_encrypt($param["bank_user_name"], $file_name);
        // 收款方开户行
        $data["bank_code"] = $param["bank_code"];
        // 付款金额
        $data["amount"] = $param["order_money"];
        // 付款说明
        $data["desc"] = $param["remark"];
        // 填充数组
        $data = $this->fillData($data, "sptrans");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }


    /**
     * 获取公钥，包括 PKCS#1 和 PKCS#8
     * @return string
     * @throws \Exception
     */
    public function getpublickey()
    {
        // 加密类型
        $data["sign_type"] = "MD5";
        // 填充数组
        $data = $this->fillData($data, "sptrans");
        // 接口地址 : PKCS#1的公钥
        $url = "https://fraud.mch.weixin.qq.com/risk/getpublickey";
        // 返回结果
        $res_json = $this->responseDispose($url, $data, "POST", true);

        $res = json_decode($res_json, true);
        // 判断接口是否出错了
        if ($res["return_code"] == 0) {
            return $res_json;
        }

        // 得到文件名
        $filePath_pkcs_1 = "./uploads/pay/wechat/public_key/{$this->mchId}/pkcs_1/";
        $filePath_pkcs_8 = "./uploads/pay/wechat/public_key/{$this->mchId}/pkcs_8/";

        // 检测文件夹是否存在
        $this->checkPath($filePath_pkcs_1);
        $this->checkPath($filePath_pkcs_8);

        // 保存文件名
        $fileName_pkcs_1 = "{$filePath_pkcs_1}publicrsa.pem";
        // 写入文件夹
        file_put_contents($fileName_pkcs_1, $res["data"]["pub_key"]);

        // 获取全程地址
        $file_name = realpath($fileName_pkcs_1);
        // PKCS#8的公钥
        $turn_code = shell_exec("openssl rsa -RSAPublicKey_in -in $file_name -pubout");
        // 保存文件夹
        $fileName_pkcs_8 = "{$filePath_pkcs_8}publicrsa.pem";
        // 写入文件夹
        file_put_contents($fileName_pkcs_8, $turn_code);
        // 返回保存路径
        return $fileName_pkcs_8;
    }

    /**
     * 查询企业支付到银行卡接口
     * @return string
     * @throws \Exception
     */
    public function query_bank()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 商户企业付款单号
        $data["partner_trade_no"] = $param["order_num"];
        // 填充数组
        $data = $this->fillData($data, "sptrans");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaysptrans/query_bank";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }


    /**
     * 企业支付到零钱接口
     * @return string
     * @throws \Exception
     */
    public function transfers()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 商户订单号
        $data["partner_trade_no"] = $param["order_num"];
        // 用户openid
        $data["openid"] = $param["openid"];
        // 校验用户姓名选项
        $data["check_name"] = "FORCE_CHECK";
        // 收款用户姓名
        $data["re_user_name"] = $param["bank_user_name"];
        // 金额
        $data["amount"] = $param["order_money"];
        // 企业付款描述信息
        $data["desc"] = $param["remark"];
        // ip 地址
        $data["spbill_create_ip"] = $param["ip_address"];
        // 填充数组
        $data = $this->fillData($data, "transfers");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }

    /**
     * 查询企业支付到零钱接口
     * @return string
     * @throws \Exception
     */
    public function gettransferinfo()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 商户企业付款单号
        $data["partner_trade_no"] = $param["order_num"];
        // 填充数组
        $data = $this->fillData($data, "query");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }
    // +----------------------------------------------------------------------
    // | End - 企业支付到零钱/银行
    // +----------------------------------------------------------------------


    // +----------------------------------------------------------------------
    // | Start - 现金红包
    // +----------------------------------------------------------------------
    /**
     * 普通红包
     * @return string
     * @throws \Exception
     */
    public function sendredpack()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 订单号
        $data["mch_billno"] = $param["order_num"];
        // 发送的openid
        $data["re_openid"] = $param["openid"];
        // 金额
        $data["total_amount"] = $param["order_money"];
        // 发放人数
        $data["total_num"] = 1;
        // 发放ip地址
        $data["client_ip"] = $param["ip_address"];
        // 祝福语
        $data["wishing"] = $param["wishing"];
        // 活动名称
        $data["act_name"] = $param["activity_name"];
        // 备注
        $data["remark"] = $param["remark"];
        // 填充数组
        $data = $this->fillData($data, "red_append");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }

    /**
     * 发送裂变红包
     * @return string
     * @throws \Exception
     */
    public function sendgroupredpack()
    {// 请求参数处理
        $param = $this->requestDispose();
        // 订单号
        $data["mch_billno"] = $param["order_num"];
        // 发送的openid
        $data["re_openid"] = $param["openid"];
        // 金额
        $data["total_amount"] = $param["order_money"];
        // 发放人数
//        $data["total_num"] = $param["order_people_num"];
        $data["total_num"] = 1;
        // 发放ip地址
        $data["client_ip"] = $param["ip_address"];
        // 祝福语
        $data["wishing"] = $param["wishing"];
        // 红包金额设置方式：ALL_RAND（由微信进行随机分配）
//        $data["amt_type"] = $param["amt_type"];
        $data["amt_type"] = "ALL_RAND";
        // 活动名称
        $data["act_name"] = $param["activity_name"];
        // 备注
        $data["remark"] = $param["remark"];
        // 填充数组
        $data = $this->fillData($data, "red_append");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }

    /**
     * 红包查询记录
     * @return string
     * @throws \Exception
     */
    public function gethbinfo()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 订单号
        $data["mch_billno"] = $param["order_num"];
        // 查询类型
        $data["bill_type"] = "MCHT";
        // 填充数组
        $data = $this->fillData($data, "query");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }
    // +----------------------------------------------------------------------
    // | End - 现金红包
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | Start - 订单相关接口
    // +----------------------------------------------------------------------
    /**
     * 扫码下订单
     * @return string
     * @throws \Exception
     */
    public function nativeOrder()
    {
        // 统一下单地址
        $res_json = $this->unifiedOrder();
        $res = json_decode($res_json, true);
        // 判断接口是否存在问题
        if ($res["return_code"] == 0) {
            return $res_json;
        }
        // 返回数据
        return json_encode($res, JSON_UNESCAPED_UNICODE);
//        return $this->qrCode($res["data"]["code_url"]);
    }

    /**
     * jsApi 下订单
     * @return string
     * @throws \Exception
     */
    public function jsApiOrder()
    {
        // 统一下单地址
        $res_json = $this->unifiedOrder();
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
     * @return string
     * @throws \Exception
     */
    public function closeOrder()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 查询订单类型，和相对应的订单号
        $data["out_trade_no"] = $param["order_num"];
        // 填充数组
        $data = $this->fillData($data, "order");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/closeorder";
        // 返回结果
        return $this->responseDispose($url, $data);
    }

    /**
     * 刷卡支付
     * @return string
     * @throws \Exception
     */
    public function microOrder()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 商品信息
        $data["body"] = $param["desc"];
        // 订单号
        $data["out_trade_no"] = $param["order_num"];
        // 金额
        $data["total_fee"] = $param["order_money"];
        // ip 地址
        $data["spbill_create_ip"] = $param["ip_address"];
        // 授权码
        $data["auth_code"] = $param["auth_code"];
        // 填充数组
        $data = $this->fillData($data, "order");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/micropay";
        // 返回结果
        return $this->responseDispose($url, $data);
    }


    /**
     * 统一下单接口
     * @return string
     * @throws \Exception
     */
    public function unifiedOrder()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 商品信息
        $data["body"] = $param["desc"];
        // 订单号
        $data["out_trade_no"] = $param["order_num"];
        // 金额
        $data["total_fee"] = $param["order_money"];
        // ip 地址
        $data["spbill_create_ip"] = $param["ip_address"];
        // 交易类型
        $data["trade_type"] = $param["trade_type"];
        // 通知地址
//        $data["notify_url"] = $this->notify_url;
        $data["notify_url"] = $param["notify_url"];
        // openid (JSAPI : 公众号支付必填)
        $data["openid"] = $param["openid"];
        // 商品ID (NATIVE : 扫码模式必填)
        $data["product_id"] = $param["product_id"];
        // 填充数组
        $data = $this->fillData($data, "order");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        // 返回结果
        return $this->responseDispose($url, $data, "post", false);
    }


    /**
     * 订单查询接口
     * order_num_type 有两个值：
     *          transaction_id(微信订单号) 和 out_trade_no(商户订单号)
     * @return string
     * @throws \Exception
     */
    public function orderQuery()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 查询订单类型，和相对应的订单号
        $data[$param["order_num_type"]] = $param["order_num"];
        // 填充数组
        $data = $this->fillData($data, "order");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
        // 返回结果
        return $this->responseDispose($url, $data);
    }


    /**
     * 退款接口
     * order_num_type 有两个值：
     *          transaction_id(微信订单号) 和 out_trade_no(商户订单号)
     * @return string
     * @throws \Exception
     */
    public function refund()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 查询订单类型，和相对应的订单号
        $data[$param["order_num_type"]] = $param["order_num"];
        // 商户退款单号
        $data["out_refund_no"] = $param["refund_num"];
        // 订单金额
        $data["total_fee"] = $param["order_money"];
        // 退款金额
        $data["refund_fee"] = $param["refund_money"];
        // 退款原因
        $data["refund_desc"] = $param["refund_reason"];
        // 通知地址
//        $data["notify_url"] = $this->notify_url;
        $data["notify_url"] = $param["notify_url"];
        // 填充数组
        $data = $this->fillData($data, "order");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }


    /**
     * 退款订单查询
     * order_num_type 有四个值：
     *          transaction_id(微信订单号) 和 out_trade_no(商户订单号)
     *          out_refund_no(商户退款单号) 和 refund_id(微信退款单号)
     * @return string
     * @throws \Exception
     */
    public function refundQuery()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 查询订单类型，和相对应的订单号
        $data[$param["order_num_type"]] = $param["order_num"];
        // 填充数组
        $data = $this->fillData($data, "order");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/refundquery";
        // 返回结果
        return $this->responseDispose($url, $data);
    }


    /**
     * 下载对账单
     * @return string
     * @throws \Exception
     */
    public function downloadBill()
    {
        // 请求参数处理
        $param = $this->requestDispose();
        // 对账日期
        $data["bill_date"] = $param["bill_date"];
        // 对账类型
        $data["bill_type"] = $param["bill_type"];
        // 填充数组
        $data = $this->fillData($data, "order");
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/downloadbill";
        // 将数据转化为 XML 格式
        $data = $this->array2xml($data);
        // 发送请求
        $res = $this->httpRequest($url, "POST", $data);
        // 判断是否为xml 格式
        $res_xml_parser = $this->xmlParser($res);
        // 判断xml格式中接口返回结果是否正确
        if ($res_xml_parser == true) {
            $res = $this->xml2array($res);
            // 判断结果返回结果
            if ($res["return_code"] != "SUCCESS") {
                return json_encode($res, JSON_UNESCAPED_UNICODE);
            }
        }
        // 得到文件名
        $filePath = "./uploads/pay/wechat/bill/" . date("Ymd") . "/";
        // 检测文件夹是否存在
        $this->checkPath($filePath);
        // 保存文件名
        $fileName = $filePath . date("His") . ".csv";
        // 写入文件夹
        file_put_contents($fileName, $res);
        // 告诉浏览器通过附件形式来处理文件
        header("Content-Disposition:  attachment;  filename=" . $fileName);
        // 下载文件大小
        header('Content-Length: ' . filesize($fileName));
        // 读取文件内容
        readfile($fileName);
    }

    // +----------------------------------------------------------------------
    // | End - 订单相关接口
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | Start - 公用方法
    // +----------------------------------------------------------------------

    /**
     * 填充数据
     * @param $param
     * @param $type
     * @return mixed
     */
    public function fillData($param, $type)
    {
        switch ($type) {
            // 填充订单数据
            case "order" :
                $param["mch_id"] = $this->mchId;
                $param["appid"] = $this->appId;
                $param["sign_type"] = "MD5";
                break;
            // 填充红包查询数据
            case "query" :
                $param["mch_id"] = $this->mchId;
                $param["appid"] = $this->appId;
                break;
            // 填充红包数据
            case "red_append" :
                $param["mch_id"] = $this->mchId;
                $param["wxappid"] = $this->appId;
                $param["send_name"] = $this->mchName;
                break;
            // 填充企业支付到零钱数据
            case "transfers" :
                $param["mchid"] = $this->mchId;
                $param["mch_appid"] = $this->appId;
                break;
            // 填充企业支付到银行数据
            case "sptrans" :
                $param["mch_id"] = $this->mchId;
                break;
        }
        $param["nonce_str"] = $this->nonceStr();
        $param["sign"] = $this->signature($param);
        return $param;
    }

    /**
     * 接口返回处理
     * @param string $url 接口地址
     * @param array $data 接口传输的数据
     * @param string $method 请求方法
     * @param bool $is_ssh 是否需要证书验证
     * @return string
     * @throws \Exception
     */
    public function responseDispose($url, $data, $method = "POST", $is_ssh = false)
    {
        // 将数据转化为 XML 格式
        $data = $this->array2xml($data);
        // 发送请求
        $resXml = $this->httpRequest($url, $method, $data, [], $is_ssh);
        // 将XML 转化为 数组
        $param = $this->xml2array($resXml);
        // 判断接口返回结果
        if ($param["return_code"] == "SUCCESS") {
            // 判断提交是否成功
            if (!empty($param["result_code"]) && $param["result_code"] == "FAIL") {
                // 接口返回失败
                $res["return_code"] = 0;
                $res["return_msg"] = $param["err_code_des"];
            } else {
                // 接口返回成功
                $res["data"] = $this->dataDispose($param);
                $res["return_code"] = 1;
                $res["return_msg"] = "SUCCESS";
            }
        } else {
            // 接口返回失败
            $res["return_code"] = 0;
            $res["return_msg"] = $param["return_msg"];
        }
        // 返回 json 数据
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }


    /**
     * 请求数据处理
     * @param $param
     * @return mixed
     */
    public function requestDispose()
    {
        $param = request()->post();
//        $res = json_decode($param, true);
        return $param;
    }


    /**
     * 数据格式处理
     * @param $param
     * @return mixed
     */
    public function dataDispose($param)
    {
//        // 金额处理
//        $total_type = ["total_fee", "cash_fee", "cmms_amt", "amount","payment_amount","cash_refund_fee","refund_fee"];
//        foreach ($total_type as $val) {
//            if (array_key_exists($val, $param)) {
//                $param[$val] = $param[$val] / 100;
//            }
//        }
        return $param;
    }

    /**
     * 生成二维码
     * @param $url
     * @return string
     */
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


        // 得到文件名
        $filePath = "./uploads/pay/wechat/micro/" . date("Ymd") . "/";
        // 检测文件夹是否存在
        $this->checkPath($filePath);
        // 保存文件名
        $fileName = $filePath . date("His") . ".png";
        // 二维码保存
        $qrCode->writeFile($fileName);
        return $fileName;
    }

    /**
     * 生成随机数
     * @return string
     */
    public function nonceStr()
    {
        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * 生成签名（只支持MD5）
     * @param $data
     * @return string
     */
    public function signature($data)
    {
        $combineStr = '';
        // 去所有的键名
        $keys = array_keys($data);
        // 排序
        asort($keys);
        // 处理数据
        foreach ($keys as $k) {
            $v = $data[$k];
            if ($k == "sign") {
                continue;
            } else if ((is_string($v) && strlen($v) > 0) || is_numeric($v)) {
                $combineStr = "${combineStr}${k}=${v}&";
            } else if (is_string($v) && strlen($v) == 0) {
                continue;
            }
        }
        $combineStr = "${combineStr}key=$this->key";
        // 返回签名
        return strtoupper(md5($combineStr));
    }


    /**
     * CURL请求
     * @param string $url 请求url地址
     * @param string $method 请求方法 get post
     * @param array $postData post数据数组
     * @param array $headers 请求header信息
     * @param bool $ssl 是否验证证书
     * @param bool|false $debug 调试开启 默认false
     * @return mixed
     */
    public function httpRequest($url, $method, $postData = [], $headers = [], $ssl = false, $debug = false)
    {
        // 将方法统一换成大写
        $method = strtoupper($method);
        // 初始化
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        // 在发起连接前等待的时间，如果设置为0，则无限等待
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        // 设置CURL允许执行的最长秒数
        curl_setopt($curl, CURLOPT_TIMEOUT, 7);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);
                if (!empty($postData)) {
                    $tmpdatastr = is_array($postData) ? http_build_query($postData) : $postData;
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }

        curl_setopt($curl, CURLOPT_URL, $url);

        if ($ssl == true) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
            // 严格校验
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            // 设置证书
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $this->certPemPath);
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $this->keyPemPath);
        }


        // 启用时会将头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        // 指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的
        curl_setopt($curl, CURLOPT_MAXREDIRS, 2);

        // 添加请求头部
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        // COOKIE带过去
//        curl_setopt($curl, CURLOPT_COOKIE, $Cookiestr);
        $response = curl_exec($curl);
        $requestInfo = curl_getinfo($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // 开启调试模式就返回 curl 的结果
        if ($debug) {
            echo "=====post data======\r\n";
            dump($postData);
            echo "=====info===== \r\n";
            dump($requestInfo);
            echo "=====response=====\r\n";
            dump($response);
            echo "=====http_code=====\r\n";
            dump($http_code);

            dump(curl_getinfo($curl, CURLINFO_HEADER_OUT));
        }
        curl_close($curl);
        return $response;
    }


    /**
     * 将XML格式字符串转换为array
     * 参考： http://php.net/manual/zh/book.simplexml.php
     * @param string $str XML格式字符串
     * @return array
     * @throws \Exception
     */
    public function xml2array($str)
    {
        $xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);
        $result = array();
        // value，一个字段多次出现，结果中的value是数组
        $bad_result = json_decode($json, TRUE);
        // return $bad_result;

        foreach ($bad_result as $k => $v) {
            if (is_array($v)) {
                if (count($v) == 0) {
                    $result[$k] = '';
                } else if (count($v) == 1) {
//                    $result[$k] = $v[0];
                    $result[$k] = current($v);
                } else {
                    throw new \Exception('Duplicate elements in XML. ' . $str);
                }
            } else {
                $result[$k] = $v;
            }
        }
        return $result;
    }


    /**
     * 将array转换为XML格式的字符串
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function array2xml($data)
    {
        $xml = new \SimpleXMLElement('<xml/>');
        foreach ($data as $k => $v) {
            if (is_string($k) && (is_numeric($v) || is_string($v))) {
                $xml->addChild("$k", htmlspecialchars("$v"));
            } else {
                throw new \Exception('Invalid array, will not be converted to xml');
            }
        }
        return $xml->asXML();
    }

    /**
     * 判断是否为xml 格式
     * @param $str
     * @return bool
     */
    function xmlParser($str)
    {
        $xml_parser = xml_parser_create();
        if (!xml_parse($xml_parser, $str, true)) {
            xml_parser_free($xml_parser);
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检测文件夹是否存在
     * @param $savepath
     * @return bool
     */
    public function checkPath($savepath)
    {
        /* 检测并创建目录 */
        if (!$this->makeDir($savepath)) {
            return false;
        } else {
            /* 检测目录是否可写 */
            if (!is_writable($savepath)) {
                //$this->error = '上传目录 ' . $savepath . ' 不可写！';
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * 创建文件夹
     * @param $savepath
     * @return bool
     */
    public function makeDir($savepath)
    {
        $dir = $savepath;
        if (is_dir($dir)) {
            return true;
        }

        if (mkdir($dir, 0777, true)) {
            chmod($dir, 0777);
            return true;
        } else {
            //$this->error = "目录 {$savepath} 创建失败！";
            return false;
        }
    }

    /**
     * ras 加密
     * @param $str
     * @param $file_name
     * @return string
     */
    public function rsa_encrypt($str, $file_name)
    {
        // 读取公钥内容
        $pu_key = openssl_pkey_get_public(file_get_contents($file_name));
        $encryptedBlock = '';
        $encrypted = '';
        // 用标准的RSA加密库对敏感信息进行加密，选择RSA_PKCS1_OAEP_PADDING填充模式
        openssl_public_encrypt($str, $encryptedBlock, $pu_key, OPENSSL_PKCS1_OAEP_PADDING);
        // 得到进行rsa加密并转base64之后的密文
        $str_base64 = base64_encode($encrypted . $encryptedBlock);
        return $str_base64;
    }

    // +----------------------------------------------------------------------
    // | End - 公用方法
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | Start - 公共例子
    // +----------------------------------------------------------------------
    public function demo()
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
//        echo $data["order_num"];
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        echo $this->pay_bank($data);


        // 企业发放到银行卡查询
//        $data["order_num"] = "152e4b79e81e33edc4b843c077c82d24";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        echo $this->query_bank($data);

        // 查询红包
//        $data["order_num"] = "33d5540a1185917e72ff8bbb6d9d";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        echo $this->gethbinfo($data);

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

        // 刷卡支付
//        $data["desc"] = "商品-xho-test";
//        $data["order_num"] = md5(time());
//        $data["order_money"] = 0.1;
//        $data["ip_address"] = "120.78.140.10";
//        $data["auth_code"] = "135463544838356441";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        echo $this->microOrder($data);

        // 下载对账单
//        $data["bill_date"] = 20180508;
//        $data["bill_type"] = "ALL";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $this->downloadBill($data);

        // 关闭订单
//        $data["order_num"] = 1503376371;
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
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
//        $data["notify_url"] = "http://develop.01nnt.com/pay/sft/test14";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->nativeOrder($data);
//        echo "<img src='$res'>";

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
//        $data["notify_url"] = "http://develop.01nnt.com/pay/sft/test14";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->jsApiOrder($data);
//        $res = json_decode($res,true);
//        return view("Fansmanage/Test/test", ["signPackage" => $signPackage, "wxpay" => $res["data"]]);


        // 订单查询
//        $data["order_num_type"] = 'out_trade_no';
//        $data["order_num"] = '150337637120180509095053';
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->orderQuery($data);
//        echo $res;

//        // 退款查询接口
//        $reqData["order_num_type"] = "out_refund_no";
//        $reqData["order_num"] = "1003022622018050853721122351525761650";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
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
//        $data["notify_url"] = "http://develop.01nnt.com/pay/sft/test14";
//        // 退款原因
//        $data["refund_reason"] = "不想买了";
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $res = $this->refund($data);
//        echo $res;
    }
    // +----------------------------------------------------------------------
    // | End - 公共例子
    // +----------------------------------------------------------------------
}