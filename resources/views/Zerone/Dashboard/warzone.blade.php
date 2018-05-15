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
    <!-- Sweet Alert -->
    <link href="{{asset('public/Zerone/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/iCheck')}}/css/custom.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/switchery')}}/css/switchery.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/chosen')}}/css/chosen.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library/footable')}}/css/footable.core.css" rel="stylesheet">
    <!-- Sweet Alert -->
</head>
<body class="">
<div id="wrapper">
    @include('Zerone/Public/Nav')
    <div id="page-wrapper" class="gray-bg">
        @include('Zerone/Public/Header')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-4">
                <h2>战区管理</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">系统管理</a>
                    </li>
                    <li >
                        <strong>战区管理</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight ecommerce">
            <div class="ibox-content m-b-sm border-bottom">
                <form method="get" role="form" id="searchForm" action="">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="warzone_add" value="{{ url('zerone/ajax/warzone_add') }}">
                    <input type="hidden" id="warzone_edit" value="{{ url('zerone/ajax/warzone_edit') }}">
                    <input type="hidden" id="warzone_delete_confirm" value="{{ url('zerone/ajax/warzone_delete_confirm') }}">
                    <div class="row">
                        <div class="pull-left padding_l_r_15">
                            <div class="form-group">
                                <label class="control-label" for="amount">&nbsp;</label>
                                <button type="button" class="block btn-w-m btn btn-primary" onclick="getAddForm()"><i class="fa fa-plus"></i>&nbsp;&nbsp;添加战区</button>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="amount">战区名称</label>
                                <input type="text" id="zone_name" name="zone_name" value="{{ $zone_name }}" placeholder="战区名称" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="amount"> &nbsp;</label>
                                <button type="submit" class="block btn btn-info"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content">

                            <table class="table table-stripped toggle-arrow-tiny" data-page-size="15">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>战区名称</th>
                                    <th>战区覆盖范围</th>
                                    <th>分公司数量</th>
                                    <th class="text-right">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($warzone as $key=>$val)
                                <tr>
                                    <td>
                                        {{$val->id}}
                                    </td>
                                    <td>
                                        {{ $val->zone_name }}
                                    </td>
                                    <td>
                                        @foreach($val->province as $kk=>$vv)
                                        <label class="label label-success" style="display:inline-block">{{ $vv->province_name }}</label>&nbsp;&nbsp;
                                        @endforeach
                                    </td>
                                    <td >
                                        @if($val->WarzoneAgent->count())
                                                {{$val->WarzoneAgent->count()}}位分公司
                                        @else
                                        0位分公司
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <button type="button" onclick="getEditForm({{ $val->id }})"  class="btn  btn-xs btn-primary"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑</button>
                                        <button type="button" onclick="getDeleteComfirmForm('{{ $val->id }}','{{ $val->zone_name }}')" class="btn  btn-xs btn-danger"><i class="fa fa-remove"></i>&nbsp;&nbsp;删除</button>
                                        {{--<button type="button" id="deleteBtn2" class="btn  btn-xs btn-danger"><i class="fa fa-remove"></i>&nbsp;&nbsp;彻底删除</button>--}}
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="99" class="text-right">
                                        {{ $warzone->appends($search_data)->links() }}
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
        {{--添加战区弹出图层--}}
        <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"></div>

        <!-- Mainly scripts -->
        <script src="{{asset('public/Zerone/library/jquery')}}/js/jquery-2.1.1.js"></script>
        <script src="{{asset('public/Zerone/library/bootstrap')}}/js/bootstrap.min.js"></script>
        <script src="{{asset('public/Zerone/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
        <script src="{{asset('public/Zerone/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>
        <!-- Custom and plugin javascript -->
        <script src="{{asset('public/Zerone')}}/js/inspinia.js"></script>
        <script src="{{asset('public/Zerone/library/pace')}}/js/pace.min.js"></script>
        <!-- Sweet alert -->
        <script src="{{asset('public/Zerone/library/datepicker')}}/js/bootstrap-datepicker.js"></script>
        <script src="{{asset('public/Zerone/library/sweetalert')}}/js/sweetalert.min.js"></script>
        <script src="{{asset('public/Zerone/library/footable')}}/js/footable.all.min.js"></script>
        <script src="{{asset('public/Zerone/library/chosen')}}/js/chosen.jquery.js"></script>
        <!-- Page-Level Scripts -->
        <script>
            //战区管理——添加战区
            function getAddForm(){
                var url = $('#warzone_add').val();
                var token = $('#_token').val();
                var data = {'_token':token};
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
            //删除战区，安全密码框弹出
            function getDeleteComfirmForm(id,zone_name){
                var url = $('#warzone_delete_confirm').val();
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
                var data = {'id':id,'zone_name':zone_name,'_token':token};
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
            //战区管理——编辑战区
            function getEditForm(id){
                var url = $('#warzone_edit').val();
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
