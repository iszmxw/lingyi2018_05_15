<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>零壹新科技程序管理平台</title>

    <link href="{{asset('public/Tooling/library/bootstrap')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Tooling/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">
    <link href="{{asset('public/Tooling/library/font')}}/css/font-awesome.css" rel="stylesheet">


    <link href="{{asset('public/Tooling')}}/css/animate.css" rel="stylesheet">
    <link href="{{asset('public/Tooling')}}/css/style.css" rel="stylesheet">

</head>

<body class="">

<div id="wrapper">

    @include('Tooling/Public/Nav')

    <div id="page-wrapper" class="gray-bg">
        @include('Tooling/Public/Header')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-4">
                <h2>"{{ $info->program_name }}"菜单结构</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">程序管理</a>
                    </li>
                    <li >
                        <strong>"{{ $info->program_name }}"菜单结构</strong>
                    </li>
                </ol>
            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">


            <div class="ibox-content m-b-sm border-bottom">

                <div class="row">
                    <input type="hidden" id="menu_add_url" value="{{ url('tooling/ajax/menu_add') }}">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label" for="amount"> &nbsp;</label>
                            <button type="button" onclick="location.href='{{ url('tooling/program/program_list') }}'" class="block btn btn-info"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回列表</button>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label" for="amount"> &nbsp;</label>
                            <button type="button" id="expand-all" class="block btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;展开所有</button>
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label" for="amount"> &nbsp;</label>
                            <button type="button" id="collapse-all" class="block btn btn-primary"><i class="fa fa-minus"></i>&nbsp;&nbsp;合并所有</button>
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label" for="amount"> &nbsp;</label>
                            <button type="button" id="addBtn" onclick="getAddForm('{{ $info->id }}')" class="block btn btn-info"><i class="fa fa-plus"></i>&nbsp;&nbsp;添加菜单</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>"零壹管理程序”菜单结构</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="dd" id="nestable2">
                                <ol class="dd-list">
                                    @foreach($list as $key=>$val)
                                    <li class="dd-item" data-id="1">
                                        <div class="dd-handle">
                                            <span class="label label-primary"><i class="{{ $val->icon_class }}"></i></span>
                                            <span class="pull-right">
                                                <div class="btn-group">
                                                    <button type="button" id="editBtn" class="block btn btn-xs btn-info"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑菜单</button>
                                                    <button type="button" id="deleteBtn" class="block btn btn-xs btn-danger"><i class="fa fa-remove"></i>&nbsp;&nbsp;删除菜单</button>
                                                </div>
                                            </span>
                                            {{ $val->menu_name }}

                                        </div>
                                        @if(!empty($son_menu[$val->id]))
                                        <ol class="dd-list">
                                            @foreach($son_menu[$val->id] as $kk=>$vv)
                                            <li class="dd-item" data-id="2">
                                                <div class="dd-handle">
                                                    <span class="pull-right">
                                                        <div class="btn-group">
                                                            <button type="button" id="editBtn" class="block btn btn-xs btn-info"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑菜单</button>
                                                            <button type="button" id="deleteBtn" class="block btn btn-xs btn-danger"><i class="fa fa-remove"></i>&nbsp;&nbsp;删除菜单</button>
                                                        </div>
                                                    </span>
                                                    {{$vv->menu_name}}
                                                </div>
                                            </li>
                                            @endforeach
                                        </ol>
                                        @endif
                                    </li>
                                    @endforeach
                                </ol>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </div>

        @include('Tooling/Public/Footer')
    </div>
    <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"></div>
</div>

<!-- Mainly scripts -->
<script src="{{asset('public/Tooling/library/jquery')}}/js/jquery-2.1.1.js"></script>
<script src="{{asset('public/Tooling/library/bootstrap')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Tooling/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
<script src="{{asset('public/Tooling/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('public/Tooling')}}/js/inspinia.js"></script>
<script src="{{asset('public/Tooling/library/pace')}}/js/pace.min.js"></script>
<script src="{{asset('public/Tooling/library/sweetalert')}}/js/sweetalert.min.js"></script>
<script src="{{asset('public/Tooling/library/nestable')}}/js/jquery.nestable.js"></script>


<script>
    $(function(){
        $('#nestable2').nestable();
        $('#expand-all').click(function(){
            $('.dd').nestable('expandAll');
        });
        $('#collapse-all').click(function(){
            $('.dd').nestable('collapseAll');
        });
        get_parents_node($('#complete_id').val());
        //设置CSRF令牌
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    function getAddForm(program_id){
        var url = $('#menu_add_url').val();
        var token = $('#_token').val();

        if(program_id==''){
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
        var data = {'program_id':program_id,'_token':token};
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
