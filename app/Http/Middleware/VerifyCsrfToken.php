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
        'api/androidRetailApi/login',//登入
        'api/androidRetailApi/goodscategory',//商品分类
        'api/androidRetailApi/goodslist',//商品列表
        'api/androidRetailApi/order_check',//订单提交接口
        'api/androidRetailApi/cancel_order',//取消订单接口
        'api/androidRetailApi/order_list',//订单列表接口
        'api/androidRetailApi/order_detail',//订单详情接口
        'api/androidRetailApi/cash_payment',//现金支付接口
        'api/androidRetailApi/other_payment',//其他支付接口
        'api/androidRetailApi/allow_zero_stock',//开启/关闭零库存开单接口
        'api/androidRetailApi/change_stock_role',//下单减库存/付款减库存接口
        'api/androidRetailApi/stock_cfg',//查询店铺设置
        'api/androidRetailApi/code',//查询店铺设置
        /****Android接口****/

        /****Android接口****/
        'api/androidSimpleApi/login',//登入
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
        'api/wechatApi/address',//默认用户收货地址
        'api/wechatApi/selftake',//默认用户取货信息
        'api/wechatApi/address_add',//添加用户收货地址
        'api/wechatApi/address_list',//用户收货地址列表
        'api/wechatApi/address_edit',//编辑用户收货地址
        'api/wechatApi/address_delete',//删除用户收货地址
        'api/wechatApi/address_status',//设置为默认收货地址
        'api/wechatApi/selftake_add',//用户添加取货信息
        'api/wechatApi/selftake_list',//用户取货信息列表
        'api/wechatApi/selftake_edit',//编辑用户取货信息
        'api/wechatApi/selftake_delete',//删除用户取货信息
        'api/wechatApi/selftake_status',//设置为默认取货信息
        'api/wechatApi/shopping_cart_empty',//清空购物车
        'api/wechatApi/order_submit',//提交订单
        'api/wechatApi/online_order_detail',//线上订单详情
        'api/wechatApi/online_order_list',//线上订单列表
        'api/wechatApi/selftake_order_list',//自取订单列表
        'api/wechatApi/selftake_order_detail',//自取订单详情
        'api/wechatApi/cancel_online_order',//取消线上订单
        'api/wechatApi/cancel_selftake_order',//取消自取订单
        'api/wechatApi/selftake_info',//取消自取订单
        'api/wechatApi/select_address',//选择收货地址
        'api/wechatApi/address_info',//单条收货地址
        'api/wechatApi/dispatch_mould',//查询运费价格
        /****wechat接口****/


        /****微信支付接口****/

        "pay/wx/downloadBill",
        "pay/wx/pay_bank",
        "pay/wx/query_bank",
        "pay/wx/transfers",
        "pay/wx/gettransferinfo",
        "pay/wx/sendredpack",
        "pay/wx/sendgroupredpack",
        "pay/wx/gethbinfo",
        "pay/wx/nativeOrder",
        "pay/wx/jsApiOrder",
        "pay/wx/unifiedOrder",
        "pay/wx/closeOrder",
        "pay/wx/microOrder",
        "pay/wx/orderQuery",
        "pay/wx/refund",
        "pay/wx/refundQuery",
        "pay/wx/downloadBill"

        /****微信支付接口****/
    ];
}
