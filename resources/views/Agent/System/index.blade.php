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
                        <li class="active">系统首页</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>

            <div class="row state-overview">
                <div class="col-lg-6 col-sm-6">
                    <section class="panel">
                        <div class="symbol red">
                            <i class="icon-user"></i>
                        </div>
                        <div class="value">
                            <h1>{{$acc_num}}</h1>
                            <p>公司人员</p>
                        </div>
                    </section>
                </div>

                <div class="col-lg-6 col-sm-6">
                    <section class="panel">
                        <div class="symbol blue">
                            <i class="icon-sitemap"></i>
                        </div>
                        <div class="value">
                            <h1>{{$org_num}}</h1>
                            <p>商户</p>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <section class="panel">
                        <header class="panel-heading">
                            最新登录记录
                        </header>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>用户</th>
                                <th>登录IP</th>
                                <th>登录地址</th>
                                <th>登录时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($login_log_list as $Key=>$val)
                            <tr>
                                <td>{{  $val->accounts->account }}</td>
                                <td>{{  long2ip($val->ip) }}</td>
                                <td>{{  $val->ip_position }}</td>
                                <td>{{  $val->created_at }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </section>
                </div>

                <div class="col-sm-6">
                    <section class="panel">
                        <header class="panel-heading">
                            最新操作记录
                        </header>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>用户</th>
                                <th>操作</th>
                                <th>时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($operation_log_list as $key=>$val)
                                <tr>
                                    <td>{{ $val->accounts->account }}</td>
                                    <td>{{ $val->operation_info }}</td>
                                    <td>{{ $val->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>

        </section>
    </section>
    <!--main content end-->

</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="{{asset('public/Agent')}}/js/jquery.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery-1.8.3.min.js"></script>
<script src="{{asset('public/Agent')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.scrollTo.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.nicescroll.js" type="text/javascript"></script>
<!--common script for all pages-->
<script src="{{asset('public/Agent')}}/js/common-scripts.js"></script>
</body>
</html>
