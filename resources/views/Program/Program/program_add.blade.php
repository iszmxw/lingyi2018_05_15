<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>零壹新科技程序管理平台</title>

    <link href="{{asset('public/Program/library/bootstrap')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/font')}}/css/font-awesome.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/switchery')}}/css/switchery.css" rel="stylesheet">
    <link href="{{asset('public/Program/library/iCheck')}}/css/custom.css" rel="stylesheet">

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
                <h2>添加程序</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="JavaScript:;">程序管理</a>
                    </li>
                    <li >
                        <strong>添加程序</strong>
                    </li>
                </ol>
            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>添加程序</h5>

                        </div>
                        <div class="ibox-content">
                            <form method="get" class="form-horizontal">
                                <div class="form-group"><label class="col-sm-2 control-label">程序名称</label>
                                    <div class="col-sm-10"><input type="text" class="form-control"></div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">选择主程序</label>
                                    <div class="col-sm-10">
                                        <select class="form-control m-b" name="account">
                                            <option>独立主程序</option>
                                            <option>餐饮先吃后付版程序（完整版）</option>
                                            <option>餐饮先付后吃版程序（无人餐厅版）</option>
                                            <option>餐饮先付后吃版程序（外卖版）</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >是否通用</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" class="js-switch" checked  value="1"/>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >程序功能模块</label>
                                    <div class="col-sm-10">
                                        @foreach($module_list as $key=>$val)
                                        <group class="checked_box_group_{{ $val['id'] }}">
                                            <div>
                                                <label class="i-checks">
                                                    <input type="checkbox" class="checkbox_module_name checkbox_module_name_{{ $val['id'] }}" name="module_id[]" value="{{ $val['id'] }}"> {{ $val['module_name'] }}
                                                </label>
                                            </div>
                                            <div>
                                                @foreach($node_list[$val['id']] as $kk=>$vv)
                                                <label class="checkbox-inline i-checks">
                                                    <input type="checkbox" data-group_id="{{ $val['id'] }}" class="checkbox_node_name checkbox_node_name_{{ $val['id'] }}" name="module_node_id[]" value="{{ $vv['module_id'].'_'.$vv['node_id'] }}"> {{$vv['node_name']}}
                                                </label>
                                                @endforeach;
                                            </div>
                                        </group>
                                        <div class="hr-line-dashed" style="clear: both;"></div>
                                        @endforeach;
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group ">
                                    <div class="col-sm-4 col-sm-offset-5">
                                        <button class="btn btn-primary" id="addbtn" type="button">确认添加</button>
                                        <button class="btn btn-write" onClick="location.href='node.html'" type="button">回到列表</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('Program/Public/Footer')
    </div>
</div>

<!-- Mainly scripts -->
<script src="{{asset('public/Program/library/jquery')}}/js/jquery-2.1.1.js"></script>
<script src="{{asset('public/Program/library/bootstrap')}}/js/bootstrap.min.js"></script>
<script src="{{asset('public/Program/library/metisMenu')}}/js/jquery.metisMenu.js"></script>
<script src="{{asset('public/Program/library/slimscroll')}}/js/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('public/Program')}}/js/inspinia.js"></script>
<script src="{{asset('public/Program/library/pace')}}/js/pace.min.js"></script>
<script src="{{asset('public/Program/library/sweetalert')}}/js/sweetalert.min.js"></script>
<script src="{{asset('public/Program/library/multiselect')}}/js/multiselect.js"></script>
<script src="{{asset('public/Program/library/switchery')}}/js/switchery.js"></script>
<script src="{{asset('public/Program/library/iCheck')}}/js/icheck.min.js"></script>

<script>
    $(function(){
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $('.checkbox_module_name').on('ifChecked', function(event){ //ifCreated 事件应该在插件初始化之前绑定
            var id = $(this).val();
            $('.checkbox_node_name_'+id).iCheck('check') ;
        }).on('ifUnchecked', function(event){ //ifCreated 事件应该在插件初始化之前绑定
            var id = $(this).val();
            $('.checkbox_node_name_'+id).iCheck('uncheck') ;
        });

        $('.checkbox_node_name').on('ifUnchecked',function(event){
            var group_id = $(this).attr('data-group_id');
            var tag=false;
            $('.checkbox_node_name_'+group_id).each(function(i,v){
                 if($('.checkbox_node_name_'+group_id+':eq('+i+')').is(":checked")){
                     tag=true;
                 }
            });
            if(!tag){
                $('.checkbox_node_name_'+group_id).iCheck('uncheck') ;
            }
        });

        //设置CSRF令牌
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#multiselect').multiselect({keepRenderingSort:true});
    });
    //提交表单
    function postForm() {
        var target = $("#currentForm");
        var url = target.attr("action");
        var module_name = $('#module_name').val();
        var _token = $('#_token').val();
        var node = '';

        $('#multiselect_to option').each(function(i,v){
            node += 'nodes[]='+$(v).val()+'&';
        });
        node = node.substring(0, node.length-1);
        var data = '_token='+_token+'&module_name='+module_name+'&'+node;
        $.post(url, data, function (json) {
            if (json.status == -1) {
                window.location.reload();
            } else if(json.status == 1) {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                },function(){
                    window.location.reload();
                });
            }else{
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
</script>
</body>

</html>
