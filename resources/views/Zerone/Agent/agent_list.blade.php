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
                <h2>分公司列表</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">分公司管理</a>
                    </li>
                    <li >
                        <strong>分公司列表</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight ecommerce">
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <input type="hidden" id="agent_list_edit" value="{{ url('zerone/ajax/agent_list_edit') }}">
            <input type="hidden" id="agent_list_lock" value="{{ url('zerone/ajax/agent_list_lock') }}">
            <input type="hidden" id="agent_list_delete" value="{{ url('zerone/ajax/agent_list_delete') }}">

            <div class="ibox-content m-b-sm border-bottom">
                <form method="get" role="form" id="searchForm" action="">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="amount">分公司名称</label>
                                <input type="text" id="amount" name="organization_name" value="{{ $search_data['organization_name'] }}" placeholder="请输入分公司名称" class="form-control">
                            </div>
                        </div>
                        {{--<div class="col-sm-3">--}}
                        {{--<div class="form-group">--}}
                        {{--<label class="control-label" for="amount">手机号码</label>--}}
                        {{--<input type="text" id="amount" name="agent_owner_mobile" value="{{ $search_data['agent_owner_mobile'] }}" placeholder="手机号码" class="form-control">--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-sm-3">--}}
                        {{--<div class="form-group">--}}
                        {{--<label class="control-label" for="amount">所在战区</label>--}}
                        {{--<select class="form-control m-b" name="account">--}}
                        {{--<option>东部战区</option>--}}
                        {{--<option>西部战区</option>--}}
                        {{--<option>南部战区</option>--}}
                        {{--<option>北部战区</option>--}}
                        {{--<option>中部战区</option>--}}
                        {{--</select>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="amount"> &nbsp;</label>
                                <button type="submit" class="block btn btn-info"><i class="fa fa-search"></i>搜索</button>
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
                                    <th>分公司名称</th>
                                    <th>所在战区</th>
                                    <th>负责人姓名</th>

                                    <th>手机号码</th>
                                    <th>分公司状态</th>
                                    <th class="col-sm-1">注册时间</th>
                                    <th class="col-sm-4 text-right" >操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listorg as $key=>$value)
                                    <tr>
                                        <td>{{$value->id}}</td>
                                        <td>{{$value->organization_name}}</td>
                                        <td>{{$value['zone_name']}}</td>
                                        <td>{{$value->organizationagentinfo->agent_owner}}</td>


                                        <td>{{$value->organizationagentinfo->agent_owner_mobile}}</td>
                                        <td>
                                            @if($value->status == 1)
                                                <label class="label label-primary">正常</label>
                                            @elseif($value->status == 0)
                                                <label class="label label-danger">已冻结</label>
                                            @endif
                                        </td>
                                        <td>{{$value->created_at}}</td>
                                            <td class="text-right">
                                                <button type="button" id="editBtn" class="btn  btn-xs btn-primary" onclick="getEditForm({{ $value->id }})"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑</button>
                                                @if($value->status == 1)
                                                <button type="button" id="lockBtn" class="btn  btn-xs btn-warning" onclick="getLockForm('{{ $value->id }}','{{$value->status}}')"><i class="fa fa-lock"></i>&nbsp;&nbsp;冻结</button>
                                                @elseif($value->status == 0)
                                                <button type="button" id="lockBtn" class="btn  btn-xs btn-info" onclick="getLockForm('{{ $value->id }}','{{$value->status}}')"><i class="fa fa-unlock"></i>&nbsp;&nbsp;解冻</button>
                                                @endif
                                                <button type="button" id="peoplesBtn" class="btn btn-outline btn-xs btn-primary" onclick="location.href='{{url('zerone/agent/agent_structure')}}?organization_id={{$value->id}}'"><i class="fa fa-users"></i>&nbsp;&nbsp;人员架构</button>

                                                <button type="button" id="programBtn" onclick="location.href='{{url('zerone/agent/agent_program')}}?organization_id={{$value->id}}'" class="btn btn-outline btn-xs btn-warning"><i class="fa fa-arrow-circle-o-left"></i>&nbsp;&nbsp;程序管理</button>
                                                <button type="button" id="companyBtn" onclick="location.href='{{url('zerone/agent/agent_fansmanage')}}?organization_id={{$value->id}}'" class="btn btn-outline btn-xs btn-danger"><i class="fa fa-arrow-circle-o-left"></i>&nbsp;&nbsp;商户管理</button>
                                            </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="9" class="footable-visible">
                                        <ul class="pagination pull-right">
                                            {{$listorg->appends($search_data)->links()}}
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

    <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"></div>

    <div class="modal inmodal" id="myModal3" tabindex="-1" role="dialog" aria-hidden="true"></div>

    <div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true"></div>
</div>

{{--<!-- Page-Level Scripts -->--}}
<script src="{{asset('public/Zerone/library/jquery')}}/js/jquery-2.1.1.js"></script>
<script src="{{asset('public/Zerone/library/bootstrap')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Zerone/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
<script src="{{asset('public/Zerone/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('public/Zerone')}}/js/inspinia.js"></script>
<script src="{{asset('public/Zerone/library/pace')}}/js/pace.min.js"></script>
<script src="{{asset('public/Zerone/library/iCheck')}}/js/icheck.min.js"></script>
<script src="{{asset('public/Zerone/library/sweetalert')}}/js/sweetalert.min.js"></script>

<script src="{{asset('public/Zerone')}}/js/switchery.js"></script>
<script src="{{asset('public/Zerone')}}/js/footable.all.min.js"></script>
<script src="{{asset('public/Zerone')}}/js/bootstrap-datepicker.js"></script>

<script>

    $(function(){

        //设置CSRF令牌
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    //编辑
    function getEditForm(id){

        var url = $('#agent_list_edit').val();
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
    //冻结
    function getLockForm(id,status){

        var url = $('#agent_list_lock').val();
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

        var data = {'id':id,'status':status,'_token':token};
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

                $('#myModal3').html(response);
                $('#myModal3').modal();
            }
        });
    }
</script>
</div>
</body>

</html>
