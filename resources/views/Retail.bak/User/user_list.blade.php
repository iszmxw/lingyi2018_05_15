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
                            <h3 class="m-b-none">粉丝用户管理</h3>
                        </div>

                        <section class="panel panel-default">
                            <header class="panel-heading">
                                粉丝用户管理
                            </header>
                            <div class="row wrapper">
                                <form class="form-horizontal" method="get">
                                    <input type="hidden" id="store_label_add_check" value="{{ url('retail/ajax/store_label_add_check') }}">
                                    <input type="hidden" id="user_list_edit" value="{{ url('retail/ajax/user_list_edit') }}">
                                    <input type="hidden" id="user_list_wallet" value="{{ url('retail/ajax/user_list_wallet') }}">
                                    <input type="hidden" id="user_list_lock" value="{{ url('retail/ajax/user_list_lock') }}">
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                    <label class="col-sm-1 control-label">用户账号</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="account" value="{{$account}}" placeholder="用户账号">
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
                                        <th>ID</th>
                                        <th>微信头像</th>
                                        <th>用户账号</th>
                                        <th>微信昵称</th>
                                        <th>关注公众号</th>
                                        <th>源头商家</th>
                                        <th>推荐人</th>
                                        <th>粉丝标签</th>
                                        <th>注册时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $key=>$value)
                                        <tr>
                                            <td>{{$value->id}}</td>
                                            <td><img src="{{asset('public/Fansmanage')}}/img/m1.jpg" alt="" class="r r-2x img-full" style="width: 50px; height: 50px;"></td>
                                            <td>{{$value->user->account}}</td>
                                            <td>{{$value->nickname}}</td>
                                            <td><label class="label label-success">是</label></td>
                                            <td><label class="label label-info">
                                                    @if($value->userOrigin->origin_id==$organization_id)
                                                        {{$store_name}}
                                                    @else
                                                        零壹联盟
                                                    @endif</label></td>
                                            <td><label class="label label-primary">{{$value->recommender_name}}</label></td>
                                            <td>
                                                <select style="width:100px" class="chosen-select2" onchange="changeUserTag(this,'{{$value->user_id}}','{{$value->store_id}}','{{$value->nickname}}')">
                                                    <option value="0">无标签</option>
                                                    @foreach($label as $k=>$v)
                                                        <option value="{{$v->id}}" @if($v->id == $value->label_id) selected @endif>{{$v->label_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{$value->created_at}}</td>
                                            <td>
                                                <button class="btn btn-info btn-xs" id="editBtn" onclick="getEditForm({{$value->id}})"><i class="fa fa-edit"></i>&nbsp;&nbsp;粉丝详情</button>
                                                {{--<button class="btn btn-primary btn-xs" id="balanceBtn" onclick="getwalletForm()"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;粉丝钱包</button>--}}
                                                @if($value->status == 1 || $value->status == -1)
                                                    <button class="btn btn-warning btn-xs" id="lockBtn" onclick="getlockForm('{{$value->id}}','{{$value->status}}')"><i class="fa fa-lock"></i>&nbsp;&nbsp;冻结</button>
                                                @else
                                                    <button class="btn btn-success btn-xs" id="lockBtn" onclick="getlockForm({{$value->id}},'{{$value->status}}')"><i class="fa fa-lock"></i>&nbsp;&nbsp;解冻</button>
                                                @endif
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



    //粉丝钱包
    function getwalletForm(id,status){
        var url = $('#user_list_wallet').val();
        var token = $('#_token').val();
        var data = {'_token':token,'id':id,'status':status};
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



    //冻结粉丝
    function getlockForm(id,status){
        var url = $('#user_list_lock').val();
        var token = $('#_token').val();
        var data = {'_token':token,'id':id,'status':status};
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