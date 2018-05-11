<form class="form-horizontal tasi-form" method="post" role="form" id="currentForm"
      action="{{ url('retail/ajax/subordinate_edit_check') }}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="id" id="id" value="{{ $info->id }}">
    <input type="hidden" name="account" id="account" value="{{ $info->account }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">编辑用户基本信息</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-id-1">用户账号</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" disabled value="{{ $info->account }}">
                        </div>
                    </div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-id-1">用户密码</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="password">
                        </div>
                    </div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-id-1">真实姓名</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="realname"
                                   value="@if(!empty($info->account_info)){{ $info->account_info->realname }}@endif">
                        </div>
                    </div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-id-1">手机号码</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="mobile" value="{{ $info->mobile }}">
                        </div>
                    </div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-id-1">安全密码</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="" name="safe_password">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                <button class="btn btn-success" type="button" id="addBtn" onclick="return postForm();">确定</button>
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
            } else if (json.status == 1) {
                swal({
                    title: "提示信息",
                    text: json.data,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                }, function () {
                    window.location.reload();
                });
            } else {
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