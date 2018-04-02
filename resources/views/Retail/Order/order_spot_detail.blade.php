<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Branch')}}/library/jPlayer/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Branch')}}/css/app.css" type="text/css" />
    <link href="{{asset('public/Branch')}}/library/sweetalert/sweetalert.css" rel="stylesheet" />
    <link href="{{asset('public/Branch')}}/library/wizard/css/custom.css" rel="stylesheet" />
    <!--[if lt IE 9]>
    <script src="{{asset('public/Branch')}}/library/ie/html5shiv.js"></script>
    <script src="{{asset('public/Branch')}}/library/ie/respond.min.js"></script>
    <script src="{{asset('public/Branch')}}/library/ie/excanvas.js"></script>
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
                            <h3 class="m-b-none">现场订单详情</h3>
                        </div>
                        <div class="row row-sm">
                            <button class="btn btn-s-md btn-success" type="button" onclick="history.back()" id="addBtn"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回列表</button>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                        </div>
                        <div class="col-lg-4">
                            <section class="panel panel-default">

                                <header class="panel-heading font-bold">
                                    现场订单详情
                                </header>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post">

                                        <input type="hidden" id="order_status_comfirm_url" value="{{ url('retail/ajax/order_status') }}">
                                        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                        <div class="form-group">
                                            <label class="col-sm-3 text-right" for="input-id-1">订单编号</label>
                                            <div class="col-sm-9">
                                                <div>
                                                    {{$order->ordersn}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <label class="col-sm-3 text-right" for="input-id-1">用户账号</label>
                                            <div class="col-sm-9">
                                                <div>
                                                    {{$order->user->account}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <label class="col-sm-3 text-right" for="input-id-1">微信昵称</label>
                                            <div class="col-sm-9">
                                                <div>
                                                    {{$order->user->UserInfo->nickname}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <label class="col-sm-3 text-right" for="input-id-1">联系方式</label>
                                            <div class="col-sm-9">
                                                <div>
                                                    {{$order->user->mobile}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        @if($order->status==-1 || $order->status==0)
                                        @else
                                        <div class="form-group">
                                            <label class="col-sm-3 text-right" for="input-id-1">支付方式</label>
                                            <div class="col-sm-9">
                                                <div>
                                                    <label class="label label-info">
                                                        @if($order->paytype==1)
                                                            余额支付
                                                        @elseif($order->paytype==2)
                                                            在线支付
                                                        @elseif($order->paytype==3)
                                                            货到付款
                                                        @elseif($order->paytype==4)
                                                            现场现金支付
                                                        @elseif($order->paytype==5)
                                                            现场刷卡支付
                                                        @elseif($order->paytype==6)
                                                            现场支付宝支付
                                                        @elseif($order->paytype==7)
                                                            现场微信支付
                                                        @elseif($order->paytype==8)
                                                            线上手动确认付款
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        @endif
                                        <div class="form-group">
                                            <label class="col-sm-3 text-right" for="input-id-1">订单状态</label>
                                            <div class="col-sm-9">
                                                <div>
                                                    @if($order->status==-1)
                                                        <label class="label label-default">已取消</label>
                                                    @elseif($order->status==0)
                                                        <label class="label label-primary">待付款</label>
                                                    @elseif($order->status==1)
                                                        <label class="label label-warning">已付款</label>
                                                    @elseif($order->status==2)
                                                        <label class="label label-success">配送中</label>
                                                    @elseif($order->status==3)
                                                        <label class="label label-info">已完成</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <label class="col-sm-3 text-right" for="input-id-1">下单时间</label>
                                            <div class="col-sm-9">
                                                <div>
                                                    <label class="label label-primary">{{$order->created_at}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <label class="col-sm-3 text-right" for="input-id-1">订单备注</label>
                                            <div class="col-sm-9">
                                                <div>
                                                    <label class="label label-danger">{{$order->remarks}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group text-center">
                                            @if($order->status==0)
                                                    <button class="btn btn-success" type="button" onclick="getPostForm('{{ $order->id }}','1')"><i class="fa fa-check"></i>&nbsp;&nbsp;确认付款</button>
                                            @endif
                                            @if($order->status==1 || $order->status==2)
                                                    <button class="btn btn-primary" type="button" onclick="getPostForm('{{ $order->id }}','3')"><i class="fa fa-check"></i>&nbsp;&nbsp;完成订单</button>
                                            @endif
                                            @if($order->status==0 || $order->status==1 || $order->status==2)
                                                    <button class="btn btn-default" type="button" onclick="getPostForm('{{ $order->id }}','-1')"><i class="fa fa-times"></i>&nbsp;&nbsp;取消订单</button>
                                            @endif
                                            @if($order->status==-1)
                                                    <button class="btn btn-default" type="button"><i class="fa fa-check"></i>&nbsp;&nbsp;已取消</button>
                                            @endif
                                            @if($order->status==3)
                                                    <button class="btn btn-success" type="button"><i class="fa fa-check"></i>&nbsp;&nbsp;已完成</button>
                                            @endif
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                    </form>
                                </div>
                            </section>
                        </div>
                        {{--购物车--}}
                        <div class="col-lg-8">
                            <section class="panel panel-default">
                                <header class="panel-heading font-bold">
                                    购物车 {{$order->user->UserInfo->nickname}}
                                </header>
                                <div class="panel-body">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>商品标题</th>
                                            <th>数量</th>
                                            <th>商品价格</th>
                                            <th>状态</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($order->RetailOrderGoods as $key=>$val)
                                        <tr>
                                            <td>{{$val->goods_id}}</td>
                                            <td>
                                                {{$val->title}}
                                            </td>
                                            <td>
                                                {{$val->total}}
                                            </td>
                                            <td>
                                                <input class="input-sm form-control" style="width: 50px;" type="text" value="{{$val->price}}">
                                            </td>
                                            <th>
                                                @if($val->status == 0)
                                                    待上菜
                                                @elseif($val->status == 1)
                                                    已上菜
                                                @else
                                                    未知状态
                                                @endif
                                                {{--<select name="account" style="width: 100px;" class="form-control form-xs m-b text-xs">--}}
                                                    {{--<option>待上菜</option>--}}
                                                    {{--<option>已上菜</option>--}}
                                                {{--</select>--}}
                                            </th>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><label class="label label-info">总计</label></td>
                                            <td>
                                                <label class="label label-danger">¥{{$order_price}}</label>
                                            </td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                        {{--购物车--}}

                    </section>
                </section>
            </section>
        </section>
    </section>
</section>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- App -->
<script src="{{asset('public/Branch')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Branch')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Branch')}}/js/app.js"></script>
<script src="{{asset('public/Branch')}}/library/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Branch')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Branch')}}/library/file-input/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="{{asset('public/Branch')}}/library/jPlayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="{{asset('public/Branch')}}/library/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script type="text/javascript" src="{{asset('public/Branch')}}/library/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript" src="{{asset('public/Branch')}}/library/wizard/js/jquery.bootstrap.wizard.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#rootwizard').bootstrapWizard({'tabClass': 'bwizard-steps'});
        $('.selected_btn').click(function(){
            $('.selected_btn').removeClass('btn-success').addClass('btn-info');
            $(this).addClass('btn-success').removeClass('btn-info');
        });
        $('.selected_table').click(function(){
            $('.selected_table').removeClass('btn-success').addClass('btn-info');
            $(this).addClass('btn-success').removeClass('btn-info');
        });
    });

    //确认订单
    function getPostForm(order_id,status){
        var url = $('#order_status_comfirm_url').val();
        var token = $('#_token').val();
        var data = {'_token':token,'order_id':order_id,'status':status};
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