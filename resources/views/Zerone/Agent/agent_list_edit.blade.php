<link href="{{asset('public/Zerone/library/iCheck')}}/css/custom.css" rel="stylesheet">
<form method="post" role="form" id="currentForm" action="{{ url('zerone/ajax/agent_list_edit_check') }}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="id" id="id" value="{{$listorg->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h3>编辑分公司</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">所在战区</label>
                    <div class="col-sm-10">
                        <select class="form-control m-b" name="zone_id">
                            @foreach($warzone as $k=>$v)
                                <option value="{{$v->id}}" @if(!empty($listorg->warzoneagent->zone_id) && $v->id == $listorg->warzoneagent->zone_id)  selected @endif>{{$v->zone_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="clear:both"></div>
                <div class="hr-line-dashed"></div>

                <div class="form-group"><label class="col-sm-2 control-label">分公司名称</label>
                    <div class="col-sm-10"><input type="text" class="form-control" name="organization_name" value="{{$listorg->organization_name}}"></div>
                </div>
                <div style="clear:both"></div>
                <div class="hr-line-dashed"></div>

                <div class="form-group"><label class="col-sm-2 control-label">负责人姓名</label>
                    <div class="col-sm-10"><input type="text" class="form-control" name="realname" value="{{$listorg->organizationagentinfo->agent_owner}}"></div>
                </div>
                <div style="clear:both"></div>
                <div class="hr-line-dashed"></div>

                <div class="form-group"><label class="col-sm-2 control-label">负责人身份证号</label>
                    <div class="col-sm-10"><input type="text" class="form-control" name="idcard" value="{{$listorg->organizationagentinfo->agent_owner_idcard}}"></div>
                </div>
                <div style="clear:both"></div>
                <div class="hr-line-dashed"></div>

                <div class="form-group"><label class="col-sm-2 control-label">手机号码</label>
                    <div class="col-sm-10"><input type="text" class="form-control" name="mobile" value="{{$listorg->organizationagentinfo->agent_owner_mobile}}"></div>
                </div>
                <div style="clear:both"></div>
                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">安全密码</label>
                    <div class="col-sm-10"><input type="password" class="form-control" name="safe_password" value=""></div>
                </div>
                <div style="clear:both"></div>
                <div class="hr-line-dashed"></div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary saveBtn" onclick="postForm()">保存</button>
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