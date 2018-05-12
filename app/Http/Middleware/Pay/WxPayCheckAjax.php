<?php
/**
 * 检测中间件
 */

namespace App\Http\Middleware\Retail;

use App\Models\Account;
use App\Models\Program;
use Closure;
use Session;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Request;

class WxPayCheckAjax
{
    public function handle($request, Closure $next)
    {
        $route_name = $request->path();                 //获取当前的页面路由
        switch ($route_name) {
            case "retail/ajax/login_check":             //检测登录数据提交
                $re = $this->checkLoginPost($request);
                return self::format_response($re, $next);
                break;


            /****检测是否登录 权限 安全密码****/
            case "retail/ajax/store_edit_check":        //店铺信息编辑弹窗页面
            case "retail/ajax/category_delete_check":   //检测是否登录 权限 安全密码
            case "retail/ajax/goods_delete_check":      //检测是否登录 权限 安全密码
            case "retail/ajax/goods_status_check":      //检测是否登录 权限 安全密码
            case "retail/ajax/order_status_check":      //检测是否登录 权限 安全密码
            case "retail/ajax/order_status_paytype_check"://检测是否登录 权限 安全密码
            case "retail/ajax/subordinate_lock_check":  //检测是否登录 权限 安全密码
            case "retail/ajax/user_list_lock_check":    //检测是否登录 权限 安全密码--冻结粉丝标签
            case "retail/ajax/purchase_list_confirm_check":   //审核订单安全密码确认
            case "retail/ajax/loss_list_confirm_check":       //审核订单安全密码确认
            case "retail/ajax/check_list_confirm_check":      //审核订单安全密码确认
            case "retail/ajax/supplier_delete_check":         //进销存管理--删除供应商确认
            case "retail/ajax/dispatch_add_check":            //运费模板--添加运费模板安全密码检测
            case "retail/ajax/goods_thumb_delete_check":     //商品图片删除--检测登录安全密码和权限
            case "retail/ajax/dispatch_list_lock_check":     //启用、弃用运费模板确认
            case "retail/ajax/dispatch_list_delete_check":   //运费模板删除确认操作
            case "retail/ajax/shengpay_apply_check":            // 终端机器号重新申请功能提交
            case "retail/ajax/shengpay_delete_check":            // 终端机器号解除绑定功能提交
            case "retail/ajax/payconfig_delete_check":           // 付款信息解除绑定功能提交
            case "retail/ajax/payconfig_apply_check":            // 付款信息重新申请功能提交
                $re = $this->checkLoginAndRuleAndSafe($request);
                return self::format_response($re, $next);
                break;
            /****检测是否登录 权限 安全密码****/


            /*********下级人员添加*********/
            case "retail/ajax/subordinate_add_check"://检测 登录 和 权限 和 安全密码 和 添加下级人员的数据提交
                $re = $this->checkLoginAndRuleAndSafeAndSubordinateAdd($request);
                return self::format_response($re, $next);
                break;
            /*********下级人员添加*********/

            /*********下级人员编辑*********/
            case "retail/ajax/subordinate_edit_check"://检测 登录 和 权限 和 安全密码 和 编辑下级人员的数据提交
                $re = $this->checkLoginAndRuleAndSafeAndSubordinateEdit($request);
                return self::format_response($re, $next);
                break;
            /*********下级人员编辑*********/

            /*********供应商添加和编辑*********/
            case "retail/ajax/supplier_add_check"://检测登录，权限，及添加栏目分类的数据
            case "retail/ajax/supplier_edit_check"://检测登录，权限，及编辑栏目分类的数据
                $re = $this->checkLoginAndRuleAndSupplier($request);
                return self::format_response($re, $next);
                break;
            /*********供应商添加和编辑*********/


            /*********栏目分类添加和编辑*********/
            case "retail/ajax/category_add_check"://检测登录，权限，及添加栏目分类的数据
            case "retail/ajax/category_edit_check"://检测登录，权限，及编辑栏目分类的数据
                $re = $this->checkLoginAndRuleAndCategoryAdd($request);
                return self::format_response($re, $next);
                break;
            /*********栏目分类添加和编辑*********/

            /*********商品添加和商品编辑*********/
            case "retail/ajax/goods_add_check"://检测登录，权限，及添加商品的数据
            case "retail/ajax/goods_edit_check"://检测登录，权限，及编辑商品的数据
                $re = $this->checkLoginAndRuleAndGoodsAdd($request);
                return self::format_response($re, $next);
                break;
            /*********商品添加和商品编辑*********/

            /*********进销存商品选择列表*********/
            case "retail/ajax/search_company":          //检测登录，权限，及供应商搜索的数据处理
                $re = $this->checkLoginAndRuleAndSearchCompany($request);
                return self::format_response($re, $next);
                break;
            /*********进销存商品选择列表*********/

            /*********进销存--供应商到货开单处理*********/
            case "retail/ajax/purchase_goods_check"://检测登录，权限，及供应商到货开单的数据处理
                $re = $this->checkLoginAndRuleAndPurchaseGoods($request);
                return self::format_response($re, $next);
                break;
            /*********进销存--供应商到货开单处理*********/

            /*********进销存--报损开单处理*********/
            case "retail/ajax/loss_goods_check"://检测登录，权限，及报损开单的数据
            case "retail/ajax/check_goods_check"://检测登录，权限，及盘点开单的数据
                $re = $this->checkLoginAndRuleAndLossAndCheckGoods($request);
                return self::format_response($re, $next);
                break;
            /*********进销存--报损开单处理*********/

            /****粉丝信息编辑****/
            case "retail/ajax/user_list_edit_check"://检测 登录 和 权限 和 安全密码 和 用户编辑数据提交
                $re = $this->checkLoginAndRuleAndSafeAndUserEdit($request);
                return self::format_response($re, $next);
            /****粉丝信息编辑****/


            /****支付设置****/
            case "retail/ajax/payconfig_check":    // 收款信息设置数据监测
            case "retail/ajax/payconfig_edit_check":    // 收款信息设置数据监测
                $re = $this->checkLoginAndRuleAndPayconfig($request);
                return self::format_response($re, $next);
                break;
            case "retail/ajax/shengpay_add_check":            // 添加终端机器号信息功能提交
            case "retail/ajax/shengpay_edit_check":
                $re = $this->checkLoginAndRuleAndShengpayAdd($request);
                return self::format_response($re, $next);
                break;

            /****粉丝信息编辑****/


        }
    }


    public function checkRoleAddAndEdit($request)
    {
        if (empty($request->input('role_name'))) {
            return self::res(0, response()->json(['data' => '角色名称不能为空', 'status' => '0']));
        }
        return self::res(1, $request);
    }
}