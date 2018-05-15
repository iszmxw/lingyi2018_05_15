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

class RetailCheckAjax
{
    public function handle($request, Closure $next)
    {
        $route_name = $request->path();                 //获取当前的页面路由
        switch ($route_name) {
            case "retail/ajax/login_check":             //检测登录数据提交
                $re = $this->checkLoginPost($request);
                return self::format_response($re, $next);
                break;
            case "retail/ajax/retail_select":           //超级管理员选择分店提交数据
            case "retail/ajax/category_delete":         //栏目删除弹窗
            case "retail/ajax/category_edit":           //栏目编辑页面

            case "retail/ajax/subordinate_edit":        //编辑下属人员信息
            case "retail/ajax/subordinate_lock":        //冻结下属页面
            case "retail/ajax/goods_thumb":             //商品图片上传页面
            case "retail/ajax/goods_delete":            //商品删除弹窗
            case "retail/ajax/goods_status":            //商品状态修改弹窗
            case "retail/ajax/order_status":            //订单状态修改确认弹窗
            case "retail/ajax/order_status_paytype":    //订单状态修改以及邓丹支付方式确认弹窗
            case "retail/ajax/upload_thumb_check":      //上传图片
            case "retail/ajax/user_list_edit":          //会员列表编辑显示页面
            case "retail/ajax/user_list_lock":          //会员列表冻结显示页面
            case "retail/ajax/user_list_wallet":        //会员列表粉丝钱包显示页面
            case "retail/ajax/select_company":          //选择供应商处理
            case "retail/ajax/goods_list":              //检测登录，权限，及添搜索商品的数据
            case "retail/ajax/purchase_list_confirm":   //进货退货审核弹窗
            case "retail/ajax/supplier_delete":         //进销存管理--删除供应商弹窗
            case "retail/ajax/supplier_edit":           //进销存管理--编辑供应商
            case "retail/ajax/loss_list_confirm":       //报损订单审核弹窗
            case "retail/ajax/check_list_confirm":      //盘点订单审核弹窗
            case "retail/ajax/order_list_details":      //订单详细信息列表
            case "retail/ajax/goods_thumb_delete":      //删除图片弹窗确认
            case "retail/ajax/thumb_edit_displayorder": //修改图片排序

            case "retail/ajax/dispatch_province_add_check"://运费模板省份添加
            case "retail/ajax/dispatch_province_edit_check"://运费模板信息编辑
            case "retail/ajax/dispatch_province_delete_check"://运费模板信息删除
            case "retail/ajax/dispatch_list_lock":            //运费模板启用弹窗
            case "retail/ajax/dispatch_list_delete":          //运费模板删除确认弹窗

            case "retail/ajax/shengpay_edit":           //编辑终端机器号
            case "retail/ajax/shengpay_apply":          //终端机器号重新申请
            case "retail/ajax/shengpay_delete":         //终端机器号解除绑定
            case "retail/ajax/payconfig_edit":          //付款信息编辑
            case "retail/ajax/payconfig_apply":         //付款信息重新申请
            case "retail/ajax/payconfig_delete":        //付款信息删除
                $re = $this->checkLoginAndRule($request);
                return self::format_response($re, $next);
                break;
            case "retail/ajax/profile_edit_check":      //检测修改个人信息的数据以及登录，权限
                $re = $this->checkLoginAndRuleAndProfileEdit($request);
                return self::format_response($re, $next);
                break;
            case "retail/ajax/safe_password_edit_check"://检测修改安全密码的数据以及登录，权限
                $re = $this->checkLoginAndRuleAndSafepasswordEdit($request);
                return self::format_response($re, $next);
                break;
            case "retail/ajax/password_edit_check"://检测修改密码的数据以及登录，权限
                $re = $this->checkLoginAndRuleAndPasswordEdit($request);
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



    /******************************复合检测开始*********************************/
    //检测登录，权限，及修改密码的数据
    public function checkLoginPost($request)
    {
        if (empty($request->input('username'))) {
            return self::res(0, response()->json(['data' => '请输入用户名或手机号码', 'status' => '0']));
        }
        if (empty($request->input('password'))) {
            return self::res(0, response()->json(['data' => '请输入登录密码', 'status' => '0']));
        }
        if (empty($request->input('captcha'))) {
            return self::res(0, response()->json(['data' => '请输入验证码', 'status' => '0']));
        }
        if (Session::get('branch_system_captcha') == $request->input('captcha')) {
            //把参数传递到下一个程序
            return self::res(1, $request);
        } else {
            //用户输入验证码错误
            //return self::res(0, response()->json(['data' => '验证码错误', 'status' => '0']));
            return self::res(1, $request);
        }
    }

    //检测登录，权限，及添加编辑供应商的数据


    public static function res($status, $response)
    {
        return ['status' => $status, 'response' => $response];
    }

    //检测登录，权限，及添加栏目的数据

    public static function format_response($re, Closure $next)
    {
        if ($re['status'] == '0') {
            return $re['response'];
        } else {
            return $next($re['response']);
        }
    }

    //检测登录，权限，及添加商品的数据

    public function checkLoginAndRule($request)
    {
        $re = $this->checkIsLogin($request);//判断是否登录和是否具有权限
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkHasRule($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测登录，权限，及搜索供应商的数据

    public function checkIsLogin($request)
    {
        $sess_key = Session::get('retail_account_id');                  //获取用户登录存储的SessionId
        if (!empty($sess_key)) {
            $sess_key = Session::get('retail_account_id');              //获取管理员ID
            $sess_key = decrypt($sess_key);                             //解密管理员ID
            Redis::connect('retail');                                   //连接到我的缓存服务器
            $admin_data = Redis::get('retail_system_admin_data_' . $sess_key);//获取管理员信息
            $admin_data = unserialize($admin_data);                     //解序列我的信息
            $request->attributes->add(['admin_data' => $admin_data]);   //添加参数
            return self::res(1, $request);                        //把参数传递到下一个中间件
        } else {                                                        //如果为空跳转到登录页面
            return self::res(0, response()->json(['data' => '登录状态失效', 'status' => '-1']));
        }
    }

    //检测登录，权限，及进货退货开单的数据


    public function checkHasRule($request)
    {
        $admin_data = $request->get('admin_data');
        if ($admin_data['id'] <> 1 && $admin_data['is_super'] <> 1) {
            //暂定所有用户都有权限
            //return self::res(1,redirect('zerone'));
            $route_name = $request->path();//获取当前的页面路由

            //查询用户所具备的所有节点的路由
            $account_info = Account::getOne([['id', $admin_data['id']]]);
            $account_routes = [];
            foreach ($account_info->nodes as $key => $val) {
                $account_routes[] = $val->route_name;
            }

            //查询该程序下所有节点的路由
            $program_info = Program::getOne([['id', 10]]);
            $program_routes = [];
            foreach ($program_info->nodes as $key => $val) {
                $program_routes[] = $val->route_name;
            }

            //计算数组差集，获取用户所没有的权限
            $unset_routes = array_diff($program_routes, $account_routes);
            //如果跳转的路由不在该程序的所有节点中。则报错
            if (!in_array($route_name, $program_routes) && !in_array($route_name, config('app.retail_route_except'))) {
                return self::res(0, response()->json(['data' => '对不起，您不具备权限', 'status' => '-1']));
            }
            //如果没有权限，则报错
            if (in_array($route_name, $unset_routes)) {
                return self::res(0, response()->json(['data' => '对不起，您不具备权限', 'status' => '-1']));
            }
            return self::res(1, $request);
        } else {
            return self::res(1, $request);
        }
    }

    //检测登录，权限，及报损、盘点开单的数据

    public function checkLoginAndRuleAndProfileEdit($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录是否有权限以及安全密码
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkProfileEdit($re['response']);//检测修改的数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    //检测 登录 和 权限 和 安全密码 和粉丝用户管理编辑数据提交

    public function checkLoginAndRuleAndSafe($request)
    {
        $re = $this->checkLoginAndRule($request);//判断是否登录是否具有权限
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkSafePassword($re['response']);//检测安全密码数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    //检测 登录 和 权限 和 安全密码 和 添加下级人员的数据提交

    public function checkSafePassword($request)
    {
        $admin_data = $request->get('admin_data');          //获取管理员用户数据
        $safe_password = $request->input('safe_password');  //安全密码
        $account = Account::getOne(['id' => '1']);          //查询超级管理员的安全密码
        if ($admin_data['is_super'] == 1) {                 //如果是超级管理员获取零壹加密盐
            $safe_password_check = $account['safe_password'];
            $key = config("app.zerone_safe_encrypt_key");//获取加安全密码密盐（零壹平台专用）
        } else {
            $safe_password_check = $admin_data['safe_password'];
            $key = config("app.retail_safe_encrypt_key");//获取安全密码加密盐（零售专用）
        }
        $encrypted = md5($safe_password);                       //加密密码第一重
        $encryptPwd = md5("lingyikeji" . $encrypted . $key);//加密密码第二重
        if (empty($safe_password)) {
            return self::res(0, response()->json(['data' => '请输入安全密码', 'status' => '0']));
        }
        if (empty($admin_data['safe_password'])) {
            return self::res(0, response()->json(['data' => '您尚未设置安全密码，请先前往 个人中心 》安全密码设置 设置', 'status' => '0']));
        }
        if ($encryptPwd != $safe_password_check) {
            return self::res(0, response()->json(['data' => '您输入的安全密码不正确', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测 登录 和 权限 和 安全密码 和 添加下级人员的数据提交

    public function checkProfileEdit(Request $request)
    {
        if (empty($request->input('realname'))) {
            return self::res(0, response()->json(['data' => '请输入用户真实姓名', 'status' => '0']));
        }
        $mobile = $request->input('mobile');
        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return self::res(0, response()->json(['data' => '请输入正确手机号码', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测登录，权限，及修改安全密码的数据

    public function checkLoginAndRuleAndSafepasswordEdit($request)
    {
        $re = $this->checkLoginAndRule($request);//判断是否登录
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkSafepasswordEdit($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测登录，权限，及修改个人账号信息的数据

    public function checkSafepasswordEdit($request)
    {
        if (empty($request->input('is_editing'))) {
            return self::res(0, response()->json(['data' => '数据传输错误', 'status' => '0']));
        }
        if ($request->input('is_editing') == '-1') {//设置安全密码时
            if (empty($request->input('safe_password'))) {
                return self::res(0, response()->json(['data' => '请输入安全密码', 'status' => '0']));
            }
            if (empty($request->input('re_safe_password'))) {
                return self::res(0, response()->json(['data' => '请重复安全密码', 'status' => '0']));
            }
            if ($request->input('safe_password') <> $request->input('re_safe_password')) {
                return self::res(0, response()->json(['data' => '两次安全密码输入不一致', 'status' => '0']));
            }
        } elseif ($request->input('is_editing') == '1') {//修改安全密码时
            if (empty($request->input('old_safe_password'))) {
                return self::res(0, response()->json(['data' => '请输入旧安全密码', 'status' => '0']));
            }
            if (empty($request->input('safe_password'))) {
                return self::res(0, response()->json(['data' => '请输入新安全密码', 'status' => '0']));
            }
            if (empty($request->input('re_safe_password'))) {
                return self::res(0, response()->json(['data' => '请重复新安全密码', 'status' => '0']));
            }
            if ($request->input('safe_password') <> $request->input('re_safe_password')) {
                return self::res(0, response()->json(['data' => '两次安全密码输入不一致', 'status' => '0']));
            }
        }
        return self::res(1, $request);
    }

    // 添加终端机器号信息功能检测


    public function checkLoginAndRuleAndPasswordEdit($request)
    {
        $re = $this->checkLoginAndRule($request);//检测登录、权限
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkPasswordEdit($re['response']);//检测修改登录密码
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                $re3 = $this->checkSafePassword($re2['response']);//检测安全密码是否输入正确
                if ($re3['status'] == '0') {
                    return $re3;
                } else {
                    return self::res(1, $re3['response']);
                }
            }
        }
    }

    // 收款信息设置数据监测

    public function checkPasswordEdit($request)
    {
        if (empty($request->input('password'))) {
            return self::res(0, response()->json(['data' => '请输入原登录密码', 'status' => '0']));
        }
        if (empty($request->input('new_password'))) {
            return self::res(0, response()->json(['data' => '新登录密码不能为空', 'status' => '0']));
        }
        if (empty($request->input('news_password'))) {
            return self::res(0, response()->json(['data' => '请确认新登录密码是否一致', 'status' => '0']));
        }
        if ($request->input('new_password') != $request->input('news_password')) {
            return self::res(0, response()->json(['data' => '新密码和重复密码不一致', 'status' => '0']));
        }
        return self::res(1, $request);
    }


    /******************************复合检测结束*********************************/


    /*********************************通用单项检测开始*******************************************/
    //部分页面检测用户是否admin，否则检测是否有权限
    public function checkLoginAndRuleAndSafeAndSubordinateAdd($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//检测登录、权限和安全密码
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkSubordinateAdd($re['response']);//检测添加下级人员数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测是否登录

    public function checkSubordinateAdd($request)
    {
        if (empty($request->input('password'))) {
            return self::res(0, response()->json(['data' => '请输入用户登录密码', 'status' => '0']));
        }
        if (empty($request->input('repassword'))) {
            return self::res(0, response()->json(['data' => '请再次输入用户登录密码', 'status' => '0']));
        }
        if ($request->input('password') <> $request->input('repassword')) {
            return self::res(0, response()->json(['data' => '两次登录密码输入不一致', 'status' => '0']));
        }
        if (empty($request->input('realname'))) {
            return self::res(0, response()->json(['data' => '请输入用户真实姓名', 'status' => '0']));
        }
        $mobile = $request->input('mobile');
        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return self::res(0, response()->json(['data' => '请输入正确手机号码', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测登录和权限

    public function checkLoginAndRuleAndSafeAndSubordinateEdit($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//检测登录、权限和安全密码
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkSubordinateEdit($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    //检测登录和权限和安全密码

    public function checkSubordinateEdit($request)
    {
        if (empty($request->input('id'))) {
            return self::res(0, response()->json(['data' => '数据传输错误', 'status' => '0']));
        }
        if (empty($request->input('realname'))) {
            return self::res(0, response()->json(['data' => '请输入真实姓名', 'status' => '0']));
        }
        if (empty($request->input('mobile'))) {
            return self::res(0, response()->json(['data' => '请输入联系方式', 'status' => '0']));
        }
        return self::res(1, $request);
    }
    /*********************************通用单项检测结束*******************************************/


    /*****************************数据检测开始****************************/
    //检测用户数据
    public function checkLoginAndRuleAndSupplier($request)
    {
        $re = $this->checkLoginAndRule($request);//检测登录、权限
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkSupplier($re['response']);//检测供应商数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                $re3 = $this->checkSafePassword($re2['response']);//检测安全密码是否输入正确
                if ($re3['status'] == '0') {
                    return $re3;
                } else {
                    return self::res(1, $re3['response']);
                }
            }
        }
    }

    //检测权限角色添加、角色编辑

    public function checkSupplier($request)
    {
        if (empty($request->input('company_name'))) {
            return self::res(0, response()->json(['data' => '请输入公司名称！', 'status' => '0']));
        }
        if (empty($request->input('contactname'))) {
            return self::res(0, response()->json(['data' => '请输入联系人姓名！', 'status' => '0']));
        }
        if (empty($request->input('contactmobile'))) {
            return self::res(0, response()->json(['data' => '请输入联系人电话！', 'status' => '0']));
        }
        $mobile = $request->input('contactmobile');
        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return self::res(0, response()->json(['data' => '请输入正确手机号码', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测编辑个人信息数据

    public function checkLoginAndRuleAndCategoryAdd($request)
    {
        $re = $this->checkLoginAndRule($request);//检测登录、权限
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkCategoryAdd($re['response']);//检测是添加栏目数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                $re3 = $this->checkSafePassword($re2['response']);//检测安全密码是否输入正确
                if ($re3['status'] == '0') {
                    return $re3;
                } else {
                    return self::res(1, $re3['response']);
                }
            }
        }
    }

    //检测添加下级人员数据

    public function checkCategoryAdd($request)
    {
        if (empty($request->input('category_name'))) {
            return self::res(0, response()->json(['data' => '请输入分类名称', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测编辑下级人员数据

    public function checkLoginAndRuleAndGoodsAdd($request)
    {
        $re = $this->checkLoginAndRule($request);//检测登录、权限
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkGoodsAdd($re['response']);   //检测添加商品数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测安全密码是否输入正确

    public function checkGoodsAdd($request)
    {
        if (empty($request->input('name'))) {
            return self::res(0, response()->json(['data' => '请输入商品名称!', 'status' => '0']));
        }
        if (empty($request->input('details'))) {
            return self::res(0, response()->json(['data' => '请填写商品详情!', 'status' => '0']));
        }
        if (!empty($request->input('price')) && !is_numeric($request->input('price'))) {
            return self::res(0, response()->json(['data' => '请正确输入价格!', 'status' => '0']));
        }
        if (!$this->positive_integer($request->input('price'), true, false)) {
            return self::res(0, response()->json(['data' => '商品价格不能为负数!', 'status' => '0']));
        }
        if (!empty($request->input('barcode')) && !is_numeric($request->input('barcode'))) {
            return self::res(0, response()->json(['data' => '请正确输入商品条码!', 'status' => '0']));
        }
        if (!empty($request->input('stock')) && !is_numeric($request->input('stock'))) {
            return self::res(0, response()->json(['data' => '请正确输入库存!', 'status' => '0']));
        }
        if ((!empty($request->input('weight')) && !is_numeric($request->input('weight'))) && (!empty($request->input('freight_price')) && !is_numeric($request->input('freight_price')))) {
            return self::res(0, response()->json(['data' => '运费和重量必须填写一个', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    /**
     * $num         字符串判断
     * $positive    正负判断
     * $int         整数/小数判断
     */
    function positive_integer($num, $positive = true, $int = true)
    {
        if ($num) {
            if (is_numeric($num)) {
                if ($positive && $num > 0 && !$int) {
                    return true;        //正数
                } elseif ($int && floor($num) == $num && !$positive) {
                    return true;        //整数
                } elseif ($positive && $int && $num > 0 && floor($num) == $num) {
                    return true;    //正整数
                } elseif ($positive && $int && $num > 0 && floor($num) != $num) {
                    return true;    //正小数
                } elseif ($positive && $num < 0 && !$int) {
                    return false;       //负数
                } elseif ($int && floor($num) != $num && !$positive) {
                    return false;       //小数
                } elseif ($positive && $int && $num < 0 && floor($num) != $num) {
                    return false;   //负小数
                } elseif ($positive && $int && $num < 0 && floor($num) == $num) {
                    return false;   //负整数
                } else {
                    return false; //未知类型的数字
                }
            } else {
                return false;   //不是数字
            }
        } elseif ($num === '0') {
            return false;
        } else {
            return true;    //表单未填写
        }
    }


    //检测修改设置安全密码

    public function checkLoginAndRuleAndSearchCompany($request)
    {
        $re = $this->checkLoginAndRule($request);//检测登录、权限
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkSearchCompany($re['response']);   //检测选择商品数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测修改登录密码

    public function checkSearchCompany($request)
    {
        if (empty($request->input('company_id')) && empty($request->input('company_name')) && empty($request->input('contactmobile'))) {
            return self::res(0, response()->json(['data' => '请输入相应供应商信息进行搜索选择!', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测添加栏目分类数据

    public function checkLoginAndRuleAndPurchaseGoods($request)
    {
        $re = $this->checkLoginAndRule($request);//检测登录、权限
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkPurchaseGoods($re['response']);   //检测进货退货开单的数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测添加栏目分类数据

    public function checkPurchaseGoods($request)
    {
        if (empty($request->orders['goods'])) {
            return self::res(0, response()->json(['data' => '请选择商品!', 'status' => '0']));
        }
        if (empty($request->orders['company_id'])) {
            return self::res(0, response()->json(['data' => '请选择供应商!', 'status' => '0']));
        }
        if (empty($request->orders['operator_id'])) {
            return self::res(0, response()->json(['data' => '请选择操作人员!', 'status' => '0']));
        }
        return self::res(1, $request);
    }


    //检测添加商品数据

    public function checkLoginAndRuleAndLossAndCheckGoods($request)
    {
        $re = $this->checkLoginAndRule($request);//检测登录、权限
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkLossAndCheckGoods($re['response']);   //检测报损、盘点开单的数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测搜索供应商的数据

    public function checkLossAndCheckGoods($request)
    {
        if (empty($request->orders['goods'])) {
            return self::res(0, response()->json(['data' => '请选择商品!', 'status' => '0']));
        }
        if (empty($request->orders['operator_id'])) {
            return self::res(0, response()->json(['data' => '请选择操作人员!', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测供应商进货退货开单的数据

    public function checkLoginAndRuleAndSafeAndUserEdit($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//检测登录、权限和安全密码
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkUserEdit($re['response']);//检测用户数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测报损、盘点开单的数据


    public function checkUserEdit($request)
    {
        if (empty($request->input('qq'))) {
            return self::res(0, response()->json(['data' => '请输qq号', 'status' => '0']));
        }
        if (empty($request->input('mobile'))) {
            return self::res(0, response()->json(['data' => '请输入手机号', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测登录提交数据

    public function checkLoginAndRuleAndPayconfig($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录是否有权限以及安全密码
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkPayconfig($re['response']);//检测修改的数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    // 添加终端机器号信息功能提交

    public function checkPayconfig($request)
    {
        if (empty($request->input('sft_pos_num'))) {
            return self::res(0, response()->json(['data' => '请输入pos商户号!', 'status' => '0']));
        }
        if (empty($request->input('sft_num'))) {
            return self::res(0, response()->json(['data' => '请输入盛付通商户号!', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    // 收款信息设置数据监测

    public function checkLoginAndRuleAndShengpayAdd($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录是否有权限以及安全密码
        if ($re['status'] == '0') {
            return $re;
        } else {
            $re2 = $this->checkShengpayAdd($re['response']);//检测修改的数据
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    /*****************************数据检测结束****************************/


    //工厂方法返回结果
    public function checkShengpayAdd($request)
    {
        if (empty($request->input('terminal_num'))) {
            return self::res(0, response()->json(['data' => '请输入终端号!', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //格式化返回值

    public function checkRoleAddAndEdit($request)
    {
        if (empty($request->input('role_name'))) {
            return self::res(0, response()->json(['data' => '角色名称不能为空', 'status' => '0']));
        }
        return self::res(1, $request);
    }
}