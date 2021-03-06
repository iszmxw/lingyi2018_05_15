<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>零壹新科技程序管理平台</title>
    <link href="{{asset('public/Zerone/library/bootstrap')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/font')}}/css/font-awesome.css" rel="stylesheet">
    <link href="{{asset('public/Zerone')}}/css/animate.css" rel="stylesheet">
    <link href="{{asset('public/Zerone')}}/css/style.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/iCheck')}}/css/custom.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">
</head>
<body class="">
<div id="wrapper">
    @include('Zerone/Public/Nav')
    <div id="page-wrapper" class="gray-bg">
        @include('Zerone/Public/Header')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-4">
                <h2>安全密码设置</h2>
                <ol class="breadcrumb">
                    <li class="active"> <a href="JavaScript:;">个人中心</a> </li>
                    <li > <strong>安全密码设置</strong> </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight ecommerce">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>安全密码设置</h5>
                        </div>
                        <div class="ibox-content">
                            <form method="post" class="form-horizontal"  role="form" id="currentForm" action="{{ url('zerone/ajax/safe_password_edit_check') }}">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">登录账号</label>
                                    <div class="col-sm-10" style="padding-top:7px;">{{$admin_data['account']}}</div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                @if($admin_data['safe_password'] == '')
                                    <input type="hidden" name="is_editing" value="-1">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">设置安全密码</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="safe_password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">重复安全密码</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="re_safe_password" class="form-control">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="is_editing" value="1">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">原安全密码</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="old_safe_password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">新安全密码</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="safe_password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">重复新密码</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="re_safe_password" class="form-control">
                                        </div>
                                    </div>
                                @endif
                                <div class="hr-line-dashed"></div>
                                <div class="form-group ">
                                    <div class="col-sm-4 col-sm-offset-5">
                                        <button class="btn btn-primary" id="addbtn" onclick="return postForm();" type="button">确认修改</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('Zerone/Public/Footer')
    </div>
</div>

<!-- Mainly scripts -->
<script src="{{asset('public/Zerone/library/jquery')}}/js/jquery-2.1.1.js"></script>
<script src="{{asset('public/Zerone/library/bootstrap')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Zerone/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
<script src="{{asset('public/Zerone/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Zerone/library/iCheck')}}/js/icheck.min.js"></script>
<!-- Custom and plugin javascript -->
<script src="{{asset('public/Zerone')}}/js/inspinia.js"></script>
<script src="{{asset('public/Zerone/library/sweetalert')}}/js/sweetalert.min.js"></script>
<script src="{{asset('public/Zerone/library/pace')}}/js/pace.min.js"></script>
<script>
    $(document).ready(function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
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
                    window.location.reload('zerone/login');
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
