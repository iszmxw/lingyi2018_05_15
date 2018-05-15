<link href="{{asset('public/Zerone/library/iCheck')}}/css/custom.css" rel="stylesheet">
<form method="post" role="form" id="currentForm" action="{{ url('zerone/ajax/fansmanage_examine_check') }}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="id" id="id" value="{{$info->id}}">
    <input type="hidden" name="status" id="sta" value="{{$status}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h3>确认操作</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">分公司名称</label>
                    <div class="col-sm-10">{{$info->fansmanage_name}}</div>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">安全密码</label>
                    <div class="col-sm-10"><input type="password" class="form-control" value="" name="safe_password"></div>
                </div>
                <div style="clear:both"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary saveBtn" onclick="postForm()" >保存</button>
            </div>
        </div>
    </div>
</form>
<script src="{{asset('public/Zerone/library/iCheck')}}/js/icheck.min.js"></script>
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