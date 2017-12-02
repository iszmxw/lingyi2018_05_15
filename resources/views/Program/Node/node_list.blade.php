<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>零壹新科技程序管理平台</title>

    <link href="{{asset('public/Program/library/bootstrap')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/datepicker')}}/css/datepicker3.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/font')}}/css/font-awesome.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">

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
                <h2>节点列表</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">功能节点管理</a>
                    </li>
                    <li >
                        <strong>节点列表</strong>
                    </li>
                </ol>
            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">


            <div class="ibox-content m-b-sm border-bottom">

                <div class="row">
                    <form method="get" role="form" id="searchForm" action="">
                        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="amount">节点名称</label>
                            <input type="text" id="node_name" name="node_name" value="{{ $search_data['node_name'] }}" placeholder="请输入节点名称" class="form-control">
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
                                    <th>节点名称</th>
                                    <th>路由名称</th>
                                    <th>添加时间</th>
                                    <th class="text-right">操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($list as $key=>$val)
                                <tr>
                                    <td>
                                        {{ $val->id }}
                                    </td>
                                    <td>
                                        {{ $val->node_name }}
                                    </td>
                                    <td >
                                        {{ $val->route_name }}
                                    </td>
                                    <td >
                                        {{ $val->created_at }}
                                    </td>
                                    <td class="text-right">
                                        <button type="button" id="editBtn"  class="btn  btn-xs btn-primary"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑</button>
                                        <button type="button" id="deleteBtn" class="btn  btn-xs btn-warning"><i class="fa fa-remove"></i>&nbsp;&nbsp;删除</button>
                                        <button type="button" id="deleteBtn2" class="btn  btn-xs btn-danger"><i class="fa fa-remove"></i>&nbsp;&nbsp;彻底删除</button>
                                    </td>
                                </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="99" class="text-right">
                                        {{ $list->appends($search_data['node_name'])->links() }}
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
<script src="{{asset('public/Program/library/datepicker')}}/js/bootstrap-datepicker.js"></script>
<script src="{{asset('public/Program/library/sweetalert')}}/js/sweetalert.min.js"></script>
<script>
    $(function(){
        //设置CSRF令牌
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.zerodate').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });
    function searchFormCheck(){
        var url = $('#searchForm').attr('action');
        var data = $('#searchForm').serialize();
        $.get(url+'?'+data,function(json){
            if(json.status==0){
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                });
                return false;
            }else{
                location.href=url+'?'+data;
            }
        });
        return false;
    }
</script>
</body>

</html>
