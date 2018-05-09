<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>个人账号信息修改 | 零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail/library/jPlayer')}}/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail/css')}}/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail/css')}}/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail/css')}}/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail/css')}}/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail/css')}}/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail/css')}}/app.css" type="text/css" />
    <link href="{{asset('public/Retail/library/sweetalert')}}/sweetalert.css" rel="stylesheet" />
    <link href="{{asset('public/Retail/library/iCheck')}}/css/custom.css" rel="stylesheet" />
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
                            <h3 class="m-b-none">账号信息</h3>
                        </div>

                        <div class="col-lg-4">
                            <section class="panel panel-default">
                                <header class="panel-heading font-bold">
                                    个人账号信息修改
                                </header>
                                <div class="panel-body">
                                    <form method="post" class="form-horizontal"  role="form" id="currentForm" action="{{ url('retail/ajax/profile_edit_check') }}">
                                        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="input-id-1">用户账号</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="input-id-1" disabled="" value="{{$user['account']}}">
                                            </div>
                                        </div>

                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="input-id-1">真实姓名</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="realname" class="form-control" value="{{$user->account_info['realname']}}">
                                            </div>
                                        </div>

                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="input-id-1">手机号码</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="mobile" class="form-control" value="{{$user['mobile']}}">
                                                <span class="help-block m-b-none">该手机号码可用作登录，与负责人手机号码可分别设置</span>
                                            </div>
                                        </div>

                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="input-id-1">安全密码</label>
                                            <div class="col-sm-9">
                                                <input type="password" name="safe_password" class="form-control">
                                            </div>
                                        </div>

                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                        <div class="form-group">
                                            <div class="col-sm-12 col-sm-offset-5">
                                                <button type="button" onclick="return postForm();" class="btn btn-success" id="addBtn">保存信息</button>
                                            </div>
                                        </div>
                                        <div class="line line-dashed b-b line-lg pull-in"></div>
                                    </form>
                                </div>
                            </section>
                        </div>

                        <div class="col-lg-8">
                            <section class="panel panel-default">
                                <header class="panel-heading font-bold">
                                    个人系统权限
                                </header>
                                <div class="panel-body">
                                    @foreach($module_node_list as $key=>$val)
                                    <div>
                                        <label class="i-checks">
                                            <input type="checkbox" value="{{$val['module_name']}}" checked="checked" disabled=""> {{$val['module_name']}}
                                        </label>
                                    </div>
                                    <div>
                                        @foreach($val['program_nodes'] as $kk=>$vv)
                                        <label class="i-checks">
                                            <input type="checkbox" value="{{$vv['node_name']}}" checked="checked" disabled=""> {{$vv['node_name']}}
                                        </label>
                                        &nbsp;&nbsp;
                                        @endforeach
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                    </section>
                </section>
            </section>
        </section>
    </section>
</section>
<script src="{{asset('public/Retail')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Retail')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Retail')}}/js/app.js"></script>
<script src="{{asset('public/Retail/library/slimscroll')}}/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail/')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail/library/file-input')}}/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail/library')}}/jPlayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail/library')}}/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script type="text/javascript" src="{{asset('public/Retail/library/sweetalert')}}/sweetalert.min.js"></script>
<script src="{{asset('public/Retail/library/iCheck')}}/js/icheck.min.js"></script>
<script>
    //custom select box
    $(function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
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
                    confirmButtonText: "确定"
                });
            }
        });
    }
</script>
</body>
</html>