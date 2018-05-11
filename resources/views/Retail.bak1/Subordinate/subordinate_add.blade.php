<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>下属添加 | 零壹云管理平台 | 零售管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail/library')}}/jPlayer/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css" />
    <link href="{{asset('public/Retail/library')}}/sweetalert/sweetalert.css" rel="stylesheet" />
    <link href="{{asset('public/Retail/library')}}/wizard/css/custom.css" rel="stylesheet" />
    <link href="{{asset('public/Retail/library')}}/iCheck/css/custom.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="{{asset('public/Retail/library')}}/ie/html5shiv.js"></script>
    <script src="{{asset('public/Retail/library')}}/ie/respond.min.js"></script>
    <script src="{{asset('public/Retail/library')}}/ie/excanvas.js"></script>
    <![endif]-->
</head>
<body class="">
<section class="vbox">
    {{--头部--}}
    @include('Retail/Public/Header')
    {{--头部--}}
    <section>
        <section class="hbox stretch">
            <!-- .aside -->
            @include('Retail/Public/Nav')
            <!-- /.aside -->
            <section id="content">
                <section class="vbox">
                    <section class="scrollable padder">
                        <div class="m-b-md">
                            <h3 class="m-b-none">操作员添加</h3>
                        </div>
                        <section class="panel">
                            <header class="panel-heading">
                                添加操作员
                            </header>
                            <div class="panel-body">
                                <form  method="post" class="form-horizontal" id="currentForm" action="{{ url('retail/ajax/subordinate_add_check') }}">
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                    <div id="rootwizard">
                                        <ul class="bwizard-steps">
                                            <li class="active"><a href="#tab1" data-toggle="tab"><span style="color:#999;" class="label">1</span> 填写基础资料</a></li>
                                        </ul>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab1">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">手机号码</label>
                                                    <div class="col-sm-10"><input type="number" class="form-control" name="mobile"></div>
                                                </div>
                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">用户密码</label>
                                                    <div class="col-sm-10"><input type="text" class="form-control" name="password"></div>
                                                </div>
                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">重复密码</label>
                                                    <div class="col-sm-10"><input type="text" class="form-control" name="repassword"></div>
                                                </div>
                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">真实姓名</label>
                                                    <div class="col-sm-10"><input type="text" class="form-control" name="realname"></div>
                                                </div>

                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">输入安全密码确认创建</label>
                                                    <div class="col-sm-5"><input type="text" class="form-control" name="safe_password"></div>
                                                </div>
                                            </div>
                                            <ul class="pager wizard">
                                                <li class="finish">
                                                    <button type="button" id="addBtn" class="btn btn-success" onclick="return postForm();">创建账号&nbsp;&nbsp;<i class="icon-arrow-right"></i></button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>

                    </section>
                </section>
            </section>
        </section>
    </section>
</section>
<script src="{{asset('public/Retail')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Retail')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Retail')}}/js/app.js"></script>
<script src="{{asset('public/Retail')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail/library')}}/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail/library')}}/file-input/bootstrap-filestyle.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/jquery.jplayer.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script src="{{asset('public/Retail/library')}}/sweetalert/sweetalert.min.js"></script>
<script src="{{asset('public/Retail/library')}}/wizard/js/jquery.bootstrap.wizard.min.js"></script>
<script src="{{asset('public/Retail/library')}}/iCheck/js/icheck.min.js"></script>
<script type="text/javascript">
    //提交表单
    function postForm() {
        var target = $("#currentForm");
        var url = target.attr("action");
        var data = target.serialize();
        $.post(url, data, function (json) {
            if (json.status == -1) {
                window.location.reload();
            } else if (json.status == 1) {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                }, function () {
                    window.location.reload();
                });
            } else {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                });
            }
        });
    }
</script>
</body>
</html>
























