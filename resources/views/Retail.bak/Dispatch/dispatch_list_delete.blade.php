<form class="form-horizontal tasi-form" method="post" id="currentForm" action="{{ url('retail/ajax/dispatch_list_delete_check') }}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="id" value="{{$id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                   确认删除运费模板
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 text-right">安全密码</label>
                        <div class="col-sm-10">
                            <input type="text" value="" placeholder="安全密码" class="form-control" name="safe_password">
                            <span class="help-block m-b-none">
                                    <p class="text-danger">删除运费模板后将不可恢复</p>
                          </span>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                <button class="btn btn-success" type="button" id="save_btn" onclick="postForm()">确定</button>
            </div>
        </div>
    </div>
</form>
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
