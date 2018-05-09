<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8"/>
    <title>下属列表 | 零壹云管理平台 | 店铺店管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail/library')}}/jPlayer/jplayer.flat.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css"/>
    <link href="{{asset('public/Retail/library')}}/sweetalert/sweetalert.css" rel="stylesheet"/>
    <link href="{{asset('public/Retail/library')}}/iCheck/css/custom.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="{{asset('public/Retail/library')}}/ie/html5shiv.js"></script>
    <script src="{{asset('public/Retail/library')}}/ie/respond.min.js"></script>
    <script src="{{asset('public/Retail/library')}}/ie/excanvas.js"></script>
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
                            <h3 class="m-b-none">操作员列表</h3>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                操作员列表
                            </header>
                            <div class="row wrapper">
                                <form class="form-horizontal" method="get">
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                    <input type="hidden" id="subordinate_edit_url"
                                           value="{{ url('retail/ajax/subordinate_edit') }}">
                                    <input type="hidden" id="subordinate_lock"
                                           value="{{ url('retail/ajax/subordinate_lock') }}">
                                    <input type="hidden" id="subordinate_delete"
                                           value="{{ url('retail/ajax/subordinate_delete') }}">
                                    <input type="hidden" id="subordinate_authorize_url"
                                           value="{{ url('retail/ajax/subordinate_authorize') }}">
                                    <label class="col-sm-1 control-label">操作员账号</label>
                                    <div class="col-sm-2">
                                        <input class="input-sm form-control" size="16" name="account" type="text"
                                               value="{{$search_data['account']}}">
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-s-md btn-info"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped b-t b-light">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>用户账号</th>
                                        <th>真实姓名</th>
                                        <th>手机号码</th>
                                        <th>用户状态</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $key=>$val)
                                        <tr>
                                            <td>{{ $val->id }}</td>
                                            <td>{{ $val->account }}</td>
                                            <td>@if(!empty($val->account_info)){{$val->account_info->realname }}@else
                                                    <label class="label label-danger">未绑定</label> @endif</td>
                                            <td>{{ $val->mobile }}</td>
                                            <td>
                                                @if($val->status == '1')
                                                    <label class="label label-success">正常</label>
                                                @else
                                                    <label class="label label-warning">已冻结</label>
                                                @endif
                                            </td>
                                            <td>{{ $val->created_at }}</td>
                                            <td>
                                                <button class="btn btn-info btn-xs" id="editBtn"
                                                        onclick="getEditForm({{ $val->id }})"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑
                                                </button>
                                                @if($val->status=='1')
                                                    <button type="button" id="lockBtn" class="btn  btn-xs btn-warning"
                                                            onclick="getLockComfirmForm('{{ $val->id }}','{{ $val->account }}','{{ $val->status }}')">
                                                        <i class="icon icon-lock"></i>&nbsp;&nbsp;冻结
                                                    </button>
                                                @else
                                                    <button type="button" id="lockBtn" class="btn  btn-xs btn-success"
                                                            onclick="getLockComfirmForm('{{ $val->id }}','{{ $val->account }}','{{ $val->status }}')">
                                                        <i class="icon icon-lock"></i>&nbsp;&nbsp;解冻
                                                    </button>
                                                @endif
                                                {{--<button class="btn btn-danger btn-xs" id="deleteBtn" onclick="getDeleteComfirmForm('{{ $val->id }}','{{ $val->account }}')"><i class="fa fa-times"></i>&nbsp;&nbsp;删除</button>--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <footer class="panel-footer">
                                <div class="row">

                                    <div class="col-sm-12 text-right text-center-xs">
                                        <ul class="pagination pull-right">
                                            {{ $list->appends($search_data)->links() }}
                                        </ul>
                                    </div>
                                </div>
                            </footer>
                        </section>

                    </section>
                </section>
            </section>
        </section>
    </section>
</section>
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"></div>
<script src="{{asset('public/Retail')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Retail')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Retail')}}/js/app.js"></script>
<script src="{{asset('public/Retail')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail/library')}}/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail/library')}}/file-input/bootstrap-filestyle.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/jquery.jplayer.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script src="{{asset('public/Retail/library')}}/sweetalert/sweetalert.min.js"></script>
<script src="{{asset('public/Retail/library')}}/iCheck/js/icheck.min.js"></script>
<script type="text/javascript">
    //冻结用户-解冻
    function getLockComfirmForm(id, account, status) {
        var url = $('#subordinate_lock').val();
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
        var data = {'id': id, 'account': account, 'status': status, '_token': token};
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
    //获取用户信息，编辑密码框
    function getEditForm(id) {
        var url = $('#subordinate_edit_url').val();
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
























