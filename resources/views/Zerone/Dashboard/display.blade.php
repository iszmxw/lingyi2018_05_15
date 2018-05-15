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

</head>

<body class="">

<div id="wrapper">

    @include('Zerone/Public/Nav')

    <div id="page-wrapper" class="gray-bg">
        @include('Zerone/Public/Header')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-4">
                <h2>管理首页</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="JavaScript:;">系统管理</a>
                    </li>
                    <li class="active">
                        <strong>管理首页</strong>
                    </li>
                </ol>
            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">零壹管理系统</span>
                            <h5>管理人员</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{$zerone['system_personnel']}}人</h1>
                            <div class="stat-percent font-bold text-info">20人 <i class="fa fa-level-up"></i></div>
                            <small>今日</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-primary pull-right">分公司系统</span>
                            <h5>管理人员</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{$zerone['service_providers']}}人</h1>
                            <div class="stat-percent font-bold text-navy">88人 <i class="fa fa-level-up"></i></div>
                            <small>今日</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-danger pull-right">商户系统</span>
                            <h5>管理人员</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{$zerone['merchant_system']}}人</h1>
                            <div class="stat-percent font-bold text-danger">18人 <i class="fa fa-level-up"></i></div>
                            <small>今日</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">所有业务系统</span>
                            <h5>管理人员</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{$zerone['all_system_personnel']}}人</h1>
                            <div class="stat-percent font-bold text-success">1680人<i class="fa fa-level-up"></i></div>
                            <small>今日</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-lg-12">
                    <div class="ibox-content forum-container">

                        <div class="forum-title">

                            <h3>分公司统计</h3>
                        </div>

                        <div class="forum-item active">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-line-chart"></i>
                                    </div>
                                    <a href="javascript:;" class="forum-item-title">分公司数量</a>
                                    <div class="forum-sub-title">进驻零壹平台的分公司的数量</div>
                                </div>


                                <div class="col-md-3 forum-info">
                                            <span class="views-number">
                                                {{$zerone['service_provider']}}家
                                            </span>
                                    <div>
                                        <small>总计</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="forum-item active">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-line-chart"></i>
                                    </div>
                                    <a href="javascript:;" class="forum-item-title">商户数量</a>
                                    <div class="forum-sub-title">注册零壹平台的商户的数量</div>
                                </div>

                                <div class="col-md-3 forum-info">
                                            <span class="views-number">
                                                {{$zerone['merchant']}}家
                                            </span>
                                    <div>
                                        <small>总计</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="forum-item active">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-line-chart"></i>
                                    </div>
                                    <a href="javascript:;" class="forum-item-title">店铺数量</a>
                                    <div class="forum-sub-title">商户在零壹平台开设的店铺数量</div>
                                </div>
                                <div class="col-md-3 forum-info">
                                            <span class="views-number">
                                                {{$zerone['shop']}}
                                            </span>
                                    <div>
                                        <small>总计</small>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
            <div class="row wrapper wrapper-content animated fadeInRight">
                <div class="col-lg-7">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>用户登录日志</h5>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-hover no-margins">
                                <thead>
                                <tr>
                                    <th>用户</th>
                                    <th>登录IP</th>
                                    <th>登录地址</th>
                                    <th>登录时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($login_log_list as $key=>$val)
                                <tr>
                                    <td>
                                        @if(empty($val->accounts))
                                            未知用户
                                        @else
                                            {{  $val->accounts->account }}
                                        @endif
                                    </td>
                                    <td>{{  long2ip($val->ip) }}</td>
                                    <td>{{  $val->ip_position }}</td>
                                    <td>{{  $val->created_at }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>用户操作日志</h5>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-hover no-margins">
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
                                        <td>
                                            @if(empty($val->accounts))
                                                未知用户
                                            @else
                                                {{  $val->accounts->account }}
                                            @endif
                                        </td>
                                        <td>{{ $val->operation_info }}</td>
                                        <td>{{ $val->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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

<!-- Custom and plugin javascript -->
<script src="{{asset('public/Zerone')}}/js/inspinia.js"></script>
<script src="{{asset('public/Zerone/library/pace')}}/js/pace.min.js"></script>

</body>

</html>
