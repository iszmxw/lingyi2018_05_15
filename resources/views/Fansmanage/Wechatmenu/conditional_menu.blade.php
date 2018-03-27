<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>零壹云管理平台 | 总分店管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/js/jPlayer/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/app.css" type="text/css" />
    <link href="{{asset('public/Fansmanage')}}/sweetalert/sweetalert.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/js/nestable/nestable.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/js/chosen/chosen.css" type="text/css" />
    <!--[if lt IE 9]>
    <script src="{{asset('public/Fansmanage')}}/js/ie/html5shiv.js"></script>
    <script src="{{asset('public/Fansmanage')}}/js/ie/respond.min.js"></script>
    <script src="{{asset('public/Fansmanage')}}/js/ie/excanvas.js"></script>
    <![endif]-->
</head>
<body class="">
<section class="vbox">
    <header class="bg-white-only header header-md navbar navbar-fixed-top-xs">
        @include('Fansmanage/Public/Header')
    </header>
    <section>
        <section class="hbox stretch">

            <!-- .aside -->
            <aside class="bg-black dk aside hidden-print" id="nav">
                <section class="vbox">
                    <section class="w-f-md scrollable">
                        @include('Fansmanage/Public/Nav')
                    </section>
                </section>
            </aside>
            <!-- /.aside -->
            <section id="content">
                <section class="hbox stretch">
                    <!-- side content -->
                    <aside class="aside bg-dark" id="sidebar">
                        <section class="vbox animated fadeInUp">
                            <section class="scrollable hover">
                                <div class="list-group no-radius no-border no-bg m-t-n-xxs m-b-none auto">
                                    <a href="{{url('fansmanage/wechatmenu/defined_menu')}}" class="list-group-item">
                                        自定义菜单
                                    </a>
                                    <a href="{{url('fansmanage/wechatmenu/conditional_menu')}}" class="list-group-item active">
                                        个性化菜单
                                    </a>
                                    <input type="hidden" id="defined_menu_add_url" value="{{ url('fansmanage/ajax/conditional_menu_add') }}">
                                    <input type="hidden" id="defined_menu_get_url" value="{{ url('fansmanage/ajax/conditional_menu_get') }}">
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                </div>
                            </section>
                        </section>
                    </aside>
                    <!-- / side content -->
                    <section>
                        <section class="vbox">
                            <section class="scrollable padder-lg">
                                <h2 class="font-thin m-b">个性化菜单</h2>
                                <div class="col-sm-4" id="menu_box">

                                </div>
                                <div class="col-sm-8">

                                    <section class="panel panel-default" id="ctrl_box">
                                        <header class="panel-heading font-bold">
                                            自定义菜单设置
                                        </header>
                                        <div class="panel-body">
                                            <form class="form-horizontal" role="form" id="defined_menu_add_check" action="{{ url('fansmanage/ajax/defined_menu_add_check') }}">
                                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                                <input type="hidden" id="wechat_menu_add" value="{{ url('fansmanage/ajax/wechat_menu_add') }}">
                                                <input type="hidden" name="response_type" id="response_type" value="1">

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label" for="input-id-1">会员标签组</label>
                                                    <div class="col-sm-10">
                                                        <select name="parent_id" class="form-control m-b" onchange="changeUserTag(this)">
                                                            <option value ="0">kbzz</option>
                                                            <option value ="0">果粒橙</option>
                                                            <option value ="0">龙的传人</option>
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label" for="input-id-1">上级菜单</label>
                                                    <div class="col-sm-10">
                                                        <select name="parent_id" class="form-control m-b">
                                                            <option value ="0">无</option>
                                                            {{--@foreach($list as $key=>$val)--}}
                                                                {{--<option value ="{{$val->id}}">{{$val->menu_name}}</option>--}}
                                                            {{--@endforeach--}}
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label" for="input-id-1">菜单名称</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" placeholder="限制四个字" name="menu_name" value="">
                                                    </div>
                                                </div>

                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label" for="input-id-1">事件类型</label>
                                                    <div class="col-sm-10">
                                                        <div class="btn-group" data-toggle="buttons">
                                                            <label class="btn btn-sm btn-info" style="margin-right: 5px;margin-top: 10px;"  id="type_1" onclick="$('#response_type').val(1)">
                                                                <input type="radio" name="event_type" value="1"><i class="fa fa-check text-active"></i> 链接
                                                            </label>

                                                            <label class="btn btn-sm btn-info onclick type_2" style="margin-right: 5px;margin-top: 10px;" >
                                                                <input type="radio" name="event_type" value="2"><i class="fa fa-check text-active"></i> 模拟关键字
                                                            </label>

                                                            <label class="btn btn-sm btn-info onclick type_2" style="margin-right: 5px;margin-top: 10px;">
                                                                <input type="radio" name="event_type" value="3"><i class="fa fa-check text-active"></i> 扫码
                                                            </label>

                                                            <label class="btn btn-sm btn-info onclick type_2" style="margin-right: 5px;margin-top: 10px;">
                                                                <input type="radio" name="event_type" value="4"><i class="fa fa-check text-active"></i> 扫码(带等待信息)
                                                            </label>

                                                            <label class="btn btn-sm btn-info onclick type_2" style="margin-right: 5px;margin-top: 10px;">
                                                                <input type="radio" name="event_type" value="5"><i class="fa fa-check text-active"></i> 拍照发图
                                                            </label>

                                                            <label class="btn btn-sm btn-info onclick type_2" style="margin-right: 5px;margin-top: 10px;">
                                                                <input type="radio" name="event_type" value="6"><i class="fa fa-check text-active"></i> 拍照或者相册发图
                                                            </label>

                                                            <label class="btn btn-sm btn-info onclick type_2" style="margin-right: 5px;margin-top: 10px;">
                                                                <input type="radio" name="event_type" value="7"><i class="fa fa-check text-active"></i> 微信相册发图
                                                            </label>

                                                            <label class="btn btn-sm btn-info onclick type_2" style="margin-right: 5px;margin-top: 10px;">
                                                                <input type="radio" name="event_type" value="8"><i class="fa fa-check text-active"></i> 地理位置
                                                            </label>
                                                        </div>
                                                        <span class="help-block m-b-none">
                      <p class="text-danger">事件类型为"链接"时，响应类型必须为跳转链接</p>
                  </span>
                                                    </div>
                                                </div>

                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label" for="input-id-1">响应类型</label>
                                                    <div class="col-sm-10">
                                                        <section class="panel panel-default">
                                                            <header class="panel-heading text-right bg-light">
                                                                <ul class="nav nav-tabs pull-left">
                                                                    <li id="link_type" class="active"><a href="#link_response" onclick="$('#response_type').val(1)" data-toggle="tab"><i class="fa fa-file-text-o text-muted"></i>&nbsp;&nbsp;跳转链接</a></li>
                                                                    <li id="text_type"><a href="#text_response" onclick="$('#response_type').val(2)" data-toggle="tab"><i class="icon icon-picture text-muted"></i>&nbsp;&nbsp;关键字回复</a></li>
                                                                </ul>
                                                                <span class="hidden-sm">&nbsp;</span>
                                                            </header>
                                                            <div class="panel-body">
                                                                <div class="tab-content">
                                                                    <div class="tab-pane fade in active" id="link_response">
                                                                        <input type="text" class="form-control" name="response_url" value="" placeholder="跳转链接">
                                                                        <span class="help-block m-b-none">
                                    <p>指定点击此菜单时要跳转的链接（注：链接需加http://）</p>
                                </span>
                                                                    </div>
                                                                    <div class="tab-pane fade in" id="text_response">
                                                                        <select style="width:260px" name="response_keyword" class="chosen-select2">
                                                                            <option value ="">请选择关键字</option>
                                                                            {{--@foreach($wechatreply as $key=>$val)--}}
                                                                                {{--<option value ="{{$val->keyword}}">{{$val->keyword}}</option>--}}
                                                                            {{--@endforeach--}}
                                                                        </select>
                                                                        <span class="help-block m-b-none">
                                    <p>指定点击此菜单时要执行的操作, 你可以在这里输入关键字, 那么点击这个菜单时就就相当于发送这个内容至公众号</p>
                                    <p>这个过程是程序模拟的, 比如这里添加关键字: 优惠券, 那么点击这个菜单是, 相当于接受了粉丝用户的消息, 内容为"优惠券"</p>
                                </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>

                                                <div class="line line-dashed b-b line-lg pull-in"></div>

                                                <div class="form-group">
                                                    <div class="col-sm-12 col-sm-offset-3">
                                                        <button type="button" class="btn btn-success" onclick="addPostForm()">添加菜单</button>
                                                        <button type="button" class="btn btn-dark" onclick="addMenuForm()">一键同步到微信公众号</button>
                                                    </div>
                                                </div>
                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                            </form>
                                        </div>
                                    </section>
                                    <section class="panel panel-default">
                                        <header class="panel-heading font-bold">
                                            常用入口链接
                                        </header>
                                        <div class="panel-body">
                                            <form class="form-horizontal" method="get">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label" for="input-id-1">点餐系统入口</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="input-id-1" disabled="" value="http://o2o.01nnt.com/diancan-11">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label" for="input-id-1">外卖系统入口</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control"  disabled="" value="http://o2o.01nnt.com/diancan-12">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label" for="input-id-1">预约系统入口</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" disabled="" value="http://o2o.01nnt.com/diancan-13">
                                                    </div>
                                                </div>
                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                            </section>
                        </section>
                    </section>
                </section>
            </section>
        </section>
    </section>
