@foreach($spec as $key=>$val)
    <div class="m-t">
        <label class="label label-primary">{{$val->name}}</label>
        <button type="button" class="btn editBtn btn-info btn-xs"><i class="fa fa-edit"></i></button>
        <button type="button" class="btn deleteBtn btn-danger btn-xs"><i class="fa fa-times"></i></button>
    </div>
    <div class="m-t">
        <div class="m-t col-lg-2">
            <label class="label label-success">米饭</label>
            <button type="button" class="btn editBtn btn-info btn-xs"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn deleteBtn btn-danger btn-xs"><i class="fa fa-times"></i></button>
        </div>
        <div class="m-t col-lg-2">
            <label class="label label-success">拉面</label>
            <button type="button" class="btn editBtn btn-info btn-xs"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn deleteBtn btn-danger btn-xs"><i class="fa fa-times"></i></button>
        </div>
        <div class="m-t col-lg-2">
            <label class="label label-success">餐包</label>
            <button type="button" class="btn editBtn btn-info btn-xs"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn deleteBtn btn-danger btn-xs"><i class="fa fa-times"></i></button>
        </div>
        <div class="m-t col-lg-2">
            <button type="button" class="btn btn-info btn-xs" id="addItem"><i class="fa fa-plus"></i>&nbsp;&nbsp;添加规格子项</button>
        </div>
    </div>
    <div style="clear: both;"></div>
    <div class="line line-dashed b-b line-lg pull-in"></div>
@endforeach

<div class="modal fade" id="myModal_SpecItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" class="form-horizontal"  role="form" id="spec_add" action="{{ url('branch/ajax/spec_item_add_check') }}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="goods_id" value="{{$goods->id}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">添加规格</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="get">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-id-1">规格名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="spec_name" value="">
                            </div>
                        </div>
                        <div class="line line-dashed b-b line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-id-1">安全密码</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="safe_password" value="">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="button" onclick="spec_item_add()">确定</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    //弹出子规格添加
    $("#addItem").click(function(){
        $('#myModal_SpecItem').modal();
    });
    //添加子规格提交
    function spec_item_add() {
        var target = $("#spec_add");
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
                    alert('添加子规格成功！');
                    {{--window.location.href = "{{asset("branch/goods/goods_list")}}";--}}
                });
            }else{
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
