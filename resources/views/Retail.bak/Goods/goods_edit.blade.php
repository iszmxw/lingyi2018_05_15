<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>编辑商品 | 零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail/library/jPlayer')}}/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css" />
    <link href="{{asset('public/Retail/library/sweetalert')}}/sweetalert.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('public/Retail/library/trumbowyg')}}/design/css/trumbowyg.css">
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
                        <div class="m-b-md">
                            <h3 class="m-b-none">编辑商品</h3>
                        </div>
                        <div class="row row-sm">
                            <button class="btn btn-s-md btn-success" type="button" onclick="history.back()" id="addBtn"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回列表</button>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading text-right bg-light">
                                <ul class="nav nav-tabs pull-left">
                                    <li class="active"><a href="#baseinfo" data-toggle="tab"><i class="fa fa-file-text-o text-muted"></i>&nbsp;&nbsp;基础信息</a></li>
                                    <li><a href="#picture" data-toggle="tab"><i class="icon icon-picture text-muted"></i>&nbsp;&nbsp;商品图片</a></li>
                                </ul>
                                <span class="hidden-sm">&nbsp;</span>
                            </header>
                            <div class="panel-body">

                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="baseinfo">
                                        <form method="post" class="form-horizontal"  role="form" id="currentForm" action="{{ url('retail/ajax/goods_edit_check') }}">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <input type="hidden" name="goods_id" value="{{$goods->id}}">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">商品分类</label>
                                                <div class="col-sm-8">
                                                    <select name="category_id" class="form-control m-b">
                                                        <option value ="0">请选择</option>
                                                        @foreach($category as $key=>$val)
                                                            <option value ="{{$val->id}}" @if($goods->category != null && $val->id == $goods->category->id)selected @endif>{{$val->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">商品名称</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="name" value="{{$goods->name}}">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">价格</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="price" value="{{$goods->price}}">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">库存</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="stock" disabled value="{{$goods->stock}}">
                                                </div>
                                            </div>


                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">商品条码</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="barcode" value="{{$goods->barcode}}">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">排序</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="displayorder" value="{{$goods->displayorder}}">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">商品详情</label>
                                                <div class="col-sm-8">
                                                    <textarea id="form-content" name="details" class="editor" cols="30" rows="10"> {{$goods->details}}</textarea>
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <div class="col-sm-12 col-sm-offset-6">
                                                    <button type="button" class="btn btn-success" onclick="return postEditForm();">保存信息</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="tab-pane fade in" id="picture">
                                        <button type="button" class="btn btn-success" onclick="return addthumb();"><i class="fa fa-plus"></i>&nbsp;&nbsp;添加图片</button>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div id="thumb_content">
                                        @include('Retail/Goods/goods_thumb_list')
                                        </div>
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







<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>

{{--上传图片--}}
<div class="modal fade" id="myModal_thumb" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-horizontal" role="form" id="uploadForm" method="post" enctype="multipart/form-data" action="">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
        <input type="hidden" name="goods_id" id="goods_id" value="{{$goods->id}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">上传图片</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="get">
                        <div class="form-group">
                            <label class="col-sm-2 text-right">图片信息</label>
                            <div class="col-sm-10">
                                <input type="file" class="filestyle" style="display: none;" name="upload_thumb" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline v-middle input-s">
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button type="button" class="btn btn-success" onclick="return uploadForm();">上传图片</button>
                </div>
            </div>
        </div>
    </form>
</div>
{{--上传图片--}}

<script src="{{asset('public/Retail')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Retail')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Retail')}}/js/app.js"></script>
<script src="{{asset('public/Retail/library')}}/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail/library')}}/file-input/bootstrap-filestyle.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/jquery.jplayer.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script src="{{asset('public/Retail/library')}}/sweetalert/sweetalert.min.js"></script>
<script src="{{asset('public/Retail/library')}}/trumbowyg/trumbowyg.js"></script>
<script src="{{asset('public/Retail/library')}}/trumbowyg/plugins/upload/trumbowyg.upload.js"></script>
<script src="{{asset('public/Retail/library')}}/trumbowyg/plugins/base64/trumbowyg.base64.js"></script>
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

        $('#deleteBtn').click(function(){
            $('#myModal').modal();
        });
    });
    //弹出大图
    function bigthumb(imgurl) {
            var bigthumb = '<div class="modal-dialog">'+
                           '<div class="modal-content">'+
                           '<div class="modal-header">' +
                           '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
                           '<h4 class="modal-title">浏览大图</h4></div>' +
                           '<div class="modal-body">' +
                           '<img src="'+imgurl+'" style="max-width: 100%;height:auto;" /></div></div></div>';
        $('#myModal').modal();
        console.log(bigthumb);
        $('#myModal').html(bigthumb);
    }

    //弹出上传图片窗口
    function addthumb() {
        $('#myModal_thumb').modal();
    }


    //编辑提交表单
    function postEditForm() {
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
                    window.location.href = "{{asset("retail/goods/goods_list")}}";
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

    //上传图片提交表单
    function uploadForm() {
        var formData = new FormData($( "#uploadForm" )[0]);
        $.ajax({
            url: '{{ url('retail/ajax/upload_thumb_check') }}' ,
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (json) {
                if (json.status == -1) {
                    window.location.reload();
                } else if(json.status == 1) {
                    swal({
                        title: "提示信息",
                        text: json.data,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "确定",
                    },function(){
                        //图片添加成功后异步刷新图片列表部分
                        var url = '{{url('retail/ajax/goods_thumb')}}';//需要异步加载的页面
                        var goods_id = $("#goods_id").val();
                        var token = $("#_token").val();
                        var data = {'goods_id':goods_id,'_token':token};
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
                                $('#thumb_content').html(response);
                                $('#myModal_thumb').modal('hide');
                            }
                        });
                    });
                }else{
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

    //删除商品图片信息
    function getDeleteForm(id){
        var url = $('#goods_thumb_delete_comfirm_url').val();
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

</script>





</body>
</html>