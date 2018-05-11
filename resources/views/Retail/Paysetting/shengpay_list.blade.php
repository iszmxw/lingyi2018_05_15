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
                            <h3 class="m-b-none">终端机器列表</h3>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                终端机器列表
                            </header>
                            <div class="row wrapper">
                                <form class="form-horizontal" method="get">
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                    <input type="hidden" id="shengpay_edit" value="{{ url('retail/ajax/shengpay_edit') }}">
                                    <input type="hidden" id="shengpay_apply" value="{{ url('retail/ajax/shengpay_apply') }}">
                                    <input type="hidden" id="shengpay_delete" value="{{ url('retail/ajax/shengpay_delete') }}">
                                    <label class="col-sm-1 control-label">终端机器号</label>

                                    <div class="col-sm-2">
                                        <input class="input-sm form-control" size="16" name="terminal_num" type="text" value="{{$search_data['terminal_num']}}">
                                    </div>

                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-s-md btn-info"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索</button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped b-t b-light">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>盛付通终端号</th>
                                        <th>状态</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $key=>$val)
                                        <tr>
                                            <td>{{ $val->id }}</td>
                                            <td>{{ $val->terminal_num }}</td>
                                            <td>
                                                @if($val->status == '0')
                                                    <label class="label label-warning">待审核</label>
                                                @elseif($val->status == '1')
                                                    <label class="label label-success">已通过</label>
                                                @elseif($val->status == '-1')
                                                    <label class="label label-danger">未通过</label>
                                                @endif
                                            </td>
                                            <td>{{ $val->created_at }}</td>
                                            <td>
                                                @if($val->status != '1')
                                                <button class="btn btn-info btn-xs" id="editBtn" onclick="getEditForm({{ $val->id }})"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑</button>
                                                @endif

                                            @if($val->status == '-1')
                                                    <button type="button" id="lockBtn" class="btn  btn-xs btn-warning" onclick="getApplyForm('{{ $val->id }}')"><i class="icon icon-lock"></i>&nbsp;&nbsp;重新申请</button>
                                                @endif
                                                <button class="btn btn-danger btn-xs" id="deleteBtn" onclick="getDeleteComfirmForm('{{ $val->id }}')"><i class="fa fa-times"></i>&nbsp;&nbsp;解除绑定</button>
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
    //编辑终端号
    function getEditForm(id){
        var url = $('#shengpay_edit').val();
        var token = $('#_token').val();
        if(id==''){
            swal({
                title: "提示信息",
                text: '数据传输错误',
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
            },function(){
                window.location.reload();
            });
            return;
        }
        var data = {'id':id,'_token':token};
        $.post(url,data,function(response){
            if(response.status=='-1'){
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                },function(){
                    window.location.reload();
                });
                return;
            }else{
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }

    //重新申请
    function getApplyForm(id){
        var url = $('#shengpay_apply').val();
        var token = $('#_token').val();
        if(id==''){
            swal({
                title: "提示信息",
                text: '数据传输错误',
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
            },function(){
                window.location.reload();
            });
            return;
        }
        var data = {'id':id,'_token':token};
        $.post(url,data,function(response){
            if(response.status=='-1'){
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                },function(){
                    window.location.reload();
                });
                return;
            }else{
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }

    //重新申请
    function getDeleteComfirmForm(id){
        var url = $('#shengpay_delete').val();
        var token = $('#_token').val();
        if(id==''){
            swal({
                title: "提示信息",
                text: '数据传输错误',
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
            },function(){
                window.location.reload();
            });
            return;
        }
        var data = {'id':id,'_token':token};
        $.post(url,data,function(response){
            if(response.status=='-1'){
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                },function(){
                    window.location.reload();
                });
                return;
            }else{
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }

</script>
</body>
</html>
























