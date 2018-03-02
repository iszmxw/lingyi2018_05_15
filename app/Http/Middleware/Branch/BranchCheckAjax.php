<?php
/**
 * 检测中间件
 */
namespace App\Http\Middleware\Branch;
use App\Models\Account;
use Closure;
use Session;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Request;

class BranchCheckAjax{
    public function handle($request, Closure $next)
    {
        $route_name = $request->path();//获取当前的页面路由
        switch ($route_name) {
            case "branch/ajax/login_check"://检测登录数据提交
                $re = $this->checkLoginPost($request);
                return self::format_response($re, $next);
                break;
            case "branch/ajax/branch_select":         //超级管理员选择分店提交数据
                $re = $this->checkLoginAndRule($request);
                return self::format_response($re, $next);
                break;
            case "branch/ajax/profile_edit_check"://检测登录，权限，及修改个人信息的数据
                $re = $this->checkLoginAndRuleAndProfileEdit($request);
                return self::format_response($re, $next);
                break;
            case "branch/ajax/safe_password_edit_check"://检测登录，权限，及修改密码的数据
                $re = $this->checkLoginAndRuleAndSafepasswordEdit($request);
                return self::format_response($re, $next);
                break;
            case "branch/ajax/password_edit_check"://检测登录，权限，及修改密码的数据
                $re = $this->checkLoginAndRuleAndPasswordEdit($request);
                return self::format_response($re, $next);
                break;
            case "company/ajax/compant_info_edit_check"://检测登录，权限，安全密码，及修改商户信息的数据
                $re = $this->checkLoginAndRuleAndSafeCompanyEdit($request);
                return self::format_response($re, $next);
                break;
            case "company/ajax/store_add_second_check": //商户创建店铺数据检测
                $re = $this->checkLoginAndRuleAndStoreAdd($request);
                return self::format_response($re, $next);
                break;
        }
    }



    /******************************复合检测开始*********************************/
    //检测登录，权限，及修改密码的数据
    public function checkLoginAndRuleAndPasswordEdit($request){
        $re = $this->checkLoginAndRule($request);//判断是否登录
        if($re['status']=='0'){//检测是否登录
            return $re;
        }else{
            $re2 = $this->checkPasswordEdit($re['response']);//检测是否具有权限
            if($re2['status']=='0'){
                return $re2;
            }else{
                $re3 = $this->checkSafePassword($re2['response']);//检测是否具有权限
                if($re3['status']=='0'){
                    return $re3;
                }else{
                    return self::res(1,$re3['response']);
                }
            }
        }
    }


