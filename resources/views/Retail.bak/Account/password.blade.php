<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>登录密码修改 | 零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail/library/jPlayer')}}/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail/library/sweetalert')}}/sweetalert.css">
    <!--[if lt IE 9]>
    <script src="{{asset('public/Retail/library/ie')}}/html5shiv.js"></script>
    <script src="{{asset('public/Retail/library/ie')}}/respond.min.js"></script>
    <script src="{{asset('public/Retail/library/ie')}}/excanvas.js"></script>
    <![endif]-->
</head>
<body class="">
<section class="vbox">
    @include('Retail/Public/Header')
    <section>
        <section class="hbox stretch">
            <!-- .aside -->
        @include('Retail/Public/Nav')
        <!-- /.aside -->
            <section id="content">
                <section class="vbox">
                    <section class="scrollable padder">
                        <div class="m-b-md">
                            <h3 class="m-b-none">登录密码</h3>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading font-bold">
                                登录密码修改
                            </header>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal"  role="form" id="currentForm" action="{{ url('retail/ajax/password_edit_check') }}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">登录账号</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" disabled="" value="@if($admin_data['is_super']==1) {{$account['account']}} @else {{$admin_data['account']}} @endif">
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">原登录密码</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">新登录密码</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="new_password" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">重复新密码</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="news_password" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">安全密码</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="safe_password" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <div class="col-sm-12 col-sm-offset-6">
                                            <button type="button" class="btn btn-success" id="addBtn" onclick="return postForm();">保存信息</button>
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                </form>
                            </div>
                        </section>
                    </section>
                </section>
            </section>
        </section>
    </section>
</section>

<!-- App -->
<script src="{{asset('public/Retail')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Retail')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Retail')}}/js/app.js"></script>
<script src="{{asset('public/Retail/library/slimscroll')}}/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail/library/file-input')}}/bootstrap-filestyle.min.js"></script>
<script src="{{asset('public/Retail/library/jPlayer')}}/jquery.jplayer.min.js"></script>
<script src="{{asset('public/Retail/library/jPlayer')}}/add-on/jplayer.playlist.min.js"></script>
<script src="{{asset('public/Retail/library/sweetalert')}}/sweetalert.min.js"></script>
<script>
    //提交表单
    function postForm() {
        var target = $("#currentForm");
        var url = target.attr("action");
        var data = target.serialize();
        $.post(url, data, function (json) {
            if (json.status == -1) {
                window.location.reload();
            } else if(json.status == 1) {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                },function(){
                    window.location.reload();
                });
            }else{
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                    //type: "warning"
                });
            }
        });
    }
</script>
</body>
</html>