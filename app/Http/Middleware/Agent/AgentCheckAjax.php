<?php
/**
 * 检测中间件囖
 */

namespace App\Http\Middleware\Agent;

use App\Models\Account;
use App\Models\Program;
use Closure;
use Session;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Request;

class AgentCheckAjax
{
    public function handle($request, Closure $next)
    {
        $route_name = $request->path();//获取当前的页面路由
        switch ($route_name) {
            case "agent/ajax/login_check"://检测登录数据提交
                $re = $this->checkLoginPost($request);
                return self::format_response($re, $next);
                break;
            //系统管理
            case "agent/ajax/agent_info_check"://检测登录和权限和安全密码和公司信息是否为空
                $re = $this->checkLoginAndRuleAndSafeAndAgentInfo($request);
                return self::format_response($re, $next);
                break;
            case "agent/ajax/account_info_check"://检测登录和权限和安全密码和个人信息是否为空
                $re = $this->checkLoginAndRuleAndSafeAndAccountInfo($request);
                return self::format_response($re, $next);
                break;
            case "agent/ajax/password_check"://检测登录和权限和安全密码和公司信息是否为空
                $re = $this->checkLoginAndRuleAndSafeAndPassword($request);
                return self::format_response($re, $next);
                break;
            case "agent/ajax/safe_password_check"://设置安全密码
                $re = $this->checkLoginAndRuleAndSafeEdit($request);
                return self::format_response($re, $next);
                break;


            case "agent/ajax/fansmanage_assets_check"://检测是否登录 权限 安全密码 数字不能为空
                $re = $this->checkLoginAndRuleAndSafeAndAssets($request);
                return self::format_response($re, $next);
                break;


            case "proxy/ajax/role_edit_check"://检测是否登录 权限 安全密码

                $re = $this->checkLoginAndRuleAndSafe($request);
                return self::format_response($re, $next);
                break;
            case "agent/ajax/fansmanage_assets":   //商户资产划入检测弹出登入和权限
                $re = $this->checkLoginAndRule($request);
                return self::format_response($re, $next);
                break;

        }
    }
    /******************************复合检测*********************************/

