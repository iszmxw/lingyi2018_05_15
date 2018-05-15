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
    <link href="{{asset('public/Zerone/library/chosen')}}/css/chosen.css" rel="stylesheet">
    <link href="{{asset('public/Zerone/library')}}/switchery/css/switchery.css" rel="stylesheet">
</head>

<body class="">


<div id="wrapper">
    @include('Zerone/Public/Nav')

    <div id="page-wrapper" class="gray-bg">
        @include('Zerone/Public/Header')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-4">
                <h2>“{{$organization_name}}”分公司管理</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">分公司管理</a>
                    </li>
                    <li >
                        <strong>“{{$organization_name}}”商户管理</strong>
                    </li>
                </ol>
            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">

            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <input type="hidden" id="agent_fansmanage_add" value="{{ url('zerone/ajax/agent_fansmanage_add') }}">
            <input type="hidden" id="agent_fansmanage_draw" value="{{ url('zerone/ajax/agent_fansmanage_draw') }}">

            <div class="ibox-content m-b-sm border-bottom">

                <div class="row">
                    <div>
                        <div class="form-group">
                            <button type="button" onclick="history.back()" class=" btn btn-info"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回列表</button>

                            <button type="button" onclick="getAddFansmanageForm('{{$organization_id}}')" class=" btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;商户划入归属</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>“{{$organization_name}}”商户管理</h5>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-stripped toggle-arrow-tiny" data-page-size="15">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>商户名称</th>
                                    <th>程序数及分店数</th>
                                    <th class="col-sm-1">入驻时间</th>
                                    <th class="col-sm-2 text-right" >操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $key=>$value)
                                <tr>
                                    <td>{{$value->id}}</td>
                                    <td>{{$value->organization_name}}</td>

                                    <td>
                                        <label class="label label-success" style="display:inline-block">@if(!empty($value['program_name'])){{$value['program_name']}}@else 还没有分配程序@endif：程序@if(!empty($value['program_balance'])) {{$value['program_balance']}}@else 0 @endif套，分店{{$value['store']}}家</label><br />
                                    </td>
                                    <td>{{$value->created_at}}</td>
                                    <td class="text-right">
                                        <button type="button"  onclick="getDrawFansmanageForm('{{$organization_id}}','{{$value->id}}')" class="btn  btn-xs btn-danger"><i class="fa fa-remove"></i>&nbsp;&nbsp;划出归属</button>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="9" class="footable-visible">
                                       {{$list->links()}}
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
<script src="{{asset('public/Zerone/library/switchery')}}/js/switchery.js"></script>
<script src="{{asset('public/Zerone/library/chosen')}}/js/chosen.jquery.js"></script>

<script>
    $(document).ready(function() {

        $('#removeBtn').click(function(){
            $('#myModal').modal();
        });
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });
        var elem = document.querySelector('.js-switch2');
        var switchery = new Switchery(elem, { color: '#1AB394' });
        $('.saveBtn').click(function(){
            swal({
                title: "温馨提示",
                text: "操作成功",
                type: "success"
            },function(){
                window.location.reload();
            });
        });
    });




    //商户划入
    function getAddFansmanageForm(organization_id){
        var url = $('#agent_fansmanage_add').val();
        var token = $('#_token').val();
        if(organization_id==''){
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

        var data = {'organization_id':organization_id,'_token':token};
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
    //商户划出
    function getDrawFansmanageForm(organization_id,fansmanage_id){
        $('.chosen-select').chosen({width:"100%",no_results_text:'对不起，没有找到结果！关键词：'});
        // activate Nestable for list 2
        var url = $('#agent_fansmanage_draw').val();
        var token = $('#_token').val();
        if(organization_id==''){
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

        var data = {'organization_id':organization_id,'fansmanage_id':fansmanage_id,'_token':token};
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