    //检测登录，权限，安全密码，及修改商户信息的数据
    public function checkLoginAndRuleAndSafeCompanyEdit($request){
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录
        if($re['status']=='0'){//检测是否登录
            return $re;
        }else{
            $re2 = $this->checkCompanyEdit($re['response']);//检测是否具有权限
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //检测登录，权限，及修改安全密码的数据
    public function checkLoginAndRuleAndSafepasswordEdit($request){
        $re = $this->checkLoginAndRule($request);//判断是否登录
        if($re['status']=='0'){//检测是否登录
            return $re;
        }else{
            $re2 = $this->checkSafepasswordEdit($re['response']);//检测是否具有权限
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //检测登录，权限，及修改个人账号信息的数据
    public function checkLoginAndRuleAndProfileEdit($request){
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录是否有权限以及安全密码
        if($re['status']=='0'){//检测是否登录
            return $re;
        }else{
            $re2 = $this->checkProfileEdit($re['response']);//检测修改的数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }


    //检测登录，权限，及商户创建店铺数据检测
    public function checkLoginAndRuleAndStoreAdd($request){
        $re = $this->checkLoginAndRuleAndSafe($request);//判断是否登录是否有权限以及安全密码
        if($re['status']=='0'){//检测是否登录
            return $re;
        }else{
            $re2 = $this->checkStoreAdd($re['response']);//检测修改的数据
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    /***/
    /******************************复合检测结束*********************************/






    /*********************************通用单项检测开始*******************************************/
    //部分页面检测用户是否admin，否则检测是否有权限
    public function checkHasRule($request){
        $admin_data = $request->get('admin_data');
        if($admin_data['id']!=1){
            //暂定除admin外所有用户都没有权限
            //return self::res(0, response()->json(['data' => '您没有该功能的权限！', 'status' => '-1']));
            return self::res(1,$request);
        }else{
            return self::res(1,$request);
        }
    }

    //检测是否登录
    public function checkIsLogin($request)
    {
        //获取用户登录存储的SessionId
        $sess_key = Session::get('branch_account_id');
        //如果为空跳转到登录页面
        if(!empty($sess_key)) {
            $sess_key = Session::get('branch_account_id');//获取管理员ID
            $sess_key = decrypt($sess_key);//解密管理员ID
            Redis::connect('branch');//连接到我的缓存服务器
            $admin_data = Redis::get('branch_system_admin_data_'.$sess_key);//获取管理员信息
            $admin_data = unserialize($admin_data);//解序列我的信息
            $request->attributes->add(['admin_data'=>$admin_data]);//添加参数
            //把参数传递到下一个中间件
            return self::res(1,$request);
        }else{
            return self::res(0, response()->json(['data' => '登录状态失效', 'status' => '-1']));
        }
    }

    //检测登录和权限
    public function checkLoginAndRule($request){
        $re = $this->checkIsLogin($request);//判断是否登录
        if($re['status']=='0'){//检测是否登录
            return $re;
        }else{
            $re2 = $this->checkHasRule($re['response']);//检测是否具有权限
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }


    //检测登录和权限和安全密码
    public function checkLoginAndRuleAndSafe($request){
        $re = $this->checkLoginAndRule($request);//判断是否登录
        if($re['status']=='0'){//检测是否登录
            return $re;
        }else{
            $re2 = $this->checkSafePassword($re['response']);//检测是否具有权限
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }
    /*********************************通用单项检测结束*******************************************/





    /*****************************数据检测开始****************************/
    //检测商户编辑信息
    public function checkCompanyEdit(Request $request)
    {
        if(empty($request->input('organization_name'))){
            return self::res(0,response()->json(['data' => '请输入商户名称', 'status' => '0']));
        }
        if(empty($request->input('company_owner_mobile'))){
            return self::res(0,response()->json(['data' => '请输入负责人手机号码', 'status' => '0']));
        }
        return self::res(1,$request);
    }
    //检测编辑个人信息数据
    public function checkProfileEdit(Request $request){
        if(empty($request->input('realname'))){
            return self::res(0,response()->json(['data' => '请输入用户真实姓名', 'status' => '0']));
        }
        if(empty($request->input('mobile'))){
            return self::res(0,response()->json(['data' => '请输入用户手机号码', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测添加店铺的数据
    public function checkStoreAdd(Request $request)
    {
        if(empty($request->input('program_id'))){
            return self::res(0,response()->json(['data' => '请选择店铺模式！', 'status' => '0']));
        }
        if(empty($request->input('organization_name'))){
            return self::res(0,response()->json(['data' => '请填写店铺名称', 'status' => '0']));
        }
        if(empty($request->input('tell'))){
            return self::res(0,response()->json(['data' => '请填写店铺负责人手机号码', 'status' => '0']));
        }
        if(empty($request->input('realname'))){
            return self::res(0,response()->json(['data' => '请填写店铺负责人姓名', 'status' => '0']));
        }
        if(empty($request->input('password'))){
            return self::res(0,response()->json(['data' => '请填写店铺登录密码', 'status' => '0']));
        }
        if(empty($request->input('re_password'))){
            return self::res(0,response()->json(['data' => '请输入重复登录密码', 'status' => '0']));
        }
        if(empty($request->input('safe_password'))){
            return self::res(0,response()->json(['data' => '请输入安全密码', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测安全密码是否输入正确
    public function checkSafePassword($request){
        $admin_data = $request->get('admin_data');
        $safe_password = $request->input('safe_password');
        $account = Account::getOne(['id'=>'1']);//查询超级管理员的安全密码
        if ($admin_data['is_super'] == 1){//如果是超级管理员获取零壹加密盐
            $safe_password_check = $account['safe_password'];
            $key = config("app.zerone_safe_encrypt_key");//获取加安全密码密盐（零壹平台专用）
        }else{
            $safe_password_check = $admin_data['safe_password'];
            $key = config("app.branch_safe_encrypt_key");//获取安全密码加密盐（商户专用）
        }
        $encrypted = md5($safe_password);//加密密码第一重
        $encryptPwd = md5("lingyikeji".$encrypted.$key);//加密密码第二重
        if(empty($safe_password)){
            return self::res(0,response()->json(['data' => '请输入安全密码', 'status' => '0']));
        }
        if(empty($admin_data['safe_password'])){
            return self::res(0,response()->json(['data' => '您尚未设置安全密码，请先前往 个人中心 》安全密码设置 设置', 'status' => '0']));
        }
        if($encryptPwd != $safe_password_check){
            return self::res(0,response()->json(['data' => '您输入的安全密码不正确', 'status' => '0']));
        }
        return self::res(1,$request);
    }

    //检测修改设置安全密码
    public function checkSafepasswordEdit($request){
        if(empty($request->input('is_editing'))){
            return self::res(0,response()->json(['data' => '数据传输错误', 'status' => '0']));
        }
        if($request->input('is_editing')=='-1'){//设置安全密码时
            if(empty($request->input('safe_password'))){
                return self::res(0,response()->json(['data' => '请输入安全密码', 'status' => '0']));
            }
            if(empty($request->input('re_safe_password'))){
                return self::res(0,response()->json(['data' => '请重复安全密码', 'status' => '0']));
            }
            if($request->input('safe_password') <> $request->input('re_safe_password')){
                return self::res(0,response()->json(['data' => '两次安全密码输入不一致', 'status' => '0']));
            }
        }elseif($request->input('is_editing')=='1'){//修改安全密码时
            if(empty($request->input('old_safe_password'))){
                return self::res(0,response()->json(['data' => '请输入旧安全密码', 'status' => '0']));
            }
            if(empty($request->input('safe_password'))){
                return self::res(0,response()->json(['data' => '请输入新安全密码', 'status' => '0']));
            }
            if(empty($request->input('re_safe_password'))){
                return self::res(0,response()->json(['data' => '请重复新安全密码', 'status' => '0']));
            }
            if($request->input('safe_password') <> $request->input('re_safe_password')){
                return self::res(0,response()->json(['data' => '两次安全密码输入不一致', 'status' => '0']));
            }
        }
        return self::res(1,$request);
    }

    //检测修改登录密码
    public function checkPasswordEdit($request){
        if(empty($request->input('password'))){
            return self::res(0,response()->json(['data' => '请输入原登录密码', 'status' => '0']));
        }
        if(empty($request->input('new_password'))){
            return self::res(0,response()->json(['data' => '新登录密码不能为空', 'status' => '0']));
        }
        if(empty($request->input('news_password'))){
            return self::res(0,response()->json(['data' => '请确认新登录密码是否一致', 'status' => '0']));
        }
        if($request->input('new_password') != $request->input('news_password')){
            return self::res(0,response()->json(['data' => '新密码和重复密码不一致', 'status' => '0']));
        }
        return self::res(1,$request);
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
        if (Session::get('branch_system_captcha') == $request->input('captcha')) {
            //把参数传递到下一个程序
            return self::res(1, $request);
        } else {
            //用户输入验证码错误
            return self::res(0, response()->json(['data' => '验证码错误', 'status' => '0']));
        }
    }

    /*****************************数据检测结束****************************/





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