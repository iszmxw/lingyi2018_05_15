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
                        <li class="active">商户列表</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                            <form class="form-horizontal tasi-form" method="get" action="">
                                <div class="form-group">
                                    <label class="control-label col-lg-1" for="inputSuccess">商户名称</label>
                                    <div class="col-lg-2">
                                        <input type="text" name="organization_name" class="form-control" value="{{ $search_data['organization_name'] }}" placeholder="商户名称">
                                    </div>
                                    {{--<label class="control-label col-lg-1" for="inputSuccess">手机号码</label>--}}
                                    {{--<div class="col-lg-2">--}}
                                        {{--<input type="text" name="fansmanage_owner_mobile" class="form-control" value="{{ $search_data['fansmanage_owner_mobile'] }}" placeholder="手机号码">--}}
                                    {{--</div>--}}
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
                                        商户列表
                                    </header>
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>商户名称</th>
                                            <th>负责人姓名</th>
                                            <th>商户账号</th>
                                            <th>手机号码</th>
                                            <th>商户状态</th>
                                            <th>注册时间</th>
                                            <th class="text-right" >操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                        @foreach($list as $key=>$val)
                                            <td>{{$val->id}}</td>
                                            <td>{{$val->organization_name}}</td>
                                            <td>{{$val->fansmanageinfo->fansmanage_owner}}</td>
                                            <td>{{$val->account->account}}</td>
                                            <td>{{$val->fansmanageinfo->fansmanage_owner_mobile}}</td>
                                            <td>
                                                @if($val->status ==1)
                                                <label class="label label-primary">正常</label>
                                                @else
                                                    <label class="label label-danger">冻结</label>
                                                @endif
                                            </td>
                                            <td>{{$val->created_at}}</td>
                                            <td class="text-right">
                                                <button type="button" id="peoplesBtn" onclick="location.href='{{url('agent/fansmanage/fansmanage_structure')}}?organization_id={{$val->id}}'" class="btn btn-outline btn-xs btn-primary"><i class="icon-sitemap"></i>&nbsp;&nbsp;店铺结构</button>
                                                <button type="button" id="programBtn" onclick="location.href='{{url('agent/fansmanage/fansmanage_program')}}?organization_id={{$val->id}}'" class="btn btn-outline btn-xs btn-info"><i class="icon-code"></i>&nbsp;&nbsp;程序划拨</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="99">
                                                <ul class="pagination pull-right">

                                                </ul>
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

