<form class="form-horizontal tasi-form" method="post" id="currentForm" action="{{ url('retail/ajax/user_list_lock_check') }}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="user_id" value="{{$user_id}}">
    <input type="hidden" name="status" value="{{$status}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                   @if($status == '1')
                        冻结粉丝确认
                   @else
                        解冻粉丝确认
                   @endif
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 text-right">微信昵称</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$nickname}}" placeholder="安全密码" class="form-control" disabled="" name="$nickname">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>
                    <div class="form-group">
                        <label class="col-sm-2 text-right">安全密码</label>
                        <div class="col-sm-10">
                            <input type="text" value="" placeholder="安全密码" class="form-control" name="safe_password">
                            <span class="help-block m-b-none">
                               @if($status == '1')
                                    <p class="text-danger">冻结了粉丝，粉丝将不能继续在店里消费。粉丝去其他联盟商家里消费也没有提成</p>
                                @else
                                    <p class="text-danger">解冻了粉丝，粉丝能继续在店里消费。粉丝去其他联盟商家里消费有提成</p>
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
                    confirmButtonText: "确定",
                },function(){
                    window.location.reload();
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
