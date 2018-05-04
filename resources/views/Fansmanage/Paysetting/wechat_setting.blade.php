<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8"/>
    <title>零壹云管理平台 | 总店管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/js/jPlayer/jplayer.flat.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/bootstrap.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/animate.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/simple-line-icons.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/font.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/css/app.css" type="text/css"/>
    <link href="{{asset('public/Fansmanage/library/sweetalert')}}/css/sweetalert.css" rel="stylesheet">
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
                <section class="vbox">
                    <section class="scrollable padder">
                        <div class="m-b-md">
                            <h3 class="m-b-none">微信支付设置</h3>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                微信支付设置
                            </header>
                            <div class="row wrapper">

                                <div class="well">
                                    <h3>温馨提示</h3>
                                    <p class="text-danger">1.你必须向微信公众平台提交企业信息以及银行账户资料，审核通过并签约后才能使用微信支付功能</p>
                                    <p class="text-danger">
                                        2.零壹支持微信支付接口，注意你的零壹访问地址一定不要写错了，这里我们用访问地址代替下面说明中出现的链接，申请微信支付的接口说明如下：</p>
                                    <p class="text-danger">JS API网页支付参数</p>
                                    <p class="text-danger">支付授权目录: https://o2o.01nnt.com/wechat/</p>
                                    <p class="text-danger">支付请求实例: https://o2o.01nnt.com/wechat/pay.php//</p>
                                    <p class="text-danger">共享收货地址: 选择"是"/</p>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <form class="form-horizontal" method="get">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">商户号</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="input-id-1" value="">
                                        </div>
                                    </div>


                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">PaySignKey</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="input-id-1" value="">
                                        </div>
                                    </div>

                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">秘钥</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="input-id-1" value="">
                                        </div>
                                    </div>

                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">微信网页授权</label>
                                        <div class="col-sm-8">
                                            <label>
                                                <input type="checkbox" checked=""><i></i> 授权回调页面已经设置成o2o.01nnt.com
                                            </label>
                                        </div>
                                    </div>

                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <div class="col-sm-12 col-sm-offset-6">

                                            <button type="button" class="btn btn-success" id="addBtn">保存资料</button>
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>

                                </form>
                            </div>
                        </section>

                    </section>
                </section>
            </section>
        </section>
    </section>
</section>

{{--编辑店铺信息--}}
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-horizontal" role="form" id="store_edit" method="post" enctype="multipart/form-data" action="">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
        <input type="hidden" name="organization_id" id="organization_id" value="{{$company_info['id']}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">店铺信息编辑</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 text-right">店铺名称</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$company_info->organization_name}}" name="organization_name" placeholder="店铺名称" class="form-control">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>


                    <div class="form-group">
                        <label class="col-sm-2 text-right">负责人</label>
                        <div class="col-sm-10">
                            @if(!empty($company_info->fansmanageinfo->fansmanage_owner))
                                <input type="text" value="{{ $company_info->fansmanageinfo->fansmanage_owner }}" name="simple_owner" placeholder="负责人" class="form-control">
                            @else
                                <input type="text" value="" name="simple_owner" placeholder="负责人" class="form-control">
                            @endif
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">手机号码</label>
                        <div class="col-sm-10">
                            @if(!empty($company_info->fansmanageinfo->fansmanage_owner_mobile))
                                <input type="number" value="{{$company_info->fansmanageinfo->fansmanage_owner_mobile}}" name="mobile" placeholder="手机号码" class="form-control">
                            @else
                                <input type="number" value="" name="mobile" placeholder="手机号码" class="form-control">
                            @endif
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">商户LOGO</label>
                        <div class="col-sm-10">
                            <input type="file" name="simple_logo" class="filestyle" style="display: none;" data-icon="true" data-classButton="btn btn-default" data-classInput="form-control inline v-middle input-s">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">安全密码</label>
                        <div class="col-sm-10">
                            <input type="password" value="" name="safe_password" placeholder="安全密码" class="form-control">
                        </div>
                    </div>
                    <div style="clear:both;"></div>

                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="button" onclick="return EditStore()">确定</button>
                </div>
            </div>
        </div>
    </form>
</div>
{{--编辑店铺信息--}}

<script src="{{asset('public/Fansmanage')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Fansmanage')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Fansmanage')}}/js/app.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/file-input/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="{{asset('public/Fansmanage')}}/js/jPlayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="{{asset('public/Fansmanage')}}/js/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script type="text/javascript" src="{{asset('public/Fansmanage')}}/js/jPlayer/demo.js"></script>
<script src="{{asset('public/Fansmanage/sweetalert')}}/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        @if(!empty($organization->fansmanageinfo->logo))
        $("#retail_logo").val("{{$organization->fansmanageinfo->logo}}");
        @endif
    });
    $('#editBtn').click(function () {
        $('#myModal').modal();
    });

    //编辑店铺信息
    function EditStore() {
        var formData = new FormData($("#store_edit")[0]);
        $.ajax({
            url: '{{ url('fansmanage/ajax/fansmanage_edit_check') }}',
            type: 'post',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (json) {
                if (json.status == -1) {
                    swal({
                        title: "提示信息",
                        text: json.data,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "确定",
                    }, function () {
                        window.location.reload();
                    });
                } else if (json.status == 1) {
                    swal({
                        title: "提示信息",
                        text: json.data,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "确定",
                    }, function () {
                        window.location.reload();
                    });
                } else {
                    swal({
                        title: "提示信息",
                        text: json.data,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "确定"
                    });
                }
            },
            error: function (json) {
                console.log(json);
            }
        });
    }
</script>
</body>
</html>























