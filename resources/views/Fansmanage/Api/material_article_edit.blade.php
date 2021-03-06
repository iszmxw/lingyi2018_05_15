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
    <link rel="stylesheet" href="{{asset('public/Fansmanage')}}/trumbowyg/design/css/trumbowyg.css">
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
                                <a href="{{url('fansmanage/api/material_image')}}" class="list-group-item">
                                    图片素材
                                </a>
                                <a href="{{url('fansmanage/api/material_article')}}" class="list-group-item active">
                                    图文素材
                                </a>

                                </div>
                            </section>
                        </section>
                    </aside>
                    <!-- / side content -->
                    <section>
                        <section class="vbox">
                            <section class="scrollable padder-lg">
                                <h2 class="font-thin m-b">编辑单条图文</h2>
                                <div class="row row-sm">
                                    <button class="btn btn-s-md btn-success" type="button" onclick="location.href='{{url('fansmanage/api/material_article')}}'" id="addBtn"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回列表</button>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                </div>
                                <section class="panel panel-default">
                                    <header class="panel-heading font-bold">
                                        编辑单条图文
                                    </header>
                                    <div class="panel-body">
                                        <form class="form-horizontal tasi-form" id="currentForm" method="post" action="{{ url('fansmanage/ajax/material_article_edit_check') }}">
                                            <input type="hidden" id="material_image_select_url" value="{{ url('fansmanage/ajax/material_image_select') }}">
                                            <input type="hidden" id="material_article_url" value="{{ url('fansmanage/api/material_article') }}">
                                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                                            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">图片</label>
                                                <div class="col-sm-10">
                                                    <button class="btn btn-info" type="button" onclick="selectImageForm(0);">选择图片素材</button>
                                                    <br/><br/>
                                                    <img id="img_show_0" src="{{url('uploads/wechat')}}/{{$admin_data['organization_id']}}/{{$image_info['filename']}}" style="width: 100px; height:100px;">
                                                    <input type="hidden" name="img_id" id="img_id_0" value="{{$image_info['id']}}">
                                                    <input type="hidden" name="thumb_media_id" id="media_id_0"  value="{{$image_info['media_id']}}">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">标题</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="input-id-1" name="title" value="{{$info['title']}}">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">作者</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="input-id-1" name="author" value="{{$info['author']}}">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">摘要</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" rows="5" name="digest">{{$info['digest']}}</textarea>
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">原文地址</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="input-id-1" name="origin_url" value="@if(!empty($info['content_source_url'])){{$info['content_source_url']}}@endif">
                                                </div>
                                            </div>

                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-id-1">正文</label>
                                                <div class="col-sm-10">
                                                    <textarea id="form-content" name="content" class="editor" cols="30" rows="10">{{$info['content']}} </textarea>
                                                </div>
                                            </div>
                                            <div class="line line-dashed b-b line-lg pull-in"></div>

                                            <div class="form-group">
                                                <div class="col-sm-12 col-sm-offset-6">

                                                    <button type="button" class="btn btn-success" onclick="return postForm();" id="save_btn">保存信息</button>
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
<script type="text/javascript" src="{{asset('public/Fansmanage')}}/js/jPlayer/demo.js"></script>

<script src="{{asset('public/Fansmanage')}}/js/wysiwyg/jquery.hotkeys.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/wysiwyg/bootstrap-wysiwyg.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/wysiwyg/demo.js"></script>

<script type="text/javascript" src="{{asset('public/Fansmanage')}}/sweetalert/sweetalert.min.js"></script>
<script src="{{asset('public/Fansmanage')}}/js/file-input/bootstrap-filestyle.min.js"></script>
<script src="{{asset('public/Fansmanage')}}/trumbowyg/trumbowyg.js"></script>


<script src="{{asset('public/Fansmanage')}}/trumbowyg/plugins/upload/trumbowyg.upload.js"></script>

<script src="{{asset('public/Fansmanage')}}/trumbowyg/plugins/base64/trumbowyg.base64.js"></script>

<script type="text/javascript">
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

    //弹出图片上传框
    function selectImageForm(i){
        var url = $('#material_image_select_url').val();
        var token = $('#_token').val();
        var data = {'_token':token,'i':i};
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
                   window.location.reload();
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