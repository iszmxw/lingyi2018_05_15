<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>零壹新科技程序管理平台</title>

    <link href="{{asset('public/Program/library/bootstrap')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/font')}}/css/font-awesome.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/switchery')}}/css/switchery.css" rel="stylesheet">

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
                <h2>程序列表</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">程序管理</a>
                    </li>
                    <li >
                        <strong>程序列表</strong>
                    </li>
                </ol>
            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">


            <div class="ibox-content m-b-sm border-bottom">

                <div class="row">
                    <form method="get" role="form" id="searchForm" action="">
                        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                        <input type="hidden" id="program_edit_url" value="{{ url('program/ajax/program_edit') }}">
                        <input type="hidden" id="program_delete_url" value="{{ url('program/ajax/program_delete') }}">
                        <input type="hidden" id="program_deleted_url" value="{{ url('program/ajax/program_deleted') }}">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="amount">程序名称</label>
                            <input type="text" id="program_name" name="program_name" value="{{ $search_data['program_name'] }}" placeholder="程序名称" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="amount"> &nbsp;</label>
                            <button type="submit" class="block btn btn-info"><i class="fa fa-search"></i>搜索</button>
                        </div>
                    </div>
                    </form>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content">

                            <table class="table table-stripped toggle-arrow-tiny" data-page-size="15">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>程序名称</th>
                                    <th class="col-sm-1">上级程序</th>
                                    <th class="col-sm-1">是否通用</th>
                                    <th>功能模块</th>
                                    <th class="col-sm-1">添加时间</th>
                                    <th class="col-sm-2 text-right" >操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $key=>$val)
                                <tr>
                                    <td>{{ $val->id }}</td>
                                    <td>{{ $val->program_name }}</td>
                                    <td>
                                        <label class="label label-info" style="display:inline-block"> {{ $pname[$val->id] }}</label>
                                    </td>
                                    <td>
                                        @if($val->is_universal==1)
                                            <label class="label label-warning" style="display:inline-block">通用版本</label>
                                        @else
                                            <label class="label label-danger" style="display:inline-block">定制版本</label>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($module_list[$val->id] as $k=>$v)
                                        <label class="label label-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="@foreach($node_list[$val->id.'_'.$v['id']] as $kk=>$vv){{ $vv['node_name'] }} &nbsp;&nbsp;@endforeach" style="display:inline-block">{{ $v['module_name'] }}</label>&nbsp;&nbsp;
                                       @endforeach
                                    </td>
                                    <td>2017-08-08 10:30:30</td>
                                    <td class="text-right">
                                        <button type="button" onclick="getEditForm({{ $val->id }})" id="editBtn"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑</button>
                                        <button type="button" id="deleteBtn" class="btn btn-xs btn-warning"><i class="fa fa-remove"></i>&nbsp;&nbsp;删除</button>
                                        <button type="button" id="deleteBtn2" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i>&nbsp;&nbsp;彻底删除</button>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="99" class="text-right">
                                        {{ $list->appends($search_data)->links() }}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


        </div>
        @include('Program/Public/Footer')
    </div>
</div>
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"></div>
<!-- Mainly scripts -->
<script src="{{asset('public/Program/library/jquery')}}/js/jquery-2.1.1.js"></script>
<script src="{{asset('public/Program/library/bootstrap')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Program/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
<script src="{{asset('public/Program/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>
<!-- Custom and plugin javascript -->
<script src="{{asset('public/Program')}}/js/inspinia.js"></script>
<script src="{{asset('public/Program/library/pace')}}/js/pace.min.js"></script>
<script src="{{asset('public/Program/library/sweetalert')}}/js/sweetalert.min.js"></script>


<script>
    $(function(){

        //设置CSRF令牌
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    //获取用户信息，编辑密码框
    function getEditForm(id){
        var url = $('#program_edit_url').val();
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
            if(response.status=='0'){
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
