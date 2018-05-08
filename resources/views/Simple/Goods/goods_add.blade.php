<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>添加商品 | 零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Simple/library/jPlayer')}}/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Simple')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Simple')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Simple')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Simple')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Simple')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Simple')}}/css/app.css" type="text/css" />
    <link href="{{asset('public/Simple/library/sweetalert')}}/sweetalert.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('public/Simple/library/trumbowyg')}}/design/css/trumbowyg.css">
    <!--[if lt IE 9]>
    <script src="{{asset('public/Simple/library/ie')}}/html5shiv.js"></script>
    <script src="{{asset('public/Simple/library/ie')}}/respond.min.js"></script>
    <script src="{{asset('public/Simple/library/ie')}}/excanvas.js"></script>
    <![endif]-->
</head>
<body class="">
<section class="vbox">
    @include('Simple/Public/Header')
    <section>
        <section class="hbox stretch">
            <!-- .aside -->
            @include('Simple/Public/Nav')
            <!-- /.aside -->
            <section id="content">
                <section class="vbox">
                    <section class="scrollable padder">
                        <div class="m-b-md">
                            <h3 class="m-b-none">添加商品</h3>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading text-right bg-light">
                                <ul class="nav nav-tabs pull-left">
                                    <li class="active"><a href="#baseinfo" data-toggle="tab"><i class="fa fa-file-text-o text-muted"></i>&nbsp;&nbsp;基础信息</a></li>
                                </ul>
                                <span class="hidden-sm">&nbsp;</span>
                            </header>
                            <div class="panel-body">

                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="baseinfo">
                                        <form method="post" class="form-horizontal"  role="form" id="currentForm" action="{{ url('simple/ajax/goods_add_check') }}">
                                            <input type="hidden" id="add_upload" value="{{ url('simple/ajax/add_upload') }}">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">商品分类</label>
                                                <div class="col-sm-8">
                                                    <select name="category_id" class="form-control m-b">
                                                        <option value ="0">请选择</option>
                                                        @foreach($category as $key=>$val)
                                                        <option value ="{{$val->id}}">{{$val->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">商品名称</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="name">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">商品图片</label>
                                                <div class="col-sm-8">
                                                    <button class="btn btn-info dim btn-large-dim" type="button" onclick="return add_upload('{{$admin_data['organization_id']}}')">+加图</button>
                                                </div>
                                            </div>


                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">价格</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="price">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">库存</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="stock">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">商品条码</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="barcode">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">排序</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="displayorder">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">商品详情</label>
                                                <div class="col-sm-8">
                                                    <textarea id="form-content" name="details" class="editor" cols="30" rows="10"> </textarea>
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <div class="col-sm-12 col-sm-offset-6">
                                                    <button type="button" class="btn btn-success" onclick="return postForm();">保存信息</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </section>
                    </section>
                </section>
            </section>
        </section>
    </section>
</section>

<script src="{{asset('public/Simple')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Simple')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Simple')}}/js/app.js"></script>
<script src="{{asset('public/Simple/library')}}/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Simple')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Simple/library')}}/file-input/bootstrap-filestyle.min.js"></script>
<script src="{{asset('public/Simple/library')}}/jPlayer/jquery.jplayer.min.js"></script>
<script src="{{asset('public/Simple/library')}}/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script src="{{asset('public/Simple/library')}}/sweetalert/sweetalert.min.js"></script>
<script src="{{asset('public/Simple/library')}}/trumbowyg/trumbowyg.js"></script>
<script src="{{asset('public/Simple/library')}}/trumbowyg/plugins/upload/trumbowyg.upload.js"></script>
<script src="{{asset('public/Simple/library')}}/trumbowyg/plugins/base64/trumbowyg.base64.js"></script>
<script>
    $(document).ready(function() {
        $('#form-content').trumbowyg({
            lang: 'fr',
            closable: false,
            mobile: true,
            fixedBtnPane: true,
            fixedFullWidth: true,
            semantic: true,
            resetCss: true,
            autoAjustHeight: true,
            autogrow: true
        });
    });
    //获取图片上传窗口
    function add_upload(organization_id) {
        var url = $("#add_upload").val();
        var data = {'organization_id':organization_id};
        $.post(url, data, function (json) {
            if (json.status == -1) {
                alert('1');
            } else if(json.status == 1) {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                },function(){
                    alert('1');
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
                    confirmButtonText: "确定",
                },function(){
                    window.location.href = "{{asset("simple/goods/goods_edit?goods_id=")}}"+json.goods_id;
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