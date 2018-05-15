<?php
/**
 * 检测是否登录的中间件
 */

namespace App\Http\Middleware\Api;

use App\Models\Account;
use App\Models\WechatAuthorization;
use App\Models\XhoLog;
use Closure;
use Session;
use Illuminate\Support\Facades\Redis;

class WechatApiCheck
{
    public function handle($request, Closure $next)
    {
        // 获取当前的页面路由
        $route_name = $request->path();
        switch ($route_name) {
            case "api/wechatApi/store_list"://检测店铺列表提交数据
                $re = $this->checkStoreList($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/category"://检测店铺分类提交数据
            case "api/wechatApi/goods_list"://检测店铺分类提交数据
                $re = $this->checkStoreIdAndFansmanageId($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/shopping_cart_add"://检测店铺购物车商品添加提交数据
            case "api/wechatApi/shopping_cart_reduce"://检测店铺购物车商品减少提交数据
                $re = $this->checkShoppingCartAddAndReduce($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/shopping_cart_list"://检测店铺购物车列表提交数据
            case "api/wechatApi/shopping_cart_empty"://检测店铺购物车列表提交数据
                $re = $this->checkFourId($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/address"://默认地址信息
                $re = $this->checkZeroneId($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/address_add"://检测添加收货地址提交数据
                $re = $this->checkAddressAdd($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/address_info"://单条地址数据
                $re = $this->checkaddressInfo($request);
                return self::format_response($re, $next);
                break;

            case "api/wechatApi/address_edit"://检测编辑收货地址提交数据
                $re = $this->checkAddressEdit($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/address_delete"://检测编辑收货地址提交数据
                $re = $this->checkAddressDelete($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/address_status"://检测设置为默认收货地址提交数据
                $re = $this->checkAddressStatus($request);
                return self::format_response($re, $next);
                break;

            case "api/wechatApi/selftake"://用户默认取货信息
            case "api/wechatApi/address_list"://检测添加收货地址提交数据
            case "api/wechatApi/selftake_list"://检测添加收货地址提交数据
            case "api/wechatApi/select_address"://选择收货地址
                $re = $this->checkZeroneUserId($request);
                return self::format_response($re, $next);
                break;

            case "api/wechatApi/selftake_add"://检测添加取货信息提交数据
                $re = $this->checkSelftakeAdd($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/selftake_edit"://检测编辑取货信息提交数据
                $re = $this->checkselftakeEdit($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/selftake_delete"://删除编辑取货信息提交数据
                $re = $this->checkselftakeDelete($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/selftake_status"://删除编辑取货信息提交数据
            case "api/wechatApi/selftake_info"://删除编辑取货信息提交数据
            $re = $this->checkselftakeStatus($request);
                return self::format_response($re, $next);
                break;

            case "api/wechatApi/order_submit"://删除编辑取货信息提交数据
                $re = $this->checkOrderSubmit($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/online_order_list"://线上订单列表
            case "api/wechatApi/selftake_order_list"://自取订单列表
                $re = $this->checkOnlineOrderList($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/online_order_detail"://线上订单详情
            case "api/wechatApi/selftake_order_detail"://自取订单详情
            case "api/wechatApi/cancel_online_order"://取消线上订单
            case "api/wechatApi/cancel_selftake_order"://取消自取订单
                $re = $this->checkOnlineOrderDetail($request);
                return self::format_response($re, $next);
                break;
            case "api/wechatApi/dispatch_mould"://运费模板
                $re = $this->checkDispatchMould($request);
                return self::format_response($re, $next);
                break;
        }
        return $next($request);
    }


    /******************************单项检测*********************************/

    /**
     * 店铺列表数据提交检测
     */
    public function checkStoreList($request)
    {

        if (empty(request()->get('organization_id'))) {
            return self::res(0, response()->json(['msg' => '联盟主id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('lat'))) {
            return self::res(0, response()->json(['msg' => '微信地理位置纬度不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('lng'))) {
            return self::res(0, response()->json(['msg' => '微信地理位置经度不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }


    /**
     * 店铺分类列表数据提交检测
     */
    public function checkStoreIdAndFansmanageId($request)
    {
        if (empty($request->input('fansmanage_id'))) {
            return self::res(0, response()->json(['msg' => '联盟主id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('store_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 默认地址查询
     */
    public function checkZeroneId($request)
    {
        if (empty($request->input('zerone_user_id'))) {
            return self::res(0, response()->json(['msg' => '零壹id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }


    /**
     * 店铺分类列表数据提交检测
     */
    public function checkFourId($request)
    {
        if (empty($request->input('fansmanage_id'))) {
            return self::res(0, response()->json(['msg' => '联盟主id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('store_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('user_id'))) {
            return self::res(0, response()->json(['msg' => '用户id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('zerone_user_id'))) {
            return self::res(0, response()->json(['msg' => '零壹id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测店铺购物车商品添加或减少提交数据
     */
    public function checkShoppingCartAddAndReduce($request)
    {
        if (empty($request->input('store_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('fansmanage_id'))) {
            return self::res(0, response()->json(['msg' => '联盟主id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('goods_id'))) {
            return self::res(0, response()->json(['msg' => '商品id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('goods_price'))) {
            return self::res(0, response()->json(['msg' => '商品价格不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('goods_name'))) {
            return self::res(0, response()->json(['msg' => '商品名称不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('goods_thumb'))) {
            return self::res(0, response()->json(['msg' => '商品图片不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('num'))) {
            return self::res(0, response()->json(['msg' => '商品数量不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('stock'))) {
            return self::res(0, response()->json(['msg' => '商品库存不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测用户收货地址添加提交数据
     */
    public function checkAddressAdd($request)
    {
        if (empty($request->input('zerone_user_id'))) {
            return self::res(0, response()->json(['msg' => '用户零壹id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('address_info'))) {
            return self::res(0, response()->json(['msg' => '选择地区不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('address'))) {
            return self::res(0, response()->json(['msg' => '详细地址不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('realname'))) {
            return self::res(0, response()->json(['msg' => '收货人真实姓名不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('mobile'))) {
            return self::res(0, response()->json(['msg' => '手机号码不能为空', 'status' => '0', 'data' => '']));
        }
        $mobile = $request->input('mobile');
        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return self::res(0, response()->json(['data' => '请输入正确手机号码', 'status' => '0']));
        }
        if (empty($request->input('sex'))) {
            return self::res(0, response()->json(['msg' => '性别不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测收货地址信息
     */
    public function checkaddressInfo($request)
    {
        if (empty($request->input('address_id'))) {
            return self::res(0, response()->json(['msg' => '地址id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }


    /**
     * 检测用户收货地址编辑提交数据
     */
    public function checkAddressEdit($request)
    {
        if (empty($request->input('address_id'))) {
            return self::res(0, response()->json(['msg' => '地址id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('address_info'))) {
            return self::res(0, response()->json(['msg' => '选择地区不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('address'))) {
            return self::res(0, response()->json(['msg' => '详细地址不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('realname'))) {
            return self::res(0, response()->json(['msg' => '收货人真实姓名不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('mobile'))) {
            return self::res(0, response()->json(['msg' => '手机号码不能为空', 'status' => '0', 'data' => '']));
        }
        $mobile = $request->input('mobile');
        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return self::res(0, response()->json(['data' => '请输入正确手机号码', 'status' => '0']));
        }
        if (empty($request->input('sex'))) {
            return self::res(0, response()->json(['msg' => '性别不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测删除用户收货地址提交数据
     */
    public function checkAddressDelete($request)
    {
        if (empty($request->input('address_id'))) {
            return self::res(0, response()->json(['msg' => '地址id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测设置为默认收货地址提交数据
     */
    public function checkAddressStatus($request)
    {
        if (empty($request->input('address_id'))) {
            return self::res(0, response()->json(['msg' => '地址id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('zerone_user_id'))) {
            return self::res(0, response()->json(['msg' => '零壹id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测零壹id
     */
    public function checkZeroneUserId($request)
    {
        if (empty($request->input('zerone_user_id'))) {
            return self::res(0, response()->json(['msg' => '用户零壹id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测添加用户收货地址提交数据
     */
    public function checkSelftakeAdd($request)
    {
        if (empty($request->input('zerone_user_id'))) {
            return self::res(0, response()->json(['msg' => '用户零壹id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('realname'))) {
            return self::res(0, response()->json(['msg' => '取货人真实姓名不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('sex'))) {
            return self::res(0, response()->json(['msg' => '性别不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('mobile'))) {
            return self::res(0, response()->json(['msg' => '手机号码不能为空', 'status' => '0', 'data' => '']));
        }
        $mobile = $request->input('mobile');
        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return self::res(0, response()->json(['data' => '请输入正确手机号码', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测编辑用户收货地址提交数据
     */
    public function checkselftakeEdit($request)
    {
        if (empty($request->input('self_take_id'))) {
            return self::res(0, response()->json(['msg' => '取货信息ID不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('realname'))) {
            return self::res(0, response()->json(['msg' => '取货人真实姓名不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('sex'))) {
            return self::res(0, response()->json(['msg' => '性别不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('mobile'))) {
            return self::res(0, response()->json(['msg' => '手机号码不能为空', 'status' => '0', 'data' => '']));
        }
        $mobile = $request->input('mobile');
        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return self::res(0, response()->json(['data' => '请输入正确手机号码', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测删除用户收货地址提交数据
     */
    public function checkselftakeDelete($request)
    {
        if (empty($request->input('self_take_id'))) {
            return self::res(0, response()->json(['msg' => '取货信息ID不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('zerone_user_id'))) {
            return self::res(0, response()->json(['msg' => '用户零壹id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测删除用户收货地址提交数据
     */
    public function checkselftakeStatus($request)
    {
        if (empty($request->input('self_take_id'))) {
            return self::res(0, response()->json(['msg' => '取货信息ID不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('zerone_user_id'))) {
            return self::res(0, response()->json(['msg' => '用户零壹id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测订单提交数据
     */
    public function checkOrderSubmit($request)
    {
        print_r($request);exit;
        if (empty($request->input('user_id'))) {
            return self::res(0, response()->json(['msg' => '用户ID不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('zerone_user_id'))) {
            return self::res(0, response()->json(['msg' => '用户零壹id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('fansmanage_id'))) {
            return self::res(0, response()->json(['msg' => '联盟id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('store_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty(json_decode($request->input('goods_list'), TRUE))) {
            return self::res(0, response()->json(['msg' => '商品数据不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('shipping_type'))) {
            return self::res(0, response()->json(['msg' => '配送方式不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('stock_type'))) {
            return self::res(0, response()->json(['msg' => '库存扣减方式不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测线上订单列表
     */
    public function checkOnlineOrderList($request)
    {
        if (empty($request->input('user_id'))) {
            return self::res(0, response()->json(['msg' => '用户ID不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('fansmanage_id'))) {
            return self::res(0, response()->json(['msg' => '联盟id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('store_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 检测线上订单列表
     */
    public function checkDispatchMould($request)
    {
        if (empty($request->input('address_id'))) {
            return self::res(0, response()->json(['msg' => '地址ID不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('fansmanage_id'))) {
            return self::res(0, response()->json(['msg' => '联盟id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('store_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('weight'))) {
            return self::res(0, response()->json(['msg' => '商品重量不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }





    /**
     * 检测线上订单详情
     */
    public function checkOnlineOrderDetail($request)
    {
        if (empty($request->input('order_id'))) {
            return self::res(0, response()->json(['msg' => '订单ID不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }



    /**
     * 工厂方法返回结果
     */
    public static function res($status, $response)
    {
        return ['status' => $status, 'response' => $response];
    }

    /**
     * 格式化返回值
     */
    public static function format_response($re, Closure $next)
    {
        if ($re['status'] == '0') {
            return $re['response'];
        } else {
            return $next($re['response']);
        }
    }

}

