<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'api/wechat/response/*',
        'api/wechat/open',

        /****Android接口****/
        'api/androidapi/login',//登入
        'api/androidapi/simple_login',//登入
        'api/androidapi/goodscategory',//商品分类
        'api/androidapi/goodslist',//商品列表
        'api/androidapi/order_check',//订单提交接口
        'api/androidapi/cancel_order',//取消订单接口
        'api/androidapi/order_list',//订单列表接口
        'api/androidapi/order_detail',//订单详情接口
        'api/androidapi/cash_payment',//现金支付接口
        'api/androidapi/other_payment',//其他支付接口
        'api/androidapi/allow_zero_stock',//开启/关闭零库存开单接口
        'api/androidapi/change_stock_role',//下单减库存/付款减库存接口
        'api/androidapi/stock_cfg',//查询店铺设置
        /****Android接口****/

        /****Android接口****/
        'api/androidSimpleApi/login',//登入
        'api/androidSimpleApi/simple_login',//登入
        'api/androidSimpleApi/goodscategory',//商品分类
        'api/androidSimpleApi/goodslist',//商品列表
        'api/androidSimpleApi/order_check',//订单提交接口
        'api/androidSimpleApi/cancel_order',//取消订单接口
        'api/androidSimpleApi/order_list',//订单列表接口
        'api/androidSimpleApi/order_detail',//订单详情接口
        'api/androidSimpleApi/cash_payment',//现金支付接口
        'api/androidSimpleApi/other_payment',//其他支付接口
        'api/androidSimpleApi/allow_zero_stock',//开启/关闭零库存开单接口
        'api/androidSimpleApi/change_stock_role',//下单减库存/付款减库存接口
        'api/androidSimpleApi/stock_cfg',//查询店铺设置
        /****Android接口****/

        /****wechat接口****/
        'api/wechatApi/store_list',//店铺列表接口
        'api/wechatApi/category',//店铺分类列表接口
        'api/wechatApi/goods_list',//店铺商品列表接口
        'api/wechatApi/shopping_cart_add',//购物车添加商品
        'api/wechatApi/shopping_cart_reduce',//购物车减少商品
        'api/wechatApi/shopping_cart_list',//购物车列表
        /****wechat接口****/

    ];
}
