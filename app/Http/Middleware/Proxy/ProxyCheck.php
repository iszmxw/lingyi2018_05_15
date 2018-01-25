<?php
/**
 * 检测是否登录的中间件
 */
namespace App\Http\Middleware\Proxy;
use Closure;
use Session;
use Illuminate\Support\Facades\Redis;

class ProxyCheck{
    public function handle($request,Closure $next){
        $route_name = $request->path();//获取当前的页面路由
        switch($route_name){
            /*****登录页,如果已经登录则不需要再次登录*****/
            case "proxy/login"://登录页,如果已经登录则不需要再次登录
                //获取用户登录存储的SessionId
                $sess_key = Session::get('zerone_account_id');
                //如果不为空跳转到首页
                if(!empty($sess_key)) {
                    return redirect('proxy');
                }
                break;

            /****仅检测是否登录及是否具有权限****/
            case "zerone/role/role_add"://添加权限角色
            case "zerone/role/role_list"://权限角色列表
            case "zerone/subordinate/subordinate_add"://添加下级人员
            case "zerone/subordinate/subordinate_list"://下级人员列表
            case "zerone/subordinate/subordinate_structure"://下级人员列表
            case "zerone/dashboard/operation_log"://战区管理所有操作记录
            case "zerone/dashboard/login_log"://战区管理所有登录记录
            case "zerone/dashboard/warzone"://战区管理首页权限
            case "zerone/dashboard/setup/setup_edit"://参数设置权限
            case "zerone/dashboard/setup"://参数设置权限
            case "zerone/dashboard/structure":         //人员结构
            case "zerone/personal":                     //个人中心——个人资料
            case "zerone/personal/password_edit":       //个人中心——密码修改
            case "zerone/personal/safe_password":   //个人中心——安全密码设置
            case "zerone/personal/operation_log":       //个人中心——我的操作日志
            case "zerone/personal/login_log":           //个人中心——我的登录日志
            case "zerone"://后台首页

            case "zerone/proxy/proxy_add":              //添加服务商
            case "zerone/proxy/proxy_examinelist":      //服务商审核列表
            case "zerone/proxy/proxy_list":             //服务商列表
            case "zerone/proxy/proxy_structure":        //服务商人员架构
            case "zerone/proxy/proxy_program":          //服务商程序管理
            case "zerone/proxy/proxy_company":          //服务商商户划拨

            case "zerone/company/company_add":          //添加商户
            case "zerone/company/company_examinelist":  //商户审核列表
            case "zerone/company/company_list":         //商户列表
            case "zerone/company/company_structure":    //商户店铺架构
            case "zerone/company/company_program":      //商户程序管理
            case "zerone/company/company_store":        //商户划拨管理

            case "zerone/store/store_add":              //店铺添加
            case "zerone/store/store_list":             //店铺列表
            case "zerone/store/store_structure":        //店铺人员架构
            case "zerone/store/store_branchlist":       //分店管理
            case "zerone/store/store_config":           //分店设置参数

                $re = $this->checkLoginAndRule($request);//判断是否登录
                return self::format_response($re,$next);
                break;
        }
        return $next($request);
    }

    //检测是否admin或是否有权限
    public function checkLoginAndRule($request){
        $re = $this->checkIsLogin($request);//判断是否登录
        if($re['status']=='0'){
            return $re;
        }else{
            $re2 = $this->checkHasRule($re['response']);//判断用户是否admin或是否有权限
            if($re2['status']=='0'){
                return $re2;
            }else{
                return self::res(1,$re2['response']);
            }
        }
    }

    //部分页面检测用户是否admin，否则检测是否有权限
    public function checkHasRule($request){
        $admin_data = $request->get('admin_data');//中间件产生的管理员数据参数
        if($admin_data['id']!=1){
            //暂定所有用户都有权限
            //return self::res(1,redirect('zerone'));
            return self::res(1,$request);
        }else{
            return self::res(1,$request);
        }
    }

    //普通页面检测用户是否登录
    public function checkIsLogin($request){
        //获取用户登录存储的SessionId
        $sess_key = Session::get('zerone_account_id');
        //如果为空跳转到登录页面
        if(empty($sess_key)) {
            return self::res(0,redirect('zerone/login'));
        }else{
            $sess_key = Session::get('zerone_account_id');//获取管理员ID
            $sess_key = decrypt($sess_key);//解密管理员ID
            Redis::connect('zeo');//连接到我的缓存服务器
            $admin_data = Redis::get('zerone_system_admin_data_'.$sess_key);//获取管理员信息
            $menu_data = Redis::get('zerone_system_menu_'.$sess_key);
            $son_menu_data = Redis::get('zerone_system_son_menu_'.$sess_key);
            $admin_data = unserialize($admin_data);//解序列我的信息
            $menu_data =  unserialize($menu_data);//解序列一级菜单
            $son_menu_data =  unserialize($son_menu_data);//解序列子菜单
            $request->attributes->add(['admin_data'=>$admin_data,'menu_data'=>$menu_data,'son_menu_data'=>$son_menu_data]);//添加参数
            //把参数传递到下一个中间件
            return self::res(1,$request);
        }
    }

    //工厂方法返回结果
    public static function res($status,$response){
        return ['status'=>$status,'response'=>$response];
    }
    //格式化返回值
    public static function format_response($re,Closure $next){
        if($re['status']=='0'){
            return $re['response'];
        }else{
            return $next($re['response']);
        }
    }
}
?>