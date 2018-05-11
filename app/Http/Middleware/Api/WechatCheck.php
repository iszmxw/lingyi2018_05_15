<?php
/**
 * 检测是否登录的中间件
 */

namespace App\Http\Middleware\Api;

use App\Models\WechatAuthorization;
use Closure;

class WechatCheck
{
    public function handle($request, Closure $next)
    {
        // 获取当前的页面路由
        $route_name = $request->path();
        switch ($route_name) {
            case "zerone/wechat"://首页
            case "zerone/wechat/goodslist"://商品列表
            case "zerone/wechat/online_order"://线上订单确认
            case "zerone/wechat/selftake_order"://自取订单确认
            case "zerone/wechat/address_add"://添加收货地址
            case "zerone/wechat/address_list"://收货地址列表
            case "zerone/wechat/selftake_add"://添加自取信息
            case "zerone/wechat/selftake_edit"://编辑自取信息
            case "zerone/wechat/onlineOrderDetail"://线上订单详情
            case "zerone/wechat/selftakeOrderDetail"://线上订单详情
                $this->checkToken();
                break;
        }
        return $next($request);
    }


    /**
     * 进入所有页面的前置流程
     */
    public function checkToken()
    {
        // 判断是组织id是否存在
        if (empty(session("zerone_auth_info.organization_id"))) {
            // 获取组织id
            $organization_id = request()->get("organization_id");
        }else{
            $organization_id = session("zerone_auth_info.organization_id");
        }

        // 判断公众号是否授权给零壹第三方公众号平台
        $res = $this->getShopBaseInfo($organization_id);
        if ($res === false) {
            exit("微信公众号没有授权到第三方");
        }

        // 判断组织id 是否 跟之前一致
        if (!empty(session("zerone_auth_info.organization_id")) && session("zerone_auth_info.organization_id") != $organization_id) {
            \Session::put("zerone_auth_info", "");
        }

        session(["zerone_auth_info.organization_id" => $organization_id]);

        // 跳转自己的地址
        $self_path = ["api/authApi/zerone_auth", "api/authApi/shop_auth", "api/authApi/change_trains"];
        // 初次访问的地址
        $url = request()->fullUrl();
        if (!in_array(request()->path(), $self_path)) {
            session(["zerone_auth_info.initial_url_address" => $url]);
            \Session::save();
        }

        // 判断是否存在 地址
        if (empty(session("zerone_auth_info.initial_url_address"))) {
            Header("Location:" . $url);
            return;
        }

        // 刷新并获取授权令牌
        $authorization_info = \Wechat::refresh_authorization_info($organization_id);
        if ($authorization_info === false) {
            exit("微信公众号没有授权到第三方");
        }
        // 判断是否存在 零壹服务用户id
        if (empty(session("zerone_auth_info.zerone_user_id"))) {
            Header("Location:" . request()->root() . "/api/authApi/zerone_auth?initial_url_address=$url");
            return;
        }
        // 判断 session 中是否存在店铺id
        if (empty(session("zerone_auth_info.shop_user_id"))) {
            Header("Location:" . request()->root() . "/api/authApi/shop_auth?organization_id={$organization_id}");
            return;
        }

        // 添加参数
        request()->attributes->add(['zerone_auth_info' => session("zerone_auth_info")]);
    }

    /**
     * 获取店铺公众号的基本信息
     * @param $organization_id
     * @return bool
     */
    public function getShopBaseInfo($organization_id)
    {
        // 获取公众号的基本信息
        $res = WechatAuthorization::getAuthInfo(["organization_id" => $organization_id], ["authorizer_appid", "authorizer_access_token"]);
        // 判断公众号是否在零壹第三方平台授权过
        return $res;
    }

}

