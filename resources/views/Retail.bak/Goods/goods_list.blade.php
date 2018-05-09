<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>商品列表 | 零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail/library/jPlayer')}}/jplayer.flat.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/Retail/library/sweetalert')}}/sweetalert.css">
    <!--[if lt IE 9]>
    <script src="{{asset('public/Company/library/ie')}}/html5shiv.js"></script>
    <script src="{{asset('public/Company/library/ie')}}/respond.min.js"></script>
    <script src="{{asset('public/Company/library/ie')}}/excanvas.js"></script>
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
                            <h3 class="m-b-none">商品查询</h3>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                商品列表
                            </header>
                            <div class="row wrapper">
                                <form class="form-horizontal" method="get">
                                    <input type="hidden" id="goods_delete_comfirm_url" value="{{ url('retail/ajax/goods_delete') }}">
                                    <input type="hidden" id="goods_status_comfirm_url" value="{{ url('retail/ajax/goods_status') }}">
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                    <div class="col-sm-2">
                                        <select name="category_id" class="form-control m-b">
                                            <option value="0">所有分类</option>
                                            @foreach($category as $key=>$val)
                                                <option value="{{$val->id}}" @if($val->id == $search_data['category_id']) selected @endif >{{$val->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label">商品标题</label>

                                    <div class="col-sm-2">
                                        <input class="input-sm form-control" size="16" type="text" name="goods_name" value="{{$search_data['goods_name']}}">
                                    </div>

                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-s-md btn-info"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索</button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped b-t b-light">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>商品标题</th>
                                        <th>商品价格</th>
                                        <th>商品分类</th>
                                        <th>库存</th>
                                        <th>排序</th>
                                        <th>商品状态（点击可切换状态）</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($goods as $key=>$val)
                                    <tr>
                                        <td>{{$val->id}}</td>
                                        <td>{{$val->name}}</td>
                                        <td>
                                            {{$val->price}}
                                        </td>
                                        <td>
                                            <label class="label label-info">@if(!empty($val->category->name)){{$val->category->name}}@else其他@endif</label>
                                        </td>
                                        <td>
                                            {{$val->stock}}
                                        </td>
                                        <td>
                                            {{$val->displayorder}}
                                        </td>
                                        <td>
                                            @if($val->status == 1)
                                                <label class="label label-success" onclick="getlockForm('{{$val->id}}','{{$val->status}}')">销售中</label>
                                            @elseif($val->status == 0)
                                                <label class="label label-danger" onclick="getlockForm('{{$val->id}}','{{$val->status}}')">已下架</label>
                                            @endif
                                        </td>
                                        <td>{{$val->created_at}}</td>
                                        <td>
                                            <button class="btn btn-info btn-xs" id="editBtn" onclick="location.href='{{url('retail/goods/goods_edit')}}?goods_id={{$val->id}}'"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑</button>
                                            <button class="btn btn-danger btn-xs" id="deleteBtn" onclick="getDeleteForm({{ $val->id }})"><i class="fa fa-times"></i>&nbsp;&nbsp;删除</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <footer class="panel-footer">
                                <div class="row">

                                    <div class="col-sm-12 text-right text-center-xs">
                                        {!! $goods->appends($search_data)->links() !!}
                                    </div>
                                </div>
                            </footer>
                        </section>

                    </section>
                </section>
            </section>
        </section>
    </section>
</section>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

<script src="{{asset('public/Retail')}}/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('public/Retail')}}/js/bootstrap.js"></script>
<!-- App -->
<script src="{{asset('public/Retail')}}/js/app.js"></script>
<script src="{{asset('public/Retail')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail/library')}}/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail/library')}}/file-input/bootstrap-filestyle.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/jquery.jplayer.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script src="{{asset('public/Retail/library')}}/sweetalert/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        $('#deleteBtn').click(function(){
            $('#myModal').modal();
        });
        $('#save_btn').click(function(){
            swal({
                title: "温馨提示",
                text: "操作成功",
                type: "success"
            });
        });
    });

    //上下架商品
    function getlockForm(id,status){
        var url = $('#goods_status_comfirm_url').val();
        var token = $('#_token').val();
        var data = {'_token':token,'id':id,'status':status};
        $.post(url,data,function(response){
            if(response.status=='-1'){
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定"
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

    //删除商品信息
    function getDeleteForm(id){
        var url = $('#goods_delete_comfirm_url').val();
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