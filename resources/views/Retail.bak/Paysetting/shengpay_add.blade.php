<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8"/>
    <title>零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/library/jPlayer/jplayer.flat.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css"/>
    <link href="{{asset('public/Retail')}}/library/sweetalert/sweetalert.css" rel="stylesheet"/>
    <link href="{{asset('public/Retail')}}/library/wizard/css/custom.css" rel="stylesheet"/>
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
                            <h3 class="m-b-none">盛付通终端机器号添加</h3>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                盛付通终端机器号添加
                            </header>
                            <div class="row wrapper">
                                <div class="well">
                                    <h3>温馨提示</h3>
                                    <p class="text-danger">1.你必须向微信公众平台提交企业信息以及银行账户资料，审核通过并签约后才能使用微信支付功能</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <form class="form-horizontal" method="post" role="form" id="currentForm"
                                      action="{{ url('retail/ajax/shengpay_add_check') }}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">终端号</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="" name="terminal_num">
                                        </div>
                                    </div>

                                    <div class="line line-dashed b-b line-lg pull-in"></div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">安全密码</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" value="" name="safe_password">
                                        </div>
                                    </div>

                                    <div class="line line-dashed b-b line-lg pull-in"></div>

                                    <div class="form-group">
                                        <div class="col-sm-12 col-sm-offset-6">

                                            <button type="button" class="btn btn-success" onclick="postForm()">确定添加
                                            </button>
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
<script type="text/javascript"
        src="{{asset('public/Retail')}}/library/wizard/js/jquery.bootstrap.wizard.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#rootwizard').bootstrapWizard({'tabClass': 'bwizard-steps'});
        $('.selected_btn').click(function () {
            $('.selected_btn').removeClass('btn-success').addClass('btn-info');
            $(this).addClass('btn-success').removeClass('btn-info');
        });
        $('.selected_table').click(function () {
            $('.selected_table').removeClass('btn-success').addClass('btn-info');
            $(this).addClass('btn-success').removeClass('btn-info');
        });
    });


    //提交表单
    function postForm() {
        var target = $("#currentForm");
        var url = target.attr("action");
        var data = target.serialize();
        $.post(url, data, function (json) {
            if (json.status == -1) {
                window.location.reload();
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
                    confirmButtonText: "确定",
                    //type: "warning"
                });
            }
        });
    }


</script>
</body>
</html>