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
                            <h3 class="m-b-none">现场订单</h3>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                现场订单查询
                            </header>
                            <div class="row wrapper">
                                <form class="form-horizontal" method="get">
                                    <label class="col-sm-1 control-label">订单编号</label>
                                    <div class="col-sm-2">
                                        <input class="input-sm form-control" name="ordersn" size="16" type="text" value="{{$search_data['ordersn']}}">
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-s-md btn-info"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索</button>
                                    </div>
                                </form>
                            </div>
                            <div class="row wrapper">
                                <form class="form-horizontal" method="get">
                                        <label class="col-sm-1 control-label">用户账号</label>
                                        <div class="col-sm-1">
                                            <input class="input-sm form-control" name="account" size="16" type="text" value="{{$search_data['account']}}">
                                        </div>

                                        <label class="col-sm-1 control-label">支付方式</label>
                                        <div class="col-sm-2">
                                            <select name="paytype" class="form-control m-b">
                                                <option @if($search_data['paytype'] == null ) selected @endif>请选择</option>
                                                <option value="0" @if($search_data['paytype']=='0') selected @endif >银行卡支付</option>
                                                <option value="1" @if($search_data['paytype']=='1') selected @endif >支付宝扫码</option>
                                                <option value="2" @if($search_data['paytype']=='2') selected @endif >支付宝二维码</option>
                                                <option value="3" @if($search_data['paytype']=='3') selected @endif >微信扫码</option>
                                                <option value="4" @if($search_data['paytype']=='4') selected @endif >微信二维码</option>
                                                <option value="-1" @if($search_data['paytype']=='-1') selected @endif >现金支付，其他支付</option>
                                            </select>
                                        </div>
                                        <label class="col-sm-1 control-label">订单状态</label>
                                        <div class="col-sm-1">
                                            <select name="status" class="form-control m-b">
                                                <option @if($search_data['status'] == null ) selected @endif>请选择</option>
                                                <option value="0" @if($search_data['status']=='0') selected @endif >待付款</option>
                                                <option value="1" @if($search_data['status']=='1') selected @endif >已付款</option>
                                                <option value="3" @if($search_data['status']=='3') selected @endif >已完成</option>
                                                <option value="-1" @if($search_data['status']=='-1') selected @endif >已取消</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-s-md btn-info"><i class="fa fa-search"></i>&nbsp;&nbsp;高级搜索</button>
                                        </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped b-t b-light">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>订单编号</th>
                                        <th>操作员账号</th>
                                        <th>操作员昵称</th>
                                        <th>联系方式</th>
                                        <th>支付方式</th>
                                        <th>订单金额</th>
                                        <th>餐位费</th>
                                        <th>订单状态</th>
                                        <th>下单时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $key=>$val)
                                    <tr>
                                        <td>{{$val->id}}</td>
                                        <td>{{$val->ordersn}}</td>
                                        <td>{{$val->account->account}}</td>
                                        <td>{{$val->account_info->realname}}</td>
                                        <td>{{$val->account->mobile}}</td>
                                        {{--1为余额，2为在线，3为到付,4现场现金， 5现场刷卡，6现场支付宝，7现场微信，8线上手动确认付款--}}
                                        <td>


                                        @if($val->paytype == '0' )
                                                <label class="label label-info">银行卡支付</label>
                                        @elseif($val->paytype == '1' )
                                                <label class="label label-info">支付宝扫码</label>
                                        @elseif($val->paytype == '2' )
                                                <label class="label label-info">支付宝二维码</label>
                                        @elseif($val->paytype == '3' )
                                                <label class="label label-info">微信扫码</label>
                                        @elseif($val->paytype == '4' )
                                                <label class="label label-info">微信二维码</label>
                                        @elseif($val->paytype == '-1' )
                                                <label class="label label-info">现金支付，其他支付</label>
                                        @elseif($val->paytype == null)
                                                <label class="label label-danger">暂未支付</label>
                                        @endif
                                        </td>

                                        <td>{{$val->order_price}}</td>
                                        <td>{{$val->seatfee}}</td>

                                        <th>
                                                {{---0待付款，-1取消状态，1为已付款，2为已发货，3为成功--}}
                                                @if($val->status==-1)
                                                    <label class="label label-default">已取消</label>
                                                @elseif($val->status==0)
                                                    <label class="label label-primary">待付款</label>
                                                @elseif($val->status==1)
                                                    <label class="label label-warning">已付款</label>
                                                @elseif($val->status==2)
                                                    <label class="label label-success">已发货</label>
                                                @elseif($val->status==3)
                                                    <label class="label label-info">已完成</label>
                                                @endif
                                        </th>

                                        <td>{{$val->created_at}}</td>
                                        <td>
                                            <button class="btn btn-info btn-xs" id="editBtn" onclick="location.href='{{url('retail/order/order_spot_detail')}}?id={{$val->id}}'"><i class="fa fa-edit"></i>&nbsp;&nbsp;查看详情</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <footer class="panel-footer">
                                <div class="row">

                                    <div class="col-sm-12 text-right text-center-xs">
                                        {{$list->appends($search_data)->links()}}
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
</body>
</html>