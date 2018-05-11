<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8"/>
    <title>零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/library/jPlayer/jplayer.flat.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css"/>
    <link href="{{asset('public/Retail')}}/library/sweetalert/sweetalert.css" rel="stylesheet"/>
    <link href="{{asset('public/Retail')}}/library/wizard/css/custom.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="{{asset('public/Retail')}}/library/ie/html5shiv.js"></script>
    <script src="{{asset('public/Retail')}}/library/ie/respond.min.js"></script>
    <script src="{{asset('public/Retail')}}/library/ie/excanvas.js"></script>
    <![endif]-->
</head>
<body class="">
<section class="vbox">
    {{--头部--}}
    @include('Retail/Public/Header')
    {{--头部--}}
    <section>
        <section class="hbox stretch">
            <!-- .aside -->
        @include('Retail/Public/Nav')
        <!-- /.aside -->
            <section id="content">
                <section class="vbox">
                    <section class="scrollable padder">
                        <div class="m-b-md">
                            <h3 class="m-b-none">盛付通付款信息添加</h3>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                盛付通付款信息添加
                            </header>
                            <div class="row wrapper">
                                <div class="well">
                                    <h3>温馨提示</h3>
                                    <p class="text-danger">1.你必须向微信公众平台提交企业信息以及银行账户资料，审核通过并签约后才能使用微信支付功能</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                @if(empty($data->id))
                                    <form class="form-horizontal" method="post" role="form" id="currentForm" action="{{ url('retail/ajax/payconfig_check') }}">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-id-1">pos商户号</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="" name="sft_pos_num">
                                            </div>
                                        </div>

                                        <div class="line line-dashed b-b line-lg pull-in"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-id-1">盛付通商户号</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="" name="sft_num">
                                            </div>
                                        </div>

                                        <div class="line line-dashed b-b line-lg pull-in"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-id-1">安全密码</label>
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control" value=""
                                                       name="safe_password">
                                            </div>
                                        </div>

                                        <div class="line line-dashed b-b line-lg pull-in"></div>

                                        <div class="form-group">
                                            <div class="col-sm-12 col-sm-offset-6">

                                                <button type="button" class="btn btn-success" onclick="postForm()">确定添加
                                                </button>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>

                                    </form>
                            </div>
                            @else
                                <table class="table table-striped b-t b-light">
                                    <input type="hidden" id="_token" value="{{csrf_token()}}">
                                    <input type="hidden" id="payconfig_edit" value="{{ url('retail/ajax/payconfig_edit') }}">
                                    <input type="hidden" id="payconfig_apply" value="{{ url('retail/ajax/payconfig_apply') }}">
                                    <input type="hidden" id="payconfig_delete" value="{{ url('retail/ajax/payconfig_delete') }}">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>pos商户号</th>
                                        <th>盛付通商户号</th>
                                        <th>状态</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->sft_pos_num }}</td>
                                        <td>{{ $data->sft_num }}</td>
                                        <td>
                                            @if($data->status == '0')
                                                <label class="label label-warning">待审核</label>
                                            @elseif($data->status == '1')
                                                <label class="label label-success">已通过</label>
                                            @elseif($data->status == '-1')
                                                <label class="label label-danger">未通过</label>
                                            @endif
                                        </td>
                                        <td>{{ $data->created_at }}</td>
                                        <td>
                                            @if($data->status != '1')
                                                <button class="btn btn-info btn-xs" id="editBtn" onclick="getEditForm({{ $data->id }})"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑
                                                </button>
                                            @endif
                                            @if($data->status == '-1')
                                                <button type="button" id="lockBtn" class="btn  btn-xs btn-warning" onclick="getApplyForm('{{ $data->id }}')"><i class="icon icon-lock"></i>&nbsp;&nbsp;重新申请
                                                </button>
                                            @endif
                                            <button class="btn btn-danger btn-xs" id="deleteBtn" onclick="getDeleteComfirmForm('{{ $data->id }}')"><i class="fa fa-times"></i>&nbsp;&nbsp;解除绑定
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif
                        </section>
                    </section>
                </section>
            </section>
        </section>
    </section>
</section>
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- App -->
<script src="{{asset('public/Retail')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Retail')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Retail')}}/js/app.js"></script>
<script src="{{asset('public/Retail')}}/library/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail')}}/library/file-input/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail')}}/library/jPlayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail')}}/library/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail')}}/library/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail')}}/library/wizard/js/jquery.bootstrap.wizard.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#rootwizard').bootstrapWizard({'tabClass': 'bwizard-steps'});
        $('.selected_btn').click(function () {
            $('.selected_btn').removeClass('btn-success').addClass('btn-info');
            $(this).addClass('btn-success').removeClass('btn-info');
        });
        $('.selected_table').click(function () {
            $('.selected_table').removeClass('btn-success').addClass('btn-info');
            $(this).addClass('btn-success').removeClass('btn-info');
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
            } else if (json.status == 1) {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                }, function () {
                    window.location.reload();
                });
            } else {
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

    //编辑终端号
    function getEditForm(id) {
        var url = $('#payconfig_edit').val();
        var token = $('#_token').val();
        if (id == '') {
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
        var data = {'id': id, '_token': token};
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

    //重新申请
    function getApplyForm(id) {
        var url = $('#payconfig_apply').val();
        var token = $('#_token').val();
        if (id == '') {
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
        var data = {'id': id, '_token': token};
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

    //重新申请
    function getDeleteComfirmForm(id) {
        var url = $('#payconfig_delete').val();
        var token = $('#_token').val();
        if (id == '') {
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
        var data = {'id': id, '_token': token};
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