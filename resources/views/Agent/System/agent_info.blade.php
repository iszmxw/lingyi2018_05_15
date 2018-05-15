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
    <link href="{{asset('public/Agent')}}/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{asset('public/Agent')}}/css/owl.carousel.css" type="text/css">
    <link href="{{asset('public/Zerone/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('public/Agent')}}/css/style.css" rel="stylesheet">
    <link href="{{asset('public/Agent')}}/css/style-responsive.css" rel="stylesheet" />

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
    </aside>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb">
                        <li><a href="#"><i class="icon-desktop"></i> 系统管理</a></li>
                        <li class="active">公司信息设置</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>

                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">
                                公司信息设置
                            </header>
                            <div class="panel-body">
                                <form class="form-horizontal tasi-form" method="post" id="currentForm" action="{{ url('agent/ajax/agent_info_check') }}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="organization_id" value="{{$data->id}}">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">所属战区</label>
                                        <div class="col-sm-10">{{$warzone->zone_name}}</div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">公司名称</label>
                                        <div class="col-sm-10"><input type="text" class="form-control" name="organization_name" value="{{$data->organization_name}}"></div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-2 control-label">公司负责人</label>
                                        <div class="col-sm-10"><input type="text" class="form-control" name="realname" value="{{$data->organizationAgentinfo->agent_owner}}"></div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-2 control-label">负责人身份证号</label>
                                        <div class="col-sm-10"><input type="text" class="form-control" name="idcard" value="{{$data->organizationAgentinfo->agent_owner_idcard}}"></div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-2 control-label">联系方式</label>
                                        <div class="col-sm-10"><input type="text" class="form-control" name="mobile" value="{{$data->organizationAgentinfo->agent_owner_mobile}}"></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">安全密码</label>
                                        <div class="col-sm-10"><input autocomplete="new-password" type="password" class="form-control" name="safe_password" value=""></div>
                                    </div>

                                    <div class="form-group" style="text-align: center;">
                                        <button type="button" onclick="postForm()" class="btn btn-shadow btn-info">保存信息</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
        </section>
    </section>
    <!--main content end-->
</section>
<script src="{{asset('public/Agent')}}/js/jquery.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery-1.8.3.min.js"></script>
<script src="{{asset('public/Agent')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.scrollTo.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.nicescroll.js" type="text/javascript"></script>
<!--common script for all pages-->
<script src="{{asset('public/Agent')}}/js/common-scripts.js"></script>
<script src="{{asset('public/Agent/library/sweetalert')}}/js/sweetalert.min.js"></script>

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
