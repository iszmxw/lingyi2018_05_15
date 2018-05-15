<?php

return [

    'encrypt_key' => '1asdfnonlkj324jtio5nto45j89y6jonh5968h5oo23nr',//加密盐
    'tooling_encrypt_key' => '1asdfnonlkj324jtsdfmio2o3immro43imio34om5om4om5',//程序管理系统加密盐

    /*******************零壹平台**************************/
    'zerone_encrypt_key' => 'hasuidfhsdafnkdfg1oi2j382rj89243503945hjfdejwio',//零壹管理平台登录密码加密盐
    'zerone_safe_encrypt_key' => 'hasuidfhsdafnkdfg1oi2j382rj89243503945hjfdfd21a1',//零壹管理平台安全密码加密盐
    /*******************零壹平台**************************/

    /*******************分公司**************************/
    'agent_encrypt_key' => 'sdfdsf121s2dfdsfds121s2df45dsf12dsf45dsf12sd45sdf12ds',//零壹管理平台登录密码加密盐
    'agent_safe_encrypt_key' => 'sdf4ds4f545e4gfds12gds54gfdg54dsf5g4ds1g2df5sg4fd12sg',//零壹管理平台安全密码加密盐
    /*******************分公司**************************/

    /*******************粉丝管理系统**************************/
    'fansmanage_encrypt_key' => 'sdfjklhdskjfhkdsfkjdshjkf121sd2fds5fds12154sd12fds12f45s',//零壹总店管理平台登录密码加密盐
    'fansmanage_safe_encrypt_key' => 'sdfdsf4545sdfd5sf45dsf45ds54sdf4ds5f45ds4f5ds4f5ds4',//零壹总店管理平台安全密码加密盐
    /*******************粉丝管理系统**************************/

    /*******************零售版店铺**************************/
    'retail_encrypt_key' => 'sadfwefdsf654dfasd5ffsc65a4dsAFDA6G4E8GFHYK4HGG4HTRjhhg9hgd6h5jdf4h48',//零壹云管理平台 - 零售版店铺登录密码加密盐
    'retail_safe_encrypt_key' => 'rtubsdgasdgjafh654896545adsg54hsdgaashfagrtghmhjuil554sdg8sg54ag4q4',//零壹云管理平台 - 零售版店铺全密码加密盐
    /*******************零售版店铺**************************/

    /*******************简版店铺管理系统**************************/
    'simple_encrypt_key' => 'sdf1564fd56sd4fsdf2sd4f8sd4fgsd5f8sd4f98dsf6dsf89ds4fgsfd5asdf8s5dafdfgh8fgjgh8j',//零壹云管理平台 - 简版店铺登录密码加密盐
    'simple_safe_encrypt_key' => 'sdgtfh8gfhg8kjhgm5jh4ksf89ddxg48f9c5gf4j98sdgfhfguytsfxcbv5956556584gyudf54g8fd4gh',//零壹云管理平台 - 简版店铺全密码加密盐
    /*******************简版店铺管理系统**************************/


    /******************微信开放平台相关参数********************/

    //正式版参数
//    'wechat_open_setting' => [
//        'open_appid' => 'wxd22806e3df9e9d35', //开放平台APPID
//        'open_token' => 'yc4uE4D8OUQEy47do91QLEQc7h4UD9O7',//消息校验Token
//        'open_key' => 'o1fcrKVU3NXkPt3P7p51ufHwKFOmUCq7XhzqqiPm6sr',//消息加解密Key
//        'open_appsecret' => 'f4de34d9a9c6000d1efdbaa9462c273a',//开放平台AppSecret
//    ],

    //测试版参数
    'wechat_open_setting' => [
        'open_appid' => 'wx60b2de8797f7ef8f', //开放平台APPID
        'open_token' => 'yc4uE4D8OUQEy47do91QLEQc7h4UD9O7',//消息校验Token
        'open_key' => 'o1fcrKVU3NXkPt3P7p51ufHwKFOmUCq7XhzqqiPm6sr',//消息加解密Key
        'open_appsecret' => 'a9f148ed3e0f67bab0d67eb51ae748c9',//开放平台AppSecret
    ],


    /******************微信开放平台相关参数********************/

    /*
     * 零壹默认公众平台相关参数
     * 用于建设零壹的账号体系
     * 零壹服务号
     */
    'wechat_web_setting' => [
        'appid' => 'wx3fb8f4754008e524',
        'appsecret' => 'eff84a38864f33660994eaaa2f258fcf',
//        'appsecret' => 'e889b1da27356de8858ac9b7934fe228',
    ],


    'allowed_error_times' => 5,//允许登录错误次数


    /*
     * 零壹管理系统无需检验权限的路由
     */
    'zerone_route_except' => [
        'zerone',//零壹管理平台首页
    ],

    /*
    * 分公司管理系统无需检验权限的路由
    */
    'agent_route_except' => [
        'agent',//零壹管理平台首页
    ],


    /*
    * 零售管理系统无需检验权限的路由
    */
    'retail_route_except' => [
        'retail',//零售管理系统首页
    ],

    /*
    * 简版店铺管理系统无需检验权限的路由
    */
    'simple_route_except' => [
        'simple',//简版店铺管理系统首页
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Shanghai',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\ZeroneRedisServiceProvider::class,
        App\Providers\HttpCurlServiceProvider::class,
        App\Providers\IP2AttrServiceProvider::class,
        App\Providers\WechatServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'HttpCurl' => App\Facades\HttpCurlFacade::class,
        'IP2Attr' => App\Facades\IP2AttrFacade::class,
        'ZeroneRedis' => App\Facades\ZeroneRedisFacade::class,
        'Wechat' => App\Facades\WechatFacade::class,
        'WechatError' => App\Facades\WechatErrorFacade::class,
    ],

];
