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
                                <h2 class="font-thin m-b">编辑多条图文</h2>
                                <div class="row row-sm">
                                    <button class="btn btn-s-md btn-success" type="button" onclick="location.href='{{url('fansmanage/api/material_article')}}'"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回列表</button>
                                    <button class="btn btn-s-md btn-success" type="button" id="addBtn"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增一条图文</button>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                </div>
                                <section class="panel panel-default">
                                    <form class="form-horizontal tasi-form" id="currentForm" method="post" action="{{ url('fansmanage/ajax/material_articles_edit_check') }}">
                                        <input autocomplete="off" type="hidden" id="material_image_select_url" value="{{ url('fansmanage/ajax/material_image_select') }}">
                                        <input autocomplete="off" type="hidden" id="material_article_url" value="{{ url('fansmanage/api/material_article') }}">
                                        <input autocomplete="off" type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                        <input autocomplete="off" type="hidden" name="id" id="id" value="{{$id}}">
                                        <input  autocomplete="off" type="hidden" name="num" id="num" value="{{$num}}">
                                        <div class="panel-group m-b" id="target_box" >
                                            @foreach($articles as $key=>$val)
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#target_box" href="#collapse1">
                                                        编辑图文{{ $key+1 }}
                                                    </a>
                                                </div>
                                                <div id="collapse1" class="panel-collapse collapse in" style="height: auto;">
                                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">图片</label>
                                                        <div class="col-sm-9">
                                                            <button class="btn btn-info" type="button" onclick="selectImageForm({{$key+1}});">选择图片素材</button>
                                                            <br/><br/>
                                                            <img id="img_show_{{$key+1}}" src="{{url('uploads/wechat')}}/{{$admin_data['organization_id']}}/{{$val['image_info']['filename']}}" style="width: 100px; height:100px;">
                                                            <input autocomplete="off" type="hidden" name="img_id_{{$key+1}}" id="img_id_{{$key+1}}" id="_token" value="{{$val['image_info']['id']}}">
                                                            <input  autocomplete="off" type="hidden" name="thumb_media_id_{{$key+1}}" id="media_id_{{$key+1}}" id="_token" value="{{$val['image_info']['media_id']}}">
                                                        </div>
                                                    </div>

                                                    <div class="line line-dashed b-b line-lg pull-in"></div>

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">标题</label>
                                                        <div class="col-sm-9">
                                                            <input autocomplete="off" type="text" class="form-control" name="title_{{$key+1}}" value="{{$val['title']}}">
                                                        </div>
                                                    </div>

                                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">作者</label>
                                                        <div class="col-sm-9">
                                                            <input autocomplete="off" type="text" class="form-control" name="author_{{$key+1}}" value="{{$val['author']}}">
                                                        </div>
                                                    </div>

                                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">原文地址</label>
                                                        <div class="col-sm-9">
                                                            <input  autocomplete="off" type="text" class="form-control" name="origin_url_{{$key+1}}" value="@if(!empty($val['content_source_url'])){{$val['content_source_url']}}@endif">
                                                        </div>
                                                    </div>

                                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                                    <div class="form-group">
                                                        <div class="col-sm-2 control-label">正文</div>
                                                        <div class="col-sm-9">
                                                            <textarea id="form-content{{$key+1}}" class="editor" cols="30" name="content_{{$key+1}}" rows="10">{{$val['content']}} </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12 col-sm-offset-6">

                                                <button type="button" class="btn btn-success" onclick="return postForm();" id="save_btn">保存信息</button>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                    </form>
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
<div id="tw_info" style="display:none;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#target_box" href="#collapse{target_num}">
                编辑图文{target_num}
            </a>
        </div>
        <div id="collapse{target_num}" class="panel-collapse collapse in" style="height: auto;">
            <div class="line line-dashed b-b line-lg pull-in"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">图片</label>
                <div class="col-sm-9">
                    <button class="btn btn-info" type="button" onclick="selectImageForm('{target_num}');">选择图片素材</button>
                    <br/><br/>
                    <img id="img_show_{target_num}" src="http://o2o.01nnt.com/uploads/wechat/6/20180316033708570.jpg" style="width: 100px; height:100px;display:none">
                    <input  autocomplete="off" type="hidden" name="img_id_{target_num}" id="img_id_{target_num}" id="_token" value="">
                    <input  autocomplete="off" type="hidden" name="thumb_media_id_{target_num}" id="media_id_{target_num}" id="_token" value="">
                </div>
            </div>

            <div class="line line-dashed b-b line-lg pull-in"></div>

            <div class="form-group">
                <label class="col-sm-2 control-label">标题</label>
                <div class="col-sm-9">
                    <input  autocomplete="off" type="text" class="form-control" name="title_{target_num}" value="">
                </div>
            </div>

            <div class="line line-dashed b-b line-lg pull-in"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">作者</label>
                <div class="col-sm-9">
                    <input  autocomplete="off" type="text" class="form-control" name="author_{target_num}" value="">
                </div>
            </div>

            <div class="line line-dashed b-b line-lg pull-in"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label">原文地址</label>
                <div class="col-sm-9">
                    <input  autocomplete="off" type="text" class="form-control" name="origin_url_{target_num}" value="">
                </div>
            </div>

            <div class="line line-dashed b-b line-lg pull-in"></div>
            <div class="form-group">
                <div class="col-sm-2 control-label">正文</div>
                <div class="col-sm-9">
                    <textarea id="form-content{target_num}" class="editor" cols="30" name="content_{target_num}" rows="10"> </textarea>
                </div>
            </div>
        </div>
    </div>

</div>
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
<!-- Ladda -->
<script src="{{asset('public/Fansmanage')}}/trumbowyg/trumbowyg.js"></script>


<script src="{{asset('public/Fansmanage')}}/trumbowyg/plugins/upload/trumbowyg.upload.js"></script>

<script src="{{asset('public/Fansmanage')}}/trumbowyg/plugins/base64/trumbowyg.base64.js"></script>



<script type="text/javascript">
    $(document).ready(function() {
        var num = $('#num').val();
        for(var i = 1 ; i<=num ; i++){
            $('#form-content'+i).trumbowyg({
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
        }

        $("#addBtn").click(function(){

            var html = $('#tw_info').html();
            var num = $('#num').val();
            num++;
            if(num>10){
                swal({
                    title: "提示信息",
                    text: "每次最多只能添加10条图文素材哦",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                },function(){
                    return;
                });
                return ;
            }
            $('#num').val(num);
            html = html.replace(/{target_num}/g,num);
            $('#target_box').append(html);
            $('#form-content'+num).trumbowyg({

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
                    window.location.href = $('#material_article_url').val();
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