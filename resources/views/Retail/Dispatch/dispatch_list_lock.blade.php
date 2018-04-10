<form class="form-horizontal tasi-form" method="post" id="currentForm" action="{{ url('retail/ajax/dispatch_list_lock_check') }}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="user_id" value="{{$id}}">
    <input type="hidden" name="status" value="{{$status}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                   @if($status == '1')
                        弃用运费模板确认
                   @else
                        启用运费模板确认
                   @endif
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 text-right">安全密码</label>
                        <div class="col-sm-10">
                            <input type="text" value="" placeholder="安全密码" class="form-control" name="safe_password">
                            <span class="help-block m-b-none">
                               @if($status == '1')
                                    <p class="text-danger">弃用后该模板不在生效</p>
                                @else
                                    <p class="text-danger">启用后该模板即可使用</p>
                                @endif
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
