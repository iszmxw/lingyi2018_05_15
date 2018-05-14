<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Session::get('organization_name') }}</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="{{asset('public/Wechat')}}/css/public/light7.min.css">
    <link rel="stylesheet" href="{{asset('public/Wechat')}}/css/error404.css">
  </head>
  <body>
    <div class="page">
	    <div class="g-flexview">
            <div class="error"></div>
            <p>啧啧啧~程序员又跑了</p>
            <a href="javascript:history.go(-1);location.reload()">刷新</a>
	    </div>
    </div>
    <script type='text/javascript' src="{{asset('public/Wechat')}}/js/public/jquery.min.js" charset='utf-8'></script>
    <script type='text/javascript' src="{{asset('public/Wechat')}}/js/public/light7.min.js" charset='utf-8'></script>
  </body>
</html>
