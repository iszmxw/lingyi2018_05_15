<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>零壹新科技程序管理平台</title>

    <link href="{{asset('public/Program/library/bootstrap')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/font')}}/css/font-awesome.css" rel="stylesheet">

    <link href="{{asset('public/Program')}}/css/animate.css" rel="stylesheet">
    <link href="{{asset('public/Program')}}/css/style.css" rel="stylesheet">

</head>

<body class="">

<div id="wrapper">

    @include('Program/Public/Nav')

    <div id="page-wrapper" class="gray-bg">
        @include('Program/Public/Header')
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
            <div class="row ">
                <div class="col-lg-12">
                    <div class="ibox-content forum-container">

                        <div class="forum-title">

                            <h3>管理人员统计</h3>
                        </div>

                        <div class="forum-item active">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-area-chart"></i>
                                    </div>
                                    <a href="javascript:;" class="forum-item-title">程序数量</a>
                                    <div class="forum-sub-title">零壹开发的所有程序数量</div>
                                </div>


                                <div class="col-md-3 forum-info">
                                            <span class="views-number">
                                                14套
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
                                        <i class="fa fa-area-chart"></i>
                                    </div>
                                    <a href="javascript:;" class="forum-item-title">功能模块数量</a>
                                    <div class="forum-sub-title">零壹开发的所有功能模块的数量</div>
                                </div>

                                <div class="col-md-3 forum-info">
                                            <span class="views-number">
                                                150个
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
                                        <i class="fa fa-area-chart"></i>
                                    </div>
                                    <a href="javascript:;" class="forum-item-title">功能节点数量</a>
                                    <div class="forum-sub-title">组成零壹所有系统的程序节点数量</div>
                                </div>
                                <div class="col-md-3 forum-info">
                                            <span class="views-number">
                                                1300
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
                            <h5>用户登陆日志</h5>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-hover no-margins">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>用户</th>
                                    <th>登陆IP</th>
                                    <th>登陆地址</th>
                                    <th>登陆时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $key=>$val)
                                    <tr>
                                        <td>{{  $val->id }}</td>
                                        <td>{{  $val->account }}</td>
                                        <td>{{  long2ip($val->ip) }}</td>
                                        <td>{{  $val->ip_position }}</td>
                                        <td>{{  $val->created_at }}0</td>
                                    </tr>
                                @endforeach
                                {{--<tr>--}}
                                    {{--<td>admin</td>--}}
                                    {{--<td>192.168.0.0.1</td>--}}
                                    {{--<td>广东深圳</td>--}}
                                    {{--<td>2017-11-24 10:41:20</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td>admin</td>--}}
                                    {{--<td>192.168.0.0.1</td>--}}
                                    {{--<td>广东深圳</td>--}}
                                    {{--<td>2017-11-24 10:41:20</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td>admin</td>--}}
                                    {{--<td>192.168.0.0.1</td>--}}
                                    {{--<td>广东深圳</td>--}}
                                    {{--<td>2017-11-24 10:41:20</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td>admin</td>--}}
                                    {{--<td>192.168.0.0.1</td>--}}
                                    {{--<td>广东深圳</td>--}}
                                    {{--<td>2017-11-24 10:41:20</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td>admin</td>--}}
                                    {{--<td>192.168.0.0.1</td>--}}
                                    {{--<td>广东深圳</td>--}}
                                    {{--<td>2017-11-24 10:41:20</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td>admin</td>--}}
                                    {{--<td>192.168.0.0.1</td>--}}
                                    {{--<td>广东深圳</td>--}}
                                    {{--<td>2017-11-24 10:41:20</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td>admin</td>--}}
                                    {{--<td>192.168.0.0.1</td>--}}
                                    {{--<td>广东深圳</td>--}}
                                    {{--<td>2017-11-24 10:41:20</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td>admin</td>--}}
                                    {{--<td>192.168.0.0.1</td>--}}
                                    {{--<td>广东深圳</td>--}}
                                    {{--<td>2017-11-24 10:41:20</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td>admin</td>--}}
                                    {{--<td>192.168.0.0.1</td>--}}
                                    {{--<td>广东深圳</td>--}}
                                    {{--<td>2017-11-24 10:41:20</td>--}}
                                {{--</tr>--}}
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
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                <tr>
                                    <td>admin</td>

                                    <td>修改了登陆密码</td>
                                    <td>2017-11-24 10:41:20</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('Program/Public/Footer')
    </div>
</div>

<!-- Mainly scripts -->
<script src="{{asset('public/Program/library/jquery')}}/js/jquery-2.1.1.js"></script>
<script src="{{asset('public/Program/library/bootstrap')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Program/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
<script src="{{asset('public/Program/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('public/Program')}}/js/inspinia.js"></script>
<script src="{{asset('public/Program/library/pace')}}/js/pace.min.js"></script>

</body>

</html>
