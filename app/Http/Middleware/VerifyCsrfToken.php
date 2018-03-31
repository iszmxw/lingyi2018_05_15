<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'api/wechat/response/*',
        'api/wechat/open',

        /****Android接口****/
        'api/androidapi/login',
        'api/androidapi/goodscategory'
        /****Android接口****/

    ];
}
