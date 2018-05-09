<form class="form-horizontal tasi-form" method="post" id="currentForm" action="{{url('retail/ajax/user_list_edit_check')}}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="user_id" value="{{$userInfo['user_id']}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">粉丝详情</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 text-right">用户账号</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$data['account']}}" placeholder="标签名称" class="form-control" disabled="" name="account">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>


                    <div class="form-group">
                        <label class="col-sm-2 text-right">微信昵称</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{$userInfo['nickname']}}" placeholder="安全密码" class="form-control" disabled="">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">源头</label>
                        <div class="col-sm-10">
                            @if(!empty($data['store_name']))
                                <label class="label label-success">自有店铺-{{$data['store_name']}}</label>
                            @else
                                <label class="label label-primary">联盟商户</label>
                            @endif
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">推荐人</label>
                        <div class="col-sm-10">
                            @if(empty($data['recommender_name']))
                                <label class="label label-success">自行关注</label>
                            @else
                                <label class="label label-primary">联盟用户-{{$data['recommender_name']}}</label>
                            @endif

                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">手机号码</label>
                        <div class="col-sm-10">
                            <input type="number" value="{{$data['mobile']}}" placeholder="手机号码" class="form-control" name="mobile">
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-sm-2 text-right">QQ号码</label>
                        <div class="col-sm-10">
                            <input type="number" value="{{$userInfo['qq']}}" placeholder="QQ号码" class="form-control" name="qq">
                        </div>
                    </div>

                    <div style="clear:both;"></div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>


                    <div class="form-group">
                        <label class="col-sm-2 text-right">安全密码</label>
                        <div class="col-sm-10">
                            <input type="text" value="" placeholder="安全密码" class="form-control" name="safe_password">
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
