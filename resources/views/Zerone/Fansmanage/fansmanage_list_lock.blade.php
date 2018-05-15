<form method="post" role="form" id="currentForm" action="{{ url('zerone/ajax/fansmanage_list_lock_check') }}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="id" id="id" value="{{$data->id}}">
    <input type="hidden" name="status" id="status" value="{{$status}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h3>确认操作</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">分公司名称</label>
                    <div class="col-sm-10">{{$data->organization_name}}</div>
                </div>
                <div style="clear:both"></div>

            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">管理员安全密码</label>
                    <div class="col-sm-10"><input type="password" name="safe_password" class="form-control" value=""> <span style="color: red">谨慎操作，如果冻结了，该商户所有人员无法登入</span></div>
                </div>


                <div style="clear:both"></div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary saveBtn" onclick="postForm()">确定</button>
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
                console.log(json);
//                 swal({
//                     title: "提示信息",
//                     text: json.data,
//                     confirmButtonColor: "#DD6B55",
//                     confirmButtonText: "确定",
//                     //type: "warning"
//                 });
            }
        });
    }
</script>