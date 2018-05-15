<form class="form-horizontal tasi-form" method="post" role="form" id="currentForm" action="{{ url('zerone/ajax/agent_assets_check') }}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="organization_id" value="{{$listOrg->id}}">
    <input type="hidden" name="status" value="{{$status}}">
    <input type="hidden" name="program_id" value="{{$oneProgram->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">@if($status == 1)程序划入@else程序划出@endif</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal tasi-form" method="get">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">分公司名称</label>
                        <div class="col-sm-9">
                            <input type="text" value="{{$listOrg->organization_name}}" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">程序名称</label>
                        <div class="col-sm-9">
                            <input type="text" value="{{$oneProgram->program_name}}" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-2">
                            <input type="text" value="1" name="number" class="form-control" >
                        </div>
                        <label class="col-sm-2 control-label">套</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">安全密码</label>
                        <div class="col-sm-9">
                            <input type="password" value="" name="safe_password" placeholder="安全密码" class="form-control" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                <button class="btn btn-success" type="button" onclick="postForm()">确定</button>
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