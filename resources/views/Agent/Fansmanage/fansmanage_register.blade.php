<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>零壹新科技分公司管理平台</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('public/Agent')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Agent')}}/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{asset('public/Agent')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="{{asset('public/Agent')}}/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>

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
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb">
                        <li><a href="#"><i class="icon-sitemap"></i> 下辖商户管理</a></li>
                        <li class="active">商户注册列表</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                            <form class="form-horizontal tasi-form" method="get">

                                <div class="form-group">
                                    <label class="control-label col-lg-1" for="inputSuccess">推广商户注册链接</label>
                                    <div class="col-lg-11">
                                        <input type="text" class="form-control" value="https://www.baidu.com?id=asd&&token=456" readonly="">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">

                            <form class="form-horizontal tasi-form" method="get" action="">
                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                <div class="form-group">
                                    <label class="control-label col-lg-1" for="inputSuccess">商户名称</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control" name="fansmanage_name" value="{{ $search_data['fansmanage_name'] }}" placeholder="商户名称">
                                    </div>
                                    <label class="control-label col-lg-1" for="inputSuccess">手机号码</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control" name="fansmanage_owner_mobile" value="{{ $search_data['fansmanage_owner_mobile'] }}" placeholder="手机号码">
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-primary"><i class="icon-search"></i> 查询</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </section>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <section class="panel">
                                    <header class="panel-heading">
                                        商户注册列表
                                    </header>
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>商户名称</th>
                                            <th>负责人姓名</th>
                                            <th>身份证号</th>
                                            <th>手机号码</th>
                                            <th>申请状态</th>
                                            <th>注册时间</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($list as $key=>$value)
                                            <tr>
                                                <td>{{$value->id}}</td>
                                                <td>{{$value->fansmanage_name}}</td>
                                                <td>{{$value->fansmanage_owner}}</td>

                                                <td>{{$value->fansmanage_owner_idcard}}</td>
                                                <td>{{$value->fansmanage_owner_mobile}}</td>
                                                <td>@if($value->status == 0)<label class="label label-warning">待审核</label>
                                                    @elseif($value->status == 1)<label class="label label-primary">已通过</label>
                                                    @elseif($value->status == -1)<label class="label label-danger">已拒绝</label>
                                                    @endif
                                                </td>
                                                <td>{{$value->created_at}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="9" class="footable-visible">
                                                {{ $list->appends($search_data)->links() }}
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </section>
                            </div>
                        </div>
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

