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
    // 商户名称
    private $mchName = "零壹服务";


    public function test13()
    {
//        $data["desc"] = "商品-xho-test";
//        $data["order_num"] = md5(time());
//        $data["order_money"] = 5;
//        $data["ip_address"] = "120.78.140.10";
//        $data["trade_type"] = "NATIVE";
//        $data["openid"] = "oK2HF1Sy1qdRQyqg69pPN5-rirrg";
//        $data["product_id"] = md5(time());
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $this->nativeOrder($data);
//        echo "<img src='http://develop.01nnt.com/uploads/pay_qr_code.png'>";
//        exit;
         // 活动名称
        $data["activity_name"] = "zzzz";
        // 发放ip地址
        $data["ip_address"] = "120.78.140.10";
        // 订单号
        $data["order_num"] = substr(md5(time()), 0, 28);
//        $data["order_num"] = "6530cb44b093892f9e14d442472b";
        // 发送的openid
        $data["openid"] = "oK2HF1Sy1qdRQyqg69pPN5-rirrg";
        // 备注
        $data["remark"] = "ganjinqiang";
        // 金额
        $data["order_money"] = "1";
        // 祝福语
        $data["wishing"] = "gongxi";

//        $data["order_num"] = "86dc28bb7e80201a3d9b7d9d6209";


        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $res = $this->sendredpack($data);
        echo $res;
    }



    // +----------------------------------------------------------------------
    // | Start - 现金红包
    // +----------------------------------------------------------------------
    /**
     * 红包查询记录
     * @param $param
     * @return string
     * @throws \Exception
     */
    public function gethbinfo($param)
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
        // 订单号
        $data["mch_billno"] = $param["order_num"];
        // 查询类型
        $data["bill_type"] = "MCHT";
        // 填充数组
        $data = $this->fillOrderData($data);
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }

    /**
     * 发送裂变红包
     * @param $param
     * @return string
     * @throws \Exception
     */
    public function sendgroupredpack($param)
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
        // 订单号
        $data["mch_billno"] = $param["order_num"];
        // 发送的openid
        $data["re_openid"] = $param["openid"];
        // 金额
        $data["total_amount"] = $param["order_money"] * 100;
        // 发放人数
        $data["total_num"] = $param["order_people_num"];
        // 发放ip地址
        $data["client_ip"] = $param["ip_address"];
        // 祝福语
        $data["wishing"] = $param["wishing"];
        // 红包金额设置方式：ALL_RAND（由微信进行随机分配）
        $data["amt_type"] = $param["amt_type"];
        // 活动名称
        $data["act_name"] = $param["activity_name"];
        // 备注
        $data["remark"] = $param["remark"];
        // 填充数组
        $data = $this->fillRedEnvelopeData($data);
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        // 返回结果
        return $this->responseDispose($url, $data, "POST", true);
    }


    /**
     * 普通红包
     * @param $param
     * @return string
     * @throws \Exception
     */
    public function sendredpack($param)
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
        // 订单号
        $data["mch_billno"] = $param["order_num"];
        // 发送的openid
        $data["re_openid"] = $param["openid"];
        // 金额
        $data["total_amount"] = $param["order_money"] * 100;
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
        $data = $this->fillRedEnvelopeData($data);
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
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
     * @param $param
     * @return string
     * @throws \Exception
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
     * jsApi 下订单
     * @param $param
     * @return string
     * @throws \Exception
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
     * @throws \Exception
     */
    public function closeOrder($param)
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
        // 查询订单类型，和相对应的订单号
        $data["out_trade_no"] = $param["order_num"];
        // 填充数组
        $data = $this->fillOrderData($data);
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/closeorder";
        // 返回结果
        return $this->responseDispose($url, $data);
    }

    /**
     * 刷卡支付
     * @param $param
     * @return string
     * @throws \Exception
     */
    public function microOrder($param)
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
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
        // 填充数组
        $data = $this->fillOrderData($data);
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/micropay";
        // 返回结果
        return $this->responseDispose($url, $data);
    }


    /**
     * 统一下单接口
     * @param array $param
     * @return string
     * @throws \Exception
     */
    public function unifiedOrder($param)
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
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
        // 填充数组
        $data = $this->fillOrderData($data);
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        // 返回结果
        return $this->responseDispose($url, $data, "post", false);
    }


    /**
     * 订单查询接口
     * order_num_type 有两个值：
     *          transaction_id(微信订单号) 和 out_trade_no(商户订单号)
     * @param array $param
     * @return string
     * @throws \Exception
     */
    public function orderQuery($param = [])
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
        // 查询订单类型，和相对应的订单号
        $data[$param["order_num_type"]] = $param["order_num"];
        // 填充数组
        $data = $this->fillOrderData($data);
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
        // 返回结果
        return $this->responseDispose($url, $data);
    }


    /**
     * 退款接口
     * order_num_type 有两个值：
     *          transaction_id(微信订单号) 和 out_trade_no(商户订单号)
     * @param array $param
     * @return string
     * @throws \Exception
     */
    public function refund($param = [])
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
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
        // 填充数组
        $data = $this->fillOrderData($data);
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
     * @param array $param
     * @return string
     * @throws \Exception
     */
    public function refundQuery($param = [])
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
        // 查询订单类型，和相对应的订单号
        $data[$param["order_num_type"]] = $param["order_num"];
        // 填充数组
        $data = $this->fillOrderData($data);
        // 接口地址
        $url = "https://api.mch.weixin.qq.com/pay/refundquery";
        // 返回结果
        return $this->responseDispose($url, $data);
    }


    /**
     * 下载对账单
     * @param array $param
     * @return string
     * @throws \Exception
     */
    public function downloadBill($param)
    {
        // 请求参数处理
        $param = $this->requestDispose($param);
        // 对账日期
        $data["bill_date"] = $param["bill_date"];
        // 对账类型
        $data["bill_type"] = $param["bill_type"];
        // 填充数组
        $data = $this->fillOrderData($data);
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
        $fileName = "./uploads/download.csv";
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

    public function fillTransfersData($param)
    {
        $param["mch_appid"] = $this->appId;
        $param["mch_id"] = $this->mchId;
        $param["nonce_str"] = $this->nonceStr();
        $param["sign"] = $this->signature($param);
        return $param;
    }

    /**
     * 填充企业支付到银行数据
     * @param $param
     * @return mixed
     */
    public function fillSptransData($param)
    {
        $param["mch_id"] = $this->mchId;
        $param["nonce_str"] = $this->nonceStr();
        $param["sign"] = $this->signature($param);
        return $param;
    }


    /**
     * 填充红包数据
     * @param $param
     * @return mixed
     */
    public function fillRedEnvelopeData($param)
    {
        $param["wxappid"] = $this->appId;
        $param["mch_id"] = $this->mchId;
        $param["send_name"] = $this->mchName;
        $param["nonce_str"] = $this->nonceStr();
        $param["sign"] = $this->signature($param);
        return $param;
    }


    /**
     * 填充订单数据
     * @param $param
     * @return mixed
     */
    public function fillOrderData($param)
    {
        $param["appid"] = $this->appId;
        $param["mch_id"] = $this->mchId;
        $param["sign_type"] = "MD5";
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
     * 请求数据处理
     * @param $param
     * @return mixed
     */
    public function requestDispose($param)
    {
        $res = json_decode($param, true);
        return $res;
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

    /**
     * 生成二维码
     * @param $url
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

        $qrCode->writeFile("./uploads/pay_qr_code.png");
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
        $bad_result = json_decode($json, TRUE);  // value，一个字段多次出现，结果中的value是数组
        // return $bad_result;
        foreach ($bad_result as $k => $v) {
            if (is_array($v)) {
                if (count($v) == 0) {
                    $result[$k] = '';
                } else if (count($v) == 1) {
                    $result[$k] = $v[0];
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

    // +----------------------------------------------------------------------
    // | End - 公用方法
    // +----------------------------------------------------------------------

    // +----------------------------------------------------------------------
    // | Start - 公共例子
    // +----------------------------------------------------------------------
    public function demo()
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
//        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
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