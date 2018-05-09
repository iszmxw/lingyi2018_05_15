<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8"/>
    <title>供应商列表 | 零壹云管理平台 | 零售版店铺管理系统</title>
    <link rel="stylesheet" href="{{asset('public/Retail/library/jPlayer')}}/jplayer.flat.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/bootstrap.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/animate.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/simple-line-icons.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/font.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public/Retail')}}/css/app.css" type="text/css"/>
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
                                <button type="button" class="btn btn-s-md btn-success" onclick="window.location='supplier_list'">供应商列表</button>
                                <button type="button" class="btn btn-s-md btn" onclick="window.location='supplier_add'">&nbsp;&nbsp;添加供应商</button>
                            </div>
                        </div>
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                供应商列表
                            </header>
                            <div class="row wrapper">
                                <form class="form-horizontal" method="get" id="searchForm" action="">
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                    <input type="hidden" id="supplier_edit_url" value="{{ url('retail/ajax/supplier_edit') }}">
                                    <input type="hidden" id="supplier_delete_comfirm_url" value="{{ url('retail/ajax/supplier_delete') }}">
                                    <label class="col-sm-1 control-label">供应商名称</label>
                                    <div class="col-sm-2">
                                        <input class="input-sm form-control" size="16" type="text" name="company_name" value="{{$search_data['company_name']}}">
                                    </div>

                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-s-md btn-info"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped b-t b-light">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>公司名称</th>
                                        <th>联系人姓名</th>
                                        <th>联系人电话</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($supplier as $key=>$val)
                                        <tr>
                                            <td>{{ $val->id }}</td>
                                            <td>{{ $val->company_name }}</td>
                                            <td>{{ $val->contactname }}</td>
                                            <td>{{ $val->contactmobile }}</td>
                                            <td>
                                                <button class="btn btn-info btn-xs" onclick="getEditForm({{ $val->id }})"><i class="fa fa-edit"></i>&nbsp;&nbsp;编辑
                                                </button>
                                                <button class="btn btn-danger btn-xs" onclick="getDeleteForm({{ $val->id }})"><i class="fa fa-times"></i>&nbsp;&nbsp;删除
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <footer class="panel-footer">
                                <div class="row">
                                    <div class="col-sm-12 text-right text-center-xs">
                                        {!! $supplier->appends($search_data)->links() !!}
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
<script src="{{asset('public/Retail/library')}}/slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{asset('public/Retail')}}/js/app.plugin.js"></script>
<script src="{{asset('public/Retail/library')}}/file-input/bootstrap-filestyle.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/jquery.jplayer.min.js"></script>
<script src="{{asset('public/Retail/library')}}/jPlayer/add-on/jplayer.playlist.min.js"></script>
<script src="{{asset('public/Retail/library')}}/sweetalert/sweetalert.min.js"></script>
<script>

    //编辑分类信息
    function getEditForm(id) {
        var url = $('#supplier_edit_url').val();
        var token = $('#_token').val();
        var data = {'id': id, '_token': token};
        $.post(url, data, function (response) {
            if (response.status == '-1') {
                swal({
                    title: "提示信息",
                    text: response.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                }, function () {
                    window.location.reload();
                });
                return;
            } else {
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        });
    }


    //删除分类信息
    function getDeleteForm(id){
        var url = $('#supplier_delete_comfirm_url').val();
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