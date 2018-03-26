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
                                    <a href="{{url('fansmanage/message/auto_reply')}}" class="list-group-item">
                                        关键词自动回复
                                    </a>
                                    <a href="{{url('fansmanage/message/subscribe_reply')}}" class="list-group-item active">
                                        关注后自动回复
                                    </a>
                                    <a href="{{url('fansmanage/message/default_reply')}}" class="list-group-item ">
                                        默认回复
                                    </a>
                                </div>
                            </section>
                        </section>
                    </aside>
                    <!-- / side content -->
                    <section>
                        <section class="vbox">
                            <section class="scrollable padder-lg">
                                <h2 class="font-thin m-b">关注后自动回复</h2>
                                <section class="panel panel-default">
                                    <header class="panel-heading">
                                        图文素材列表
                                        <input type="hidden" id="id" value="@if(!empty($info)){{$info['id']}}@endif">
                                        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                        <input type="hidden" id="subscribe_reply_text_edit_url" value="{{ url('fansmanage/ajax/subscribe_reply_text_edit') }}">
                                        <input type="hidden" id="subscribe_reply_article_edit_url" value="{{ url('fansmanage/ajax/subscribe_reply_article_edit') }}">
                                        <input type="hidden" id="subscribe_reply_image_edit_url" value="{{ url('fansmanage/ajax/subscribe_reply_image_edit') }}">
                                    </header>

                                    <div class="table-responsive">
                                        <table class="table table-striped b-t b-light">
                                            <thead>
                                            <tr>
                                                <th>回复类型</th>
                                                <th>是否启用</th>
                                                <th>回复内容</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>文字回复</td>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" disabled class="reply_type" autocomplete="off" value="1" @if(!empty($info) && $info['reply_type']=='1') checked="checked" @endif>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-xs "  id="editText" onclick="return getEditTextForm();"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;编辑文字</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>图文素材</td>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" disabled autocomplete="off" class="reply_type" value="3" @if(!empty($info) && $info['reply_type']=='3') checked="checked" @endif>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-xs" id="editArticle" onclick="return getEditArticleForm();"><i class="fa fa-tasks"></i>&nbsp;&nbsp;编辑图文</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>图片素材</td>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" disabled autocomplete="off"  class="reply_type" value="2" @if(!empty($info) && $info['reply_type']=='2') checked="checked" @endif>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-xs" id="editPicture" onclick="return getEditImageForm();"><i class="icon icon-picture"></i>&nbsp;&nbsp;编辑图片</button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
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
<script type="text/javascript" src="{{asset('public/Fansmanage')}}/sweetalert/sweetalert.min.js"></script>
<script src="{{asset('public/Fansmanage')}}/trumbowyg/trumbowyg.js"></script>
<script src="{{asset('public/Fansmanage')}}/trumbowyg/plugins/upload/trumbowyg.upload.js"></script>
<script src="{{asset('public/Fansmanage')}}/trumbowyg/plugins/base64/trumbowyg.base64.js"></script>
<script type="text/javascript">
    //弹出文本输入框
    function getEditTextForm(){
        var url = $('#subscribe_reply_text_edit_url').val();
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
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }
    //弹出图片输入框
    function getEditImageForm(){
        var url = $('#subscribe_reply_image_edit_url').val();
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
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }
    //弹出图文输入框
    function getEditArticleForm(){
        var url = $('#subscribe_reply_article_edit_url').val();
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
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }
</script>
</body>
</html>
