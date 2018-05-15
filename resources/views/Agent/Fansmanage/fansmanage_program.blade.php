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
    <link href="{{asset('public/Agent/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">

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
                        <li><a href="#"><i class="icon-code"></i> 下辖商户管理</a></li>
                        <li class="active">程序划拨管理</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                            <button type="button" class="btn btn-info" onclick="history.back()"><i class="icon-reply"></i> 返回列表</button>
                        </div>
                    </section>
                    <section class="panel">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="organization_id" id="organization_id" value="{{$oneFansmanage->id}}">
                                <input type="hidden" id="fansmanage_assets" value="{{ url('agent/ajax/fansmanage_assets') }}">
                                <section class="panel">
                                    <header class="panel-heading">
                                        商户："{{$oneFansmanage->organization_name}}"程序划拨
                                    </header>
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>程序名称</th>
                                            <th>剩余数量</th>
                                            <th>使用数量</th>
                                            <th>添加时间</th>
                                            <th class="text-right">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{$data->id}}</td>
                                            <td><span class="label label-danger"><i class="icon-code"></i> {{$data->program_name}}</span></td>
                                            <td> <span class="label label-primary">剩余：@if($data->program_balance){{$data->program_balance}}@else 0 @endif 套</span></td>
                                            <td><span class="label label-warning">已用：@if($data->program_used_num){{$data->program_used_num}}@else 0 @endif 套</span></td>
                                            <td>{{$data->created_at}}</td>
                                            <td class="text-right">
                                                <button class="btn btn-info btn-xs" id="addBtn" onclick="getAssetsAdd('{{$data->id}}','1')"><i class="icon-arrow-down"></i>&nbsp;&nbsp;程序划给</button>
                                                <button class="btn btn-primary btn-xs" id="minuBtn" onclick="getAssetsReduce('{{$data->id}}','0')"><i class="icon-arrow-up"></i>&nbsp;&nbsp;程序收回</button>
                                            </td>
                                        </tr>
                                        </tbody>

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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>


<!-- js placed at the end of the document so the pages load faster -->
<script src="{{asset('public/Agent')}}/js/jquery-1.8.3.min.js"></script>
<script src="{{asset('public/Agent')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.scrollTo.min.js"></script>
<script src="{{asset('public/Agent')}}/js/jquery.nicescroll.js" type="text/javascript"></script>

<!--common script for all pages-->
<script src="{{asset('public/Agent')}}/js/common-scripts.js"></script>
<!-- js placed at the end of the document so the pages load faster -->
<script src="{{asset('public/Agent')}}/js/jquery.nestable.js"></script>
<!-- Custom and plugin javascript -->
<script src="{{asset('public/Agent/library/iCheck')}}/js/icheck.min.js"></script>
<script src="{{asset('public/Agent/library/sweetalert')}}/js/sweetalert.min.js"></script>
<!-- Page-Level Scripts -->
<script>
    //程序划入
    function getAssetsAdd(program_id,status) {
        var url = $('#fansmanage_assets').val();
        var token = $('#_token').val();
        var organization_id = $('#organization_id').val();
        if (program_id == '') {
            swal({
                title: "提示信息",
                text: '数据传输错误',
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
            }, function () {
                window.location.reload();
            });
            return;
        }

        var data = {'program_id': program_id, 'status':status, 'organization_id':organization_id, '_token': token};
        $.post(url, data, function (response) {
            if (response.status == '-1') {
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                }, function () {
                    window.location.reload();
                });
                return;
            } else {
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }
    //程序划出
    function getAssetsReduce(program_id,status) {

        var url = $('#fansmanage_assets').val();
        var token = $('#_token').val();
        var organization_id = $('#organization_id').val();
        if (program_id == '') {
            swal({
                title: "提示信息",
                text: '数据传输错误',
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
            }, function () {
                window.location.reload();
            });
            return;
        }

        var data = {'program_id': program_id, 'status':status, 'organization_id':organization_id, '_token': token};
        $.post(url, data, function (response) {
            if (response.status == '-1') {
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                }, function () {
                    window.location.reload();
                });
                return;
            } else {

                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }
</script>
</body>
</html>
