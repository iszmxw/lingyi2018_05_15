<?php
/**
 * 检测是否登录的中间件
 */

namespace App\Http\Middleware\Api;

use App\Models\Account;
use Closure;
use Session;
use Illuminate\Support\Facades\Redis;

class AndroidRetailApiCheck
{
    public function handle($request, Closure $next)
    {
        $route_name = $request->path();//获取当前的页面路由
        switch ($route_name) {
            /*****登录*****/
            case "api/androidRetailApi/login"://检测登入提交数据
                $re = $this->checkLogin($request);
                return self::format_response($re, $next);
                break;
            /****登录****/
            case "api/androidRetailApi/goodscategory"://检测Token和商品分类数据
                $re = $this->checkTokenAndGoodsCategoryData($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/goodslist"://检测Token和商品列表数据
                $re = $this->checkTokenAndGoodsListData($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/order_check"://检测Token和订单提交数据
                $re = $this->checkTokenAndOrderCheck($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/cancel_order"://检测Token和关闭订单
                $re = $this->checkTokenAndCancelOrder($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/order_list"://检测Token和订单列表
                $re = $this->checkTokenAndOrderList($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/order_detail"://检测Token和订单详情
                $re = $this->checkTokenAndOrderDetail($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/cash_payment"://检测Token和现金支付数据
                $re = $this->checkTokenAndCashPayment($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/other_payment"://检测Token和其他支付数据
                $re = $this->checkTokenAndOtherPayment($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/allow_zero_stock"://检测Token和开启/关闭零库存开单
                $re = $this->checkTokenAndAllowZeroStock($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/change_stock_role"://检测Token和下单减库存/付款减库存
                $re = $this->checkTokenAndChangeStockRole($request);
                return self::format_response($re, $next);
                break;
            case "api/androidRetailApi/stock_cfg"://检测Token和下单减库存/付款减库存
                $re = $this->checkTokenAndStockCfg($request);
                return self::format_response($re, $next);
                break;
        }
        return $next($request);
    }


    /******************************复合检测*********************************/

    /**
     * 检测token值 And 商品列表接口店铺id是否为空
     */
    public function checkTokenAndGoodsCategoryData($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkRetailId($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /**
     * 检测token值 And 商品列表接口店铺id是否为空
     */
    public function checkTokenAndGoodsListData($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkRetailId($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /**
     * 检测token值 And 提交订单接口
     */
    public function checkTokenAndOrderCheck($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkOrderCheck($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /**
     * 检测token值 And 取消订单接口店铺id是否为空
     */
    public function checkTokenAndCancelOrder($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkCancelOrder($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /**
     * 检测token值 And 订单列表接口
     */
    public function checkTokenAndOrderList($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkRetailId($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /**
     * 检测token值 And 订单详情
     */
    public function checkTokenAndOrderDetail($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkOrderDetail($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    /**
     * 检测token值 And 现金支付接口数据是否为空
     */
    public function checkTokenAndCashPayment($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkCashPayment($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /**
     * 检测token值 And 现金支付接口数据是否为空
     */
    public function checkTokenAndOtherPayment($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkOtherPayment($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    /**
     * 检测token值 And 开启/关闭零库存开单
     */
    public function checkTokenAndAllowZeroStock($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkAllowZeroStock($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /**
     * 检测token值 And 下单减库存/付款减库存
     */
    public function checkTokenAndChangeStockRole($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkChangeStockRole($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /**
     * 检测token值 And 查询店铺设置
     */
    public function checkTokenAndStockCfg($request)
    {
        $re = $this->checkToken($request);//判断Token值是否正确
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkRetailId($re['response']);//检测数据提交
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /******************************单项检测*********************************/

    /**
     * 普通页面检测用户是否登录
     */
    public function checkLogin($request)
    {
        if (empty($request->input('account'))) {
            return self::res(0, response()->json(['msg' => '请输入用户名', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('password'))) {
            return self::res(0, response()->json(['msg' => '请输入密码', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 普通页面检测商品列表接口数据是否为空
     */
    public function checkRetailId($request)
    {
        if (empty($request->input('organization_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }


    /**
     * 普通页面检测提交订单接口数据是否为空
     */
    public function checkOrderCheck($request)
    {
        if (empty($request->input('organization_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty(json_decode($request->input('goodsdata'), TRUE))) {
            return self::res(0, response()->json(['msg' => '商品数据不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 普通页面检测取消订单接口数据是否为空
     */
    public function checkCancelOrder($request)
    {
        if (empty($request->input('organization_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('order_id'))) {
            return self::res(0, response()->json(['msg' => '订单id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }


    /**
     * 普通页面检测订单详情接口数据是否为空
     */
    public function checkOrderDetail($request)
    {
        if (empty($request->input('organization_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('order_id'))) {
            return self::res(0, response()->json(['msg' => '订单id不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }


    /**
     * 普通页面检测现金支付接口数据是否为空
     */
    public function checkCashPayment($request)
    {
        if (empty($request->input('organization_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('order_id'))) {
            return self::res(0, response()->json(['msg' => '订单id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('paytype'))) {
            return self::res(0, response()->json(['msg' => '支付方式不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 普通页面检测其他支付接口数据是否为空
     */
    public function checkOtherPayment($request)
    {
        if (empty($request->input('organization_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('order_id'))) {
            return self::res(0, response()->json(['msg' => '订单id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('paytype'))) {
            return self::res(0, response()->json(['msg' => '支付方式不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('payment_company'))) {
            return self::res(0, response()->json(['msg' => '支付公司名字不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }


    /**
     * 普通页面检测开启/关闭零库存开单接口数据是否为空
     */
    public function checkAllowZeroStock($request)
    {
        if (empty($request->input('organization_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('cfg_value'))) {
            return self::res(0, response()->json(['msg' => 'cfg_value值不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }

    /**
     * 普通页面检测开启/关闭零库存开单接口数据是否为空
     */
    public function checkChangeStockRole($request)
    {
        if (empty($request->input('organization_id'))) {
            return self::res(0, response()->json(['msg' => '店铺id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('cfg_value'))) {
            return self::res(0, response()->json(['msg' => 'cfg_value值不能为空', 'status' => '0', 'data' => '']));
        }
        return self::res(1, $request);
    }


    /**
     * 检测token值
     */
    public function checkToken($request)
    {
        if (empty($request->input('account_id'))) {
            return self::res(0, response()->json(['msg' => '用户id不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('timestamp'))) {
            return self::res(0, response()->json(['msg' => '当前时间戳不能为空', 'status' => '0', 'data' => '']));
        }
        if (empty($request->input('token'))) {
            return self::res(0, response()->json(['msg' => 'token值不能为空', 'status' => '0', 'data' => '']));
        }
//        if(time() - ($request->input('timestamp')/1000)>120){//如果超过两分钟
//            return self::res(0, response()->json(['msg' => '访问超时', 'status' => '0', 'data' => '']));
//        }

        $account_id = $request->account_id;//用户账号id
        $token = $request->token;//店铺令牌
        $timestamp = $request->timestamp;//app传过来的时间戳
        $data = Account::where([['id', $account_id]])->first();//查询用户信息
        if (empty($data)) {
            return self::res(0, response()->json(['msg' => '用户不存在', 'status' => '0', 'data' => '']));
        }
        $sort = array($data['account'], $timestamp);
        ksort($sort);//字典排序
        $store_token = '';
        foreach ($sort as $key => $value) {//拼接token
            $store_token .= $value;
        }
        $store_token = base64_encode($store_token . $data['uuid']) . 'lingyi2018';//第一次加密
        $store_token = md5($store_token);//第二次加密
        if ($store_token != $token) {
            return self::res(0, response()->json(['msg' => 'token值不正确,无权访问', 'status' => '0', 'data' => '']));
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

?>