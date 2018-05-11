<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/library/jPlayer/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css" />
    <link href="{{asset('public/Retail')}}/library/sweetalert/sweetalert.css" rel="stylesheet" />
    <link href="{{asset('public/Retail')}}/library/wizard/css/custom.css" rel="stylesheet" />
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
                            <h3 class="m-b-none">开单管理-供应商单据</h3>
                        </div>
                        <div class="row wrapper">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-s-md btn-success" onclick="window.location='purchase_goods'">供应商单据</button>
                                <button type="button" class="btn btn-s-md btn" onclick="window.location='loss_goods'">&nbsp;&nbsp;报损单据</button>
                                <button type="button" class="btn btn-s-md btn" onclick="window.location='check_goods'">&nbsp;&nbsp;盘点单据</button>
                            </div>
                        </div>

                        <section class="panel panel-default">
                            <header class="panel-heading">
                                供应商单据
                            </header>
                            <div class="row wrapper">
                                <form class="form-horizontal" method="get">
                                    <input type="hidden" id="order_list_details" value="{{ url('retail/ajax/order_list_details') }}">
                                    <input type="hidden" id="purchase_list_confirm" value="{{ url('retail/ajax/purchase_list_confirm') }}">
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                    <label class="col-sm-1 control-label">订单编号</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="ordersn" value="{{$ordersn}}" placeholder="订单编号">
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="submit" class="btn btn-s-md btn-info"><i class="icon icon-magnifier"></i>&nbsp;&nbsp;搜索</button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped b-t b-light">
                                    <thead>
                                    <tr>
                                        <th>订单编号</th>
                                        <th>类型</th>
                                        <th>供应商</th>
                                        <th>供应商联系方式</th>
                                        <th>经手操作人员</th>
                                        <th>订单总金额</th>
                                        <th>审核</th>
                                        <th>开单时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $key=>$val)
                                    <tr>
                                        <td>{{$val->ordersn}}</td>
                                        <td>
                                            @if($val->type == 1)
                                            进货
                                            @elseif($val->type == 2)
                                            退货
                                            @else
                                            未知
                                            @endif
                                        </td>
                                        <td><label class="label label-success">{{$val->RetailSupplier->company_name}}</label></td>
                                        <td>
                                                {{$val->RetailSupplier->contactmobile}}
                                        </td>
                                        <td>
                                            <label class="label label-info">
                                                {{$val->account_info->realname}}
                                            </label>
                                        </td>
                                        <td>
                                            <label class="label label-danger">
                                                {{$val->order_price}}
                                            </label>
                                        </td>
                                        <td>
                                            @if($val->status == 0)
                                                <button class="btn btn-warning btn-xs" onclick="getlockForm('{{$val->id}}','{{$val->status}}')"><i class="fa fa-lock"></i>&nbsp;&nbsp;未审核</button>
                                            @else
                                                <button class="btn btn-success btn-xs"><i class="fa fa-lock"></i>&nbsp;&nbsp;已审核</button>
                                            @endif
                                        </td>
                                        <td>{{$val->created_at}}</td>
                                        <td>
                                            <button class="btn btn-primary btn-xs" onclick="getwalletForm('{{$val->id}}','{{$val->type}}')"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;查看详情</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <footer class="panel-footer">
                                <div class="row">

                                    <div class="col-sm-12 text-right text-center-xs">
                                        {{$list->links()}}
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
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



    //订单详情
    function getwalletForm(id,type){
        var url = $('#order_list_details').val();
        var token = $('#_token').val();
        var data = {'_token':token,'id':id,'type':type};
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



    //审核订单
    function getlockForm(id,status){
        var url = $('#purchase_list_confirm').val();
        var token = $('#_token').val();
        var data = {'_token':token,'id':id,'status':status};
        $.post(url,data,function(response){
            if(response.status=='-1'){
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定"
                },function(){
                    window.location.reload();
                });
                return;
            }else{
                console.log(response);
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }
    //添加会员标签
    function getEditForm(id){
        var url = $('#user_list_edit').val();
        var token = $('#_token').val();
        var data = {'_token':token,'id':id};
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

    function changeUserTag(obj,user_id,store_id,nickname){
        var label_id = $(obj).val();
        var url = $('#store_label_add_check').val();
        var token = $('#_token').val();
        var data = {'_token':token,'label_id':label_id,'user_id':user_id,'store_id':store_id,'nickname':nickname};
        $.post(url,data,function(json){
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