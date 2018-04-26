<?php
/**
 *新版本登录界面
 *
 **/

namespace App\Http\Controllers\Zerone;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Gregwar\Captcha\CaptchaBuilder;
use App\Models\Account;
use App\Models\ErrorLog;
use App\Models\LoginLog;
use Session;

class LoginController extends Controller
{

    /**
     * 登录页面
     */
    public function display()
    {
        dump(Session::all());
        return view('Zerone/Login/display');
    }

    /**
     * 生成验证码
     */
    public function captcha()
    {
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        //可以设置图片宽高及字体
        $builder->build($width = 150, $height = 35, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();
        //把内容存入session
        Session::flash('zerone_system_captcha', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }

    /**
     * 检测登录
     */
    public function login_check()
    {
        $ip = Request::getClientIp();//获取访问者IP
        $addr_arr = \IP2Attr::find($ip);//获取访问者地址
        $addr = $addr_arr[0] . $addr_arr[1] . $addr_arr[2] . $addr_arr[3];//获取访问者地址
        $ip = ip2long($ip);//IP查询完地址后转化为整型。便于存储和查询
        $allowed_error_times = config("app.allowed_error_times");//允许登录错误次数
        $username = Request::input('username');//接收用户名
        $password = Request::input('password');//接收用户密码
        $key = config("app.zerone_encrypt_key");//获取加密盐
        $encrypted = md5($password);//加密密码第一重
        $encryptPwd = md5("lingyikeji" . $encrypted . $key);//加密密码第二重

        $error_log = ErrorLog::getOne([['ip', $ip]]);//查询该IP下的错误记录
        /* 如果没有错误记录 或 错误次数小于允许错误的最大次数 或 错误次数超出 但时间已经过了10分钟。*/
        if (empty($error_log) || $error_log['error_time'] < $allowed_error_times || (strtotime($error_log['error_time']) >= $allowed_error_times && time() - strtotime($error_log['updated_at']) >= 600)) {
            if ($account_info = Account::getOneForLogin($username)) {//查询账号或者手机号是否存在
                if ($encryptPwd != $account_info->password) {//查询密码是否对的上
                    ErrorLog::addErrorTimes($ip, 1);//访问者IP添加错误次数+1
                    return response()->json(['data' => '登录账号、手机号或密码输入错误', 'status' => '0']);
                } elseif ($account_info->status <> '1') {//查询账号状态
                    ErrorLog::addErrorTimes($ip, 1);//访问者IP添加错误次数+1
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
                        'login_time' => time()//登录时间
                    ];
                    if ($account_info->id <> 1) {//如果不是admin这个超级管理员
                        if ($account_info->organization->program_id <> '1') {//如果账号不属于零壹平台管理系统，则报错，不能登录。1是零壹凭条管理系统的ID
                            ErrorLog::addErrorTimes($ip, 1);//访问者IP添加错误次数+1
                            return response()->json(['data' => '登录账号、手机号或密码输入错误', 'status' => '0']);
                        } else {
                            ErrorLog::clearErrorTimes($ip);//清除掉错误记录
                            //插入登录记录
                            if (LoginLog::addLoginLog($account_info['id'], 1, $account_info->organization_id, $ip, $addr)) {//写入登录日志
                                Session::put('zerone_account_id', encrypt($account_info->id));//存储登录session_id为当前用户ID
                                //构造用户缓存数据
                                if (!empty($account_info->account_info->realname)) {
                                    $admin_data['realname'] = $account_info->account_info->realname;
                                } else {
                                    $admin_data['realname'] = '未设置';
                                }
                                if (!empty($account_info->account_roles)) {
                                    foreach ($account_info->account_roles as $key => $val) {
                                        $account_info->role = $val;
                                    }
                                    $admin_data['role_name'] = $account_info->role->role_name;
                                } else {
                                    $admin_data['role_name'] = '角色未设置';
                                }

                                \ZeroneRedis::create_account_cache($account_info->id, $admin_data);//生成账号数据的Redis缓存
                                \ZeroneRedis::create_menu_cache($account_info->id, 1);//生成对应账号的系统菜单
                                return response()->json(['data' => '登录成功', 'status' => '1']);
                            } else {
                                return response()->json(['data' => '登录失败', 'status' => '0']);
                            }
                        }
                    } else {
                        ErrorLog::clearErrorTimes($ip);//清除掉错误记录
                        //插入登录记录
                        if (LoginLog::addLoginLog($account_info['id'], 1, 0, $ip, $addr)) {//admin,唯一超级管理员，不属于任何组织
                            Session::put('zerone_account_id', encrypt($account_info->id));//存储登录session_id为当前用户ID
                            $admin_data['realname'] = '系统管理员';
                            $admin_data['role_name'] = '系统管理员';
                            //构造用户缓存数据
                            \ZeroneRedis::create_account_cache($account_info->id, $admin_data);//生成账号数据的Redis缓存
                            \ZeroneRedis::create_menu_cache($account_info->id, 1);//生成对应账号的系统菜单
                            return response()->json(['data' => '登录成功', 'status' => '1']);
                        } else {
                            return response()->json(['data' => '登录失败', 'status' => '0']);
                        }
                    }
                }
            } else {
                ErrorLog::addErrorTimes($ip, 1);
                return response()->json(['data' => '登录账号、手机号或密码输入错误', 'status' => '0']);
            }
        } else {
            return response()->json(['data' => '您短时间内错误的次数超过' . $allowed_error_times . '次，请稍候再尝试登录 ', 'status' => '0']);
        }
    }
}

?>