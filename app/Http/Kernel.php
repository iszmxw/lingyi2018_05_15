<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        /**************************零壹程序管理系统*******************************/
        'ToolingCheck'=>\App\Http\Middleware\Tooling\ToolingCheck::class,//检测普通页面跳转的中间件
        'ToolingCheckAjax'=>\App\Http\Middleware\Tooling\ToolingCheckAjax::class,//检测Ajax数据提交的中间件
        /**************************零壹程序管理系统*******************************/

        /**************************零壹平台管理系统*******************************/
        'ZeroneCheck'=>\App\Http\Middleware\Zerone\ZeroneCheck::class,//检测普通页面跳转的中间件
        'ZeroneCheckAjax'=>\App\Http\Middleware\Zerone\ZeroneCheckAjax::class,//检测Ajax数据提交的中间件
        /**************************零壹平台管理系统*******************************/

        /**************************分公司平台管理系统*******************************/
        'AgentCheck'=>\App\Http\Middleware\Agent\AgentCheck::class,//检测普通页面跳转的中间件
        'AgentCheckAjax'=>\App\Http\Middleware\Agent\AgentCheckAjax::class,//检测Ajax数据提交的中间件
        /**************************分公司平台管理系统*******************************/

        /**************************零壹粉丝管理系统*******************************/
        'FansmanageCheck'=>\App\Http\Middleware\Fansmanage\FansmanageCheck::class,//检测普通页面跳转的中间件
        'FansmanageCheckAjax'=>\App\Http\Middleware\Fansmanage\FansmanageCheckAjax::class,//检测Ajax数据提交的中间件
        /**************************零壹粉丝管理系统*******************************/


        /**************************零壹总店管理系统*******************************/
        'RetailCheck'=>\App\Http\Middleware\Retail\RetailCheck::class,//检测普通页面跳转的中间件
        'RetailCheckAjax'=>\App\Http\Middleware\Retail\RetailCheckAjax::class,//检测Ajax数据提交的中间件
        /**************************零壹总店管理系统*******************************/

        /**************************简版的店铺管理系统*******************************/
        'SimpleCheck'=>\App\Http\Middleware\Simple\SimpleCheck::class,//检测普通页面跳转的中间件
        'SimpleCheckAjax'=>\App\Http\Middleware\Simple\SimpleCheckAjax::class,//检测Ajax数据提交的中间件
        /**************************简版的店铺管理系统*******************************/

        /**************************零售接口*******************************/
        'AndroidRetailApiCheck'=>\App\Http\Middleware\Api\AndroidRetailApiCheck::class,//检测普通页面跳转的中间件
        /**************************零售接口*******************************/

        /**************************零售简版接口*******************************/
        'AndroidSimpleApiCheck'=>\App\Http\Middleware\Api\AndroidSimpleApiCheck::class,//检测普通页面跳转的中间件
        /**************************零售简版接口*******************************/

        /**************************微信APi接口*******************************/
        'WechatApiCheck'=>\App\Http\Middleware\Api\WechatApiCheck::class,//检测普通页面跳转的中间件
        /**************************微信APi接口*******************************/

        /**************************微信前端页面*******************************/
        'WechatCheck'=>\App\Http\Middleware\Api\WechatCheck::class,//检测普通页面跳转的中间件
        /**************************微信前端页面*******************************/

        // 账号体系测试
        'UserCheck'=>\App\Http\Middleware\User\UserCheck::class,//检测普通页面跳转的中间件
//        'ShopCheck'=>\App\Http\Middleware\User\ShopCheck::class,//检测普通页面跳转的中间件


        /**************************微信支付接口*******************************/
        'WxPayCheckAjax'=>\App\Http\Middleware\Pay\WxPayCheckAjax::class,//检测普通页面跳转的中间件
        /**************************微信支付接口*******************************/
    ];
}