    /********公共部分**********/
    //检测登录和权限和安全密码
    public function checkLoginAndRuleAndSafe($request)
    {
        $re = $this->checkLoginAndRule($request);//判断是否登录
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkSafePassword($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    /********系统管理**********/
    //检测登录和权限和安全密码和分公司修改信息
    public function checkLoginAndRuleAndSafeAndAgentInfo($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkAgentInfo($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测登录和权限和安全密码和个人修改信息
    public function checkLoginAndRuleAndSafeAndAccountInfo($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkAccountInfo($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测登录和权限和安全密码和登入密码修改信息
    public function checkLoginAndRuleAndSafeAndPassword($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkPassword($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }

    //检测是否登录 权限 修改安全密码
    public function checkLoginAndRuleAndSafeEdit($request)
    {
        $re = $this->checkLoginAndRule($request);//判断是否登录和权限
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkSafeEdit($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    //检测登录和权限和安全密码和ID是否为空
    public function checkLoginAndRuleAndSafeAndID($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkID($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    //检测登录和权限
    public function checkLoginAndRule($request)
    {
        $re = $this->checkIsLogin($request);//判断是否登录
        if ($re['status'] == '0') {//检测是否登录
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


    //检测是否登录 权限 安全密码 数字不能为空
    public function checkLoginAndRuleAndSafeAndAssets($request)
    {
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录
        if ($re['status'] == '0') {//检测是否登录
            return $re;
        } else {
            $re2 = $this->checkAssets($re['response']);//检测是否具有权限
            if ($re2['status'] == '0') {
                return $re2;
            } else {
                return self::res(1, $re2['response']);
            }
        }
    }


    /******************************单项检测*********************************/


    /*****公共部分*****/

    //检测安全密码是否输入正确
    public function checkSafePassword($request)
    {
        $admin_data = $request->get('admin_data');
        $safe_password = $request->input('safe_password');

        if ($admin_data['is_super'] == '2') {
            $key = config("app.zerone_safe_encrypt_key");//获取加密盐
        } else {
            $key = config("app.agent_safe_encrypt_key");//获取加密盐
        }
        $encrypted = md5($safe_password);//加密密码第一重
        $encryptPwd = md5("lingyikeji" . $encrypted . $key);//加密密码第二重
        if (empty($safe_password)) {
            return self::res(0, response()->json(['data' => '请输入安全密码', 'status' => '0']));
        }
        if (empty($admin_data['safe_password']) && $admin_data['is_super'] != '2') {
            return self::res(0, response()->json(['data' => '您尚未设置安全密码，请先前往 个人中心 》安全密码设置 设置', 'status' => '0']));
        }
        if ($encryptPwd != $admin_data['safe_password']) {
            return self::res(0, response()->json(['data' => '您输入的安全密码不正确', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //部分页面检测用户是否admin，否则检测是否有权限
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
            $program_info = Program::getOne([['id', 2]]);
            $program_routes = [];
            foreach ($program_info->nodes as $key => $val) {
                $program_routes[] = $val->route_name;
            }

            //计算数组差集，获取用户所没有的权限
            $unset_routes = array_diff($program_routes, $account_routes);
            //如果跳转的路由不在该程序的所有节点中。则报错
            if (!in_array($route_name, $program_routes) && !in_array($route_name, config('app.agent_route_except'))) {
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

    //检测是否登录
    public function checkIsLogin($request)
    {
        $sess_key = Session::get('agent_account_id');
        //如果为空返回登录失效
        if (empty($sess_key)) {
            return self::res(0, response()->json(['data' => '登录状态失效', 'status' => '-1']));
        } else {
            $sess_key = Session::get('agent_account_id');//获取管理员ID
            $sess_key = decrypt($sess_key);//解密管理员ID
            Redis::connect('zeo');//连接到我的缓存服务器
            $admin_data = Redis::get('agent_system_admin_data_' . $sess_key);//获取管理员信息
            $admin_data = unserialize($admin_data);//解序列我的信息
            $request->attributes->add(['admin_data' => $admin_data]);//添加参数
            //把参数传递到下一个中间件
            return self::res(1, $request);
        }
    }


    /*****系统管理*****/
    //检测公司信息设置
    public function checkAgentInfo($request)
    {
        if (empty($request->input('organization_name'))) {
            return self::res(0, response()->json(['data' => '请输入分公司名称', 'status' => '0']));
        }
        if (empty($request->input('realname'))) {
            return self::res(0, response()->json(['data' => '请输入负责人姓名', 'status' => '0']));
        }
        if (empty($request->input('idcard'))) {
            return self::res(0, response()->json(['data' => '请输入负责人身份证号', 'status' => '0']));
        }
        $mobile = $request->input('mobile');
        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return self::res(0, response()->json(['data' => '请输入正确手机号码', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测个人信息修改
    public function checkAccountInfo($request)
    {

        if (empty($request->input('realname'))) {
            return self::res(0, response()->json(['data' => '请输入负责人姓名', 'status' => '0']));
        }
        $mobile = $request->input('mobile');
        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return self::res(0, response()->json(['data' => '请输入正确手机号码', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测登入密码修改
    public function checkPassword($request)
    {

        if (empty($request->input('old_password'))) {
            return self::res(0, response()->json(['data' => '请输入原密码', 'status' => '0']));
        }
        if (empty($request->input('password'))) {
            return self::res(0, response()->json(['data' => '请输入新密码', 'status' => '0']));
        }
        if (empty($request->input('re_password'))) {
            return self::res(0, response()->json(['data' => '请重复新密码', 'status' => '0']));
        }
        if ($request->input('password') <> $request->input('re_password')) {
            return self::res(0, response()->json(['data' => '两次安全密码输入不一致', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //检测修改设置安全密码
    public function checkSafeEdit($request)
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


    //检测登录提交数据
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
//        if (Session::get('zerone_system_captcha') == $request->input('captcha')) {
//            //把参数传递到下一个程序
        return self::res(1, $request);
//        } else {
//            //用户输入验证码错误
//            return self::res(0, response()->json(['data' => '验证码错误', 'status' => '0']));
//        }
    }


    //检测商户编辑表信息
    public function checkAssets($request)
    {
        $num = $request->input('num');
        if (preg_match("/^[1-9]{1}\d{0,9}$/", $num)) {
            return self::res(1, $request);
        } else {
            return self::res(0, response()->json(['data' => '请输入正确的数量', 'status' => '0']));
        }
    }


    //检测登录提交数据
    public function checkID($request)
    {
        if (empty($request->input('id'))) {
            return self::res(0, response()->json(['data' => '无效的数据传输', 'status' => '0']));
        }
        return self::res(1, $request);
    }

    //工厂方法返回结果
    public static function res($status, $response)
    {
        return ['status' => $status, 'response' => $response];
    }

    //格式化返回值
    public static function format_response($re, Closure $next)
    {
        if ($re['status'] == '0') {
            return $re['response'];
        } else {
            return $next($re['response']);
        }
    }
}