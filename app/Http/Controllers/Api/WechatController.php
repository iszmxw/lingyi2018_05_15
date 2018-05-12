<?php
/**
 * Wechat接口
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\WechatWebAuthorization;
use App\Services\Curl\HttpCurl;
use Session;

class WechatController extends Controller

{
    /**
     * 店铺列表
     */
    public function display(Request $request)
    {
//        {{session("zerone_auth_info.organization_id")}} --->fansnamage_id
//
//        {{session("zerone_auth_info.zerone_user_id")}} --->zerone_user_id
//
//        {{session("zerone_auth_info.shop_user_id")}} ---->user_id
//
//        {{session("store_id")}} ---->store_id

        // 获取微信公众号JSSDK 凭证
        $this->getSignPackage();
        // 赋值
        $zerone_jssdk_info = (request()->get("zerone_jssdk_info"));
        // 渲染页面
        return view('Simple/Wechat/display', ['appId' => $zerone_jssdk_info['appId'], 'nonceStr' => $zerone_jssdk_info['nonceStr'], 'timestamp' => $zerone_jssdk_info['timestamp'], 'signature' => $zerone_jssdk_info['signature']]);
    }

    /**
     * 店铺列表
     */
    public function goodslist(Request $request)
    {
        $store_id = $request->store_id;

        Session::put('store_id', $store_id);

        $organization_name = Organization::getPluck([['id', $store_id]], 'organization_name');

        Session::put('organization_name', $organization_name);

        // 渲染页面
        return view('Simple/Wechat/goodslist');
    }

    /**
     * 确定订单
     */
    public function online_order(Request $request)
    {
        $status = $request->status;
        // 真实姓名
        $realname = $request->realname;
        // 性别
        $selftake_id = $request->selftake_id;
        // 手机号
        $mobile = $request->mobile;
        // 默认取货信息 1为默认

        // 渲染页面
        return view('Simple/Wechat/online_order', ['status' => $status, 'selftake_id' => $selftake_id, 'mobile' => $mobile, 'realname' => $realname]);
    }

    /**
     * 确定订单
     */
    public function selftake_order(Request $request)
    {
        // 渲染页面
        return view('Simple/Wechat/selftake_order');
    }

    /**
     * 添加收货地址
     */
    public function address_add(Request $request)
    {
        // 渲染页面
        return view('Simple/Wechat/address_add');
    }

    /**
     * 收货地址列表
     */
    public function address_list(Request $request)
    {
        // 渲染页面
        return view('Simple/Wechat/address_list');
    }

    /**
     * 添加自取信息
     */
    public function selftake_add(Request $request)
    {
        // 渲染页面
        return view('Simple/Wechat/selftake_add');
    }

    /**
     * 添加自取信息
     */
    public function selftake_edit(Request $request)
    {
        // 渲染页面
        return view('Simple/Wechat/selftake_edit');
    }

    /**
     * 自取信息列表
     */
    public function selftake_list(Request $request)
    {
        // 渲染页面
        return view('Simple/Wechat/selftake_list');
    }

    /**
     * 订单详情
     */
    public function onlineOrderDetail(Request $request)
    {
        // 渲染页面
        return view('Simple/Wechat/onlineOrderDetail');
    }

    /**
     * 订单详情
     */
    public function selftakeOrderDetail(Request $request)
    {
        // 渲染页面
        return view('Simple/Wechat/selftakeOrderDetail');
    }


    /**
     * 获取 wx.config 里面的签名,JSSDk 所需要的
     */
    public function getSignPackage()
    {
        $wxid = "gh_c548784211ab";
        $wechat_config = WechatWebAuthorization::getWechatConfig($wxid);
        $res = WechatWebAuthorization::updateWechatVoucher($wechat_config, ["jssdk"]);

        // 设置得到签名的参数
        $url = request()->fullUrl();
        $timestamp = time();
        $nonceStr = substr(md5(time()), 0, 16);
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket={$res["jsapi_ticket"]}&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array("appId" => $res["appid"], "nonceStr" => $nonceStr, "timestamp" => $timestamp, "url" => $url, "rawString" => $string, "signature" => $signature);

        request()->attributes->add(['zerone_jssdk_info' => $signPackage]);
    }


}