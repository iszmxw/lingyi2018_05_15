<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.html">

    <title>零壹新科技分公司管理平台</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('public/Agent')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Agent')}}/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{asset('public/Agent')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{asset('public/Agent')}}/css/style.css" rel="stylesheet">
    <link href="{{asset('public/Agent')}}/css/style-responsive.css" rel="stylesheet" />
    <link href="{{asset('public/Agent/library/iCheck')}}/css/custom.css" rel="stylesheet" />
    <link href="{{asset('public/Agent/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{asset('public/Agent')}}/js/html5shiv.js"></script>
    <script src="{{asset('public/Agent')}}/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<section id="container" class="">
    <!--header start-->
    <header class="header white-bg">
        @include('Agent/Public/Header')
    </header>
    <!--header end-->
    <!--sidebar start-->
    <aside>
        @include('Agent/Public/Nav')
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <!--breadcrumbs start -->
                        <ul class="breadcrumb">
                            <li><a href="#"><i class="icon-user"></i> 个人信息</a></li>
                            <li class="active">个人信息</li>
                        </ul>
                        <!--breadcrumbs end -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-5">
                        <section class="panel">
                            <header class="panel-heading">
                                个人信息修改
                            </header>
                            <div class="panel-body">
                                <form class="form-horizontal tasi-form" method="post" id="currentForm" action="{{ url('agent/ajax/account_info_check') }}">
                                    <input type="hidden" name="_token"  value="{{csrf_token()}}">
                                    <input type="hidden" name="id" value="{{$user['id']}}">
                                    <input type="hidden" name="organization_id" value="{{$user['organization_id']}}">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">用户账号</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{$user['account']}}" disabled="true" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">用户角色</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{$admin_data['role_name']}}" disabled="true" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">真实姓名</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$user['account_info']['realname']}}" name="realname">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">联系方式</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$user['mobile']}}" name="mobile">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">安全密码 </label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="safe_password" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group" style="text-align: center;">
                                        <button type="button" onclick="postForm()" class="btn btn-shadow btn-info">保存信息</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-7">
                        <section class="panel">
                            <header class="panel-heading">
                                我的权限
                            </header>
                            <div class="panel-body">
                                @foreach($module_node_list as $key=>$val)
                                <div>
                                    <label class="i-checks">
                                        <input type="checkbox" value="option1" id="inlineCheckbox1" checked="checked" disabled=""> {{ $val['module_name'] }}
                                    </label>
                                </div>
                                <div>
                                    @foreach($val['program_nodes'] as $kk=>$vv)
                                    <label class="i-checks">
                                        <input type="checkbox" value="option1" id="inlineCheckbox1" checked="checked" disabled=""> {{ $vv['node_name'] }}
                                    </label>
                                    &nbsp;&nbsp;
                                    @endforeach
                                </div>
                                <div style="margin-top: 10px;"></div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </section>
    </aside>
</section>

<script src="{{asset('public/Agent')}}/js/jquery.js"></script>
<script src="{{asset('public/Agent')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.scrollTo.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="{{asset('public/Agent')}}/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="{{asset('public/Agent')}}/js/common-scripts.js"></script>
<script src="{{asset('public/Agent/library/iCheck')}}/js/icheck.min.js"></script>
<script src="{{asset('public/Agent/library/sweetalert')}}/js/sweetalert.min.js"></script>

<!--script for this page-->
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
                    confirmButtonText: "确定"
                },function(){
                    window.location.reload();
                });
            }else{
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定"
                });
            }
        });
    }

    //custom select box

    $(function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
</script>
</body>
</html>
