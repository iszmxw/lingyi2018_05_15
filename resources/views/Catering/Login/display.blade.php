<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>Musik | Web Application</title>
    <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="{{asset('public/Catering')}}/js/jPlayer/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Catering')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Catering')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Catering')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Catering')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Catering')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Catering')}}/css/app.css" type="text/css" />
    <!--[if lt IE 9]>
    <script src="{{asset('public/Catering')}}/js/ie/html5shiv.js"></script>
    <script src="{{asset('public/Catering')}}/js/ie/respond.min.js"></script>
    <script src="{{asset('public/Catering')}}/js/ie/excanvas.js"></script>
    <![endif]-->
</head>
<body class="bg-success dker">
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <div class="container aside-xl">
        <a class="navbar-brand block" href="index.html"><span class="h1 font-bold">ZERONE</span></a>
        <section class="m-b-lg">
            <header class="wrapper text-center">
                <strong>零壹云管理平台 - 总分店管理系统登录</strong>
            </header>
            <form role="form" id="currentForm" action="{{ url('catering/ajax/login_check') }}">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group">
                    <input type="text" name="username" placeholder="账号|手机号码" class="form-control  input-lg text-center no-border">
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="登录密码" class="form-control  input-lg text-center no-border">
                </div>
                <div class="form-group">
                    <input type="text" placeholder="验证码" class="col-sm-6 input-lg text-center no-border">
                    <img src="{{ URL('catering/login/captcha') }}/{{ time() }}" class="col-sm-6" id="login_captcha" onclick="return changeCaptcha();">
                    <div style="clear: both;"></div>
                </div>
                <button type="submit" onclick="postForm()" class="btn btn-lg btn-warning lt b-white b-2x btn-block"><i class="icon-arrow-right pull-right"></i><span class="m-r-n-lg">登录</span></button>
                <div class="line line-dashed"></div>

            </form>
        </section>
    </div>
</section>
<!-- footer -->
<footer id="footer">
    <div class="text-center padder">
        <p>
            <small>Web app framework base on Bootstrap<br>&copy; 2014</small>
        </p>
    </div>
</footer>
<!-- / footer -->
<script src="{{asset('public/Catering')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Catering')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Catering')}}/js/app.js"></script>
<script src="{{asset('public/Catering')}}/js/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Catering')}}/js/app.plugin.js"></script>
<script type="text/javascript" src="{{asset('public/Catering')}}/js/jPlayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="{{asset('public/Catering')}}/js/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script type="text/javascript" src="{{asset('public/Catering')}}/js/jPlayer/demo.js"></script>
<script>
    $(function(){
        //设置CSRF令牌
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    //更换验证码
    function changeCaptcha(){
        var url = $("#captcha_url").val();
        url = url + "/" + Math.random();
        $("#login_captcha").attr("src",url);
    }

    //提交表单
    function postForm(){
        var target = $("#currentForm");
        var url = target.attr("action");
        var data = target.serialize();

        $.post(url,data,function(json){
            if(json.status==1){
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定"
                },function(){
                    window.location.reload();
                });
            }else{
                console.log(json);
//                swal({
//                    title: "提示信息",
//                    text: json.data,
//                    confirmButtonColor:"#DD6B55",
//                    confirmButtonText: "确定",
//                    //type: "warning"
//                });
//                changeCaptcha();
            }
        });
    }
</script>
</body>
</html>
