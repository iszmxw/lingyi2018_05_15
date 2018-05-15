<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>零壹新科技程序管理平台</title>
    <link href="{{asset('public/Zerone/library/bootstrap')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/font')}}/css/font-awesome.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">

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
                <h2>“{{$listOrg['organization_name']}}”程序管理</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">分公司管理</a>
                    </li>
                    <li >
                        <strong>“{{$listOrg['organization_name']}}”程序管理</strong>
                    </li>
                </ol>
            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">


            <div class="ibox-content m-b-sm border-bottom">

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label" for="amount"> &nbsp;</label>
                            <button type="button" onclick="history.back()" class="block btn btn-info"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回列表</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                <input type="hidden" name="organization_id" id="organization_id" value="{{$listOrg['id']}}">
                <input type="hidden" id="agent_assets" value="{{ url('zerone/ajax/agent_assets') }}">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>“{{$listOrg['organization_name']}}”程序管理</h5>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-stripped toggle-arrow-tiny" data-page-size="15">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>程序名字</th>
                                    <th>剩余程序</th>
                                    <th>使用程序</th>
                                    <th>添加时间</th>
                                    <th class="text-right">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $key=>$value)
                                <tr>
                                    <td>{{$value->id}}</td>
                                    <td>{{$value->program_name}}</td>
                                    <td><div> <span class="label label-primary">剩余：@if(!empty($value->program_balance)){{$value->program_balance}}@else 0 @endif套</span>&nbsp;&nbsp;</div></td>

                                    <td><div><span class="label label-warning">已用：@if(!empty($value->program_used_num)){{$value->program_used_num}}@else 0 @endif套</span>&nbsp;&nbsp;</div></td>
                                    <td>{{$value->created_at}}</td>
                                    <td class="text-right">
                                        <button class="btn btn-info btn-xs" onclick="getAssetsAdd('{{$value->id}}','1')"><i class="icon-arrow-down"></i>&nbsp;&nbsp;程序划入</button>
                                        <button class="btn btn-primary btn-xs" onclick="getAssetsReduce('{{$value->id}}','0')"><i class="icon-arrow-up"></i>&nbsp;&nbsp;程序划出</button>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="99">
                                        <ul class="pagination pull-right">
                                            {{$list->links()}}
                                        </ul>
                                    </td>
                                </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('Zerone/Public/Footer')
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
</div>
<script src="{{asset('public/Zerone/library/jquery')}}/js/jquery-2.1.1.js"></script>
<script src="{{asset('public/Zerone/library/bootstrap')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Zerone/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
<script src="{{asset('public/Zerone/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('public/Zerone')}}/js/inspinia.js"></script>
<script src="{{asset('public/Zerone/library/pace')}}/js/pace.min.js"></script>
<script src="{{asset('public/Zerone/library/iCheck')}}/js/icheck.min.js"></script>
<script src="{{asset('public/Zerone/library/sweetalert')}}/js/sweetalert.min.js"></script>
<!-- Page-Level Scripts -->

<script>
    //程序划入
    function getAssetsAdd(program_id,status) {

        var url = $('#agent_assets').val();
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
        var url = $('#agent_assets').val();
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
