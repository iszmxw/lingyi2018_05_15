<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>新增供应商 | 零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail/library/jPlayer')}}/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail/library/sweetalert')}}/sweetalert.css">
    <!--[if lt IE 9]>
    <script src="{{asset('public/Retail/library/ie')}}/html5shiv.js"></script>
    <script src="{{asset('public/Retail/library/ie')}}/respond.min.js"></script>
    <script src="{{asset('public/Retail/library/ie')}}/excanvas.js"></script>
    <![endif]-->
</head>
<body class="">
<section class="vbox">
    @include('Retail/Public/Header')
    <section>
        <section class="hbox stretch">
            <!-- .aside -->
        @include('Retail/Public/Nav')
        <!-- /.aside -->
            <section id="content">
                <section class="vbox">
                    <section class="scrollable padder">
                        <div class="row wrapper">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-s-md btn" onclick="window.location='supplier_list'">供应商列表</button>
                                <button type="button" class="btn btn-s-md btn-success" onclick="window.location='supplier_add'">&nbsp;&nbsp;添加供应商</button>
                            </div>
                        </div>
                        <section class="panel panel-default">

                            <header class="panel-heading font-bold">
                                添加商品供应商
                            </header>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal"  role="form" id="currentForm" action="{{ url('retail/ajax/supplier_add_check') }}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">公司名称</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="company_name" value="">
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">联系人姓名</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="contactname">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">联系人电话</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="contactmobile">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">排序</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="displayorder" value="0">
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-id-1">安全密码</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="safe_password" value="">
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>

                                    <div class="form-group">
                                        <div class="col-sm-12 col-sm-offset-6">
                                            <button type="button" class="btn btn-success" onclick="return postForm();">保存信息</button>
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
<script src="{{asset('public/Retail/library/slimscroll')}}/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail/library/file-input')}}/bootstrap-filestyle.min.js"></script>
<script src="{{asset('public/Retail/library/jPlayer')}}/jquery.jplayer.min.js"></script>
<script src="{{asset('public/Retail/library/jPlayer')}}/add-on/jplayer.playlist.min.js"></script>
<script src="{{asset('public/Retail/library/sweetalert')}}/sweetalert.min.js"></script>
<script>
    //提交表单
    function postForm() {
        var target = $("#currentForm");
        var url = target.attr("action");
        var data = target.serialize();
        $.post(url, data, function (json) {
            if (json.status == -1) {
                window.location.reload();
            } else if(json.status == 1) {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定"
                },function(){
                    window.location.href='supplier_list';
                });
            }else{
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定"
                });
            }
        });
    }
</script>
</body>
</html>