<?php
/**
 *新版本登录界面
 *
 **/
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\ZeroneRedis\ZeroneRedis;
use Illuminate\Support\Facades\Request;
use Gregwar\Captcha\CaptchaBuilder;
use App\Models\Account;
use App\Models\ErrorLog;
use App\Models\LoginLog;
use Session;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    /*
     * 登录页面
     */
    public function display()
    {
        return view('Agent/Login/display');
    }

    /**
     * 生成验证码,直接生产引用
     */
    public function captcha($tmp)
    {
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        //可以设置图片宽高及字体
        $builder->build($width = 250, $height = 70, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();
        //把内容存入session
        Session::flash('milkcaptcha', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }

    //验证注册码的正确与否
    public function getCode($capt)
    {
        $code = Session::get('milkcaptcha');
        if ($code == $capt) {
            //用户输入验证码正确
            return '1';
        } else {
            //用户输入验证码错误
            return '0';
        }
    }

    //检测登录
    public function login_check()
    {
        $ip = Request::getClientIp();//获取访问者IP
        $addr_arr = \IP2Attr::find($ip);//获取访问者地址
        $addr = $addr_arr[0] . $addr_arr[1] . $addr_arr[2] . $addr_arr[3];//获取访问者地址
        $ip = ip2long($ip);//IP查询完地址后转化为整型。便于存储和查询
        $allowed_error_times = config("app.allowed_error_times");//允许登录错误次数
        $username = Request::input('username');//接收用户名
        $password = Request::input('password');//接收用户密码
//        $captcha = Request::input('captcha');//接收验证码
//        $check_captcha = $this->getCode($captcha);
//        if($check_captcha ==0){
//            return response()->json(['data' => '验证码输入错误', 'status' => '0']);
//        }
        $account_info = Account::getOneForLogin($username);//根据账号查询
        if (empty($account_info)) {   //若果没有查询到数据，账号名称错误
            ErrorLog::addErrorTimes($ip, 2);
            return response()->json(['data' => '登录账号、手机号或密码输入错误', 'status' => '0']);
        }
        if ($account_info->id == 1) {//如果是超级管理员获取零壹加密盐
            $key = config("app.zerone_encrypt_key");//获取加密盐--零壹加密盐
        } else {
            $key = config("app.agent_encrypt_key");//获取加密盐--分公司加密盐
        }

        $encrypted = md5($password);//加密密码第一重
        $encryptPwd = md5("lingyikeji" . $encrypted . $key);//加密密码第二重
        //实例化错误记录表模型
        $error_log = ErrorLog::getOne([['ip', $ip]]);//查询该IP下的错误记录

        //如果没有错误记录 或 错误次数小于允许错误的最大次数 或 错误次数超出 但时间已经过了10分钟
        if (empty($error_log) || $error_log['error_time'] < $allowed_error_times || (strtotime($error_log['error_time']) >= $allowed_error_times && time() - strtotime($error_log['updated_at']) >= 300)) {
            if (!empty($account_info)) {
                if ($encryptPwd != $account_info->password) {//查询密码是否对的上
                    ErrorLog::addErrorTimes($ip, 2);
                    return response()->json(['data' => '登录账号、手机号或密码输入错误', 'status' => '0']);
                } elseif ($account_info->status <> '1') {//查询账号状态
                    ErrorLog::addErrorTimes($ip, 2);
                    return response()->json(['data' => '您的账号状态异常，请联系管理员处理', 'status' => '0']);
                } else {
                    //登录成功要生成缓存的登录信息
                    $admin_data = [
                        'id' => $account_info->id,    //用户ID
                        'account' => $account_info->account,//用户账号
                        'organization_id' => $account_info->organization_id,//组织ID
                        'is_super' => $account_info->is_super,//是否超级管理员
                        'parent_id' => $account_info->parent_id,//上级ID
                        'parent_tree' => $account_info->parent_tree,//上级树
                        'deepth' => $account_info->deepth,//账号在组织中的深度
                        'mobile' => $account_info->mobile,//绑定手机号
                        'safe_password' => $account_info->safe_password,//安全密码
                        'account_status' => $account_info->status,//用户状态
                        'ip' => $ip,//登录IP
                        'login_position' => $addr,//登录地址
                        'login_time' => time(),//登录时间
                    ];
                    if ($account_info->id <> 1) {//如果不是admin这个超级管理员
                        if ($account_info->organization->program_id <> '2') {//如果账号不属于分公司管理系统，则报错，不能登录
                            ErrorLog::addErrorTimes($ip, 2);
                            return response()->json(['data' => '登录账号、手机号或密码输入错误', 'status' => '0']);
                        } else {
                            ErrorLog::clearErrorTimes($ip);//清除掉错误记录
                            //插入登录记录
                            if (LoginLog::addLoginLog($account_info['id'], 2, $account_info->organization->id, $ip, $addr)) {//写入登录日志
                                Session::put('agent_account_id', encrypt($account_info->id));//存储登录session_id为当前用户ID

                                //构造用户缓存数据
                                if (!empty($account_info->account_info->realname)) {
                                    $admin_data['realname'] = $account_info->account_info->realname;
                                } else {
                                    $admin_data['realname'] = '未设置';
                                }
                                if (!empty($account_info->account_roles) && $account_info->account_roles->count() != 0) {
                                    foreach ($account_info->account_roles as $key => $val) {
                                        $account_info->role = $val;
                                    }
                                    $admin_data['role_name'] = $account_info->role->role_name;
                                } else {
                                    $admin_data['role_name'] = '角色未设置';
                                }
                                \ZeroneRedis::create_agent_account_cache($account_info->id, $admin_data);//生成账号数据的Redis缓存
                                \ZeroneRedis::create_menu_cache($account_info->id, 2);//生成对应账号的系统菜单
                                return response()->json(['data' => '登录成功', 'status' => '1']);
                            } else {
                                return response()->json(['data' => '登录失败', 'status' => '0']);
                            }
                        }
                    } else {
                        ErrorLog::clearErrorTimes($ip);//清除掉错误记录
                        //插入登录记录
                        Session::put('agent_account_id', encrypt($account_info->id));//存储登录session_id为当前用户ID
                        $admin_data['realname'] = '系统管理员';
                        $admin_data['role_name'] = '系统管理员';
                        //构造用户缓存数据
                        \ZeroneRedis::create_agent_account_cache($account_info->id, $admin_data);//生成账号数据的Redis缓存
                        \ZeroneRedis::create_menu_cache($account_info->id, 2);//生成对应账号的系统菜单
                        return response()->json(['data' => '登录成功', 'status' => '1']);
                    }
                }
            } else {
                ErrorLog::addErrorTimes($ip, 2);
                return response()->json(['data' => '登录账号、手机号或密码输入错误', 'status' => '0']);
            }
        } else {
            return response()->json(['data' => '您短时间内错误的次数超过' . $allowed_error_times . '次，请稍候再尝试登录 ', 'status' => '0']);
        }
    }
}

?>