</section>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<script src="{{asset('public/Fansmanage')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Fansmanage')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Fansmanage')}}/js/app.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/app.plugin.js"></script>
<script type="text/javascript" src="{{asset('public/Fansmanage')}}/js/jPlayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="{{asset('public/Fansmanage')}}/js/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/nestable/jquery.nestable.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="{{asset('public/Fansmanage')}}/js/jPlayer/demo.js"></script>
<script type="text/javascript" src="{{asset('public/Fansmanage')}}/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript">
    $(function(){
        get_menu_add_box();
//        get_menu();
    });

    function get_menu_add_box(){
        var url = $('#defined_menu_add_url').val();
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
                $('#ctrl_box').html(response);
            }
        });
    }

//    function get_menu(){
//        var url = $('#defined_menu_get_url').val();
//        var token = $('#_token').val();
//        var data = {'_token':token};
//        $.post(url,data,function(response){
//            if(response.status=='-1'){
//                swal({
//                    title: "提示信息",
//                    text: response.data,
//                    confirmButtonColor: "#DD6B55",
//                    confirmButtonText: "确定",
//                },function(){
//                    window.location.reload();
//                });
//                return;
//            }else{
//                $('#menu_box').html(response);
//            }
//        });
//    }
</script>
</body>
</html>
